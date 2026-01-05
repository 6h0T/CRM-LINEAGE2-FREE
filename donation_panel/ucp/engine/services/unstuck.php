<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['unstuck'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['unstuck'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['unstuck']));

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

if(intval($char[0]['align']) > 0) {
	fim($LANG[12098]);
}

$x = $char[0]['xloc'];
$y = $char[0]['yloc'];

if($x > -110000 && $x < -100000 && $y > -260000 && $y < -240000) {
	fim($LANG[12055].' #JAIL');
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

$cached_op = pack("cVVVVV", 2, intval($cid), intval($char[0]['world']), intval($unstuck_loc_x), intval($unstuck_loc_y), intval($unstuck_loc_z));
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}


$execute = Services::Unstuck($_SESSION['acc'], $cid, $unstuck_loc_x, $unstuck_loc_y, $unstuck_loc_z);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'unstuck', vCode('Utilizou o unstuck'), $custo);
	fim($LANG[12056], 'OK', '', $custo);
} else {
	fim($LANG[12055]);
}
