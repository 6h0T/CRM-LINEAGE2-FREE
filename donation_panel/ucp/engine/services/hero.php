<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['hero'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['hero'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['hero']));

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
if(count($checkIsNobless) == 0) {
	fim($LANG[40065]);
}

if($checkIsNobless[0]['hero_type'] > 0) {
	fim($LANG[40066]);
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

$cached_op = pack("cVV", 107, intval($cid), 2);
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}

$execute = Services::setHero($cid);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'hero', vCode('Se tornou hero'), $custo);
	fim($LANG[12056], 'OK', '', $custo);
} else {
	fim($LANG[12055]);
}

