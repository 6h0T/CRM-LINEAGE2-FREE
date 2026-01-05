<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['namecolor'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['namecolor'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['namecolor']));
$cor = !empty($_POST['cor']) ? vCode($_POST['cor']) : '';

if(empty($cor)) {
	fim($LANG[12058]);
}

$xpldColors = @explode(',', trim($hexColors));
if(!in_array($cor, $xpldColors)) {
	fim($LANG[12058]);
}

if(empty($cid)) {
	fim($LANG[10088]);
}

require('private/classes/classServices.php');

$char = Services::checkChar($_SESSION['acc'], $cid);
if(count($char) == 0) {
	fim($LANG[12100]);
}

if(($char[0]['login'] > $char[0]['logout'] || empty($char[0]['logout'])) && !empty($char[0]['login'])) {
	fim($LANG[12099]);
}

if($custo > 0) {
	if(debitBalance($_SESSION['acc'], $custo) != 'OK') {
		fim($LANG[10097]);
	}
}

$c3 = substr($cor, 0, 2);
$c2 = substr($cor, 2, 2);
$c1 = substr($cor, 4, 2);

$hexColor = hexdec($c1.$c2.$c3);

require('private/includes/cacheD.php');

if(!is_resource(l2_cached_open())) {
	fim($LANG[12055].' #CacheD');
}

l2_cached_close();

$cached_op = pack("cccVV", 111, 0, 1, intval($cid), intval($hexColor));
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055].' #1');
}

$execute = Services::changeTitleColor($_SESSION['acc'], $cid, $hexColor);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'titlecolor', vCode('Change title color to #'.$cor.'('.$hexColor.')'), $custo);
	fim($LANG[12056], 'OK', '', $custo);
} else {
	fim($LANG[12055]);
}
