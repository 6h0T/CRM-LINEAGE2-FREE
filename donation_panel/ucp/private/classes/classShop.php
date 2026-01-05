<?php

class Shop {

	public static function listCatego() {
		
		$sql = DB::Executa("SELECT * FROM site_shop_categories ORDER BY ordem ASC", "SITE");
		return $sql;
		
	}
	
	public static function listPacks($cat=0) {
		
		$sql = DB::Executa("SELECT * FROM site_shop_packs ".(!empty($cat) ? "WHERE scat_id = '".$cat."'" : "")." ORDER BY ordem ASC", "SITE");
		return $sql;
		
	}
	
	public static function findPack($pack) {
		
		$sql = DB::Executa("SELECT TOP 1 * FROM site_shop_packs WHERE spack_id = '".$pack."'", "SITE");
		return $sql;
		
	}
	
	public static function listItens($pack) {
		
		$sql = "SELECT * FROM site_shop_itens WHERE spack_id = '".$pack."' ORDER BY ordem ASC";
		return DB::Executa($sql, "SITE");
		
	}

	public static function listChars($acc) {
		
		$sql = DB::Executa("SELECT TOP 7 char_name, char_id AS obj_Id FROM user_data WHERE account_name = '".$acc."' AND char_name NOT LIKE '%\_%' ESCAPE '\'", "WORLD");
		return $sql;
		
	}
	
	public static function findChar($acc, $personagem) {
		
		$sql = DB::Executa("SELECT TOP 1 CASE WHEN (login > logout OR logout IS NULL) AND login IS NOT NULL THEN 1 ELSE 0 END AS online FROM user_data WHERE account_name = '".$acc."' AND char_id = '".$personagem."'", "WORLD");
		return $sql;
		
	}
	
	public static function findPrice($itensID) {
		
		$sql = DB::Executa("SELECT SUM(valor) AS price FROM site_shop_itens WHERE sitem_id IN (".$itensID.")", "SITE");
		return $sql;
		
	}
	
	public static function itensDetail($itensID) {
		
		$sql = DB::Executa("SELECT * FROM site_shop_itens WHERE sitem_id IN (".$itensID.")", "SITE");
		return $sql;
		
	}
	
	public static function insertItem($cid, $itemID, $count, $enchant) {
		
		
		$whatsTable = DB::Executa("SELECT TOP 1 * FROM SYSOBJECTS WHERE NAME = 'ItemDelivery' AND XTYPE = 'U'", "WORLD");
		if(count($whatsTable) > 0) {
			
			# Delivery 1 (ItemDelivery)
			$find = DB::Executa("SELECT TOP 1 * FROM ItemDelivery WHERE item_id = '".$itemID."' AND char_id = '".$cid."'", "WORLD");
			if(count($find) > 0) {
				$insert = DB::Executa("UPDATE ItemDelivery SET item_amount = (item_amount+".$count.") WHERE item_id = '".$itemID."' AND char_id = '".$cid."'", "WORLD");
			} else {
				$insert = DB::Executa("INSERT INTO ItemDelivery (char_id, item_id, item_amount, enchant) VALUES (".$cid.", ".$itemID.", ".$count.", ".$enchant.")", "WORLD");
			}
			
		} else {
			
			# Delivery 2 (user_delivery)
			$findAccID = DB::Executa("SELECT TOP 1 uid FROM user_account WHERE account = '".$_SESSION['acc']."'", "DB");
			$findCharName = DB::Executa("SELECT TOP 1 char_name FROM user_data WHERE char_id = '".$cid."'", "WORLD");
			$insert = DB::Executa("INSERT INTO user_delivery (account_id, char_id, account_name, char_name, item_id, quantity, enchant, status) VALUES ('".$findAccID[0]['uid']."', ".$cid.", '".$_SESSION['acc']."', '".$findCharName[0]['char_name']."', ".$itemID.", ".$count.", 0, 0)", "WORLD");
			
		}
		
		return $insert;
		
	}
	
	public static function logShop($acc, $multObjsID, $char, $itemID, $itemName, $itemSA, $packID, $amount, $enchant, $valor, $byFP, $byFP_price) {
		
		$sql = DB::Executa("
		INSERT INTO site_log_shop 
		(log_account, log_cid, log_item_objs_id, log_item_id, log_item_name, log_item_sa, log_pack_id, log_amount, log_enchant, log_price, log_date, by_full_pack, by_full_pack_price) VALUES 
		('".$acc."', '".$char."', '".$multObjsID."', '".$itemID."', '".$itemName."', '".$itemSA."', '".$packID."', '".$amount."', '".$enchant."', '".$valor."', GETDATE(), '".$byFP."', '".$byFP_price."')
		", "SITE");
		return $sql;
		
	}
	
}
