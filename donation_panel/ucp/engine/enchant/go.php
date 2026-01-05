<?php

if(!$indexing) { exit; }

if($funct['enchnt'] != 1) { fim($LANG[40003], 'ERROR', './'); }

if($logged != 1) { fim('Access denied!', 'RELOAD'); }

$itemObjID = !empty($_POST['ieobj']) ? intval(trim($_POST['ieobj'])) : '';
$newEnchant = !empty($_POST['ieval']) ? intval(trim($_POST['ieval'])) : '';
$cid = !empty($_POST['iecha']) ? intval(trim($_POST['iecha'])) : '';

if(empty($cid)) { fim($LANG[10088]); }

require('private/classes/classEnchant.php');

$findChar = Enchant::findChar($_SESSION['acc'], $cid);
if(count($findChar) == 0) { fim($LANG[10026]); }

if(intval(trim($findChar[0]['online'])) > 0) {
	fim($LANG[12099]);
}

$findItem = Enchant::findItem($itemObjID, $cid);
if(count($findItem) == 0) { fim($LANG[40049].' #1'); }

$findItem[0]['enchant_level'] = intval(trim($findItem[0]['enchant_level']));

if($enchant['regrd'] != 1) {
	
	if($newEnchant < $findItem[0]['enchant_level'] || $newEnchant < 0) {
		fim($LANG[40050]);
	}
	
}

if($newEnchant < 0 || $newEnchant == $findItem[0]['enchant_level']) {
	fim($LANG[40051]);
}

$itemID = $findItem[0]['item_id'];

if(strpos(','.$enchant['weapD'].',', ','.$itemID.',') !== false) {
	$grade = 'D';
	$iwora = 1;
} else if(strpos(','.$enchant['armrD'].',', ','.$itemID.',') !== false) {
	$grade = 'D';
	$iwora = 2;
} else if(strpos(','.$enchant['weapC'].',', ','.$itemID.',') !== false) {
	$grade = 'C';
	$iwora = 1;
} else if(strpos(','.$enchant['armrC'].',', ','.$itemID.',') !== false) {
	$grade = 'C';
	$iwora = 2;
} else if(strpos(','.$enchant['weapB'].',', ','.$itemID.',') !== false) {
	$grade = 'B';
	$iwora = 1;
} else if(strpos(','.$enchant['armrB'].',', ','.$itemID.',') !== false) {
	$grade = 'B';
	$iwora = 2;
} else if(strpos(','.$enchant['weapA'].',', ','.$itemID.',') !== false) {
	$grade = 'A';
	$iwora = 1;
} else if(strpos(','.$enchant['armrA'].',', ','.$itemID.',') !== false) {
	$grade = 'A';
	$iwora = 2;
} else if(strpos(','.$enchant['weapS'].',', ','.$itemID.',') !== false) {
	$grade = 'S';
	$iwora = 1;
} else if(strpos(','.$enchant['armrS'].',', ','.$itemID.',') !== false) {
	$grade = 'S';
	$iwora = 2;
} else if(strpos(','.$enchant['weapS80'].',', ','.$itemID.',') !== false) {
	$grade = 'S80';
	$iwora = 1;
} else if(strpos(','.$enchant['armrS80'].',', ','.$itemID.',') !== false) {
	$grade = 'S80';
	$iwora = 2;
} else if(strpos(','.$enchant['weapS84'].',', ','.$itemID.',') !== false) {
	$grade = 'S84';
	$iwora = 1;
} else if(strpos(','.$enchant['armrS84'].',', ','.$itemID.',') !== false) {
	$grade = 'S84';
	$iwora = 2;
} else {
	fim($LANG[40049].' #2');
}

if($iwora == 1) { $iwora = 'w'; } else { $iwora = 'a'; }

$maxEnchantVar = 'max'.$iwora.$grade.'';
$maxEnchant = intval(trim($enchant[$maxEnchantVar]));

if($newEnchant > $maxEnchant) {
	fim('Max enchant: +'.$maxEnchant);
}

$minEnchantVar = 'min'.$iwora.$grade.'';
$minEnchant = intval(trim($enchant[$minEnchantVar]));


// Ok! Cost...

if($newEnchant <= $findItem[0]['enchant_level']) {
	
	$price = 0;
	
} else if($newEnchant <= $minEnchant) {
	
	$price = 0;
	
} else {
	
	$perVar = 'per'.$iwora.$grade.'';
	
	$prsCost = ';'.trim($enchant[$perVar]).';';
	
	if(strpos($prsCost, ';'.$newEnchant.':') !== false) {
		
		$xpld1 = explode(';'.$newEnchant.':', $prsCost);
		$xpld2 = explode(';', $xpld1[1]);
		
		$price = $xpld2[0];
		
	} else {
		
		$difEnch = $newEnchant - $findItem[0]['enchant_level'];
		if($difEnch < 0) {
			$difEnch = 0;
		}
		
		$difMin = ($minEnchant - $findItem[0]['enchant_level']);
		if($difMin < 0) {
			$difMin = 0;
		}
		
		$costVar = 'cos'.$iwora.$grade.'';
		$price = ($difEnch - $difMin) * intval(trim($enchant[$costVar]));
		
	}
	
}

if($price > 0) {
	if(debitBalance($_SESSION['acc'], $price) != 'OK') {
		fim($LANG[10097]);
	}
}


# dddddddddd - charId, wareHouse, itemDbId, newItemType, newItemAmount,\ # newEnchant, newEroded, newBless, newIdent, newWished # (char offline!)

$cached_op = pack("cVVVVVVVVVV", 14, intval($findItem[0]['char_id']), intval($findItem[0]['warehouse']), intval($findItem[0]['item_id']), intval($findItem[0]['item_type']), intval($findItem[0]['amount']), intval($newEnchant), 0, 0, 0, 0, 1);
$result = l2_cached_push(pack("s", strlen($cached_op)+2).$cached_op.ansi2unicode('site-atualstudio'));
if($result != '1') {
	fim($LANG[12055].' #CACHED');
}

Enchant::logEnchant($findItem[0]['item_id'], $itemObjID, $cid, $findItem[0]['enchant_level'], $newEnchant, $price);

fim($LANG[12056], 'OK', '', $price);
