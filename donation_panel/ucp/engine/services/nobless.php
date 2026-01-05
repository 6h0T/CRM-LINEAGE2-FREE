<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['nobless'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['nobless'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['nobless']));

if(empty($cid)) {
	fim($LANG[10088]);
}

require('private/classes/classServices.php');

$char = Services::checkChar($_SESSION['acc'], $cid);
if(count($char) == 0) {
	fim($LANG[12100]);
}

if(intval(trim($char[0]['online'])) > 0) {
	fim($LANG[12099]);
}

$checkIsNobless = Services::checkIsNobless($cid);
if(count($checkIsNobless) > 0) {
	fim($LANG[40064]);
}

if($custo > 0) {
	if(debitBalance($_SESSION['acc'], $custo) != 'OK') {
		fim($LANG[10097]);
	}
}

require('private/includes/cacheD.php');

if(!is_resource(l2_cached_open())) {
	fim($LANG[12055].' #CacheD');
}

l2_cached_close();

$cached_op = pack("cVV", 106, intval($cid), 1);
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}

$execute = Services::setNobless($cid);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'nobless', vCode('Adquiriu nobless status'), $custo);
	fim($LANG[12056], 'OK', '', $custo);
} else {
	fim($LANG[12055]);
}

