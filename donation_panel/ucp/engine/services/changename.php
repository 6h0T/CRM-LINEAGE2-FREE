<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['changename'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['changename'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['changename']));
$nickname = !empty($_POST['nickname']) ? vCode($_POST['nickname']) : '';
$nickname2 = !empty($_POST['nickname2']) ? vCode($_POST['nickname2']) : '';

if(empty($nickname) || empty($nickname2)) {
	fim($LANG[12058]);
}

if(empty($cid)) {
	fim($LANG[10088]);
}

if($nickname != $nickname2) {
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

if(intval($char[0]['align']) > 0) {
	fim($LANG[12098]);
}

if(preg_match("/[^a-zA-Z0-9]/", $nickname)) {
	fim($LANG[16000]);
}

if(strlen($nickname) > 16) {
	fim($LANG[16001]);
}

$checkNameExists = Services::checkNameExists($nickname);
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

$cached_op = pack("cV", 4, intval($cid));
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode(''.trim($nickname).'').ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}

$execute = Services::changeNickname($_SESSION['acc'], $cid, $nickname);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'changename', vCode('Alterou nickname de "'.trim($char[0]['char_name']).'" para "'.$nickname.'"'), $custo);
	fim($LANG[12056], 'OK', './?module=services&page=list', $custo);
} else {
	fim($LANG[12055]);
}
