<?php

class Services {
	
	public static function findChars($login) {
		
		$sql = DB::Executa("
		SELECT
			TOP 7
			C.char_id AS obj_Id, 
			C.use_time AS onlinetime, 
			C.char_name, 
			C.nickname AS title, 
			(CONVERT(bigint, (DATEDIFF(SECOND,{d '1970-01-01'}, C.login) + 10800)) * 1000) AS lastAccess, 
			CASE WHEN (C.login > C.logout OR C.logout IS NULL) AND C.login IS NOT NULL THEN 1 ELSE 0 END AS online, 
			C.subjob0_class AS base_class, 
			C.subjob1_class AS subclass1, 
			C.subjob2_class AS subclass2, 
			C.subjob3_class AS subclass3, 
			C.Lev AS level, 
			C.gender AS sex, 
			C.Duel AS pvpkills, 
			C.PK AS pkkills, 
			C.Align AS karma, 
			CLAN.name AS clan_name, 
			ALLY.name AS ally_name,
			CASE WHEN UN1.nobless_type IS NOT NULL THEN 1 ELSE 0 END AS nobless,  
			CASE WHEN UN2.hero_type IS NOT NULL THEN 1 ELSE 0 END AS hero_end
		FROM
			user_data AS C
		LEFT JOIN
			Pledge AS CLAN ON CLAN.pledge_id = C.pledge_id
		LEFT JOIN
			Alliance AS ALLY ON ALLY.id = CLAN.alliance_id
		LEFT JOIN
			user_nobless AS UN1 ON UN1.char_id = C.char_id AND UN1.nobless_type > 0
		LEFT JOIN
			user_nobless AS UN2 ON UN2.char_id = C.char_id AND UN2.hero_type > 0
		WHERE
			C.account_name = '".$login."'
			AND C.char_name NOT LIKE '%\_%' ESCAPE '\'
		", "WORLD");
		return $sql;
		
	}
	
	public static function checkChar($acc, $cid) {
		
		$sql = DB::Executa("SELECT TOP 1 * FROM user_data WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function changeNameColor($acc, $cid, $cor) {
		
		$checkCharExist = DB::Executa("SELECT TOP 1 * FROM user_name_color WHERE char_id = '".$cid."'", "WORLD");
		if(count($checkCharExist) == 0) {
			$sql = DB::Executa("INSERT INTO user_name_color VALUES ('".$cid."', '".intval($cor)."')", "WORLD");
		} else {
			$sql = DB::Executa("UPDATE user_name_color SET color_rgb = '".intval($cor)."' WHERE char_id = '".$cid."'", "WORLD");
		}
		return $sql;
		
	}
	
	public static function changeTitleColor($acc, $cid, $cor) {
		
		$sql = DB::Executa("UPDATE user_data SET TitleColor = '".intval($cor)."' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function cleanKarma($acc, $cid) {
		
		$sql = DB::Executa("UPDATE user_data SET align = '0' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function resetPK($acc, $cid) {
		
		$sql = DB::Executa("UPDATE user_data SET PK = '0' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function checkNameExists($name) {
		
		$sql = DB::Executa("SELECT TOP 1 * FROM user_data WHERE char_name = '".$name."'", "WORLD");
		return $sql;
		
	}
	
	public static function changeNickname($acc, $cid, $name) {
		
		$sql = DB::Executa("UPDATE user_data SET char_name = '".$name."' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function logServices($acc, $cid, $key, $values, $price) {
		
		$sql = DB::Executa("INSERT INTO site_log_services (log_account, log_cid, log_key, log_value, log_price, log_date) VALUES ('".$acc."', '".$cid."', '".$key."', '".$values."', '".$price."', GETDATE())", "SITE");
		return $sql;
		
	}
	
	public static function checkClanExists($cid, $clanID) {
		
		$sql = DB::Executa("SELECT TOP 1 * FROM Pledge WHERE pledge_id = '".$cid."' AND ruler_id = '".$clanID."'", "WORLD");
		return $sql;
		
	}
	
	public static function checkClanNameExists($name) {
		
		$sql = DB::Executa("SELECT TOP 1 * FROM Pledge WHERE name = '".$name."'", "WORLD");
		return $sql;
		
	}
	
	public static function changeClanName($cid, $clanID, $name) {
		
		$sql = DB::Executa("UPDATE Pledge SET name = '".$name."' WHERE pledge_id = '".$clanID."' AND ruler_id = '".$cid."'", "WORLD");
		return $sql;
		
	}
	
	public static function changeClanNameLog($cid, $clanID, $name_old, $name_new) {
		
		$sql = DB::Executa("INSERT INTO site_log_changeclanname (cid, clanid, name_old, name_new, changedate) VALUES ('".$cid."', '".$clanID."', '".$name_old."', '".$name_new."', GETDATE())", "SITE");
		return $sql;
		
	}
	
	public static function changeSex($acc, $cid, $sex) {
		
		$sql = DB::Executa("UPDATE user_data SET gender = '".$sex."' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function findAccs($acc) {
		
		$sql = DB::Executa("SELECT ssn.*, ssn.name AS login, ACC.uid AS accid FROM ssn INNER JOIN user_account AS ACC ON ACC.account = ssn.name WHERE ssn.email = (SELECT email FROM ssn WHERE name = '".$acc."') AND ssn.name <> '".$acc."'", "DB");
		return $sql;
		
	}
	
	public static function transferCharacter($acc, $cid, $newacc, $accid) {
		
		$sql = DB::Executa("UPDATE user_data SET account_name = '".$newacc."', account_id = '".$accid."' WHERE char_id = '".$cid."' AND account_name = '".$acc."'", "WORLD");
		return $sql;
		
	}
	
	public static function Unstuck($acc, $cid, $x, $y, $z) {
		$sql = "UPDATE user_data SET xloc = '".$x."', yloc = '".$y."', zloc = '".$z."' WHERE char_id = '".$cid."' AND account_name = '".$acc."'";
		return DB::Executa($sql, "WORLD");
		
	}
	
	public static function checkIsNobless($cid) {
		$sql = "SELECT TOP 1 * FROM user_nobless WHERE nobless_type > 0 AND char_id = '".$cid."'";
		return DB::Executa($sql, "WORLD");
	}

	public static function setNobless($cid) {
		$sql = "INSERT INTO user_nobless (char_id, nobless_type) VALUES ('".$cid."', 1)";
		return DB::Executa($sql, "WORLD");
	}

	public static function checkIsHero($cid) {
		$sql = "SELECT TOP 1 * FROM user_nobless WHERE nobless_type > 0 AND char_id = '".$cid."'";
		return DB::Executa($sql, "WORLD");
	}

	public static function setHero($cid) {
		$sql = "UPDATE user_nobless SET hero_type = '2' WHERE char_id = '".$cid."'";
		return DB::Executa($sql, "WORLD");
	}

	public static function countNewAccChars($acc) {
		$sql = "SELECT COUNT(*) AS quant FROM user_data WHERE account_name = '".$acc."' AND char_name NOT LIKE '%\_%' ESCAPE '\'";
		return DB::Executa($sql, "WORLD");
		
	}

	public static function checkHasClassInSub($cid, $classes) {
		$sql = "SELECT TOP 1 * FROM user_data WHERE char_id = '".$cid."' AND (subjob0_class IN (".$classes.") OR subjob1_class IN (".$classes.") OR subjob2_class IN (".$classes.") OR subjob3_class IN (".$classes."))";
		return DB::Executa($sql, "WORLD");
		
	}

}

