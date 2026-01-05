<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['clanname'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['clanname'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['clanname']));
$name = !empty($_POST['name']) ? vCode($_POST['name']) : '';
$name2 = !empty($_POST['name2']) ? vCode($_POST['name2']) : '';

if(empty($name) || empty($name2)) {
	fim($LANG[12058]);
}

if(empty($cid)) {
	fim($LANG[10088]);
}

if($name != $name2) {
	fim($LANG[10099]);
}

require('private/classes/classServices.php');

$char = Services::checkChar($_SESSION['acc'], $cid);
if(count($char) == 0) {
	fim($LANG[12100]);
}

if(($char[0]['login'] > $char[0]['logout'] || empty($char[0]['logout'])) && !empty($char[0]['login'])) {
	fim($LANG[12099]);
}

$clanID = intval(trim($char[0]['pledge_id']));

if(empty($clanID)) {
	fim($LANG[10089]);
}

if(preg_match("/[^a-zA-Z0-9]/", $name)) {
	fim($LANG[16000]);
}

if(strlen($name) > 16) {
	fim($LANG[16001]);
}

$clan = Services::checkClanExists($clanID, $cid);
if(count($clan) == 0) {
	fim($LANG[10100]);
}

$checkNameExists = Services::checkClanNameExists($name);
if(count($checkNameExists) > 0) {
	fim($LANG[14004]);
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

$cached_op = pack("cVV", 26, intval($clanID), ''.trim($name).'');
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}


$execute = Services::changeClanName($cid, $clanID, $name);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'clanname', vCode('Alterou o nome do seu clan (ID: '.$clan[0]['pledge_id'].') de "'.trim($clan[0]['name']).'" para "'.$name.'"'), $custo);
	fim($LANG[12056], 'OK', '', $custo);
} else {
	fim($LANG[12055]);
}
