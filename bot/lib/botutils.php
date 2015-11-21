<?php

class botutil {
	
	public static $message = array();
	public static $profiles = array(); // Collect for Username on target site
	
	public static function getPdo()
	{
		
		$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
		return new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot", $options);
	}
	
	public static function getAgentString()
	{
		$pdo = self::getPdo();
		
		$sql = "SELECT id, agent_string FROM browser ORDER BY count ASC, RAND() LIMIT 1";
		$sth = $pdo->prepare($sql);
		
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		$sql = "UPDATE browser SET count = count + 1 WHERE id = :id ";
		$sth = $pdo->prepare($sql);
		$sth->execute(array(":id" => $row['id']));
		
		$pdo = null;
		
		return trim($row['agent_string']);
	}
	
	public static function getNewProfile($site_id, $username, &$command, $bot = null)
	{
		$pdo = self::getPdo();
		
		$sql = "SELECT sex FROM user_profiles WHERE username = :username AND site_id = :site_id ";
		
		$sth = $pdo->prepare($sql);
		$sth->execute(array(':username' => $username, ':site_id' => $site_id));
		
		$row = $sth->fetch();
		$profile_sex = $row['sex'];
		
		if(isset($command['repeat_profile']) && $command['repeat_profile'] == "Y"){
		
			$sql = "SELECT id, username, password FROM user_profiles WHERE (site_id='".$site_id."') AND (status='true') AND (in_use='false') AND (sex='".$profile_sex."') and (username!='".$username."') AND ";
			$sql .= "(";
		
			if(isset($command['msg_type']) && $command['msg_type'] == "pm"){
				$sql .= "(lastused < DATE_SUB(NOW(), INTERVAL 24 HOUR)) or ";
			}
		
			$sql .= "(lastused='0000-00-00 00:00:00') or (used='false')) ORDER BY lastused LIMIT 1";
				
		}else{
			$sql = "SELECT id, username, password FROM user_profiles WHERE (site_id='".$site_id."') AND (status='true') AND (in_use='false') AND (sex='".$profile_sex."') and (username!='".$username."') ORDER BY lastused Limit 1";
		}

		if ($bot !== null) $bot->savelog($sql);
		
		$sth = $pdo->prepare($sql);
		
		$sth->execute();
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		$sql = "UPDATE user_profiles SET lastused = NOW() WHERE id = :id ";
		$sth = $pdo->prepare($sql);
		$sth->execute(array(":id" => $row['id']));
		
		/*-----------------------------------------------------------------
		 * SET Profile Status
		 *----------------------------------------------------------------*/
		if(empty($command['profile_banned'])) {
			$sql = "UPDATE user_profiles SET in_use='false' WHERE username = :username AND site_id = :site_id ";
			$sth = $pdo->prepare($sql);
			$sth->execute(array(':username' => $username, ':site_id' => $site_id));
		} else {
			$sql = "UPDATE user_profiles SET status='false', in_use='false' WHERE username = :username AND site_id = :site_id ";
			$sth = $pdo->prepare($sql);
			$sth->execute(array(':username' => $username, ':site_id' => $site_id));
			unset($command['profile_banned']);
		}
		$pdo = null;
		return $row;
	}
	
	public static function profileCount($site_id = null, $username = null){
		if($site_id != null && $username != null) {
			$pdo = self::getPdo();
			$sql = "UPDATE user_profiles SET login_count = login_count + 1 WHERE username = :username AND site_id = :site_id ";
			$sth = $pdo->prepare($sql);
			$sth->execute(array(':username' => $username, ':site_id' => $site_id));
			$pdo = null;
			return true;
		}
		return false;
	}

	public static function setNoResponse($bot_id = 0, $status = FALSE, $bot = null) {
		$pdo = self::getPdo();
		$sql = "UPDATE commands SET response = :response WHERE id = :id ";
		$sth = $pdo->prepare($sql);
		$sth->execute(array(':id' => $bot_id, ':response' => (($status == TRUE) ? 3 : 1)));
		$pdo = null;
	}
	
