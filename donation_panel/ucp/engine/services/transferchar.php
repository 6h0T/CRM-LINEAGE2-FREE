<?php

if(!$indexing) { exit; }

if($funct['servic'] != 1 || $service['actv']['transferchar'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if($service['actv']['transferchar'] != 1) { fim($LANG[40003]); }

$cid = !empty($_POST['cid']) ? intval(trim($_POST['cid'])) : '';
$custo = intval(trim($service['cost']['transferchar']));
$newacc = !empty($_POST['newacc']) ? vCode($_POST['newacc']) : '';

if(empty($newacc)) {
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

$accs = Services::findAccs($_SESSION['acc']);
if(count($accs) > 0) {
	
	for($i=0, $c=count($accs); $i < $c; $i++) {
		
		$accsArray[] = $accs[$i]['name'];
		
		if($newacc == $accs[$i]['name']) {
			$accid = $accs[$i]['accid'];
		}
		
	}
	
	if(!in_array($newacc, $accsArray)) {
		fim($LANG[12055]);
	}
	
} else {
	fim($LANG[12055]);
}

$countNAC = Services::countNewAccChars($newacc);
if($countNAC[0]['quant'] >= 7) {
	fim($LANG[39074]);
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

$cached_op = pack("cVVV", 31, intval($cid), intval($accid), ''.trim($newacc).'');
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result !== '1') {
	fim($LANG[12055]);
}


$execute = Services::transferCharacter($_SESSION['acc'], $cid, $newacc, $accid);
if($execute) {
	@Services::logServices($_SESSION['acc'], $cid, 'transferchar', vCode('Foi transferido da conta "'.trim($char[0]['account_name']).'" para "'.$newacc.'"'), $custo);
	fim($LANG[12056], 'OK', './?module=services&page=list', $custo);
} else {
	fim($LANG[12055]);
}
