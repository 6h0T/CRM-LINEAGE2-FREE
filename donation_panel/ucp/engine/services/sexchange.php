<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['sexchange'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['sexchange'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['sexchange']));
$sex = !empty($_POST['gender']) ? vCode($_POST['gender']) : '';

if($sex != '1') { $sex = '0'; }

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

$cached_op = pack("cVVVVVVV", 16, intval($cid), intval($sex), intval($char[0]['race']), intval($char[0]['class']), intval($char[0]['face_index']), intval($char[0]['hair_shape_index']), intval($char[0]['hair_color_index']));
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}


$execute = Services::changeSex($_SESSION['acc'], $cid, $sex);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'sexchange', vCode('Alterou seu gênero para '.(($sex == '0') ? 'Masculino' : 'Feminino')), $custo);
	fim($LANG[12056], 'OK', './?module=services&page=list', $custo);
} else {
	fim($LANG[12055]);
}