	/**
	 * Get message text
	 * 
	 * @access public
	 * @param object ( Bot Class )
	 * @param string ( Male / Female / Gay / Lesbian )
	 * @param string ( Lang code : DE / EN )
	 */
	public static function getMessageText($bot, $gender, $lang = 'DE') {
		$pdo = self::getPdo();
		
		if(empty(self::$message)) {
			$username = array();
			$profile_type = ((empty($bot->command['profile_type'])) ? '' : $bot->command['profile_type']);
			$target_site = ((empty($bot->command['target_cm'])) ? '' : $bot->command['target_cm']);
			
			/*-----------------------------------------------------------------
			 * Get username !!
			 *----------------------------------------------------------------*/
			// Checking username for target site and profile type are avaliable ?
			$sql = 'SELECT COUNT(*) as total_rows FROM messages_part2 WHERE 
				status = :status AND 
				language = :language AND 
				target = :target AND 
				part = :part AND 
				target_cm = :target_cm AND
				profile_type = :profile_type';
			$sth = $pdo->prepare($sql);
			$sth->execute(array(
				':status' => 'true',
				':language' => $lang,
				':target' => $gender,
				':part' => '4',
				':target_cm' => $target_site,
				':profile_type' => $profile_type
			));
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			
			if($row['total_rows'] == 0) {
				$sql = 'SELECT message FROM messages_part2 WHERE status = :status AND language = :language AND target = :target AND part = :part';
				$sth = $pdo->prepare($sql);
				$sth->execute(array(
					':status' => 'true',
					':language' => $lang,
					':target' => $gender,
					':part' => '4'
				));
				$row = $sth->fetchAll(PDO::FETCH_ASSOC);
			} else {
				$sql = 'SELECT message FROM messages_part2 WHERE 
					status = :status AND 
					language = :language AND 
					target = :target AND 
					part = :part AND 
					target_cm = :target_cm AND
					profile_type = :profile_type';
				$sth = $pdo->prepare($sql);
				$sth->execute(array(
					':status' => 'true',
					':language' => $lang,
					':target' => $gender,
					':part' => '4',
					':target_cm' => $target_site,
					':profile_type' => $profile_type
				));
				$row = $sth->fetchAll(PDO::FETCH_ASSOC);
			}

			if(!empty($row)) {
				foreach($row as $r) {
					self::$profiles[] = $r['message'];
				}
			} else {
				// Random Username if not exist
				switch($lang) {
					case 'EN':
						self::$profiles = array(
							'Charlotte', 'Shannon', 'Emilia', 'Annabelle', 'Jennie'
						);
						break;
					case 'DE':
					default:
						self::$profiles = array(
							'Michaela', 'Christina', 'Vanessa', 'Jessica', 'Luise'
						);
						break;
				}
			}
			
			/*-----------------------------------------------------------------
			 * Get message part
			 *----------------------------------------------------------------*/
			for($i = 1; $i <= 3; $i++ ) {
				$sql = 'SELECT COUNT(*) as total_rows FROM messages_part2 WHERE 
					status = :status AND 
					language = :language AND 
					target = :target AND 
					part = :part';
				$sth = $pdo->prepare($sql);
				$sth->execute(array(
					':status' => 'true',
					':language' => $lang,
					':target' => $gender,
					':part' => $i
				));
				$row = $sth->fetch(PDO::FETCH_ASSOC);
				
				if($row['total_rows'] == 0) {
					$sql = 'SELECT message FROM messages_part2 WHERE 
						status = :status AND 
						language = :language AND 
						target = :target AND 
						part = :part';
					$sth = $pdo->prepare($sql);
					$sth->execute(array(
						':status' => 'true',
						':language' => $lang,
						':target' => 'Male',
						':part' => $i
					));
					$row = $sth->fetchAll(PDO::FETCH_ASSOC);
				} else {
					$sql = 'SELECT message FROM messages_part2 WHERE 
						status = :status AND 
						language = :language AND 
						target = :target AND 
						part = :part';
					$sth = $pdo->prepare($sql);
					$sth->execute(array(
						':status' => 'true',
						':language' => $lang,
						':target' => $gender,
						':part' => $i
					));
					$row = $sth->fetchAll(PDO::FETCH_ASSOC);
				}

				if(!empty($row)) {
					if(empty(self::$message[$i])) {
						self::$message[$i] = array();
					}
					
					foreach($row as $r) {
						self::$message[$i][] = trim($r['message']);
					}
				}
			}
		}

		/*-----------------------------------------------------------------
		 * Random message system 
		 *----------------------------------------------------------------*/
		 $message = '';
		 $mrand = rand(0,(count($bot->command['messages'])-1));
		 $full_msg = ((empty($bot->command['full_msg'])) ? 0 : $bot->command['full_msg']);
		 
		 if($full_msg == 1) {
		 	$message = $bot->command['messages'][$mrand]['message'];	
		 } else {
			// Message part
			for($i = 1; $i <= 3; $i++) {
				if(!empty(self::$message[$i])) {
					$rand = rand(0, (count(self::$message[$i])-1));
					$message .= self::$message[$i][$rand].' ';
					if($i == 2) {
						$message .= $bot->command['messages'][$mrand]['message'].' ';
					}
				}
			 }
			 $message .= self::$profiles[rand(0, (count(self::$profiles)-1))];
		 }
		 
		 /*-----------------------------------------------------------------
		  * Random title system 
		  *----------------------------------------------------------------*/
		 $subject = $bot->command['messages'][$mrand]['subject'];
		 
		 /*-----------------------------------------------------------------
		  * Close pdo
		  *----------------------------------------------------------------*/
		 $pdo = null;
		 
		 /*-----------------------------------------------------------------
		  * Return message
		  *----------------------------------------------------------------*/
		 return array(
		 	'subject' => $subject,
		 	'message' => $message
		 );
	}
}
