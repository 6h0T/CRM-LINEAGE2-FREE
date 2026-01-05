<?php

class Vote {
	
	public static function getLastVote($acc, $topsite_id) {
		$sql = DB::Executa("SELECT TOP 1 * FROM site_votes WHERE account = '".$acc."' AND topsite_id = '".$topsite_id."' ORDER BY vote_date DESC", "SITE");
		return $sql;
	}
	
	public static function canVote($acc, $topsite_id, $cooldownHours) {
		$lastVote = self::getLastVote($acc, $topsite_id);
		if(count($lastVote) > 0) {
			$cooldownSeconds = $cooldownHours * 3600;
			$timeSinceVote = time() - $lastVote[0]['vote_date'];
			if($timeSinceVote < $cooldownSeconds) {
				return array(
					'can_vote' => false,
					'time_remaining' => $cooldownSeconds - $timeSinceVote
				);
			}
		}
		return array('can_vote' => true, 'time_remaining' => 0);
	}
	
	public static function registerVote($acc, $topsite_id, $topsite_name, $ip, $reward) {
		$sql = DB::Executa("INSERT INTO site_votes (account, topsite_id, topsite_name, ip_address, reward, vote_date, status) VALUES ('".$acc."', '".$topsite_id."', '".$topsite_name."', '".$ip."', '".$reward."', '".time()."', '1')", "SITE");
		return $sql;
	}
	
	public static function getVoteHistory($acc, $limit = 50) {
		$sql = DB::Executa("SELECT TOP ".$limit." * FROM site_votes WHERE account = '".$acc."' ORDER BY vote_date DESC", "SITE");
		return $sql;
	}
	
	public static function getTotalVotes($acc) {
		$sql = DB::Executa("SELECT COUNT(*) AS total FROM site_votes WHERE account = '".$acc."' AND status = '1'", "SITE");
		return $sql;
	}
	
	public static function getTotalRewards($acc) {
		$sql = DB::Executa("SELECT SUM(reward) AS total FROM site_votes WHERE account = '".$acc."' AND status = '1'", "SITE");
		return $sql;
	}
	
	public static function verifyVoteAPI($api_url, $ip) {
		$url = $api_url . $ip;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if($httpCode == 200 && !empty($response)) {
			$data = json_decode($response, true);
			if(isset($data['voted']) && $data['voted'] == true) {
				return true;
			}
			if(isset($data['isVoted']) && $data['isVoted'] == true) {
				return true;
			}
			if(isset($data['result']) && $data['result'] == true) {
				return true;
			}
			if($response === 'true' || $response === '1') {
				return true;
			}
		}
		
		return false;
	}
	
	public static function addBalance($acc, $amount) {
		$checkExists = DB::Executa("SELECT TOP 1 * FROM site_balance WHERE account = '".$acc."'", "SITE");
		if(count($checkExists) > 0) {
			$sql = DB::Executa("UPDATE site_balance SET saldo = (saldo+".$amount.") WHERE account = '".$acc."'", "SITE");
		} else {
			$sql = DB::Executa("INSERT INTO site_balance (account, saldo) VALUES ('".$acc."', '".$amount."')", "SITE");
		}
		return $sql;
	}
	
	public static function deliverToCharacter($acc, $charId, $coinID, $amount) {
		$whatsTable = DB::Executa("SELECT TOP 1 * FROM SYSOBJECTS WHERE NAME = 'ItemDelivery' AND XTYPE = 'U'", "WORLD");
		if(count($whatsTable) > 0) {
			$find = DB::Executa("SELECT TOP 1 * FROM ItemDelivery WHERE item_id = '".$coinID."' AND char_id = '".$charId."'", "WORLD");
			if(count($find) > 0) {
				$insert = DB::Executa("UPDATE ItemDelivery SET item_amount = (item_amount+".$amount.") WHERE item_id = '".$coinID."' AND char_id = '".$charId."'", "WORLD");
			} else {
				$insert = DB::Executa("INSERT INTO ItemDelivery (char_id, item_id, item_amount, enchant) VALUES (".$charId.", ".$coinID.", ".$amount.", 0)", "WORLD");
			}
		} else {
			$findAccID = DB::Executa("SELECT TOP 1 uid FROM user_account WHERE account = '".$acc."'", "DB");
			$findCharName = DB::Executa("SELECT TOP 1 char_name FROM user_data WHERE char_id = '".$charId."'", "WORLD");
			$insert = DB::Executa("INSERT INTO user_delivery (account_id, char_id, account_name, char_name, item_id, quantity, enchant, status) VALUES ('".$findAccID[0]['uid']."', ".$charId.", '".$acc."', '".$findCharName[0]['char_name']."', ".$coinID.", ".$amount.", 0, 0)", "WORLD");
		}
		return $insert;
	}
	
	public static function getFirstCharacter($acc) {
		$sql = DB::Executa("SELECT TOP 1 char_id, char_name FROM user_data WHERE account_name = '".$acc."' ORDER BY char_id ASC", "WORLD");
		return $sql;
	}
	
	public static function formatTimeRemaining($seconds) {
		$hours = floor($seconds / 3600);
		$minutes = floor(($seconds % 3600) / 60);
		$secs = $seconds % 60;
		
		if($hours > 0) {
			return sprintf("%02d:%02d:%02d", $hours, $minutes, $secs);
		} else {
			return sprintf("%02d:%02d", $minutes, $secs);
		}
	}
	
}

