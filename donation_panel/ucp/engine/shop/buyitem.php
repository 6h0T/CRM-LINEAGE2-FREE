<?php

if(!$indexing) { exit; }

if($funct['shopon'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

$pack = !empty($_POST['pack']) ? intval(trim($_POST['pack'])) : '';
$char = !empty($_POST['char']) ? intval(trim($_POST['char'])) : '';
$itens = !empty($_POST['itens']) ? $_POST['itens'] : '';

if(empty($char) || empty($itens) || !is_array($itens)) { fim($LANG[39006]); }

require('private/classes/classShop.php');

$findChar = Shop::findChar($_SESSION['acc'], $char);
if(count($findChar) == 0) { fim($LANG[10026]); }

if(trim($findChar[0]['online']) == '1') {
	fim($LANG[12099]);
}

$byFP = 0; $byFP_price = 0;

if(vCode($itens[0]) == 'all') {
	
	$findPack = Shop::findPack($pack);
	if(count($findPack) == 0) { fim($LANG[39058]); }
	
	$price = intval($findPack[0]['valor']);
	
	$byFP = 1; $byFP_price = $price;
	
	$listItens = Shop::listItens($pack);
	if(count($listItens) == 0) { fim($LANG[12055]); }
	
	$itensIDs = '';
	for($i=0, $c=count($listItens); $i < $c; $i++) {
		$itensIDs .= intval($listItens[$i]['sitem_id']).',';
	}
	$itensIDs = substr($itensIDs, 0, -1);
	
} else {
	
	$itensIDs = '';
	for($i=0, $c=count($itens); $i < $c; $i++) {
		$itensIDs .= intval($itens[$i]).',';
	}
	$itensIDs = substr($itensIDs, 0, -1);
	
	$findPrice = Shop::findPrice($itensIDs);
	if(count($findPrice) == 0) { fim($LANG[12055]); }
	$price = intval($findPrice[0]['price']);
	
}

if($price > 0) {
	if(debitBalance($_SESSION['acc'], $price) != 'OK') {
		fim($LANG[10097]);
	}
}

$erros=0;

$itens = Shop::itensDetail($itensIDs);
if(count($itens) == 0) { fim($LANG[12055]); }
for($i=0, $c=count($itens); $i < $c; $i++) {
	
	$itemID = trim($itens[$i]['id_ingame']);
	$amount = trim($itens[$i]['amount']);
	$cumult = trim($itens[$i]['cumulativo']);
	$enchant = trim($itens[$i]['enchant']);
	
	if($itemDelivery == 1) {
		
		$insert = Shop::insertItem($char, $itemID, $amount, $enchant);
		
	} else {
		
		require_once('private/includes/cacheD.php');
		
		if(!is_resource(l2_cached_open())) {
			fim($LANG[12055].' #CacheD');
		}
		
		l2_cached_close();
		
		$cached_op = pack("cVVVVVVVVVV", 55, intval($char), 0, intval($itemID), intval($amount), intval($enchant), 0, 0, 0, 0, 1);
		$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
		if($result === '1') {
			$insert = true;
		} else {
			$insert = false;
		}
		
	}
	
	if(!$insert) {
		$erros += 1;
	}
	
	if(!Shop::logShop($_SESSION['acc'], '', $char, $itemID, trim($itens[$i]['nome']), trim($itens[$i]['sa']), trim($itens[$i]['spack_id']), $amount, $enchant, trim($itens[$i]['valor']), $byFP, $byFP_price)) {
		$erros += 1;
	}
	
}

if($erros == 0) {
	fim($LANG[12056], 'OK', './?module=shop&page=list');
} else {
	fim($LANG[12055]);
}

