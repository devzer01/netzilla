<?php
require_once('config.php');

class funcs
{
	function check_input($value)
	{
		// Stripslashes
		if (get_magic_quotes_gpc())
		{
			$value = stripslashes($value);
		}
		// Quote if not a number
		if (!is_numeric($value))
		{
			//$value = mysql_real_escape_string($value) ;
			$value = addslashes($value);
		}
		return $value;
	}


	/*********************************
	 ** START REGISTERING FUNCTION  **
	 *********************************/

	#STEP1
	static function registerMember($save)
	{
		$clean_save = array();
		foreach($save as $key => $val)
		{
			$clean_save[$key] = funcs::check_input($val);
		}
		//get column names
		$colnames = array_flip(DBconnect::get_col_names(TABLE_MEMBER));
		//delete everything that is not in the database
		$member_post = array_intersect_key($clean_save, $colnames);
		//create the member and get the id from the creation
		return DBconnect::assoc_insert_1D($member_post, TABLE_MEMBER);
	}

	/*#Noi paste this function
	static function checkStep($username, $password, $code)
	{
		if(funcs::checkInCompleteInfo($username, $password, $code))
			return '?action=incompleteinfo';
		else if(funcs::checkMobileVerify($username))
			return '?action=mobileverify';
		else if(funcs::chekActivateMember($username, $password, $code, $adv))
			return '';

	}*/

	#STEP2
	#Noi paste this function
	function checkInCompleteInfo($username, $password, $code)
	{
		$username = funcs::check_input($username);
		$password = funcs::check_input($password);
		$code = funcs::check_input($code);

		$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER."
					WHERE ".TABLE_MEMBER_USERNAME."='".$username."'
						AND ".TABLE_MEMBER_PASSWORD."='".$password."'
						AND ".TABLE_MEMBER_VALIDATION."='".$code."'
						AND ".country."=0
						AND ".state."=0
						AND ".city."=0";
		$row = DBconnect::get_nbr($sql);
		if($row > 0)
			return true;
		else
			return false;
	}

	#STEP3
	#Singh paste this function
	#Noi changed on 2012-03-22, adding condition
	static function checkMobileVerify($username, $password = null, $code = null)
	{
		$username = funcs::check_input($username);

		if($password != null && $code != null)
		{
			$password = funcs::check_input($password);
			$code = funcs::check_input($code);
			$no = DBconnect::retrieve_value("SELECT waitver_mobileno
											 FROM ".TABLE_MEMBER."
											 WHERE ".TABLE_MEMBER_USERNAME."='".htmlspecialchars($username,ENT_QUOTES,'UTF-8')."'
											 AND ".TABLE_MEMBER_PASSWORD."='".$password."'
											 AND ".TABLE_MEMBER_VALIDATION."='".$code."' ");
		}
		else
			$no = DBconnect::retrieve_value("SELECT waitver_mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".htmlspecialchars($username,ENT_QUOTES,'UTF-8')."'");

		return $no;
	}

	#Singh paste this function
	static function getWaitVerifyMobile($usr){
		if(strlen(trim($usr)) > 0){
			$no = funcs::checkMobileVerify($usr);
			return $no;
		}
		else{
			return 0;
		}
	}

	#Singh paste this function
	static function verifyMobile($usr,$code){
		$usr = funcs::check_input($usr);
		$code = funcs::check_input($code);

		$expired_time = 7*24*60*60;
		if(strlen(trim($usr)) > 0)
		{
			$usrId = funcs::getUserid(htmlspecialchars($usr,ENT_QUOTES,'UTF-8'));
			if(ctype_digit($usrId) && strlen(trim($code)) > 0)
			{
				$chk = DBConnect::assoc_query_1D("SELECT vcode_mobile,waitver_mobileno,vcode_mobile_insert_time FROM ".TABLE_MEMBER." WHERE id=".$usrId);
				if(strlen(trim($chk['waitver_mobileno'])) > 0 && strlen(trim($chk['vcode_mobile'])) > 0 && $chk['vcode_mobile'] == $code)
				{
					if(time() <= ((int)$chk['vcode_mobile_insert_time']+$expired_time))
					{
						$query = "UPDATE ".TABLE_MEMBER." SET validated='1',mobileno='".$chk['waitver_mobileno']."',waitver_mobileno='' WHERE id=".$usrId;
						DBconnect::execute_q($query);
						
						$username = funcs::findUserName($usrId);
						$coinVal = funcs::checkCoin($username);

						//$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('1','$usrId','Mobile Verify','30',".$coinVal.", NOW())";
						//DBconnect::execute($sqlAddCoinLog);

						return 1;
					}
					else{
						return 4;
					}
				}
				else{
					return 3;
				}
			}
			else{
				return 2;
			}
			unset($usrId);
		}
	}
	#STEP4
	#Noi paste this function
	static function chekActivateMember($username, $password, $code)
	{
		$username = funcs::check_input($username);
		$password = funcs::check_input($password);
		$code = funcs::check_input($code);

		$status = DBconnect::retrieve_value("SELECT isactive FROM ".TABLE_MEMBER."
											 WHERE ".TABLE_MEMBER_USERNAME."='".htmlspecialchars($username,ENT_QUOTES,'UTF-8')."'
											 AND ".TABLE_MEMBER_PASSWORD."='".$password."'
											 AND ".TABLE_MEMBER_VALIDATION."='".$code."' ");
		if($status == 1)
			return true;
		else
			return false;
	}

	#Noi paste this function
	static function getNecessaryInfo($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT ".TABLE_MEMBER_PASSWORD.", ".TABLE_MEMBER_VALIDATION."
				FROM ".TABLE_MEMBER."
				WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$rec = DBconnect::assoc_query_1D($sql);

		return $rec;
	}

	#Noi paste this function
	static function getRealIpAddr()
	{
		//if (!empty($_SERVER['HTTP_CLIENT_IP']) && ($_SERVER['HTTP_CLIENT_IP']!="unknown"))   //check ip from share internet
			//$ip = $_SERVER['HTTP_CLIENT_IP'];
			
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && ($_SERVER['HTTP_X_FORWARDED_FOR']!="unknown"))   //to check ip is pass from proxy
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	static function curPageURL()
	{
		/*$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80")
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}
		else
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}*/
		$pageURL = $_SERVER["REQUEST_URI"];
		return $pageURL;
	}

	#Noi paste this function
	static function preventMultipleRegister($username)
	{
		setcookie("lovelysingle_activated", $username, time()+60*60*24*30*365, "/");//, ".yourbuddy24.com", 1, true);
		$ip = funcs::getRealIpAddr();
		$sql = "UPDATE ".TABLE_MEMBER." SET ip_address='".$ip."'
						WHERE ".TABLE_MEMBER_USERNAME."='".$username."' LIMIT 1";
		DBconnect::execute($sql);
	}

	static function checkAllowRegister()
	{
		$ip = funcs::getRealIpAddr();
		$sql = "SELECT COUNT(id) AS num FROM ".TABLE_MEMBER." WHERE ip_address = '".$ip."'";
		$rcount = DBconnect::retrieve_value($sql);
		
		if($rcount>0)
			return false;
		else
			return true;
	}

	#Pakin Change this function
	function activateMember($username, $password, $code, $adv=0)
	{
		$username = funcs::check_input($username);
		$password = funcs::check_input($password);
		$code = funcs::check_input($code);
		$adv = funcs::check_input($adv);

		$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER."
					WHERE ".TABLE_MEMBER_USERNAME."='".$username."'
						AND ".TABLE_MEMBER_PASSWORD."='".$password."'
						AND ".TABLE_MEMBER_VALIDATION."='".$code."'
						AND ".TABLE_MEMBER_ISACTIVE."=0
						AND signin_datetime = '0000-00-00 00:00:00'";
		$row = DBconnect::get_nbr($sql);

		if($row > 0)
		{
			$vcode = funcs::randomPassword(6);
			$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE."=1, coin='".FREECOINS."', validation_code = '$vcode', isactive_datetime=NOW()
						WHERE ".TABLE_MEMBER_USERNAME."='".$username."' LIMIT 1";
			DBconnect::execute($sql);

			$userid = funcs::getUserid($username);
			//INSERT COIN LOG
			$coinVal = funcs::checkCoin($username);
			$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('1','$userid','Activate Member',".FREECOINS.",".$coinVal.", NOW())";
			DBconnect::execute($sqlAddCoinLog);

			$subject = funcs::getText($_SESSION['lang'], '$first_time_inbox_subject');
			$message = funcs::getText($_SESSION['lang'], '$first_time_inbox_message');
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
					".TABLE_MESSAGE_INBOX_FROM."=2,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute($sql);

			funcs::preventMultipleRegister($username);

			#Pakin Change this function
			self::NewSorting($username);
			return true;
		}
		else
			return false;
	}

	#Noi paste this function
	function checkIncompleteInfoById($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT country, state, city, waitver_mobileno, mobileno FROM ".TABLE_MEMBER."  WHERE id = '".$userid."'";
		$row = DBconnect::assoc_query_1D($sql);

		if($row['country'] == '0' && $row['state'] == '0' && $row['city'] == '0')
		{
			return 2; //go to register step2
		}
		else if(!(isset($row['mobileno'])) || $row['mobileno'] == "")
		{
			return 3; //go to mobile verify
		}
		else
		{
			return 1; //complete info, users can access to the link which allow their permission
		}
	}

	/*********************************
	 **  END REGISTERING FUNCTION   **
	 *********************************/


	#Danny paste this function
	function checkMember($username, $password, $code=0, $adv=0)
	{
		$username = funcs::check_input($username);
		$password = funcs::check_input($password);
		$code= funcs::check_input($code);
		$adv= funcs::check_input($adv);

		if($code == '' or $code == '0')
		{
			$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER."
								   WHERE ".TABLE_MEMBER_USERNAME."='".$username."'
									 AND ".TABLE_MEMBER_PASSWORD."='".$password."'";
			$row = DBconnect::get_nbr($sql);

			if($row > 0)
				return true;
			else
				return false;
		}
		else
		{
			$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER."
								   WHERE ".TABLE_MEMBER_USERNAME."='".$username."'
						      		 AND ".TABLE_MEMBER_PASSWORD."='".$password."'
									 AND ".TABLE_MEMBER_VALIDATION."='".$code."'";
			$row = DBconnect::get_nbr($sql);

			if($row > 0)
				return true;
			else
				return false;
		}
	}
	#Danny paste this function
	function checkAdvertise($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT advertise_regist FROM ".TABLE_MEMBER."
				   WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		return $row = DBconnect::get_nbr($sql);
	}
	#Danny paste this function
	function activateMember2($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT isactive FROM ".TABLE_MEMBER."
				   WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$row = DBconnect::get_nbr($sql);
		if($row == 0)
		{
			$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE."=1
						WHERE ".TABLE_MEMBER_USERNAME."='".$username."' LIMIT 1";
			DBconnect::execute($sql);

			$userid = funcs::getUserid($username);

			//Hauke
			$membership = 3;
			$rate = 1;
			$paid_via = 3;
			$payment_complete = 1;
			$today = date("Y-m-d");

			funcs::insertpayment($userid,$membership,$rate,$paid_via, $payment_complete);

			$sql = "UPDATE ".TABLE_PAY_LOG." SET "."real_name"."='$real_name',
											 	 "."real_street"."='$street',
												 "."real_city"."='$city',
												 "."sum_paid"."='0',
												 "."cancelled"."='1',
												 "."cancelled_date"."='$today'
									   	   WHERE "."username"."='$username'";
			DBconnect::execute($sql);

			$sql = "UPDATE ".TABLE_MEMBER." SET "."type"."='$membership',
										"."signup_datetime=now(),
										"."advertise_regist='2'
									  	  WHERE username ='$username'";
			DBconnect::execute($sql);

			//Hauke end

			$subject = funcs::getText($_SESSION['lang'], '$first_time_inbox_subject');
			$message = funcs::getText($_SESSION['lang'], '$first_time_inbox_message');
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
					".TABLE_MESSAGE_INBOX_FROM."=1,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute($sql);
			self::NewSorting($username);
			return true;
		}
		else
			return false;
	}


	#Noi paste this function
	function completeInfo($postArray)
	{
		$sql = "";
		if(count($postArray)>0)
		{
			$sql = "UPDATE ".TABLE_MEMBER." SET ";
			$i = 0;
			foreach($postArray as $key => $val)
			{
				$val = funcs::check_input($val);

				if($i>0)
					$sql .= ", ";

				$sql .= $key . "='".$val."'";
				$i++;
			}
			if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username']!=""))
				$sql .= " WHERE username='".$_SESSION['sess_username']."'";
			else
				$sql .= " WHERE username='".$_SESSION['registered_u']."'";
		}
		DBconnect::execute($sql);
	}
	#Noi paste this function
	function increaseVcodeCount($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "UPDATE ".TABLE_MEMBER." SET vcode_count = vcode_count+1 WHERE id = '".$userid."'";
		DBconnect::execute($sql);
	}
	function checkVcodeCount($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT vcode_count FROM ".TABLE_MEMBER." WHERE id = '".$userid."'";
		$vcount = DBconnect::retrieve_value($sql);

		if($vcount < 3)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	#Pakin Change this function => new!!!
	function NewSorting($username)
	{
		$username = funcs::check_input($username);

		$rec = self::findSortingDatas($username);
		$flag = $rec['flag'];
		$gender = $rec['gender'];
		$birthday = $rec['birthday'];
		$city = $rec['city'];
		$count = $rec['count1'];
		$isactive = $rec['isactive'];
		if($count>=1){ self::shiftSorting($username);}
		if($isactive==1){
			$count = self::findSortingCount($username);
			$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_COUNT."=".TABLE_MEMBER_COUNT."+1
						WHERE ".TABLE_MEMBER_COUNT." >= $count
							AND ".TABLE_MEMBER_CITY." = '$city'
							AND ".TABLE_MEMBER_GENDER." = '$gender'";
			DBconnect::execute_q($sql);
			$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_COUNT."= $count WHERE ".TABLE_MEMBER_USERNAME."= '$username'";
			DBconnect::execute_q($sql);
		}
	}
	#Pakin Change this function => new!!!
	function shiftSorting($username)
	{
		$username = funcs::check_input($username);

		$rec = self::findSortingDatas($username);
		$flag = $rec['flag'];
		$gender = $rec['gender'];
		$birthday = $rec['birthday'];
		$count = $rec['count1'];
		$city = $rec['city'];
		$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_COUNT."= 0 WHERE ".TABLE_MEMBER_USERNAME."= '$username'";
		DBconnect::execute_q($sql);
		$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_COUNT."=".TABLE_MEMBER_COUNT."-1
					WHERE ".TABLE_MEMBER_COUNT."> $count
						AND ".TABLE_MEMBER_CITY." = '$city'
						AND ".TABLE_MEMBER_GENDER." = '$gender'";
		return DBconnect::execute_q($sql);
	}
	#Pakin Change this function => new!!!
	function findSortingCount($username)
	{
		$username = funcs::check_input($username);

		$rec = self::findSortingDatas($username);
		$flag = $rec['flag'];
		$gender = $rec['gender'];
		$birthday = $rec['birthday'];
		$count = $rec['count1'];
		$city = $rec['city'];
		$pic = $rec['pic'];
		if($pic){
			" AND ".TABLE_MEMBER_PICTURE." != ''";
		} else {
			" AND ".TABLE_MEMBER_PICTURE." = ''";
		}
		$sql = "SELECT ".TABLE_MEMBER_COUNT." AS count1
					FROM ".TABLE_MEMBER."
					WHERE ".TABLE_MEMBER_CITY." = '$city'
						AND ".TABLE_MEMBER_GENDER." = '$gender'
						AND ".TABLE_MEMBER_BIRTHDAY." <= '$birthday'
						AND ".TABLE_MEMBER_FLAG." = '$flag'
						AND ".TABLE_MEMBER_ISACTIVE." = 1
					ORDER BY ".TABLE_MEMBER_COUNT." ASC";
		$rec = DBconnect::assoc_query_1D ($sql);
		$count = $rec['count1']+1;
		return $count;
	}
	#Pakin Change this function => new!!!
	function findSortingDatas($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT ".TABLE_MEMBER_FLAG." AS flag,"
				  .TABLE_MEMBER_GENDER." AS gender,"
				  .TABLE_MEMBER_BIRTHDAY." AS birthday,"
				  .TABLE_MEMBER_COUNT." AS count1,"
				  .TABLE_MEMBER_CITY." AS city,"
				  .TABLE_MEMBER_PICTURE." AS pic,"
				  .TABLE_MEMBER_ISACTIVE." AS isactive
				 FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."= '$username' ";
		$rec = DBconnect::assoc_query_1D ($sql);
		return $rec;
	}
	#Pakin Change this function => new!!!
	function findUserName($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT ".TABLE_MEMBER_USERNAME." AS user
					FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."= '$userid' ";
		$rec = DBconnect::assoc_query_1D ($sql);
		$username = $rec['user'];
		return $username;
	}
	static function addFavorite($parentid, $childid)
	{
		$parentid = funcs::check_input($parentid);
		$childid = funcs::check_input($childid);

		$sql = "SELECT COUNT(*) FROM ".TABLE_FAVORITE." WHERE ".TABLE_FAVORITE_PARENT."=".$parentid." AND ".TABLE_FAVORITE_CHILD."=".$childid;
		if(DBconnect::get_nbr($sql)>0)
			echo 2;
		else
		{
			$sql = "INSERT INTO ".TABLE_FAVORITE."
					SET		".TABLE_FAVORITE_PARENT."=".$parentid.",
							".TABLE_FAVORITE_CHILD."=".$childid.",
							".TABLE_FAVORITE_DATETIME."='".funcs::getDateTime()."'";
			if(DBconnect::execute_q($sql))
				echo 1;
			else
				echo 0;
		}
	}

	static function addLonelyHeart($save)
	{
		$clean_save = array();
		foreach($save as $key => $val)
		{
			$clean_save[$key] = funcs::check_input($val);
		}
		//send message to email chat tool
		funcs::sendMessage($save[TABLE_LONELYHEART_USERID], 'Kontaktanzeige', $save[TABLE_LONELYHEART_HEADLINE], $save[TABLE_LONELYHEART_TEXT], 5, true);

		//get column names
		$colnames = array_flip(DBconnect::get_col_names(TABLE_LONELYHEART));
		//delete everything that is not in the database
		$member_post = array_intersect_key($clean_save, $colnames);
		//create the member and get the id from the creation
		return DBconnect::assoc_insert_1D($member_post, TABLE_LONELYHEART);
	}

	static function addLonelyHeartSoap($userid, $target, $category, $headline, $message)
	{
		$userid = funcs::check_input($userid);
		$target = funcs::check_input($target);
		$category = funcs::check_input($category);
		$headline = funcs::check_input($headline);
		$message = funcs::check_input($message);

		$sql = "INSERT INTO ".TABLE_LONELYHEART."
		SET		".TABLE_LONELYHEART_USERID."='$userid',
			".TABLE_LONELYHEART_TARGET."='$target',
			".TABLE_LONELYHEART_CATEGORY."='$category',
			".TABLE_LONELYHEART_HEADLINE."='$headline',
			".TABLE_LONELYHEART_TEXT."='$message';
			".TABLE_LONELYHEART_DATETIME."='".funcs::getDateTime()."'";
		return DBconnect::execute_q($sql);
	}

	static function addMessage_archive($userid, $messageid)
	{
		$userid = funcs::check_input($userid);

		$list = '';
		$sql = "UPDATE ".TABLE_MESSAGE_INBOX." SET ".TABLE_MESSAGE_INBOX_ARCHIVE."=1
		WHERE ".TABLE_MESSAGE_INBOX_TO."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_MESSAGE_INBOX_ID."=". funcs::check_input($value);
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function admin_addSuggestion($subject, $message)
	{
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$sql = "INSERT INTO ".TABLE_ADMIN_SUGGESTION."
				SET ".TABLE_ADMIN_SUGGESTION_SUBJECT."='".$subject."',
				".TABLE_ADMIN_SUGGESTION_MESSAGE."='".$message."',
				".TABLE_ADMIN_SUGGESTION_DATETIME."='".funcs::getDateTime()."'";

		return DBconnect::execute_q($sql);
	}

	static function admin_checkMessage($mid,$type)
	{
		$mid = funcs::check_input($mid);
		$type = funcs::check_input($type);

		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_ADMIN_MESSAGE_INBOX." SET status = '1' WHERE ".TABLE_ADMIN_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_ADMIN_MESSAGE_OUTBOX." SET status = '1' WHERE ".TABLE_ADMIN_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}

	static function admin_deleteMessage_inbox($messageid)
	{
		$messageid = funcs::check_input($messageid);

		if(count($messageid)>0)
		{
			$list = '';
			$sql = "DELETE FROM ".TABLE_ADMIN_MESSAGE_INBOX." WHERE 1";
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_ADMIN_MESSAGE_INBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
			return DBconnect::execute_q($sql);
		}

		return false;
	}

	static function admin_deleteMessage_outbox($messageid)
	{
		$messageid = funcs::check_input($messageid);

		if(count($messageid)>0)
		{
			$list = '';
			$sql = "DELETE FROM ".TABLE_ADMIN_MESSAGE_OUTBOX." WHERE 1";
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_ADMIN_MESSAGE_OUTBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";

			return DBconnect::execute_q($sql);
		}


		return false;
	}

	static function admin_deleteSuggestionBox($suggestion_id)
	{
		if(count($suggestion_id)>0)
		{
			$sql = "DELETE FROM ".TABLE_ADMIN_SUGGESTION;
			for($n=0; $suggestion_id[$n]; $n++)
			{
				if($n == 0)
				{
					$sql .= " WHERE ( ";
				}
				else
				{
					$sql .= " OR ";
				}
				$sugges_id = funcs::check_input($suggestion_id[$n]);
				$sql .= TABLE_ADMIN_SUGGESTION_ID."=".$sugges_id;
			}
			$sql .= ")";
			return DBconnect::execute_q($sql);
		}

		return false;
	}

	static function admin_getAllMessage_inbox($start, $limit)
	{
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT m1.".TABLE_MEMBER_USERNAME.",
				m1.".TABLE_MEMBER_ID." AS userid,
				m2.*
				FROM ".TABLE_MEMBER." m1, ".TABLE_ADMIN_MESSAGE_INBOX." m2
				WHERE m1.".TABLE_MEMBER_ID."=m2.".TABLE_ADMIN_MESSAGE_INBOX_FROM.
				" ORDER BY ".TABLE_ADMIN_MESSAGE_INBOX_DATETIME." DESC";

		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function admin_getAllMessage_outbox($start, $limit)
	{
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT m1.".TABLE_MEMBER_USERNAME.",
				m1.".TABLE_MEMBER_ID." AS userid,
				m2.".TABLE_ADMIN_MESSAGE_OUTBOX_ID.",
				m2.".TABLE_ADMIN_MESSAGE_OUTBOX_SUBJECT.",
				m2.".TABLE_ADMIN_MESSAGE_OUTBOX_MESSAGE.",
				m2.".TABLE_ADMIN_MESSAGE_OUTBOX_DATETIME.",
				m2.status
				FROM ".TABLE_MEMBER." m1, ".TABLE_ADMIN_MESSAGE_OUTBOX." m2
				WHERE m1.".TABLE_MEMBER_ID."=m2.".TABLE_ADMIN_MESSAGE_OUTBOX_TO;
		$sql .= " ORDER BY ".TABLE_ADMIN_MESSAGE_OUTBOX_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function admin_getMessage_inbox($id)
	{
		$id = funcs::check_input($id);

		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME.", t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_ADMIN_MESSAGE_INBOX." t2 WHERE t2.".TABLE_ADMIN_MESSAGE_INBOX_ID."=".$id." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_ADMIN_MESSAGE_INBOX_FROM;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function admin_getMessage_outbox($id)
	{
		$id = funcs::check_input($id);

		$sql = "SELECT t1.username, t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_ADMIN_MESSAGE_OUTBOX." t2 WHERE t2.".TABLE_MESSAGE_OUTBOX_ID."=".$id." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_ADMIN_MESSAGE_OUTBOX_TO;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function admin_getNumAllMessage_inbox()
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_ADMIN_MESSAGE_INBOX);
	}

	static function admin_getNumAllMessage_outbox()
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_ADMIN_MESSAGE_OUTBOX);
	}

	static function admin_getUsername_Message($messageid)
	{
		$messageid = funcs::check_input($messageid);

		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." t1, ".TABLE_ADMIN_MESSAGE_INBOX." t2 WHERE t1.".TABLE_MEMBER_ID."=".TABLE_ADMIN_MESSAGE_INBOX_FROM." AND t2.".TABLE_ADMIN_MESSAGE_INBOX_ID."=".$messageid;

		return DBconnect::retrieve_value($sql);
	}

	static function admin_replyMessage($messageid, $subject, $message)
	{
		$messageid = funcs::check_input($messageid);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$data = funcs::admin_getMessage_inbox($messageid);
		if($data[TABLE_ADMIN_MESSAGE_INBOX_FROM] != ''){
			$sql = "INSERT INTO ".TABLE_ADMIN_MESSAGE_OUTBOX."
					SET ".TABLE_ADMIN_MESSAGE_OUTBOX_TO."=".$data[TABLE_ADMIN_MESSAGE_INBOX_FROM].",
					".TABLE_ADMIN_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_MESSAGE."='".$message."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);

			/*/////
			echo $sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$data[TABLE_ADMIN_MESSAGE_INBOX_FROM].",
					".TABLE_MESSAGE_INBOX_FROM."=".$_SESSION['sess_id'].",
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);
			*/
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$data[TABLE_ADMIN_MESSAGE_INBOX_FROM].",
					".TABLE_MESSAGE_INBOX_FROM."=1,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);

			$sql = "UPDATE ".TABLE_ADMIN_MESSAGE_INBOX."
					SET ".TABLE_ADMIN_MESSAGE_INBOX_REPLY."=1
					WHERE ".TABLE_ADMIN_MESSAGE_INBOX_ID."=".$messageid;
			return DBconnect::execute_q($sql);
		}
		else
			return false;
	}

	static function admin_sendMessage($to, $subject, $message)
	{
		$to = funcs::check_input($to);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$userid = funcs::getUserid($to);
		if($userid != '')
		{
			funcs::sendMemberNotificationEmail($userid,$subject,$message);
			$sql = "INSERT INTO ".TABLE_ADMIN_MESSAGE_OUTBOX."
					SET ".TABLE_ADMIN_MESSAGE_OUTBOX_TO."=".$userid.",
					".TABLE_ADMIN_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_MESSAGE."='".$message."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);

			$sql = "INSERT INTO ".TABLE_SUGGESTION_INBOX."
					SET ".TABLE_SUGGESTION_INBOX_TO."=".$userid.",
					".TABLE_SUGGESTION_INBOX_SUBJECT."='".$subject."',
					".TABLE_SUGGESTION_INBOX_MESSAGE."='".$message."',
					".TABLE_SUGGESTION_INBOX_DATETIME."='".funcs::getDateTime()."'";
			return DBconnect::execute_q($sql);
		}
		else
			return false;
	}

	static function admin_updateSuggestion($subject, $message, $suggestion_id)
	{
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);
		$suggestion_id = funcs::check_input($suggestion_id);

		$sql = "UPDATE ".TABLE_ADMIN_SUGGESTION."
				SET ".TABLE_ADMIN_SUGGESTION_SUBJECT."='".$subject."',
				".TABLE_ADMIN_SUGGESTION_MESSAGE."='".$message."'
				WHERE ".TABLE_ADMIN_SUGGESTION_ID."=".$suggestion_id;
		return DBconnect::execute_q($sql);
	}

	static function adv_search($save)
	{
		/*NEED TO VALIDATE INPUT DATA*/
		$col_member = DBconnect::get_col_names(TABLE_MEMBER);
		echo count($col_member)."<br>";

		$col = array_flip($col_member);
			echo count($col)."<br>";
		$save_post = array_intersect_key($save, $col);
		foreach($save_post as $key => $save_post){
			echo $save_post[$key]."<br>";
		}
		echo count($save_post);
		return DBconnect::advance_search(TABLE_MEMBER, $save_post, TABLE_MEMBER_ID,$col_member);
	}

	static function chklastlogin()
	{
		$sql = "SELECT `".TABLE_MESSAGE_INBOX."`.* FROM `".TABLE_MEMBER."`
				INNER JOIN  `".TABLE_MESSAGE_INBOX."`
				ON `".TABLE_MESSAGE_INBOX."`. `".TABLE_MESSAGE_INBOX_TO."`  =  `".TABLE_MEMBER."`. `".TABLE_MEMBER_ID."`
			  	WHERE `".TABLE_MEMBER_SIGNIN_DATETIME."` < '".funcs::getlast24DateTime()."'";
		$rec = DBconnect::assoc_query_2D($sql);
		if($rec){
			foreach($rec as $key => $val){
				$msgid = $val[TABLE_MESSAGE_INBOX_ID];
				$sql = "SELECT * FROM `".TABLE_MESSAGE_ALERT."`
							 WHERE `".TABLE_MESSAGE_ALERT_MASSAGE_ID."` = '".$msgid."'";
				$rec2 = DBconnect::assoc_query_2D($sql);
				if(!$rec2){ $alert[] = $msgid; }
			} // Foreach
		} //IF
		return $alert;
	}

	static function checkMessage($mid,$type)
	{
		$mid = funcs::check_input($mid);
		$type = funcs::check_input($type);

		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_MESSAGE_INBOX." SET status = '1', read_date = NOW() WHERE ".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_MESSAGE_OUTBOX." SET status = '1', read_date = NOW() WHERE ".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}

	static function checkSugges($mid,$type)
	{
		$mid = funcs::check_input($mid);
		$type = funcs::check_input($type);

		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_SUGGESTION_INBOX." SET status = '1', read_date = NOW() where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_SUGGESTION_OUTBOX." SET status = '1', read_date = NOW() where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}


	static function checkPermission(&$smarty, $permission)
	{
		/*NEED VALIDATE INPUT DATA*/
		if(!in_array($_SESSION['sess_permission'], $permission))
		{
			$text = funcs::getText($_SESSION['lang'], '$allow');

			/*for($n=0; $permission[$n]; $n++)
			{
				if($permission[$n] != 8){

					if($n == 0 || $n == 1)
						$text .= ' ';
					elseif(!$permission[$n+1] && $permission[$n] > 1)
						$text .= ' '.funcs::getText($_SESSION['lang'], '$and').' ';
					else
						$text .= ', ';
					switch($permission[$n])
					{
						case 2:
							$text .= funcs::getText($_SESSION['lang'], '$Membership_Gold');
							break;
						case 3:
							$text .= funcs::getText($_SESSION['lang'], '$Membership_Silver');
							break;
						case 4:
							$text .= funcs::getText($_SESSION['lang'], '$Membership_Bronze');
							break;
						case 5:
							$text .= funcs::getText($_SESSION['lang'], '$Test_Membership');
							break;
						case 8:
							$text .= "Administratoren";
							break;
						case 9:
							$text .= "Studiadmin";
							break;
					}
				}
			}*/
			$text .= '.';

			//$smarty->assign('text', $text);
			//$smarty->assign('section', 'blank_membership');
			//$smarty->assign('payment_history',funcs::getPaymentHistory($_SESSION['sess_id']));
			//funcs::prepareMembershipPage($smarty);

			//$smarty->display('index.tpl');
			header("location: ?action=register&type=membership");
			exit();
		}
	}

	static function checkFor1DayGold($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT payment, payment_received FROM ".TABLE_MEMBER." WHERE id = ".$userid;
		$result = DBconnect::assoc_query_1D($sql);
		$paydate = $result['payment_received'];

		if ((time() - 86400) <= strtotime($paydate)){
			return true;
		}
		else{
			return false;
		}
	}

	static function DeleteCard($cardid)
	{
		$cardid = funcs::check_input($cardid);

		$picpath = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDPATH,TABLE_CARD_ID,$cardid);
		$pictmp = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDTMP,TABLE_CARD_ID,$cardid);
		@unlink($picpath);
		@unlink($pictmp);
		$cond = "WHERE ".TABLE_CARD_ID."= '$cardid'";
		DBconnect::delete_data (TABLE_CARD,$cond);
	}

	static function deleteFavorite($userid, $username)
	{
		$userid = funcs::check_input($userid);
		$username = funcs::check_input($username);

		$id = funcs::getUserid($username);
		$sql = "DELETE FROM ".TABLE_FAVORITE." WHERE ".TABLE_FAVORITE_CHILD."=".$id." AND ".TABLE_FAVORITE_PARENT."=".$userid." LIMIT 1";
		DBconnect::execute_q($sql);
	}

	static function deleteFotoAlbum($fotoid, $userid)
	{
		$fotoid = funcs::check_input($fotoid);
		$userid = funcs::check_input($userid);

		$data = funcs::getFotoAlbum($fotoid, $userid);
		$pic = UPLOAD_DIR.$data[TABLE_FOTOALBUM_PICTUREPATH];
		if(is_file($pic))
			unlink($pic);

		$sql = "DELETE FROM ".TABLE_FOTOALBUM." WHERE ".TABLE_FOTOALBUM_ID."=".$fotoid." AND ".TABLE_FOTOALBUM_USERID."=".$userid;
		DBconnect::execute_q($sql);

		//Delete from Temp
		$sql = "DELETE FROM phototemp WHERE ".TABLE_FOTOALBUM_ID."=".$fotoid." AND ".TABLE_FOTOALBUM_USERID."=".$userid;
		DBconnect::execute_q($sql);

		return true;
	}

	static function deleteLonely_Heart($userid, $lonelyid)
	{
		$userid = funcs::check_input($userid);

		$sql = "DELETE FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid;
		for($n=0; $lonelyid[$n]; $n++)
		{
			if($n == 0)
				$sql .= " AND (";
			else
				$sql .= " OR ";

			$id = funcs::check_input($lonelyid[$n]);
			$sql .= TABLE_LONELYHEART_ID."=".$id;
		}
		$sql .= ")";

		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_inbox($userid, $messageid)
	{
		$userid = funcs::check_input($userid);

		$list = '';
		$sql = "DELETE FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";

				$id = funcs::check_input($value);
				$list .= TABLE_MESSAGE_INBOX_ID."=".$id;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_outbox($userid, $messageid)
	{
		$userid = funcs::check_input($userid);

		$list = '';
		$sql = "DELETE FROM ".TABLE_MESSAGE_OUTBOX." WHERE ".TABLE_MESSAGE_OUTBOX_FROM."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";

				$id = funcs::check_input($value);
				$list .= TABLE_MESSAGE_OUTBOX_ID."=".$id;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_suggestionInbox($userid, $messageid)
	{
		$userid = funcs::check_input($userid);

		$list = '';
		$sql = "DELETE FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_TO."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";

				$id = funcs::check_input($value);
				$list .= TABLE_SUGGESTION_INBOX_ID."=".$id;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_suggestionOutbox($userid, $messageid)
	{
		$userid = funcs::check_input($userid);

		$list = '';
		$sql = "DELETE FROM ".TABLE_SUGGESTION_OUTBOX." WHERE ".TABLE_SUGGESTION_OUTBOX_FROM."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";

				$id = funcs::check_input($value);
				$list .= TABLE_SUGGESTION_OUTBOX_ID."=".$id;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function editLonelyHeart($userid, $lonelyid, $data)
	{
		$userid = funcs::check_input($userid);
		$lonelyid = funcs::check_input($lonelyid);

		$data_tmp = array();
		foreach($data as $key => $val)
		{
			$data_tmp[$key] = funcs::check_input($val);
		}
		$data = $data_tmp;

		$sql = "UPDATE ".TABLE_LONELYHEART."
				SET		".TABLE_LONELYHEART_TARGET."='".$data[TABLE_LONELYHEART_TARGET]."',
						".TABLE_LONELYHEART_CATEGORY."='".$data[TABLE_LONELYHEART_CATEGORY]."',
						".TABLE_LONELYHEART_HEADLINE."='".$data[TABLE_LONELYHEART_HEADLINE]."',
						".TABLE_LONELYHEART_TEXT."='".$data[TABLE_LONELYHEART_TEXT]."'
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid."
				AND ".TABLE_LONELYHEART_ID."=".$lonelyid;
		funcs::sendMessage($userid, 'Kontaktanzeige', $data[TABLE_LONELYHEART_HEADLINE], $data[TABLE_LONELYHEART_TEXT], 5);
		return DBconnect::execute_q($sql);
	}

	static function getAllFotoAlbum($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT * FROM ".TABLE_FOTOALBUM."
				WHERE ".TABLE_FOTOALBUM_USERID."=".$userid."
				ORDER BY ".TABLE_FOTOALBUM_DATETIME;
		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllFotoAlbumFromTemp($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT * FROM phototemp
				WHERE ".TABLE_FOTOALBUM_USERID."=".$userid."
				and status='2'
				ORDER BY ".TABLE_FOTOALBUM_DATETIME;
		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllLonely_Heart($userid, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT * FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid."
				ORDER BY ".TABLE_LONELYHEART_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllMessage_inbox($userid, $archive, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$archive = funcs::check_input($archive);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT
				CASE WHEN m1.id = 1 THEN 'System Admin'
									 ELSE m1.".TABLE_MEMBER_USERNAME."
				END AS username,
				m1.".TABLE_MEMBER_ID." AS userid,
				m2.".TABLE_MESSAGE_INBOX_ID.",
				m2.".TABLE_MESSAGE_INBOX_SUBJECT.",
				m2.".TABLE_MESSAGE_INBOX_DATETIME.",
				m2.".TABLE_MESSAGE_OUTBOX_MESSAGE.",
				m2.".TABLE_MESSAGE_INBOX_ARCHIVE.",
				m2.".TABLE_MESSAGE_INBOX_REPLY.",
				m2.".TABLE_MESSAGE_INBOX_STATUS."
				FROM ".TABLE_MEMBER." m1, ".TABLE_MESSAGE_INBOX." m2
				WHERE m1.".TABLE_MEMBER_ID."=m2.".TABLE_MESSAGE_INBOX_FROM."
				AND m2.".TABLE_MESSAGE_INBOX_TO."=".$userid."
				AND ".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive;
		$sql .= " ORDER BY ".TABLE_MESSAGE_INBOX_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getCountNewMessage_inbox($type = "")
	{
		if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username']!=""))
		{
			$user_id = funcs::getUserid($_SESSION['sess_username']);
			$ext_sql = "";
			if($type!="")
			{
				if($type=="sms")
					$ext_sql = " AND subject = 'SMS'";
				else
					$ext_sql = " AND subject != 'SMS'";
			}

			$sql = "SELECT COUNT(id) FROM message_inbox WHERE to_id = '".$user_id."' AND status = '0'".$ext_sql;
			return DBconnect::retrieve_value($sql);
		}
		else
			return "0";
	}

	static function getCountNewSuggetion_inbox()
	{
		if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username']!=""))
		{
			$user_id = funcs::getUserid($_SESSION['sess_username']);

			$sql = "SELECT COUNT(id) FROM  suggestion_inbox WHERE to_id = '".$user_id."' AND status = '0'";
			return DBconnect::retrieve_value($sql);
		}
		else
			return "0";
	}

	static function getCountAllNewMessage_inbox()
	{
		//$unit = " ".funcs::getText($_SESSION['lang'], '$newmessage');
		$arrAllStatus[] = funcs::getCountNewMessage_inbox("sms");
		$arrAllStatus[] = funcs::getCountNewMessage_inbox("email");
		$arrAllStatus[] = funcs::getCountNewMessage_inbox();
		$arrAllStatus[] = funcs::getCountNewSuggetion_inbox();

		return $arrAllStatus;
	}

	static function getRandomUser()
	{
		$user_id = funcs::getUserid($_SESSION['sess_username']);

		$sql = "SELECT lookmen, lookwomen FROM member WHERE id ='".$user_id."'";
		$filter = DBconnect::assoc_query_1D($sql);

		//For lookpairs, we can select both men and women if they looking of pairs then no need conditon for this
		$cond = "";
		if(($filter['lookmen']=="1") && ($filter['lookwomen']!="1")) //If they are looking for men only then select only member which is a men
			$cond = " AND m.gender ='1'";
		elseif(($filter['lookmen']!="1") && ($filter['lookwomen']=="1")) //If they are looking for women only then select only member which is a women
			$cond = " AND m.gender ='2'"; 

		$sql = "SELECT m.username, m.picturepath, m.country, c.name FROM member m LEFT JOIN xml_countries c ON m.country = c.id WHERE m.isactive ='1' AND (m.picturepath !='' AND m.picturepath IS NOT NULL) AND c.status=1".$cond." AND m.signup_datetime >  '2012-03-01 00:00:00' ORDER BY RAND() DESC LIMIT 1";
		$row = DBconnect::assoc_query_1D($sql);
		return $row;
	}

	static function getAllMessage_outbox($userid, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT
				CASE WHEN m1.id = 1 THEN 'System Admin'
									 ELSE m1.".TABLE_MEMBER_USERNAME."
				END AS username,
				m1.".TABLE_MEMBER_ID." AS userid,
				m2.".TABLE_MESSAGE_OUTBOX_ID.",
				m2.".TABLE_MESSAGE_OUTBOX_SUBJECT.",
				m2.".TABLE_MESSAGE_OUTBOX_MESSAGE.",
				m2.".TABLE_MESSAGE_OUTBOX_DATETIME.",
				m2.".TABLE_MESSAGE_OUTBOX_STATUS."
				FROM ".TABLE_MEMBER." m1, ".TABLE_MESSAGE_OUTBOX." m2
				WHERE m1.".TABLE_MEMBER_ID."=m2.".TABLE_MESSAGE_OUTBOX_TO."
				AND m2.".TABLE_MESSAGE_OUTBOX_FROM."=".$userid;
		$sql .= " ORDER BY ".TABLE_MESSAGE_OUTBOX_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllMessage_suggestionInbox($userid, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT ".TABLE_SUGGESTION_INBOX_ID.",
				".TABLE_SUGGESTION_INBOX_SUBJECT.",
				".TABLE_SUGGESTION_INBOX_DATETIME.",
				".TABLE_SUGGESTION_INBOX_MESSAGE.",
				".TABLE_SUGGESTION_INBOX_REPLY.",
				".TABLE_SUGGESTION_INBOX_STATUS."
				FROM ".TABLE_SUGGESTION_INBOX."
				WHERE ".TABLE_SUGGESTION_INBOX_TO."=".$userid;
		$sql .= " ORDER BY ".TABLE_SUGGESTION_INBOX_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllMessage_suggestionOutbox($userid, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT ".TABLE_SUGGESTION_OUTBOX_ID.",
				".TABLE_SUGGESTION_OUTBOX_SUBJECT.",
				".TABLE_SUGGESTION_OUTBOX_MESSAGE.",
				".TABLE_SUGGESTION_OUTBOX_DATETIME.",
				".TABLE_MESSAGE_OUTBOX_STATUS."
				FROM ".TABLE_SUGGESTION_OUTBOX."
				WHERE ".TABLE_SUGGESTION_OUTBOX_FROM."=".$userid;
		$sql .= " ORDER BY ".TABLE_SUGGESTION_OUTBOX_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllSuggestion()
	{
		$sql = "SELECT * FROM ".TABLE_ADMIN_SUGGESTION."
				ORDER BY ".TABLE_ADMIN_SUGGESTION_DATETIME." DESC";

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAnswerChoice($lang, $first, $choice, $answer)
	{
		//$userid = funcs::check_input($userid);

		include_once('configs/'.$lang.'.php');

		eval("\$data2 = $lang::$choice;");
		if($first != '')
		{
			eval("\$data1 = $lang::$first;");
			$data = array_merge($data1, $data2);
		}
		else
			$data = $data2;

		return $data[$answer];
	}

	static function getAnswerCity($lang, $answer)
	{
		/*include_once('configs/'.$lang.'.php');

		$choice = simplexml_load_string(funcs::getText($lang, '$country'));
		for($n = 0; $n < count($choice->country); $n++)
		{
			for($i = 0; $i < count($choice->country[$n]->state); $i++)
			{
				for($x = 0; $x < count($choice->country[$n]->state[$i]->city); $x++)
				{
					if($choice->country[$n]->state[$i]->city[$x]->id == $answer)
					{
						return $choice->country[$n]->state[$i]->city[$x]->name;
						break;
					}
				}
			}
		}*/
		$lang = funcs::check_input($lang);
		$answer = funcs::check_input($answer);

		if($lang == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$sql = "SELECT ".$select_field." AS name FROM xml_cities WHERE id=".$answer;
		return DBconnect::retrieve_value($sql);
	}

	static function getAnswerArea($city)
	{
		$city = funcs::check_input($city);

		$sql = "SELECT plz FROM xml_cities WHERE id=".$city;
		return DBconnect::retrieve_value($sql);
	}

	static function getAnswerCountry($lang, $answer)
	{
		/*include_once('configs/'.$lang.'.php');

		$choice = simplexml_load_string(funcs::getText($lang, '$country'));
		for($n = 0; $n < count($choice->country); $n++)
		{
			if($choice->country[$n]->id == $answer)
			{
				return $choice->country[$n]->name;
				break;
			}
		}*/
		$lang = funcs::check_input($lang);
		$answer = funcs::check_input($answer);

		if($lang == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$sql = "SELECT ".$select_field." AS name FROM xml_countries WHERE id=".$answer;
		return DBconnect::retrieve_value($sql);
	}

	static function getAnswerState($lang, $answer)
	{
		/*include_once('configs/'.$lang.'.php');

		$choice = simplexml_load_string(funcs::getText($lang, '$country'));
		for($n = 0; $n < count($choice->country); $n++)
		{
			for($i = 0; $i < count($choice->country[$n]->state); $i++)
			{
				if($choice->country[$n]->state[$i]->id == $answer)
				{
					return $choice->country[$n]->state[$i]->name;
					break;
				}
			}
		}*/
		$lang = funcs::check_input($lang);
		$answer = funcs::check_input($answer);

		if($lang == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$sql = "SELECT ".$select_field." AS name FROM xml_states WHERE id=".$answer;
		return DBconnect::retrieve_value($sql);
	}

	static function getChoiceCard()
	{
		$sql = "SELECT * FROM ".TABLE_CARD;
		return DBconnect::assoc_query_2D($sql);
	}

	static function getChoice($lang, $first, $choice)
	{
		eval("\$data2 = $lang::$choice;");
		if($first != '')
		{
			eval("\$data1 = $lang::$first;");
			$data = array_merge($data1, $data2);
		}
		else
			$data = $data2;

		return $data;
	}

	static function getDate()
	{
		return date("Y-m-d", time());
	}

	static function getDateTime()
	{
		return date("Y-m-d H:i:s", time());
	}

	static function getEmail($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT ".TABLE_MEMBER_EMAIL." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."=".$userid;
		return DBconnect::retrieve_value($sql);
	}

	static function getFotoAlbum($fotoid, $userid)
	{
		$fotoid = funcs::check_input($fotoid);
		$userid = funcs::check_input($userid);

		$sql = "SELECT * FROM ".TABLE_FOTOALBUM."
				WHERE ".TABLE_FOTOALBUM_ID."=".$fotoid."
				AND ".TABLE_FOTOALBUM_USERID."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	/**
	* This static function is used for find a suitable size.
	* Select the image properties with php static function getimagesize.
	* Put the new suitable size in array.
	* @param $w this is a width image
	* @param $h this is a height of image
	* @param $limitw this is a limit width of image
	* @param $limith this is a limit height of image
	* @return array $newSize the array with new suitable size
	*/
	static function ImageCalSize($w,$h,$limitw,$limith)
	{
		if($w>$limitw){
			$per = $limitw/$w;
			$w = $w * $per ;
			$h = $h * $per ;
		}
		if($h >$limith){
			$per = $limith/$h;
			$w = $w * $per ;
			$h = $h * $per ;
		}
		$newSize = array($w,$h);
		return $newSize;
	}

	/**
	* This static function is used for resize the image.
	* Select the image properties with getImgProperty.
	* Find the suitable image size with ImageCalSize.
	* Resize images by  each type of images
	* @param $FileTmp this is a width image
	* @param array $LimitSize this is an array size of image
	* @param $picPath this is a  path of image
	*/
	static function ImageResize($FileTmp,$LimitSize,$picPath)
	{
		$ImgProp = funcs::getImgProperty($FileTmp);
		$ImgWidth = $ImgProp['width']  ;
		$ImgHeight = $ImgProp['height'] ;
		$ImgType = $ImgProp['type'] ;
		$NewSize = funcs::ImageCalSize($ImgWidth,$ImgHeight,$LimitSize[0],$LimitSize[1]);
		$image_p = imagecreatetruecolor($NewSize[0], $NewSize[1]);
		switch($ImgType){
			case 'gif': $image = imagecreatefromgif($FileTmp);
			break;
			case 'jpeg': $image = imagecreatefromjpeg($FileTmp);
			break;
			case 'png': $image = imagecreatefrompng($FileTmp);
			break;
			case 'bmp': $image = imagecreatefromwbmp ($FileTmp);
			break;
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $NewSize[0], $NewSize[1], $ImgWidth, $ImgHeight);
		 switch($ImgType){
			case 'gif': $image = imagegif($image_p,$picPath);
			break;
			case 'jpeg': $image = imagejpeg($image_p,$picPath);
			break;
			case 'png': $image = imagepng($image_p,$picPath);
			break;
			case 'bmp': $image = imagewbmp($image_p,$picPath);
			break;
		}
	}

	static function ImageResizeThumbs($FileTrue,$LimitSize,$picPath)
	{
		$ImgProp = funcs::getImgProperty($FileTrue);
		$ImgWidth = $ImgProp['width']  ;
		$ImgHeight = $ImgProp['height'] ;
		$ImgType = $ImgProp['type'] ;
		$NewSize = funcs::ImageCalSize($ImgWidth,$ImgHeight,$LimitSize[0],$LimitSize[1]);
		$image_p = imagecreatetruecolor($NewSize[0], $NewSize[1]);
		switch($ImgType){
			case 'gif': $image = imagecreatefromgif($FileTmp);
			break;
			case 'jpeg': $image = imagecreatefromjpeg($FileTmp);
			break;
			case 'png': $image = imagecreatefrompng($FileTmp);
			break;
			case 'bmp': $image = imagecreatefromwbmp ($FileTmp);
			break;
		}
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $NewSize[0], $NewSize[1], $ImgWidth, $ImgHeight);
		 switch($ImgType){
			case 'gif': $image = imagegif($image_p,$picPath);
			break;
			case 'jpeg': $image = imagejpeg($image_p,$picPath);
			break;
			case 'png': $image = imagepng($image_p,$picPath);
			break;
			case 'bmp': $image = imagewbmp($image_p,$picPath);
			break;
		}
	}

	/**
	* This static function is used for get the image properties .
	* Select the image properties with php static function getimagesize.
	* Put the properties in array.
	* @param $img this is a path of image
	* @return array $prop the array with image properties
	*/
	static function getImgProperty($img)
	{
		$ImgSize = getimagesize($img);
		$ImgMime = $ImgSize[mime];
		$mime = explode('/',$ImgMime);
		$ImgType = $mime[1];
		$prop['width'] = $ImgSize[0];
		$prop['height'] = $ImgSize[1];
		$prop['type'] = $ImgType;
		return($prop);
	}

	static function getlast24DateTime()
	{
		$stamp = time() - (1 * 24 * 60 * 60);
		return date("Y-m-d H:m:s", $stamp);
	}

	static function getListFavorite($userid, $char, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$char = funcs::check_input($char);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "SELECT t1.*, t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_FAVORITE." t2 WHERE t2.".TABLE_FAVORITE_PARENT."=".$userid." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_FAVORITE_CHILD;
		if(!(($char == 'All') || ($char == '')))
			$sql .= " AND t1.".TABLE_MEMBER_USERNAME." LIKE '".$char."%' ";
		$sql .= " ORDER BY ".TABLE_MEMBER_USERNAME;
		if(($start>=0) && ($limit >=0))
			$sql .= " LIMIT ".$start.", ".$limit;
		return DBconnect::assoc_query_2D($sql);
	}

	static function getLonelyHeart($userid, $lonelyid)
	{
		$userid = funcs::check_input($userid);
		$lonelyid = funcs::check_input($lonelyid);

		$sql = "SELECT * FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid."
				AND ".TABLE_LONELYHEART_ID."=".$lonelyid;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_inbox($userid, $id, $archive)
	{
		$userid = funcs::check_input($userid);
		$id = funcs::check_input($id);
		$archive = funcs::check_input($archive);

		$sql = "SELECT
					CASE WHEN t1.id = 1 THEN 'System Admin'
										 ELSE t1.".TABLE_MEMBER_USERNAME."
					END AS username,
					t2.*
				FROM ".
					TABLE_MEMBER." t1, ".
					TABLE_MESSAGE_INBOX." t2
				WHERE
					t2.".TABLE_MESSAGE_INBOX_ID."=".$id."
				AND
					t2.".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive."
				AND
					t1.".TABLE_MEMBER_ID."=t2.".TABLE_MESSAGE_INBOX_FROM."
				AND
					t2.".TABLE_MESSAGE_INBOX_TO."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_outbox($userid, $id)
	{
		$userid = funcs::check_input($userid);
		$id = funcs::check_input($id);

		$sql = "SELECT
					CASE WHEN t1.id = 1 THEN 'System Admin'
									 ELSE t1.".TABLE_MEMBER_USERNAME."
					END AS username,
					t2.*
				FROM ".
					TABLE_MEMBER." t1, ".
					TABLE_MESSAGE_OUTBOX." t2
				WHERE
					t2.".TABLE_MESSAGE_OUTBOX_ID."=".$id."
				AND
					t1.".TABLE_MEMBER_ID."=t2.".TABLE_MESSAGE_OUTBOX_TO."
				AND
					t2.".TABLE_MESSAGE_OUTBOX_FROM."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);


	}

	static function getMessage_suggestionInbox($userid, $id)
	{
		$userid = funcs::check_input($userid);
		$id = funcs::check_input($id);

		$sql = "SELECT * FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_ID."=".$id." AND ".TABLE_SUGGESTION_INBOX_TO."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_suggestionOutbox($userid, $id)
	{
		$userid = funcs::check_input($userid);
		$id = funcs::check_input($id);

		$sql = "SELECT * FROM ".TABLE_SUGGESTION_OUTBOX." WHERE ".TABLE_SUGGESTION_OUTBOX_ID."=".$id." AND ".TABLE_SUGGESTION_OUTBOX_FROM."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessageEmail_Forgot(&$smarty,$username, $password)
	{

		/*$message = funcs::getText($_SESSION['lang'], '$username').': '.$username."<br>";
		$message .= funcs::getText($_SESSION['lang'], '$password').': '.$password."<br>";*/

		$smarty->assign('username', $username);
		$smarty->assign('password', $password);
		$smarty->assign('url_web', URL_WEB);
		$message = $smarty->fetch('forgot.tpl');

		return $message;
	}

	static function getMessageEmail_membership(&$smarty, $username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT ".TABLE_MEMBER_PASSWORD." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$password = DBconnect::retrieve_value($sql);
		$sql = "SELECT validation_code FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$code = DBconnect::retrieve_value($sql);

		$smarty->assign('username', $username);
		$smarty->assign('password', $password);
		$smarty->assign('code', $code);
		$smarty->assign('url_web', URL_WEB);
		$message = $smarty->fetch('activate_message.tpl');

		return $message;
	}

	static function getMessageEmail_Testmembership($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT ".TABLE_MEMBER_PASSWORD." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$password = DBconnect::retrieve_value($sql);
		$sql = "SELECT validation_code FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		$code = DBconnect::retrieve_value($sql);
		$message = "
			<html><head></head><body>
			Liebes ".funcs::getText($_SESSION['lang'], '$KM_Name')."-Mitglied,<br><br>
					vielen Dank, dass du dich f&uuml;r ".funcs::getText($_SESSION['lang'], '$KM_Name')." entschieden hast. <br> <br>
					Dein Benutzername lautet: <b>".$username."</b><br>
					Dein Freischaltcode lautet: <b>".$code."</b><br><br>
					Um die Registrierung abzuschlie&szlig;en, klicke bitte auf den folgenden Link und folge den weiteren Anweisungen. Dein Mitgliederaccount wird direkt danach aktiviert.<br><br>
					<b>Registrierung abschlie&szlig;en</b>:<br><br>
					<a href='".URL_WEB."?action=activate'>Dein ".funcs::getText($_SESSION['lang'], '$KM_Name')."-Registrierungslink</a><br><br>
					Viel Spa&szlig; mit ".funcs::getText($_SESSION['lang'], '$KM_Name')."!<br>
					Dein ".funcs::getText($_SESSION['lang'], '$KM_Name')."<br>
					-------------------------------------------------------------<br>
					Hinweis: Du erh&auml;ltst diese Nachricht, da du dich zu ".funcs::getText($_SESSION['lang'], '$KM_Name')." angemeldet hast. Diese Nachricht wurde automatisch erzeugt, antworte bitte nicht darauf.
			</body></html>";

		return $message;
	}

	static function getMessageEmail_mobileGuide(&$smarty, $username)
	{
		$username = funcs::check_input($username);
		$smarty->assign('username', $username);
		$smarty->assign('url_web', URL_WEB);
		$message = $smarty->fetch('mobile_user_guide.tpl');

		return $message;
	}

	static function addMessageToInbox($userid, $subject, $message)
	{
		$userid = funcs::check_input($userid);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
				SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
				".TABLE_MESSAGE_INBOX_FROM."='1',
				".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
				".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
				".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
		return DBconnect::execute_q($sql);
	}

	static function getNamePass_email($email)
	{
		$email = funcs::check_input($email);

		$sql = "SELECT ".TABLE_MEMBER_USERNAME.", ".TABLE_MEMBER_PASSWORD." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_EMAIL."='".$email."' LIMIT 1";

		return DBconnect::assoc_query_1D($sql);
	}

	static function getResendActivation_email($email)
	{
		$email = funcs::check_input($email);

		$sql = "SELECT ".TABLE_MEMBER_USERNAME.", ".TABLE_MEMBER_PASSWORD.", isactive, signin_datetime FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_EMAIL."='".$email."' LIMIT 1";

		return DBconnect::assoc_query_1D($sql);
	}

	/*static function getNewest($gender, $limit, $withoutID)
	{
		//$gender
		//*0 : Any
		//*1 : Men
		//*2 : Women
		//*3 : Pairs

		$cond =  " WHERE ".TABLE_MEMBER_STATUS." != 1";
		if($gender) {  $cond .= " AND ".TABLE_MEMBER_GENDER."=".$gender ; }
		$cond .= ($withoutID!="")? " and id not in ($withoutID)" : "";
		// ".TABLE_MEMBER_ID.", ".TABLE_MEMBER_USERNAME.", ".TABLE_MEMBER_PICTURE."
		$sql = "SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER.$cond." ORDER BY picturepath DESC LIMIT ".$limit;

		$data = DBconnect::assoc_query_2D($sql);

		for($n=0; $data[$n]; $n++)
		{
			$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
			$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
			$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);
		}

		return $data;
	}*/

	static function getNewest($gender, $limit, $withoutID){
		//$gender *0 : Any	*1 : Men	*2 : Women	*3 : Pairs

		$gender = funcs::check_input($gender);
		$limit = funcs::check_input($limit);
		$withoutID = funcs::check_input($withoutID);

		$random_datetime = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'NEWEST_MEMBER_DATETIME'");
		$members = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'MEMBER_ACCOUNT'");

		if((date("Y-m-d",time())) > (date("Y-m-d",$random_datetime))){
			/////$cond =  " WHERE fake = 1 AND last_action_to is null AND last_action_from is null AND rundmail is null AND was_newest is null AND isactive = 1 AND flag = 1 AND picturepath <> ''";
			$cond =  " WHERE 1 AND picturepath <> ''";
			if($gender) {  $cond .= " AND ".TABLE_MEMBER_GENDER."=".$gender ; }
			$cond .= ($withoutID!="")? " and id not in ($withoutID)" : "";
			$sql = "SELECT id, username, gender, picturepath, city, civilstatus, birthday, appearance, height, description, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER.$cond." ORDER BY RAND()LIMIT ".$limit;
			$data = DBconnect::assoc_query_2D($sql);
			if ($gender == 2){
				$sql_update = "UPDATE config SET value = 'today', long_value = '".serialize($data)."' WHERE name = 'GETNEWEST_FEMALE'";
				$sql_timestamp = "UPDATE config SET value = '".time()."' WHERE name = 'NEWEST_MEMBER_DATETIME'";
				DBconnect::execute($sql_timestamp);
			}
			else{
				$sql_update = "UPDATE config SET value = 'today', long_value = '".serialize($data)."' WHERE name = 'GETNEWEST_MALE'";
			}

			DBconnect::execute($sql_update);
		}
		else{
			if ($gender == 2){
				$sql = "SELECT long_value FROM config WHERE name = 'GETNEWEST_FEMALE'";
			}
			else{
				$sql = "SELECT long_value FROM config WHERE name = 'GETNEWEST_MALE'";
			}
			$data = DBconnect::retrieve_value($sql);
			$data = unserialize($data);
		}


		// [Phai 05/12/2011] for($n=0; $data[$n]; $n++)
		for($n=0; $n<count($data); $n++)
		{
			$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
			$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
			$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);

			DBconnect::update_field('member','was_newest',1,'id',$data[$n]['id']);
		}

		return $data;
	}

	static function getNumAllMessage_inbox($userid, $archive)
	{
		$userid = funcs::check_input($userid);
		$archive = funcs::check_input($archive);

		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."=".$userid." AND ".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive);
	}

	static function getNumAllMessage_outbox($userid)
	{
		$userid = funcs::check_input($userid);

		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_MESSAGE_OUTBOX." WHERE ".TABLE_MESSAGE_OUTBOX_FROM."=".$userid);
	}

	static function getNumAllMessage_suggestionInbox($userid)
	{
		$userid = funcs::check_input($userid);

		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_TO."=".$userid);
	}

	static function getNumAllMessage_suggestionOutbox($userid)
	{
		$userid = funcs::check_input($userid);

		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_SUGGESTION_OUTBOX." WHERE ".TABLE_SUGGESTION_OUTBOX_FROM."=".$userid);
	}

	static function getNumListFavorite($userid, $char)
	{
		$userid = funcs::check_input($userid);
		$char = funcs::check_input($char);

		$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER." t1, ".TABLE_FAVORITE." t2 WHERE t2.".TABLE_FAVORITE_PARENT."=".$userid." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_FAVORITE_CHILD;
		if(!(($char == 'All') || ($char == '')))
			$sql .= " AND t1.".TABLE_MEMBER_USERNAME." LIKE '".$char."%' ";
		return DBconnect::get_nbr($sql);
	}

	static function getNumLonelyHeart($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT COUNT(*) FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid;

		return DBconnect::get_nbr($sql);
	}

	/*static function getOfDay($gender, $limit)
	{
		$sql = "SELECT *, (YEAR(CURDATE())-YEAR(t.".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(t.".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." t WHERE t.".TABLE_MEMBER_GENDER."=".$gender." AND flag = 1 ORDER BY t.picturepath DESC LIMIT ".$limit;

		$data = DBconnect::assoc_query_2D($sql);

		for($n=0; $data[$n]; $n++)
		{
			$data[$n][TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[$n][TABLE_MEMBER_GENDER]);
			$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
			$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
			$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);
		}

		return $data;
	}*/

	static function getOfDay($gender, $limit)
	{
		$gender = funcs::check_input($gender);
		$limit = funcs::check_input($limit);

		$random_datetime = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'OFDAY_MEMBER_DATETIME'");
		$members = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'MEMBER_ACCOUNT'");
		if((date("Y-m-d",time())) > (date("Y-m-d",$random_datetime))){
			//$sql = "SELECT *, (YEAR(CURDATE())-YEAR(t.".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(t.".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." t WHERE t.".TABLE_MEMBER_GENDER."=".$gender." AND fake = 0 AND isactive = 1 AND t.picturepath <> '' ORDER BY RAND() LIMIT ".$limit;
			$sql = "SELECT m.id, m.username, m.picturepath, m.birthday, m.gender, m.civilstatus, m.city, m.lookmen, m.lookwomen, m.lookpairs, (YEAR(CURDATE())-YEAR(m.".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(m.".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." m LEFT JOIN xml_countries c ON m.country=c.id WHERE m.".TABLE_MEMBER_GENDER."=".$gender." AND m.isactive = 1 AND m.picturepath <> '' AND c.status=1 ORDER BY RAND() LIMIT ".$limit;
			$data = DBconnect::assoc_query_2D($sql);
			if($gender == 1){
				$sql_update = "UPDATE config SET value = 'today', long_value = '".serialize($data)."' WHERE name = 'GETOFDAY_MALE'";
			}
			else{
				$sql_update = "UPDATE config SET value = 'today', long_value = '".serialize($data)."' WHERE name = 'GETOFDAY_FEMALE'";
				$sql_timestamp = "UPDATE config SET value = '".time()."' WHERE name = 'OFDAY_MEMBER_DATETIME'";
				DBconnect::execute($sql_timestamp);
			}
			DBconnect::execute($sql_update);
		}
		else
		{
			if($gender == 1){
				$sql = "SELECT long_value FROM config WHERE name = 'GETOFDAY_MALE'";
			}
			else{
				$sql = "SELECT long_value FROM config WHERE name = 'GETOFDAY_FEMALE'";
			}
			$data = DBconnect::retrieve_value($sql);
			$data = unserialize($data);
		}
		// [Phai 05/12/2011] for($n=0; $data[$n]; $n++)

		for($n=0; $n<count($data); $n++)
		{
			$data[$n][TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[$n][TABLE_MEMBER_GENDER]);
			$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
			$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
			$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);
		}
		return $data;
	}


	static function getProfile($id)
	{
		$id = funcs::check_input($id);

		$sql = "SELECT * FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_ID."`=\"$id\"";

		return DBconnect::assoc_query_1D($sql);
	}

	static function getAdvanceProfile($id, $array_type)
	{
		$id = funcs::check_input($id);

		$sql = "SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." WHERE id=".$id;

		if($array_type == 1)
		{
			$data = DBconnect::assoc_query_1D($sql);

			$data[TABLE_MEMBER_HEIGHT] = ($data[TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $data[TABLE_MEMBER_HEIGHT]) : "";
			$data[TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[TABLE_MEMBER_GENDER]);
			$data[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[TABLE_MEMBER_CITY]);
			$data[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[TABLE_MEMBER_CIVIL]);
			$data[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[TABLE_MEMBER_APPEARANCE]);
			$data['picturepath'] = trim($data['picturepath']);
		}
		else
		{
			$data = DBconnect::assoc_query_2D($sql);

			for($n=0; $data[$n]; $n++)
			{
				$data[$n][TABLE_MEMBER_HEIGHT] = ($data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $data[$n][TABLE_MEMBER_HEIGHT]) : "";
				$data[$n][TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[$n][TABLE_MEMBER_GENDER]);
				$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
				$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
				$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);
				$data[$n]['picturepath'] = trim($data[$n]['picturepath']);
			}
		}
		return $data;
	}

	static function getRangeAge($begin=18, $finish=99)
	{
		$ages = array();
		$age = range($begin, $finish);
		foreach($age as $age1)
			$ages[$age1] = $age1;

		return $ages;
	}

	static function getSuggestion($suggestion_id)
	{
		$suggestion_id = funcs::check_input($suggestion_id);

		$sql = "SELECT * FROM ".TABLE_ADMIN_SUGGESTION."
				WHERE ".TABLE_ADMIN_SUGGESTION_ID."=".$suggestion_id;

		return DBconnect::assoc_query_1D($sql);
	}

	static function getText($lang, $text)
	{
		eval("\$data = $lang::$text;");

		return $data;
	}

	static function getUserBirthday($date)
	{
		$date = funcs::check_input($date);

		$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE DAYOFMONTH(".TABLE_MEMBER_BIRTHDAY.") = DAYOFMONTH('".$date."') AND MONTH(".TABLE_MEMBER_BIRTHDAY.") = MONTH('".$date."')";

		return DBconnect::assoc_query_2D($sql);
	}

	static function getUserid($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT ".TABLE_MEMBER_ID." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";

		return DBconnect::retrieve_value($sql);
	}

	static function getUsername_Message($messageid)
	{
		$messageid = funcs::check_input($messageid);

		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." t1, ".TABLE_MESSAGE_INBOX." t2 WHERE t1.".TABLE_MEMBER_ID."=".TABLE_MESSAGE_INBOX_FROM." AND t2.".TABLE_MESSAGE_INBOX_ID."=".$messageid;

		return DBconnect::retrieve_value($sql);
	}

	static function getYear($sub_begin=90, $sub_finish=18)
	{
		$year = array();
		$year_now = date("Y", time());
		$year_range = range($year_now-$sub_finish,$year_now-$sub_begin);
		foreach($year_range as $year_range1)
			$year[$year_range1] = $year_range1;

		return $year;
	}

	static function isEmail($email){
		$email = funcs::check_input($email);

		return DBconnect::retrieve_value("SELECT COUNT(*) FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_EMAIL."`='".$email."'");
	}

	static function isUsername($username){
		$username = funcs::check_input($username);

		return DBconnect::retrieve_value("SELECT COUNT(*) FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_USERNAME."`='".$username."'");
	}

	static function isPhoneNumber($phone_number)
	{
		$phone_number = funcs::check_input($phone_number);

		return DBconnect::retrieve_value("SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE mobileno = '$phone_number' OR waitver_mobileno = '$phone_number'");
	}

	static function checkCoin($userName)//current remain coin
	{
		$userName = funcs::check_input($userName);

		return DBconnect::retrieve_value("SELECT coin FROM member WHERE username='".$userName."'");
	}

	static function getMinusCoin($name)
	{
		$name = funcs::check_input($name);

		$sql = "select value from config where name = '".$name."'";
		$data = DBconnect::assoc_query_1D($sql);

		/* if ($name=='COIN_SMS') {
			$sqlEmail = "select value from config where name = 'COIN_EMAIL'";
			$dataEmail = DBconnect::assoc_query_1D($sqlEmail);

			$return = $data[value] + $dataEmail[value];
			return $return;
		}else { */
			return $data['value'];
// 		}

	}

	static function updateCoin($points,$userid)
	{
		$points = funcs::check_input($points);
		$userid = funcs::check_input($userid);

		$username=DBConnect::retrieve_value("SELECT username FROM member where id = '".$userid."'");
		$remainCoin = funcs::checkCoin($username);
		$updatePoint = $remainCoin - $points;

		$sqlUpdate = "update member set coin = ".$updatePoint." where username = '".$username."' limit 1";
// 		echo $sqlUpdate;exit;
		DBconnect::execute($sqlUpdate);

		//INSERT COIN LOG
		$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('1','$userid','Update Coin','$points',".$updatePoint.", NOW())";
		DBconnect::execute($sqlAddCoinLog);

		return true;
	}

	static function updateCoinPoint($sms, $email, $freecoins)
	{
		$sms = funcs::check_input($sms);
		$email = funcs::check_input($email);
		$freecoins = funcs::check_input($freecoins);

		$sqlCoinSms = "Select id FROM config WHERE name = 'COIN_SMS'";
		$coinSmsVal = DBConnect::assoc_query_1D($sqlCoinSms);
		if($coinSmsVal=='')
		{
			DBconnect::execute("INSERT INTO config (id, name, value) values('', 'COIN_SMS', '".$sms."')");
		}
		else
		{
			$sqlSms = "UPDATE config SET value='".$sms."' WHERE name='COIN_SMS'";
			DBConnect::execute($sqlSms);
		}

		$sqlCoinEmail = "Select id FROM config WHERE name = 'COIN_EMAIL'";
		$coinEmailVal = DBConnect::assoc_query_1D($sqlCoinEmail);
		if($coinEmailVal=='')
		{
			DBconnect::execute("INSERT INTO config (id, name, value) values('', 'COIN_EMAIL', '".$email."')");
		}
		else
		{
			$sqlEmail = "UPDATE config SET value='".$email."' WHERE name='COIN_EMAIL'";
			DBConnect::execute($sqlEmail);
		}

		$sqlFreeCoins = "Select id FROM config WHERE name = 'FREECOINS'";
		$freeCoinsVal = DBConnect::assoc_query_1D($sqlFreeCoins);
		if($freeCoinsVal=='')
		{
			DBconnect::execute("INSERT INTO config (id, name, value) values('', 'FREECOINS', '".$freecoins."')");
		}
		else
		{
			$sqlEmail = "UPDATE config SET value='".$freecoins."' WHERE name='FREECOINS'";
			DBConnect::execute($sqlEmail);
		}

		return null;
	}

	static function getCoinData()
	{
		$sql_free = "SELECT value FROM config WHERE name='FREECOINS'";
		$freeCoins = DBConnect::retrieve_value($sql_free);

		$sql_sms = "SELECT value FROM config WHERE name='COIN_SMS'";
		$coinSms = DBConnect::retrieve_value($sql_sms);

		$sql_email = "SELECT value FROM config WHERE name='COIN_EMAIL'";
		$coinEmail = DBConnect::retrieve_value($sql_email);

		$data =  array(array(	'coin_sms' => $coinSms,
						'coin_email' => $coinEmail,
						'freecoins' => $freeCoins
						));
		return $data;

	}

	static function insertCoinLog($member_id, $send_to, $coin_field, $coin_minus, $coin_remain)
	{
		$member_id = funcs::check_input($member_id);
		$send_to = funcs::check_input($send_to);
		$coin_field = funcs::check_input($coin_field);
		$coin_minus = funcs::check_input($coin_minus);
		$coin_remain = funcs::check_input($coin_remain);

		$sqlInsertLog = "insert into coin_log(id, member_id, send_to, coin_field, coin, coin_remain, log_date) values('', $member_id, '$send_to', '$coin_field', '-$coin_minus', $coin_remain, now())";
		DBconnect::execute($sqlInsertLog);

		return null;
	}

	static function getExchangeRate($id)
	{
		$id = funcs::check_input($id);

		$sql = "select * from coin_package where id =".$id;
		$exChangeData = DBconnect::assoc_query_1D($sql);

		return $exChangeData;
	}

	static function getAllCurrency($sql)
	{
		$reCurr = DBConnect::assoc_query_2D($sql);

		return $reCurr;
	}

	static function fetchCurrencyData($sql)
	{
		$result = DBconnect::assoc_query_1D($sql);

		return $result;
	}

	static function executeCurrency($sql)
	{
		DBconnect::execute($sql);

		return true;
	}

	static function checkCookie()
	{
		if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
		{
			//check username and password from db
			$sql = "select username, password from member where username ='".$_COOKIE["username"]."' ";
			$data = DBconnect::assoc_query_1D($sql);

			$usernameDb = $data['username'];
			$passwordDb = $data['password'];
			$passwordMd5 = md5($passwordDb);

			if($usernameDb==$_COOKIE['username'] && $passwordMd5==$_COOKIE['password'])
			{
				funcs::loginSite($usernameDb, $passwordDb,'1');
			}

		}
	}

	static function loginSite($username, $password, $remember = 0)
	{
		$username = funcs::check_input($username);
		$password = funcs::check_input($password);
		$remember = funcs::check_input($remember);

		$member = DBconnect::login(funcs::check_input($password), funcs::check_input($username), TABLE_MEMBER, TABLE_MEMBER_PASSWORD, TABLE_MEMBER_USERNAME);
		//$member = DBconnect::login($password, $username, TABLE_MEMBER, TABLE_MEMBER_PASSWORD, TABLE_MEMBER_USERNAME);
		if(((int)$member[TABLE_MEMBER_ID] > 0) && ($member[TABLE_MEMBER_ISACTIVE] == 1))
		{
			$_SESSION['sess'] = session_id();
			$_SESSION['sess_id'] = $member[TABLE_MEMBER_ID];
			$_SESSION['gender'] = $member['gender'];
			$status = $member[TABLE_MEMBER_STATUS];
			$_SESSION['tcheck'] = $member['tcheck'];

			/*jeab add sess_memberid2012/02/29*/
			$_SESSION['sess_memberid'] = $member['id'];

			/*Jeab added cookies 2012/02/21*/
			if($remember==1)
			{
			setcookie('username',$username,time()+60*60*24*30, "/");
			setcookie('password',md5($password),time()+60*60*24*30, "/");
			setcookie('notremember',1,time()-3600, "/");
			}
			else
			{
			setcookie('username',$username,time()-3600, "/");
			setcookie('password',md5($password),time()-3600, "/");
			setcookie('notremember',1,time()+60*60*24*30, "/");
			}

			if ($status == 3) {
				if (funcs::checkFor1DayGold($_SESSION['sess_id'])) {
					$status = 2;
					$_SESSION['sess_permission'] = $status;
				}
			}

			/*********************
			*1 : ADMIN
			*2 : MEMBERSHIP-GOLD
			*3 : MEMBERSHIP-SILVER
			*4 : MEMBERSHIP-BRONZE
			*5 : TEST-MEMBERSHIP
			*********************/
			$_SESSION['sess_permission'] = $status;
			switch($status){
				case 1:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 1;
					$_SESSION['payment_admin'] = 1;
				break;
				case  2:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;
				case  3:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;
				case  4:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;
				case  5:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 0;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;
				case 8:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;
				case  9:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;
					$_SESSION['payment_admin'] = 0;
				break;

			}
			$_SESSION['sess_username'] = $username;

			if(($member[TABLE_MEMBER_SIGNIN_DATETIME] == '0000-00-00 00:00:00') && ($member['fake'] == '0'))
			{
				$_SESSION['sess_first'] = 1;
				$to_id = funcs::randomStartProfile($_SESSION['sess_id']);
				funcs::sendMessage($_SESSION['sess_id'],$to_id,'New registration','New registration',3,true);
				if($member['description'] != "")
				{
					funcs::addLonelyHeartFromDescription($member);
				}
			}
			else
				$_SESSION['sess_first'] = 0;

			/*----------------------Random visitor from database -------------------*/
			if((date("Y-m-d",time()) > $member["rand_visitor_datetime"]) || ($member["rand_visitor"] == ""))
			{
			$sql="SELECT m.*, (YEAR(CURDATE())-YEAR(m.".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(m.".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM member m LEFT JOIN xml_countries c ON m.country=c.id WHERE ((m.".TABLE_MEMBER_STATUS." != 1 and m.isactive='1' and m.picturepath != '') AND c.status=1";
			/*$i=0;
			if(($member['lookpairs']==1)||($member['lookmen']==1)||($member['lookwomen']==1)){
			   $sql .= " and( ";
       			if ($member['lookpairs']==1){
               			 $sql.="(lookpairs = '1')";
                			$i=$i+1;
       			}
      			if($member['lookmen']==1){
                			 if($i > 0)
                			$sql.="or";
                			$sql.=" (gender='1' and ";
               			if($member['gender']==1)
                        		 $sql.="lookmen='1')";
                			 if($member['gender']==2)
                        		$sql.="lookwomen='1')";
                			$i=$i+1;
        			}
        			if($member['lookwomen']==1){
                			if($i > 0)
               			 $sql.="or";
                			$sql.=" (gender='2' and ";
                			if($member['gender']==1)
                       		 $sql.="lookmen='1')";
               			 if($member['gender']==2)
                        		$sql.="lookwomen='1')";
                			$i=$i+1;
        			}
				$sql.=")";
				}
				$limit = rand(2,4);
       			$sql .= ") order by rand() limit ".$limit;*/

			// [Start] Phai edited on 19/7/2550

			if($member['gender']==1)
			{
				if($member['sexuality'] == 1)
				{
					$sql .= " and m.gender=1";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and m.gender=2";
				}
				else
				{
					$sql .= " and m.gender=2";
				}
			}
			else
			{
				if($member['sexuality'] == 1)
				{
					$sql .= " and m.gender = '2'";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and m.gender = '1'";
				}
				else
				{
					$sql .= " and m.gender = '1'";
				}
			}

			$limit = 5;
			$sql .= ") order by rand() limit ".$limit;

			// [End] Phai edited on 19/7/2550

			$data = DBconnect::assoc_query_2D($sql);

			$rand_id = "";
			for($n=0; $data[$n]; $n++)
			{
				$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
				$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
				$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);

				if($n == 0) {

					$rand_id .= $data[$n]['id']; }

				else {
				$rand_id .= ";".$data[$n]['id']; }
			}

			$sql = "UPDATE `".TABLE_MEMBER."` SET rand_visitor= '$rand_id',rand_visitor_datetime='".funcs::getDateTime()."' WHERE ".TABLE_MEMBER_ID." = '".$member[id]."'";
			DBconnect::execute_q($sql);

			} //end if checkdate

			$sql = "UPDATE `".TABLE_MEMBER."` SET `".TABLE_MEMBER_SIGNIN_DATETIME."`= '".funcs::getDateTime()."'
						  WHERE `".TABLE_MEMBER_ID."` = '".$member["id"]."'";
			DBconnect::execute_q($sql);
			$_SESSION['sql_sess'] = $sql;
			$sql = "INSERT INTO `".TABLE_MEMBER_SESSION."`
					SET `".TABLE_MEMBER_SESSION_MEMBER_ID."`=\"$member[id]\",
						`".TABLE_MEMBER_SESSION_SESSION_ID."`=\"$_SESSION[sess]\",
						`".TABLE_MEMBER_SESSION_SESSION_DATETIME."`='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);
			return true;
		}
		return false;
	}

	static function addLonelyHeartFromDescription($member)
	{
		if($member['gender'] == 1)
			$sex = 2;
		else
			$sex = 1;
		$category = 4;

		$subject_arr = array(
							"Gibt es dich da draussen?",
							"Suche mehr als einen Flirt!",
							"Wo bist du?",
							"Deckel zum Topf?",
							"Einsamkeit kann weh tun...",
							"Suche vielleicht genau dich...",
							"Erster Versuch",
							"Hallo ihr da draussen",
							"Gibt es noch Liebe?",
							"Suche Freundschaft und mehr",
							"I miss you",
							"Love is in the air",
							"Herzblatt gesucht...",
							"Kannst du mich glücklich machen?",
							"Schluss mit allein sein...",
							"Suche Glück, biete Liebe...",
							"Ehrlichkeit und Leidenschaft...",
							"Entzünde meine Flamme...",
							"Ich suche.. genau dich?!",
							"Sei mein Junimond...",
							"Liebesturteln im Sommer...",
							"Verzauber mich...",
							"Pferd sucht Sattel",
							"Partner fürs Leben?",
							"Topf sucht seinen Deckel...",
							"Feuer sucht Flamme",
							"Kannst du mich entflammen?",
							"Rendevouz ala carte...",
							"Umarmung in langen Nächten gesucht...",
							"Mach dem allein sein ein Ende",
							"Zu zweit in den Sonnenuntergang...",
							"Liebe für mehr als einen Sommer?",
							"Sonnenschein gesucht...",
							"Vielleicht schwierig... aber lieb!",
							"Dickkopf mit Verwöhncharakter",
							"Liege sucht Decke...",
							"Nie mehr Urlaub alleine...",
							"Einsame Nächte beenden...",
							"Schluss mit der Einsamkeit...",
							"Ich will die Suche einfach nicht aufgeben...",
							"Schraubendreher sucht Schraube",
							"Sommer und allein?",
							"Frühling alleine war genug",
							"Sein mein Herzstein",
							"Suche Spaß & mehr",
							"Flirt oder Liebe?!?",
							"Bist du die perfekte Welle?",
							"Allein sein ödet mich an",
							"Mein Bett ist groß und kalt",
							"Sag mir, wo bist du",
							"Himmel & Hölle",
							"Urlaub und Leben zu zweit",
							"Suche was Verrücktes",
							"Bring mich zum Lachen...",
							"Gebe Liebe, suche Nähe",
							"Verspielt & verträumt allein",
							"Liebe macht süchtig",
							"I love... vielleicht dich?!",
							"Umarm mich",
							"Sexy + sexy = ... uns?",
							"Hot and spicey...",
							"Suche jemanden zum Verwöhnen",
							"Ich warte... auf dich?!?",
							"Dinner for two?"
						);

		$subject = $subject_arr[mt_rand(0,count($subject_arr)-1)];

		$description = addslashes($member['description']);

		$sql = "INSERT INTO ".TABLE_LONELYHEART." (`id`, `userid`, `target`, `category`, `headline`, `text`, `admin`, `datetime`) VALUES (NULL, '{$member['id']}', '{$sex}', '{$category}', '{$subject}', '{$description}', '0', NOW());";
		return DBConnect::execute_q($sql);
	}

	static function logoutSite()
	{
		session_destroy();

		foreach($_COOKIE as $key=>$val)
		{
			if($key!="lovelysingle_activated")
			{
				setcookie($key,$_SESSION[$val],time() -3600, "/");
			}
		}

		header("Location: ./");
	}

	/**
	* This static function is used for retrieves the number of Card
	* @return array $rows the array with image properties
	*/
	static function numDataCard(){
		$sql = "SELECT * FROM ".TABLE_CARD;
		$rows = DBconnect::num_rows($sql) ;
		return $rows ;
	}

	static function replyMessage($messageid, $subject, $message)
	{
		$messageid = funcs::check_input($messageid);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$data = funcs::getMessage_inbox($_SESSION['sess_id'], $messageid, 0);
		if($data[TABLE_MESSAGE_INBOX_FROM] != '')
		{
			$userid = $data[TABLE_MESSAGE_INBOX_FROM];
			$from =$_SESSION['sess_id'];
			$fake = funcs::getFake($userid);
        //echo $type;
                        if($fake == 1){

                        		/*if (funcs::lookForSpecialChars($subject)){
                        			$subject = utf8_decode($subject);
                        		}
                        		if (funcs::lookForSpecialChars($message)){
                        			$message = utf8_decode($message);
                        		}*/

                                include("./libs/nusoap.php");
                                $data = funcs::getpaymentdata($from);
                                $message_assoc_array= array('to'=>$userid,'from'=>$from,'msg'=>$message, 'subject'=>$subject, 'serverID'=>SERVER_ID, 'type'=>$data['type'], 'payment'=>$data['payment']);
                                $parameters = array('msg'=>$message_assoc_array);
                                $soapclient = new soapclientnusoap(SERVER_URL);
                                $array = $soapclient->call('sendMessage',$parameters);
                                $sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
                                        SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
                                        ".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
                                        ".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
                                        ".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
                                        ".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
                                DBconnect::execute_q($sql);
                                //echo $soapclient->getError()."<br>";
                                //echo $soapclient->getHeaders()."<br>";
                                //echo SERVER_URL;
                                //print_r($array);
                                //echo $soapclient->getHTTPBody();
                                return true;
                        }
						else {
								funcs::emailAfterEmail($userid,$from,$subject,$message);
								$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
										SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
										".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
										".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
										".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
										".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
								DBconnect::execute_q($sql);

								$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
										SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
										".TABLE_MESSAGE_INBOX_FROM."=".$from.",
										".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
										".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
										".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
								return DBconnect::execute_q($sql);
						}

			$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
			DBconnect::execute($sql);
			$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
			DBconnect::execute($sql);

			$sql = "UPDATE ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_REPLY."=1
					WHERE ".TABLE_MESSAGE_INBOX_ID."=".$messageid;
			return DBconnect::execute_q($sql);
		}

		else
			return false;
	}

	static function randomPassword($length)
	{
		$char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUWVXYZ";
		$char_arr = str_split($char);
		$pass = '';
		for($n=0;$n<$length;$n++)
		{
			$rand_char = rand(0,count($char_arr)-1);
			$pass .= $char_arr[$rand_char];
		}
		return $pass;
	}

	static function randomStartProfile($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "SELECT city FROM member WHERE id =".$userid;
		$city = DBconnect::retrieve_value($sql);

		$sql = "SELECT country FROM member WHERE id =".$userid;
		$country = DBconnect::retrieve_value($sql);

		$sql = "SELECT gender FROM member WHERE id =".$userid;
		$gender = DBconnect::retrieve_value($sql);

		$sql = "SELECT birthday FROM member WHERE id =".$userid;
		$birthday = DBconnect::retrieve_value($sql);
		$birthdayyear = substr($birthday,0,4);

		$birthdayfrom = $birthday;
		$birthdayto = ($birthdayyear + rand(1,5)).'-01-01';

//		if (date('Y') - $birthdayyear < 23)
//		{
//			$birthdayfrom = ($birthdayyear + 4).'-01-01';
//		}

		$gender=($gender == 2)?1:2;

		$sql="SELECT username FROM member WHERE agent='' AND fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND country = '$country' AND city !='$city' AND birthday BETWEEN '$birthdayfrom' AND '$birthdayto' ORDER BY last_action_from ASC LIMIT 1";
		$result = DBconnect::assoc_query_1D($sql);
		if($result){
			return array_shift($result);
		}
		else {
			$birthdayfrom = ($birthdayyear - 4).substr($birthday,4);
			$sql="SELECT username FROM member WHERE agent='' AND fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND country = '$country' AND city !='$city' AND birthday BETWEEN '$birthdayfrom' AND '$birthdayto' ORDER BY last_action_from ASC LIMIT 1";
			$result = DBconnect::assoc_query_1D($sql);
			if($result){
				return array_shift($result);
			}
			else{
				$sql="SELECT username FROM member WHERE agent='' AND fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND country = '$country' AND city !='$city' ORDER BY last_action_from ASC LIMIT 1";
				$result = DBconnect::assoc_query_1D($sql);
				if($result){
					return array_shift($result);
				}
				else
					return "Gabi";
			}
		}
	}

	static function searchMember($search_for, $gender, $picture, $state, $city, $area, $min_age, $max_age)
	{
		$search_for = funcs::check_input($search_for);
		$gender = funcs::check_input($gender);
		$picture = funcs::check_input($picture);
		$state = funcs::check_input($state);
		$city = funcs::check_input($city);
		$area = funcs::check_input($area);
		$min_age = funcs::check_input($min_age);
		$max_age = funcs::check_input($max_age);

		switch($search_for)
		{
			case 1:
				$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_GENDER."=".$gender." AND ".TABLE_MEMBER_PICTURE."=".$picture." AND ".TABLE_MEMBER_CITY."=".city." AND ".TABLE_MEMBER_AREA."=".$area." AND ".TABLE_MEMBER_AGE.">=".$min_age." AND ".TABLE_MEMBER_AGE."<=".$max_age." ORDER BY picturepath DESC";
			break;
			case 2:
			break;

			return DBconnect::assoc_query_2D($sql);
		}
	}

	static function searchgender($wsex, $sex, $limit)
	{
		$wsex = funcs::check_input($wsex);
		$sex = funcs::check_input($sex);
		$limit = funcs::check_input($limit);

		switch($wsex)
		{
			case 'm':
				$wsex = 1;
			break;
			case 'w':
				$wsex = 2;
			break;
			case 'p':
				$wsex = 3;
			break;
		}
		$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_GENDER."=".$wsex;
		switch($sex)
		{
			case 'm':
				$sql .= " AND ".TABLE_MEMBER_LOOKMEN."=1";
			break;
			case 'w':
				$sql .= " AND ".TABLE_MEMBER_LOOKWOMEN."=1";
			break;
			case 'p':
				$sql .= " AND ".TABLE_MEMBER_LOOKPAIRS."=1";
			break;
		}
		$sql .= " ORDER BY picturepath DESC LIMIT $limit";
		return DBconnect::assoc_query_2D($sql);
	}

	static function sendFeedback($from, $subject, $message)
	{
		$from = funcs::check_input($from);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		///// for suggestion only
		/*/////
		funcs::sendMessage($from, 'Sabine_Kummerkasten', $subject, $message, 1);

		$sql = "INSERT INTO ".TABLE_ADMIN_MESSAGE_INBOX."
				SET ".TABLE_ADMIN_MESSAGE_INBOX_FROM."=".$from.",
				".TABLE_ADMIN_MESSAGE_INBOX_SUBJECT."='".$subject."',
				".TABLE_ADMIN_MESSAGE_INBOX_MESSAGE."='".$message."',
				".TABLE_ADMIN_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
		DBconnect::execute($sql);

		$sql = "INSERT INTO ".TABLE_SUGGESTION_OUTBOX."
				SET ".TABLE_SUGGESTION_OUTBOX_FROM."=".$from.",
				".TABLE_SUGGESTION_OUTBOX_SUBJECT."='".$subject."',
				".TABLE_SUGGESTION_OUTBOX_MESSAGE."='".$message."',
				".TABLE_SUGGESTION_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
		return DBconnect::execute_q($sql);
		*/
		/*mtype 1--> Kummerkasten*/
		$currentCoin = funcs::checkCoin($_SESSION[sess_username]);
		$minusEmail = funcs::getMinusCoin('COIN_EMAIL');

		if($currentCoin >= $minusEmail) {
			if(funcs::updateCoin($minusEmail,$from)) {
				$userid = funcs::getUserid('Gabi');
				$coin_remain = funcs::checkCoin($_SESSION[sess_username]);
				funcs::insertCoinLog($from, $userid, 'email', $minusEmail, $coin_remain);

				if($userid != '')	{
					include_once("./libs/nusoap.php");

					$data = funcs::getpaymentdata($from);
					$message_assoc_array= array('to'=>$userid,'from'=>$from,'msg'=>$message, 'subject'=>$subject, 'serverID'=>SERVER_ID, 'type'=>$data['type'], 'payment'=>$data['payment'], 'mtype'=>'1');
					$parameters = array('msg'=>$message_assoc_array);
					$soapclient = new soapclientnusoap(SERVER_URL);
					//$soapclient = new soapclientnusoap('http://soap.raturo.de/soapserver.php');

					$array = $soapclient->call('sendMessage',$parameters);

					$sql = "INSERT INTO ".TABLE_SUGGESTION_OUTBOX."
						SET ".TABLE_SUGGESTION_OUTBOX_FROM."=".$from.",
						".TABLE_SUGGESTION_OUTBOX_SUBJECT."='".$subject."',
						".TABLE_SUGGESTION_OUTBOX_MESSAGE."='".$message."',
						".TABLE_SUGGESTION_OUTBOX_DATETIME."='".funcs::getDateTime()."'";

					DBconnect::execute_q($sql);

					$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
					DBconnect::execute_q($sql);
					$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
					DBconnect::execute_q($sql);

					return true;
				}
				else{
					return false;
				}
			}
		}
	}

	static function ageVerify($usrBD,$ageAllow = 16){
		$arr_bd = explode('-',$usrBD);
		if((count($arr_bd) != 3) || (checkdate((int)$arr_bd[1],(int)$arr_bd[2],(int)$arr_bd[0]) != 1)){
			unset($arr_bd);
			return 0;
		}
		else{
			if(date('Y') - (int)$arr_bd[0] > $ageAllow){
				unset($arr_bd);
				return 1;
			}
			elseif(date('Y') - (int)$arr_bd[0] == $ageAllow){
				if(date('n') < (int)$arr_bd[1]){
					unset($arr_bd);
					return 0;
				}
				elseif(date('n') == (int)$arr_bd[1]){
					if(date('j') < (int)$arr_bd[2]){
						unset($arr_bd);
						return 0;
					}
					else{
						unset($arr_bd);
						return 1;
					}
				}
				else{
					unset($arr_bd);
					return 1;
				}
			}
			else{
				unset($arr_bd);
				return 0;
			}
		}
	}

	function sendMailPanama($email, $subject, $message, $from)
	{
		//mail server config
		 $connect=array(
		 'host'=>'201.218.196.146',
		 'auth'=>TRUE,
		 'localhost'=>'Hallo',
		 'username'=>'mailuser',
		 'password'=>'tcv7$61Vs',
		 'type'=>'smtp' //smtp,mail,sendmail
		 );

		$hdrs = array(
		   'From'    => $from,
		   'Subject' => $subject,
		   'To' => $email
		   );

		$crlf = "\n";
		$mime = new Mail_mime($crlf);

		include_once("html2text.php");
    	$mime->setTXTBody(html2text(html_entity_decode($message, ENT_QUOTES, "UTF-8")));

		//$mime->setTXTBody($message);
		$mime->setHTMLBody($message);
		$body = $mime->get();
		$hdrs = $mime->headers($hdrs);

		$mail =& Mail::factory($connect['type'],$connect);

		if (!($mail->send($hdrs['To'], $hdrs, $body)))
		{
			 return false;
		}
		else
		{
			return true;
		}
	}

	function sendMailGmail($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "smtp.gmail.com";
		$params["port"] = "587";
		$params["auth"] = true;
		//$params["username"] = "lovely.singles@gmail.com";
		//$params["password"] = "sXTd7m8n";
		$params["username"] = "ls.registrierung@gmail.com";
		$params["password"] = "Q7WagU5jNPv:";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailHotmail($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "smtp.live.com";
		$params["port"] = "587";
		$params["auth"] = true;
		$params["username"] = "yourbuddy24@hotmail.com";
		$params["password"] = "yb123456";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailYahoo($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "ssl://smtp.mail.yahoo.com";
		$params["port"] = "465";
		$params["auth"] = true;
		$params["username"] = "lovely.singles@yahoo.com";
		$params["password"] = "Schafi1986";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailYahooDE($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "ssl://smtp.mail.yahoo.com";
		$params["port"] = "465";
		$params["auth"] = true;
		$params["username"] = "lovelysingles.email@yahoo.de";
		$params["password"] = "ok0K0G9P";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailGMX($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "smtp.gmx.com";
		$params["port"] = "25";
		$params["auth"] = true;
		$params["username"] = "lovely-singles@gmx.de";
		$params["password"] = "Schafi1986";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailInbox($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "ssl://my.inbox.com";
		$params["port"] = "465";
		$params["auth"] = true;
		$params["username"] = "LS_UPDATE@inbox.com";
		$params["password"] = "Vxz5U9NV";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailGmail2($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "smtp.gmail.com";
		$params["port"] = "587";
		$params["auth"] = true;
		//$params["username"] = "lovely.singles@gmail.com";
		//$params["password"] = "sXTd7m8n";
		$params["username"] = "ls.note.update@gmail.com";
		$params["password"] = "Vxz5U9NV";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function sendMailTOnline($email, $subject, $message, $from)
	{
		$recipients = $email;
		$params["host"] = "smtpmail.t-online.de";
		$params["port"] = "587";
		$params["auth"] = true;
		$params["username"] = "lovely-singles@t-online.de";
		$params["password"] = "telefon9876$4 ";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $params["username"];
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;
	}

	function _sendMail($email, $subject, $message, $from)
	{
		/*$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=UTF-8' . "\r\n".
		'From: '.$from . "\r\n" .
   	 	'Reply-To:'.$from. "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		if(mail($email,$subject,$message,$header)){
			return true;
		}
		else{
			return false;
		} */

		$recipients = $email;
		$params["host"] = "127.0.0.1";
		//$params["host"] = "lovely-singles.com";
		$params["port"] = "25";
		$params["auth"] = true;
		$params["username"] = "no-reply@lovely-singles.com";
		$params["password"] = "YgVjU6JvcwJl";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = "no-reply@lovely-singles.com";
		$headers['To'] = $email;
		$headers['Subject'] = "=?utf-8?B?".base64_encode($subject)."?=";

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;

		//echo funcs::getEmail($userid);
		//mail($email,$subject,$message,$header);
	}

	function sendMail($email, $subject, $message, $from)
	{
		/*$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=UTF-8' . "\r\n".
		'From: '.$from . "\r\n" .
   	 	'Reply-To:'.$from. "\r\n" .
    		'X-Mailer: PHP/' . phpversion();
		if(mail($email,$subject,$message,$header)){
			return true;
		}
		else{
			return false;
		} */

		$recipients = $email;
		$params["host"] = "127.0.0.1";
		//$params["host"] = "lovely-singles.com";
		$params["port"] = "25";
		$params["auth"] = true;
		$params["username"] = "ls@lonelyconnection.com";
		$params["password"] = "E8qR5qI3EJYd";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = "ls@lonelyconnection.com";
		$headers['To'] = $email;
		$headers['Subject'] = "=?utf-8?B?".base64_encode($subject)."?=";

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;

		//echo funcs::getEmail($userid);
		//mail($email,$subject,$message,$header);
	}

	static function getFake($id)
	{
		$id = funcs::check_input($id);

		$sql = "SELECT fake FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."=".$id;
		//echo $sql;
		return DBconnect::retrieve_value($sql);
	}

	static function removeEmailAddressFromText($text)
	{
		return preg_replace('#[^\s]+@[^\s]+#', '[EMAIL BLOCKED]', $text);
	}

	static function sendMessage($from, $to, $subject, $message, $mtype, $isfree = false){
		/*mtype 0--> Normal
				1--> Kummerkasten
				2--> Technik
				3--> Neuanmeldung
				4--> Abo là¸£à¸„uft aus
				5--> Neue Kontaktanzeige
		*/
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		//REMOVE EMAIL FROM SUBJECT AND MESSAGE
		$subject = funcs::removeEmailAddressFromText($subject);
		$message = funcs::removeEmailAddressFromText($message);

		$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$from."'");
		$currentCoin = funcs::checkCoin($username);
		$minusEmail = ($isfree == false)? funcs::getMinusCoin('COIN_EMAIL') : 0;
		$userid = funcs::getUserid($to);

		if($userid)
		{
			if ($currentCoin >= $minusEmail) {
				if (funcs::updateCoin($minusEmail,$from)) {

					$coin_remain = funcs::checkCoin(DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$from));
					funcs::insertCoinLog($from, $userid, 'email', $minusEmail, $coin_remain);

					$fake = funcs::getFake($userid);

					if($fake == 1)
					{
						/*if (funcs::lookForSpecialChars($subject)){
						 $subject = utf8_decode($subject);
						}
						if (funcs::lookForSpecialChars($message)){
						$message = utf8_decode($message);
						}*/

						include_once("./libs/nusoap.php");

						$data = funcs::getpaymentdata($from);
						$message_assoc_array= array('to'=>$userid,'from'=>$from,'msg'=>$message, 'subject'=>$subject, 'serverID'=>SERVER_ID, 'type'=>$data['type'], 'payment'=>$data['payment'], 'mtype'=>$mtype);
						$parameters = array('msg'=>$message_assoc_array);
						$soapclient = new soapclientnusoap(SERVER_URL);
						//$soapclient = new soapclientnusoap('http://soap.raturo.de/soapserver.php');

						$array = $soapclient->call('sendMessage',$parameters);

						if ($mtype != 3 && $mtype != 5){
							$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
										SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
										".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
										".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
										".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
										".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
							DBconnect::execute_q($sql);
						}

						$first_message_sent = DBConnect::retrieve_value("SELECT first_message_sent FROM member WHERE id=".$from);
						if(!$first_message_sent)
						{
							$fake_member_agent = DBConnect::retrieve_value("SELECT agent FROM member WHERE id=".$userid);
							DBConnect::execute_q("UPDATE member SET agent='".$fake_member_agent."', agent_profile_username='".$to."' WHERE id=".$from);
						}

						if($isfree==false)
						{
							$sql= "UPDATE member SET first_message_sent=1 WHERE id = ".$from;
							DBconnect::execute_q($sql);
						}

						$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
						DBconnect::execute_q($sql);
						$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
						DBconnect::execute_q($sql);

						return true;
					}
					else
					{
						funcs::emailAfterEmail($userid,$from,stripslashes($subject),stripslashes($message));
						$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
										SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
										".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
										".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
										".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
										".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
						DBconnect::execute_q($sql);

						$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
										SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
										".TABLE_MESSAGE_INBOX_FROM."=".$from.",
										".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
										".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
										".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
						DBconnect::execute_q($sql);

						$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
						DBconnect::execute_q($sql);
						$sql= "UPDATE member SET last_action_from = NOW(),first_message_sent=1 WHERE id = ".$from;
						DBconnect::execute_q($sql);

						return true;
					}
				}
			}
		}
		else
			return false;
	}

	static function sendQuestionMessage($from, $subject, $message, $mtype,&$smarty=null)
	{
		/*mtype 0--> Normal
				1--> Kummerkasten
				2--> Technik
				3--> Neuanmeldung
				4--> Abo là¸£à¸„uft aus
				5--> Neue Kontaktanzeige
		*/
		$from = funcs::check_input($from);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);
		$mtype = funcs::check_input($mtype);

		$userid = 1;

		if($userid != ''){
			$fake = funcs::getFake($userid);

			if($fake == 1)
			{
				include_once("./libs/nusoap.php");

				$data = funcs::getpaymentdata($from);
				$message_assoc_array= array('to'=>$userid, 'from'=>$from, 'msg'=>$message, 'subject'=>$subject, 'serverID'=>SERVER_ID, 'type'=>$data['type'], 'payment'=>$data['payment'], 'mtype'=>$mtype);
				$parameters = array('msg'=>$message_assoc_array);
				$soapclient = new soapclientnusoap(SERVER_URL);
				//$soapclient = new soapclientnusoap('http://soap.raturo.de/soapserver.php');

				$array = $soapclient->call('sendMessage',$parameters);

				if ($mtype != 3 && $mtype != 5){
					$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
								SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
								".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
								".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
								".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
								".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
					DBconnect::execute_q($sql);
				}

				$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
				DBconnect::execute_q($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute_q($sql);

				return true;
			}
			else
			{
				funcs::emailAfterEmail($userid,$from,$subject,$message);
				$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
								SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
								".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
								".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
								".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
								".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);

				/*/////
				$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
								SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
								".TABLE_MESSAGE_INBOX_FROM."=".$from.",
								".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
								".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
								".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);
				*///////

				$sql = "INSERT INTO ".TABLE_ADMIN_MESSAGE_INBOX."
				SET ".TABLE_ADMIN_MESSAGE_INBOX_FROM."=".$from.",
				".TABLE_ADMIN_MESSAGE_INBOX_SUBJECT."='".$subject."',
				".TABLE_ADMIN_MESSAGE_INBOX_MESSAGE."='".$message."',
				".TABLE_ADMIN_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);

				$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
				DBconnect::execute_q($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute_q($sql);

				return true;
			}
		}
		else
			return false;
	}

	static function AdminSendMessage($from, $to, $subject, $message, $mtype){
		/*mtype 0--> Normal
				1--> Kummerkasten
				2--> Technik
				3--> Neuanmeldung
				4--> Abo là¸£à¸„uft aus
				5--> Neue Kontaktanzeige
		*/
		$from = funcs::check_input($from);
		$to = funcs::check_input($to);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);
		$mtype = funcs::check_input($mtype);

		$userid = funcs::getUserid($to);

		if($userid != '')	{
			$fake = funcs::getFake($userid);

			if($fake == 1){
        		/*if (funcs::lookForSpecialChars($subject)){
        			$subject = utf8_decode($subject);
        		}
        		if (funcs::lookForSpecialChars($message)){
        			$message = utf8_decode($message);
        		}*/
				include_once("./libs/nusoap.php");

				$data = funcs::getpaymentdata($from);
				$message_assoc_array= array('to'=>$userid,'from'=>$from,'msg'=>$message, 'subject'=>$subject, 'serverID'=>SERVER_ID, 'type'=>$data['type'], 'payment'=>$data['payment'], 'mtype'=>$mtype);
				$parameters = array('msg'=>$message_assoc_array);
				$soapclient = new soapclientnusoap(SERVER_URL);
                //$soapclient = new soapclientnusoap('http://soap.raturo.de/soapserver.php');

				$array = $soapclient->call('sendMessage',$parameters);

				if ($mtype != 3 && $mtype != 5){
					$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
						SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
						".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
						".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
						".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
						".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
					DBconnect::execute_q($sql);
				}

				$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
				DBconnect::execute_q($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute_q($sql);

				return true;
			}
			else
			{
				funcs::AdminSendEmail($userid,$from,$subject,$message);
				$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
						SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
						".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
						".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
						".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
						".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);

				$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
						SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
						".TABLE_MESSAGE_INBOX_FROM."=".$from.",
						".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
						".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
						".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql);

				$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
				DBconnect::execute_q($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute_q($sql);

				return true;
			}
		}
		else
			return false;
	}

	static function AdminSendEmail($userid,$from,$subj,$str)
	{
		$userid = funcs::check_input($userid);
		$from = funcs::check_input($from);
		$subj = funcs::check_input($subj);
		$str = funcs::check_input($str);

		$smarty = new smarty_web();
		$smarty->config_load($_SESSION['lang'].'.conf');

		$sql = "SELECT ".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$userid'";
		$rec =  DBconnect::retrieve_value($sql);

		$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$from'";
		$sender= DBconnect::assoc_query_1D($sql);

		$mess = $str; // funcs::split_word($str);
		$teamname = funcs::getText($_SESSION['lang'], '$KM_Website');
		$intro = str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_intro'));

		if(count($sender) > 0){
			$smarty->assign('teamname', $teamname);
			$smarty->assign('url_web', URL_WEB);
			$smarty->assign('rec', $rec);
			$smarty->assign('intro', $intro);
			$smarty->assign('subj', $subj);
			$smarty->assign('mess', str_replace('[KM_Website]', $teamname, $mess));
			$smarty->assign('footer1', str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_footer1')));
			$smarty->assign('footer2', str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_footer2')));
			$smarty->assign('footer3', funcs::getText($_SESSION['lang'], '$adminsendemail_footer3'));
			$message = $smarty->fetch('adminsendemail_message.tpl');
		}//end if

		$fake = $sender['fake'];
		$subj ="Lovely Singles - ".$intro; //You have new message from $user";
		if(fake == 0){
			funcs::sendMail(funcs::getEmail($userid), $subj, $message, 'no-reply@lovely-singles.com');		
		}
		else {
			funcs::sendMailPanama(funcs::getEmail($userid), $subj, $message, 'no-reply@lovely-singles.com');
		}	
	}

	static function sendBonusEmail($to, $subject, $message)
	{
		$to = funcs::check_input($to);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		$userid = funcs::getUserid($to);

		if($userid != '')
		{
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
					".TABLE_MESSAGE_INBOX_FROM."=1,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);

			$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
			DBconnect::execute_q($sql);

			$smarty = new smarty_web();
			$smarty->config_load($_SESSION['lang'].'.conf');

			$mess = $str; // funcs::split_word($str);
			$teamname = funcs::getText($_SESSION['lang'], '$KM_Website');
			$intro = str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_intro'));

			$smarty->assign('teamname', $teamname);
			$smarty->assign('url_web', URL_WEB);
			$smarty->assign('rec', $to);
			$smarty->assign('intro', $intro);
			$smarty->assign('subj', $subj);
			$smarty->assign('mess', str_replace('[KM_Website]', $teamname, $message));
			$smarty->assign('footer1', str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_footer1')));
			$smarty->assign('footer2', str_replace('[KM_Website]', $teamname, funcs::getText($_SESSION['lang'], '$adminsendemail_footer2')));
			$smarty->assign('footer3', funcs::getText($_SESSION['lang'], '$adminsendemail_footer3'));
			$message = $smarty->fetch('admin_manage_bonus_email.tpl');

			funcs::sendMail(funcs::getEmail($userid), $subject, $message, 'no-reply@lovely-singles.com');	
			return true;
		}
		else
			return false;
	}

	static function sendMessageViaSMS($from, $to, $subject, $message)
	{
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);

		//REMOVE EMAIL FROM SUBJECT AND MESSAGE
		$subject = funcs::removeEmailAddressFromText($subject);
		$message = funcs::removeEmailAddressFromText($message);

		$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$from."'");
		$currentCoin = funcs::checkCoin($username);
		$minusSms = funcs::getMinusCoin('COIN_SMS');
		$userid = funcs::getUserid($to);
		
		if($userid)
		{
			if($currentCoin >= $minusSms) {
				if(funcs::updateCoin($minusSms,$from)){
					
					$coin_remain = funcs::checkCoin($username);
					funcs::insertCoinLog($from, $userid, 'email with sms', $minusSms, $coin_remain);
				
					$fake = funcs::getFake($userid);

					if($fake == 1)
					{
						$msg_arr= array(	'to'=>$userid,
											'from'=>$from,
											'msg'=>$message,
											'subject'=>$subject,
											'serverID'=>SERVER_ID,
											'msg_type'=>1);
						$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
						$result = $client->sendSMS($msg_arr);

						$first_message_sent = DBConnect::retrieve_value("SELECT first_message_sent FROM member WHERE id=".$from);
						if(!$first_message_sent)
						{
							$fake_member_agent = DBConnect::retrieve_value("SELECT agent FROM member WHERE id=".$userid);
							DBConnect::execute_q("UPDATE member SET agent='".$fake_member_agent."', agent_profile_username='".$to."' WHERE id=".$from);
						}
					}
					else
					{
						funcs::emailAfterEmail($userid,$from,stripslashes($subject),stripslashes($message));
						$dial_number = funcs::getMobileNo($userid);
						sendSMSCode($dial_number, $username.": ".$message);

						// Send only to SMS, no email needed.
						//funcs::AdminSendEmail($userid,$from,$subject,$message);

						$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
								SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
								".TABLE_MESSAGE_INBOX_FROM."=".$from.",
								".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
								".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
								".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
						DBconnect::execute_q($sql);

						/*$sql = "SELECT COUNT(*) AS total FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."=".$userid;

						$row = DBconnect::assoc_query_1D($sql);
						if($row['total']==2)
						{
							$remind_message = funcs::getText($_SESSION['lang'], '$twice_sms_reminder');
							sendSMSCode($dial_number, 'System Admin'.": ".$remind_message);
						}*/
					}
					$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
								SET ".TABLE_MESSAGE_OUTBOX_TO."=".$userid.",
								".TABLE_MESSAGE_OUTBOX_FROM."=".$from.",
								".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
								".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message."',
								".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
					DBconnect::execute_q($sql);

					$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
					DBconnect::execute_q($sql);
					$sql= "UPDATE member SET last_action_from = NOW(), first_message_sent=1 WHERE id = ".$from;
					DBconnect::execute_q($sql);

					/*Start :: Noi added SMS TRACE*/
					$sql = "INSERT INTO sms_trace
								SET to_id=".$userid.",
								from_id=".$from.",
								message='".$message."',
								datetime='".funcs::getDateTime()."'";
					DBconnect::execute_q($sql);
					/*End :: Noi*/

					return true;
				
				}
			}
			else
			{
				$dial_number = funcs::getMobileNo($from);
				if(!DBConnect::retrieve_value("SELECT userid FROM warning_sms WHERE userid='".$from."' and sent_date='".date("Y-m-d")."'"))
				{
					DBConnect::execute_q("INSERT INTO warning_sms (userid,sent_date) VALUES ('".$from."','".date("Y-m-d")."')");
					sendSMSCode($dial_number, funcs::getText($_SESSION['lang'], '$not_enough_coin'));
				}
				return false;
			}
		}
		else
			return false;
	}

	static function sendfakeMessage($from, $to, $subject, $message, $mtype, $msg_type=0)
	{
		$from = funcs::check_input($from);
		$to = funcs::check_input($to);
		$subject = funcs::check_input($subject);
		$message = funcs::check_input($message);
		$mtype = funcs::check_input($mtype);

		funcs::emailAfterEmail($to,$from,$subject,$message);

		if($to != '')
		{
			if($mtype != 1){  //1 --> Kummerkasten Mail
				$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
						SET ".TABLE_MESSAGE_INBOX_TO."=".$to.",
						".TABLE_MESSAGE_INBOX_FROM."=".$from.",
						".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
						".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
						".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			}
			else {
				$sql = "INSERT INTO ".TABLE_SUGGESTION_INBOX."
						SET ".TABLE_MESSAGE_INBOX_TO."=".$to.",
						".TABLE_ADMIN_MESSAGE_INBOX_SUBJECT."='".$subject."',
						".TABLE_ADMIN_MESSAGE_INBOX_MESSAGE."='".$message."',
						".TABLE_ADMIN_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			}

			//$fp = fopen('test.log', 'w');

			$sql1= "UPDATE member SET last_action_to = NOW() WHERE id = ".$to;
			DBconnect::execute($sql1);
			$sql1= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
			DBconnect::execute($sql1);

			//fprintf($fp, "%s\n", $sql);
			return DBconnect::execute_q($sql);
		}
		else
			return false;
	}

	#Pakin Change this static function
	static function updateProfile($userid, $save)
	{
		$userid = funcs::check_input($userid);

		$col_member = array_flip(DBconnect::get_col_names(TABLE_MEMBER));
		foreach($col_member as $key => $value) {
			echo $value[$key];
		}
		$save_post = array_intersect_key($save, $col_member);
		foreach($save_post as $key => $val)
		{
			$validate_save[$key] = funcs::check_input($val);
		}
		#Pakin Change this static function
		$username =  self::findUserName($userid);
		self::NewSorting($username);
		funcs::logProfileAction($userid,2);

		/*/////
		$save['username'] = $username;
		$save['secret'] = 'kontaktmarkt';
		$client = new SoapClient(null, array('location' => "http://emailchat.napil.de/services/soapserver.php", 'uri' => "urn://kontaktmarkt"));
		$result = $client->updateProfile($save);
		*/
		return DBconnect::update_1D_row_with_1D_array(TABLE_MEMBER, $validate_save, TABLE_MEMBER_ID, $userid);
	}

	static function uploadFotoAlbum($file, $userid)
	{
		$userid = funcs::check_input($userid);

		$dataReal = funcs::getAllFotoAlbum($userid);
		$dataTemp = funcs::getAllFotoAlbumFromTemp($userid);
		$numData = count($dataReal)+count($dataTemp);

		if($numData < 10)
		{
			$file_name = time().'_'.$file['name'];
			$path = $userid.'/foto/';
			//echo UPLOAD_DIR.$path;
			if(!is_dir(UPLOAD_DIR.$path))
				mkdir(UPLOAD_DIR.$path,0777,true);

			$path = $path;
			if(move_uploaded_file($file['tmp_name'], UPLOAD_DIR.$path.$file_name))
			{
				if(PHOTO_APPROVAL > 0){
					$sql = "INSERT INTO phototemp
							SET ".TABLE_FOTOALBUM_USERID."=".$_SESSION['sess_id'].",
							".TABLE_FOTOALBUM_PICTUREPATH."='".$path.$file_name."',
							status = '2', site='1',
							".TABLE_FOTOALBUM_DATETIME."='".funcs::getDatetime()."'";
				}else{
					$sql = "INSERT INTO ".TABLE_FOTOALBUM."
						SET ".TABLE_FOTOALBUM_USERID."=".$_SESSION['sess_id'].",
						".TABLE_FOTOALBUM_PICTUREPATH."='".$path.$file_name."',
						".TABLE_FOTOALBUM_DATETIME."='".funcs::getDatetime()."'";
				}

				return DBconnect::execute_q($sql);
			}
		}
		return false;
	}

	static function getFreesms($username)
	{
		$username = funcs::check_input($username);

		$sql = "SELECT sms FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		return DBconnect::retrieve_value($sql);
	}

	static function getTextSMS($username)
	{
		$username = funcs::check_input($username);

		$code = substr(time(), 6);
		$query = "UPDATE ".TABLE_MEMBER." SET vcode_mobile='$code',vcode_mobile_insert_time=".time()." WHERE ".TABLE_MEMBER_USERNAME."='".htmlspecialchars($username,ENT_QUOTES,'UTF-8')."'";
		DBconnect::execute_q($query);

		return $code;
	}

	static function checkmobile($user)
	{
		$user = funcs::check_input($user);

		$sql = "SELECT mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$no = DBconnect::retrieve_value($sql);

		return $no;
	}

	static function checkvalidated($user)
	{
		$user = funcs::check_input($user);

		$sql = "SELECT validated FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$validated = DBconnect::retrieve_value($sql);

		return $validated;
	}

	static function sendFreesms($from, $to, $msg)
	{
		$from = funcs::check_input($from);
		$to = funcs::check_input($to);
		$msg = funcs::check_input($msg);

		$sql = "SELECT mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$from."'";
		$no = DBconnect::retrieve_value($sql);
		//echo $sql;
		$no=preg_replace("/^0049/","0",$no);
		$to=preg_replace("/^0049/","0",$to);
		sendSMS_BULK($to,$no,utf8_decode($msg));
		$sql = "UPDATE ".TABLE_MEMBER." SET sms=sms+1 WHERE ".TABLE_MEMBER_USERNAME."='".$from."' LIMIT 1";
		return DBconnect::execute_q($sql);
	}

	static function checkcode($code,$mobnr,$user)
	{
		$code = funcs::check_input($code);
		$mobnr = funcs::check_input($mobnr);
		$user = funcs::check_input($user);

		$sql = "SELECT vcode_mobile FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$vcode = DBconnect::retrieve_value($sql);
		$userid = funcs::getUserid($user);
		if($vcode == $code){
			$query = "UPDATE ".TABLE_MEMBER." SET mobileno='$mobnr' WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
						include("./libs/nusoap.php");
				$message_assoc_array= array('profileID'=>$userid,'serverID'=>SERVER_ID,'param'=>"mobileNr", 'val'=>$mobnr);
				$parameters = array('msg'=>$message_assoc_array);
				$soapclient = new soapclientnusoap(SERVER_URL);
				$array = $soapclient->call('updateProfile',$parameters);
			//echo $query;
			DBconnect::execute_q($query);
			return true;
		}
		else{
			return false;
		}
	}

	static function checkcodeVal($user, $code)
	{
		$user = funcs::check_input($user);
		$code = funcs::check_input($code);

		$userid = funcs::getUserid($user);
		if ($userid)
		{
			$query = "SELECT vcode_mobile FROM ".TABLE_MEMBER." WHERE id=".$userid." AND vcode_mobile=".$code;
			if(DBConnect::retrieve_value($query))
			{
				$query = "UPDATE ".TABLE_MEMBER." SET validated='1' WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
				DBconnect::execute_q($query);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	static function membershipvalid($user)
	{
		$user = funcs::check_input($user);

		$sql = "SELECT 1 FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."' AND payment > NOW()";
		$check = DBconnect::retrieve_value($sql);
		if($check == 1){
			return true;
		}
		else{
			return false;
		}
	}

	static function insertpayment($userid,$membership,$rate,$paid_via, $payment_complete)
	{
		$userid = funcs::check_input($userid);
		$membership = funcs::check_input($membership);
		$rate = funcs::check_input($rate);
		$paid_via = funcs::check_input($paid_via);
		$payment_complete = funcs::check_input($payment_complete);

		$pathinfo = pathinfo($_SERVER["SCRIPT_FILENAME"]);
		$prolonging = ($pathinfo['basename'] == "recurrent_every_day.php")?1:0;

		//funcs::setToExpired($userid);
		if(($membership == 1) || ($membership == 4))
		{
			$sql = "UPDATE ".TABLE_MEMBER." SET type=".$membership." WHERE id=".$userid;
			return DBconnect::execute_q($sql);
		}
		else
		{
			/*$sql = "UPDATE ".TABLE_MEMBER." SET type = $membership, payment_received = NOW(), payment = ";
			$value_names = "user_id, start_date, end_date, membership_type, paid_via";
			$values = "'$userid', NOW(), ";
			if ($rate==1){
				$end_date = "NOW() + INTERVAL 3 DAY";
			}elseif ($rate==2){
				$end_date = "NOW() + INTERVAL 1 MONTH";
			}elseif ($rate==3){
				$end_date = "NOW() + INTERVAL 3 MONTH";
			}elseif ($rate==4){
				$end_date = "NOW() + INTERVAL 1 YEAR";
			}

			$values .= $end_date.", '$membership', '$paid_via'";
			$sql .= $end_date." WHERE id = ".$userid;
			$check = DBconnect::execute_q($sql);
			if($check)
			{
				DBconnect::insert_row("history", $value_names, $values);
				return true;
			}
			else
			{
				return false;
			}*/
			$payment_complete = in_array($payment_complete, array(0,1))?$payment_complete:0;
			$history = funcs::getPaymentHistory($userid);
			if($history['new_paid_until'] != '')
			{
				$duration = funcs::dateDiff("-", date('Y-m-d'), $history['new_paid_until']);
			}
			else
			{
				$history['new_paid_until'] = "0000-00-00 00:00:00";
			}
			if($duration >= 0)
				$start = "NOW()";
			else
				$start = "DATE('{$history['new_paid_until']}')";

			$cancelled = 0;
			$cancelled_date = "0000-00-00 00:00:00";

			if ($rate==1)
			{
				$end_date = " + INTERVAL 3 DAY";
				$sum = SILVER_DURATION_PRICE_1;
			}
			elseif ($rate==2)
			{
				$end_date = " + INTERVAL 1 MONTH";
				if($membership == 2)
				{
					$sum = GOLD_DURATION_PRICE_2;
				}
				elseif($membership == 3)
				{
					$sum = SILVER_DURATION_PRICE_2;
				}
			}
			elseif ($rate==3)
			{
				$end_date = " + INTERVAL 3 MONTH";
				if($membership == 2)
				{
					$sum = GOLD_DURATION_PRICE_3;
				}
				elseif($membership == 3)
				{
					$sum = SILVER_DURATION_PRICE_3;
				}
			}
			elseif ($rate==4)
			{
				$end_date = " + INTERVAL 1 YEAR";
				if($membership == 2)
				{
					$sum = GOLD_DURATION_PRICE_4;
				}
				elseif($membership == 3)
				{
					$sum = SILVER_DURATION_PRICE_4;
				}
			}
			elseif ($rate==5)
			{
				$end_date = "";
				$cancelled = $history['cancelled'];
				$cancelled_date = $history['cancelled_date'];
				$sum = GOLD_DURATION_PRICE_5;
			}
			elseif ($rate==6)
			{
				$end_date = "";
				$cancelled = $history['cancelled'];
				$cancelled_date = $history['cancelled_date'];
				$sum = GOLD_DURATION_PRICE_6;
			}

			$member_data = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_MEMBER." WHERE id='".$userid."'");
			$sql = "INSERT INTO ".TABLE_PAY_LOG."
			SET ".TABLE_MEMBER_USERNAME."=\"$member_data[username]\",
				".TABLE_MEMBER_PASSWORD."=\"$member_data[password]\",
				".TABLE_PAYLOG_REALNAME."=\"$history[real_name]\",
				".TABLE_PAYLOG_REALSTREET."=\"$history[real_street]\",
				".TABLE_PAYLOG_REALZIP."=\"$history[real_plz]\",
				".TABLE_PAYLOG_REALCITY."=\"$history[real_city]\",
				".TABLE_PAYLOG_IP."=\"$history[ip_address]\",
				".TABLE_PAYLOG_BANKNAME."=\"$history[bank_name]\",
				".TABLE_PAYLOG_BANKCODE."=\"$history[bank_blz]\",
				".TABLE_PAYLOG_ACCOUNT."=\"$history[bank_account]\",
				".TABLE_MEMBER_MOBILENO."=\"$member_data[mobileno]\",
				".TABLE_PAYLOG_OLDTYPE."=\"$member_data[type]\",
				".TABLE_PAYLOG_NEWTYPE."=\"$membership\",
				".TABLE_PAYLOG_OLDPAYMENT."=\"$history[new_paid_until]\",
				".TABLE_PAYLOG_NEWPAYMENT."= $start $end_date,
				".TABLE_PAYLOG_PAIDVIA."= $paid_via,
				".TABLE_PAYLOG_SUM."= $sum,
				".TABLE_PAYLOG_PAYDAY."= NOW(),
				cancelled = '$cancelled',
				cancelled_date = '$cancelled_date',
				payment_complete = $payment_complete,
				prolonging = $prolonging";

			DBconnect::execute($sql);
			$prolonging_payment_id = mysql_insert_id();

			if($payment_complete == 1)
			{
				$sql = "UPDATE ".TABLE_MEMBER." SET type = $membership, payment_received = NOW(), payment = $start $end_date WHERE id = ".$userid;
				DBConnect::execute($sql);
			}
			else
			{
				$sql = "UPDATE ".TABLE_MEMBER." SET prolonging_payment_id = $prolonging_payment_id WHERE id = ".$userid;
				DBConnect::execute($sql);
			}
		}
	}

	static function setToExpired($userid)
	{
		/*$payment_history = funcs::getPaymentHistory($userid);
		if(is_array($payment_history))
		{
			$sql = "UPDATE ".TABLE_MEMBER." SET payment=(CURDATE() - INTERVAL 1 DAY) WHERE id=".$userid;
			DBconnect::execute($sql);
			$sql = "UPDATE ".TABLE_HISTORY." SET cancelled=1, end_date=(CURDATE() - INTERVAL 1 DAY) WHERE id=".$payment_history['id'];
			return DBconnect::execute_q($sql);
		}
		else
		{
			return false;
		}*/
	}

	//=============== [Start] Phai changed this function on 20/7/2550 ===================

	/*function getPaymentHistory($userid){
		$sql = "SELECT * FROM ".TABLE_HISTORY." WHERE id=".$userid;
		$result = DBconnect::assoc_query_1D($sql);
		if ($result){
			return $result;
		}
		else{
			return false;
		}
	}*/

	static function getPaymentHistory($user_id)
	{
		$user_id = funcs::check_input($user_id);

		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$user_id."'");
		if($username)
		{
			$sql = "SELECT *, DATE(payday) as start_date, DATE(new_paid_until) as end_date, new_type as membership_type, DATE(reminder_date) as reminder_date, cancelled, DATE(cancelled_date) as cancelled_date FROM ".TABLE_PAY_LOG." WHERE username='".$username."' AND payment_complete = '1' AND DATE(new_paid_until) >= '".date("Y-m-d")."' ORDER BY id DESC LIMIT 1";

			$payment_history = DBConnect::assoc_query_1D($sql);

			$arr = array(1=> 'Admin',2 => "VIP", 3 => "Premium", 4=> "Standard", 9=> "StudiAdmin");
			if($payment_history)
			{
				$payment_history['type'] = $arr[$payment_history['new_type']];
				$payment_history['id'] = $payment_history['ID'];
			}
			else
			{
				$payment_history = array('user_id' => $user_id,
									'membership_type' => DBConnect::retrieve_value("SELECT type FROM ".TABLE_MEMBER." WHERE id=".$user_id)
									);
				$payment_history['type'] = $arr[$payment_history['membership_type']];
				$payment_history['payday'] = date("Y-m-d");
				$payment_history['new_paid_until'] = date("Y-m-d");

				//return $payment_history;
			}
			return $payment_history;
		}
		else
			return null;
	}

	//=============== [End] Phai changed this function on 20/7/2550 ===================

	static function cancelPaymentHistory($id, $user_id)
	{
		$id = funcs::check_input($id);
		$user_id = funcs::check_input($user_id);

		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$user_id."'");

		$sql = "SELECT username FROM ".TABLE_PAY_LOG." WHERE ID=".$id;
		if(DBConnect::retrieve_value($sql) == $username)
		{
			DBConnect::execute_q("UPDATE ".TABLE_PAY_LOG." SET cancelled=1, cancelled_date=NOW() WHERE ID >=".$id." AND username='".$username."'");
			//DBConnect::execute_q("UPDATE ".TABLE_MEMBER." SET type=4 WHERE id=".$user_id);

			//logout ang login again.
			//$info = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_MEMBER." WHERE id=".$user_id);
			//$_SESSION = null;
			//funcs::loginSite($info['username'], $info['password']);
		}
	}

	static function getPaymentData($userid)
	{
		$userid = funcs::check_input($userid);

		$sql = "Select id, type, payment, payment_received FROM ".TABLE_MEMBER." WHERE id=".$userid;
		$result = DBconnect::assoc_query_1D($sql);
		if($result){
			$expire = $result['payment'];
			$today = date("d-m-Y",time());
			list($day1,$month1,$year1)=explode("-",$today);
			list($year2,$month2,$day2)=explode("-",$expire);
			$tdate1=mktime(0,0,0,intval($month1),intval($day1),intval($year1));
			$tdate2=mktime(0,0,0,intval($month2),intval($day2),intval($year2));
			$diffdays=round(($tdate2-$tdate1)/86400);
			$array_expire = array('expires' => $diffdays);
			$result = array_merge($result,$array_expire);
			return $result;
		}
		else{
			return false;
		}
	}

	static function emailAfterEmail($userid,$from,$subj,$str){

		$userid = funcs::check_input($userid);
		$from = funcs::check_input($from);

		if(!$smarty)
		{
			$smarty = new smarty_web();
			$smarty->config_load($_SESSION['lang'].'.conf');
		}

		$sql = "SELECT ".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$userid'";
		$rec =  DBconnect::retrieve_value($sql);

		$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$from'";
		$sender= DBconnect::assoc_query_2D($sql);

		$mess = funcs::split_word($str);
		$teamname = funcs::getText($_SESSION['lang'], '$KM_Name');
		$website = funcs::getText($_SESSION['lang'], '$KM_Website');
		if(count($sender) > 0){
			foreach($sender as $key => $value){
				$city = funcs::getAnswerCity($_SESSION['lang'], $value[TABLE_MEMBER_CITY]);
				$gender = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$gender', $value[TABLE_MEMBER_GENDER]);
				$temp = split('-',$value['birthday']);
				$age=date("Y")-$temp[0];
				$user = $value['username'];
				$smarty->assign('rec', $rec);
				$smarty->assign('user', $user);
				$smarty->assign('age', $age);
				$smarty->assign('gender', $gender);
				$smarty->assign('city', $city);
				$smarty->assign('subj', $subj);
				$smarty->assign('mess', $mess);
				if($value['picturepath'] != ""){
					$smarty->assign('picturepath', $value['picturepath']);
				}
				else
				{
					$smarty->assign('picturepath', 'default.jpg');
				}
				$smarty->assign('teamname', $teamname);
				$smarty->assign('url_web', URL_WEB);
				$message = $smarty->fetch('email_message.tpl');

			}//end foreach
		}//end if
		$subj_ext = str_replace('[PROFILE_NAME]', $user, str_replace('[KM_Website]', $website, funcs::getText($_SESSION['lang'], '$emailAfterEmail_subject')));
		$subj = self_name. " - " .$subj_ext;// " - You have new message from $user";
		funcs::sendMail(funcs::getEmail($userid), $subj, $message, 'no-reply@lovely-singles.com');		
	}

	static function sendMemberNotificationEmail($userid,$subj,$str)
	{
		$userid = funcs::check_input($userid);

		if(!$smarty)
		{
			$smarty = new smarty_web();
			$smarty->config_load($_SESSION['lang'].'.conf');
		}

		$mess = funcs::split_word($str);
		$teamname = funcs::getText($_SESSION['lang'], '$KM_Name');
		$website = funcs::getText($_SESSION['lang'], '$KM_Website');

		$smarty->assign('teamname', $teamname);
		$smarty->assign('url_web', URL_WEB);
		$smarty->assign('subj', $subj);
		$smarty->assign('msg', $str);
		$message = $smarty->fetch('email_message_admin.tpl');

		$subj = self_name. " - der Administrator von lovely-singles.com hat Ihnen eine wichtige Nachricht hinterlassen.";
		funcs::sendMail(funcs::getEmail($userid), $subj, $message, 'no-reply@lovely-singles.com');
	}

	static function getChoiceCountry()
	{
		if($_SESSION['lang'] == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$sql = "SELECT id, $select_field AS name FROM xml_countries WHERE status = 1 OR id = 23 ORDER BY priority ASC";
		$country = DBconnect::assoc_query_2D($sql);

		return $country;
	}

	static function getChoiceCountryXML()
	{
		if($_SESSION['lang'] == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$sql = "SELECT id, $select_field AS name, country_prefix, country_prefix_hidden FROM xml_countries WHERE status = 1 ORDER BY priority ASC";
		$country = DBconnect::assoc_query_2D($sql);

		$sql = "SELECT id, $select_field AS name, parent FROM xml_states WHERE parent=".$country['id']." AND status = 1 ORDER BY $select_field ASC";
		$result = mysql_query($sql);

		while($row = mysql_fetch_array($result))
			$state[$row['parent']][$row['id']] = $row['name'];

		$sql = "SELECT id, $select_field AS name, parent, plz FROM xml_cities WHERE parent=".$state['id']." AND status = 1 ORDER BY $select_field ASC";
		$result = mysql_query($sql);

		while($row = mysql_fetch_array($result))
			$city[$row['parent']][$row['id']] = $row['name'];

		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?><category>";
		foreach($country as $country_val)
		{
			echo "<country>";
			echo "<id>".$country_val['id']."</id>";
			echo "<name>".$country_val['name']."</name>";
			if($state[$country_val['id']])
			{
				foreach($state[$country_val['id']] as $key => $state_val)
				{
					echo "<state>";
					echo "<id>".$key."</id>";
					echo "<name>".$state_val."</name>";
					if($city[$key])
					{
						foreach($city[$key] as $key1 => $city_val)
						{
							echo "<city>";
							echo "<id>".$key1."</id>";
							echo "<name>".$city_val."</name>";
							echo "</city>";
						}
					}
					echo "</state>";
				}
			}
			echo "</country>";
		}
		echo "</category>";
	}

	static function checkLastLogin()
	{
		echo time();
		$sql ="SELECT * FROM ".TABLE_MEMBER."WHERE signin_datetime =''";
	}

	static function getLocationXML()
	{
		if($_SESSION['lang'] == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><category>\n";
		$countries = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, country_prefix, country_prefix_hidden FROM xml_countries WHERE status = 1 ORDER BY priority ASC");
		foreach($countries as $country)
		{
			$xml .= "<country>\n<id>".$country['id']."</id>\n<name>".$country['name']."</name>\n";

			$states = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, parent FROM xml_states WHERE parent=".$country['id']." AND status = 1 ORDER BY $select_field ASC");
			foreach($states as $state)
			{
				$xml .= "<state>\n<id>".$state['id']."</id>\n<name>".$state['name']."</name>\n";

				$cities = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, parent, plz FROM xml_cities WHERE parent=".$state['id']." AND status = 1 ORDER BY $select_field ASC");
				foreach($cities as $city)
				{
					$xml .= "<city>\n<id>".$city['id']."</id>\n<name>".$city['name']."</name>\n";
					$xml .= "</city>\n";
				}

				$xml .= "</state>\n";
			}

			$xml .= "</country>\n";
		}
		$xml .= "</category>";

		return $xml;
	}

	static function getLocationXML_with_test_country()
	{
		if($_SESSION['lang'] == 'eng')
			$select_field = "name";
		else
			$select_field = "name_de";

		$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><category>\n";
		$countries = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, country_prefix, country_prefix_hidden FROM xml_countries WHERE status = 1 OR id=23 ORDER BY priority ASC");
		foreach($countries as $country)
		{
			$xml .= "<country>\n<id>".$country['id']."</id>\n<name>".$country['name']."</name>\n";

			$states = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, parent FROM xml_states WHERE parent=".$country['id']." AND status = 1 ORDER BY $select_field");
			foreach($states as $state)
			{
				$xml .= "<state>\n<id>".$state['id']."</id>\n<name>".$state['name']."</name>\n";

				$cities = DBConnect::assoc_query_2D("SELECT id, $select_field AS name, parent, plz FROM xml_cities WHERE parent=".$state['id']." AND status = 1 ORDER BY $select_field");
				foreach($cities as $city)
				{
					$xml .= "<city>\n<id>".$city['id']."</id>\n<name>".$city['name']."</name>\n";
					$xml .= "</city>\n";
				}

				$xml .= "</state>\n";
			}

			$xml .= "</country>\n";
		}
		$xml .= "</category>";

		return $xml;
	}

	static function getVariablesFromURL($url)
	{
		$url_get = split("\?",$url);
		$url_get = $url_get[1];
		$url_var = split("\&",$url_get);
		$get = array();
		foreach($url_var as $item)
		{
			$temp = split("=", $item);
			$get[$temp[0]] = $temp[1];
		}
		return $get;
	}
	#Pakin Change this static function
	static function getUsersList($arr)
	{
		$country = funcs::check_input($arr['co']);
		$city = funcs::check_input($arr['ci']);
		$state = funcs::check_input($arr['s']);
		$gender = funcs::check_input($arr['g']);
		$look_for_gender = funcs::check_input($arr['lg']);
		$search_username = funcs::check_input($arr['u']);
		$surname = funcs::check_input($arr['l']);
		$email = funcs::check_input($arr['e']);
		$mt = funcs::check_input($arr['mt']);
		$order = funcs::check_input($arr['order']);
		$type = funcs::check_input($arr['type']);
		$date_range = funcs::check_input($_GET['r']);
		$fake = isset($arr['f'])? funcs::check_input($arr['f']) : null;
		$from = funcs::check_input($arr['from']);
		$to = funcs::check_input($arr['to']);
		$min_age = funcs::check_input($arr['min_age']);
		$max_age = funcs::check_input($arr['max_age']);
		#Pakin Change this static function
		$username = $_SESSION['sess_username'];
		/*$SuperUser = array('superadmin','bigbrother','heiko','cyric');
		if(!in_array($username,$SuperUser)){
			$condman = " (".TABLE_MEMBER_COUNT." <=".MALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 1)";
			$condwoman = " (".TABLE_MEMBER_COUNT." <=".FEMALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 2)";
			$CONDITION  = " AND ( $condman OR $condwoman ) AND ".TABLE_MEMBER_FLAG." != 1" ;
		}*/

		$sqlGetMember = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t1.flag as flag, t1.signup_datetime as registred, t1.id as user_id, t1.mobileno as mobileno,
										t1.picturepath as picturepath, t4.name as ".TABLE_MEMBER_CITY.",
										t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",
										t1.type as type, t1.agent, t1.fake
									FROM ".TABLE_MEMBER." t1
										LEFT OUTER JOIN xml_countries t2
											ON t1.country = t2.id
										LEFT OUTER JOIN xml_states t3
											ON t1.state=t3.id
										LEFT OUTER JOIN xml_cities t4
											ON t1.city=t4.id
									WHERE (t1.".TABLE_MEMBER_ISACTIVE." = 1) $CONDITION "; // AND (t1.".TABLE_MEMBER_PICTURE." !='')";

		if($country!=0 && $country!=''){
			 $sqlGetMember .= " AND (t1.country='$country')";
		}

		if($city!=0 && $city!=''){
			$sqlGetMember .= " AND (t1.city='$city')";
		}

		if($state!=0 && $state!=''){
			$sqlGetMember .= " AND (t1.state='$state')";
		}

		if($gender!=0 && $gender!=''){
			$sqlGetMember .= " AND (t1.gender='$gender')";
		}

		if($look_for_gender=='1'){
			$sqlGetMember .= " AND (t1.lookmen=1)";
		}
		elseif($look_for_gender=='2'){
			$sqlGetMember .= " AND (t1.lookwomen=1)";
		}

		if($search_username!=""){
			$sqlGetMember .= " AND (t1.username like '$search_username%')";
		}

		if($surname!=""){
			$sqlGetMember .= " AND (t1.surname like '$surname%')";
		}

		if($email!=""){
			$sqlGetMember .= " AND (t1.email like '$email%')";
		}

		if(($fake === '0') || ($fake === '1'))
		{
			$sqlGetMember .= " AND (t1.fake = '$fake')";
		}

		if($mt != '')
		{
			$sqlGetMember .= " AND (t1.type = '$mt')";
		}

		if($date_range == 'today')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) = CURDATE())";
		elseif($date_range == 'yesterday')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) = CURDATE() - INTERVAL 1 DAY)";
		elseif($date_range == 'week')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) >= CURDATE() - INTERVAL 7 DAY)";
		elseif($date_range == 'month')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) >= CURDATE() - INTERVAL 30 DAY)";
		elseif($date_range == 'search')
			$sqlGetMember .= " AND ((DATE(t1.signup_datetime) >= '".$from."') AND (DATE(t1.signup_datetime) <= '".$to."'))";

		if(($min_age!="") && ($max_age))
		{
			/*$mny = date('Y')-$min_age;
			$mxy = date('Y')-$max_age;
			$mindate = $mny."-01-01";
			$maxdate = $mxy."-01-01";

			$sqlGetMember .= " and (DATEDIFF(NOW(), t1.birthday ) >= DATEDIFF(NOW(), '$mindate' )";
			$sqlGetMember .= " and DATEDIFF(NOW(), t1.birthday ) <= DATEDIFF(NOW(), '$maxdate' ))";*/
			$sqlGetMember .= " AND (YEAR(NOW()) - YEAR(t1.birthday)) >= ".$min_age;
			$sqlGetMember .= " AND (YEAR(NOW()) - YEAR(t1.birthday)) <= ".$max_age;
		}

		switch($order)
		{
			case 'city':
				$sqlGetMember .= " ORDER BY t4.name";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'state':
				$sqlGetMember .= " ORDER BY t3.name";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'country':
				$sqlGetMember .= " ORDER BY t2.name";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'flag':
				$sqlGetMember .= " ORDER BY t1.flag";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'name':
				$sqlGetMember .= " ORDER BY username";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'type':
				$sqlGetMember .= " ORDER BY type";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'signup':
				$sqlGetMember .= " ORDER BY signup_datetime";
				$sqlGetMember .= (strtolower($type)=="desc")?" DESC":" ASC";
				$sqlGetMember .= ", picturepath DESC, username ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			default:
				$sqlGetMember .= " ORDER BY user_id";
				$sqlGetMember .= (strtolower($type)=="asc")?" ASC":" DESC";
				$sqlGetMember .= ", ".TABLE_MEMBER_USERNAME." ASC";
				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				//$rec = MESSAGE_RECORD_LIMIT-count($data);
				break;
		}

		if($arr['getAll'] == true)
			$data = DBconnect::assoc_query_2D($sqlCountMember);
		else
			$data = DBconnect::assoc_query_2D($sqlGetMember);

		$sqlCountMember = "select count(*) " . substr($sqlCountMember, strpos($sqlCountMember, "F")); 
		$sqlCountMember = substr($sqlCountMember,0, strpos($sqlCountMember, "ORDER BY"));
		$countMember = DBconnect::retrieve_value($sqlCountMember);
		//print_r($data);

		return array("data" => $data, "count" => $countMember);
	}

	function getVisitor()
	{
		if(isset($_SESSION['sess_username']))
		{
			$rand_visitor = DBConnect::retrieve_value("SELECT rand_visitor FROM ".TABLE_MEMBER." WHERE username='{$_SESSION['sess_username']}'");

			$temp = str_replace(";", ",",$rand_visitor);
			if($temp != '')
			{
				$sql = "SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." WHERE id IN (".$temp.") LIMIT 3";
				$data = DBconnect::assoc_query_2D($sql);
				if(count($data) > 0)
				{
					foreach($data as &$item)
					{
						$choice = funcs::getChoice($_SESSION['lang'],'','$gender');
						$item['gender'] = $choice[$item['gender']];
						$item[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $item[TABLE_MEMBER_CITY]);
						$item[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $item[TABLE_MEMBER_CIVIL]);
						$item[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $item[TABLE_MEMBER_APPEARANCE]);
						$item['picturepath'] = trim($item['picturepath']);
					}
				}
			}
			return $data;
		}
		else
			return FALSE;
	}

	static function member_account()
	{
		$random_datetime = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'RAND_MEMBER_DATETIME'");
		$members = DBConnect::retrieve_value("SELECT value FROM config WHERE name = 'MEMBER_ACCOUNT'");
		if((date("Y-m-d",time())) > (date("Y-m-d",$random_datetime)))
		{
			$add = mt_rand(55,80);
			//$date = ((time() - $random_datetime) / (24*60*60));
			$account	 =  $members + $add;//((floor($date)) * $add);
			$sql = "UPDATE ".TABLE_CONFIG." SET value='$account' WHERE name = 'MEMBER_ACCOUNT'";
			DBconnect::execute_q($sql);
			$sql = "UPDATE ".TABLE_CONFIG." SET value='".time()."' WHERE name = 'RAND_MEMBER_DATETIME'";
			DBconnect::execute_q($sql);
		}
		return number_format(MEMBER_ACCOUNT);
	}

	static function split_word($str)
	{
		 $str_arr = str_split($str);
		 $message = "";
		  if(count($str_arr) > 160 )
		 {
			for($i=0;$i<count($str_arr);$i++)
			{
				if($i < 160)
				{
					$message  .=  $str_arr[$i];
				}else if(($i == 160)){
					$message .= "...";
				}
			}
			return  $message;
		}else  {
			return $str;
		}
	}

	function getMessageHistory($member_a, $member_b, $start, $num)
	{
		$member_a = funcs::check_input($member_a);
		$member_b = funcs::check_input($member_b);
		$start = funcs::check_input($start);
		$num = funcs::check_input($num);

		$sql = "SELECT
					t1.*,
					CASE WHEN t2.id = 1 THEN 'System Admin'
					ELSE t2.".TABLE_MEMBER_USERNAME."
					END AS username
				FROM ".TABLE_MESSAGE_INBOX." t1, ".TABLE_MEMBER." t2 WHERE from_id=".$member_b." AND to_id=".$member_a." AND t1.from_id = t2.id";
		$messages1 = DBConnect::assoc_query_2D($sql);

		$sql = "SELECT
					t1.*,
					CASE WHEN t2.id = 1 THEN 'System Admin'
					ELSE t2.".TABLE_MEMBER_USERNAME."
					END AS username
				FROM ".TABLE_MESSAGE_OUTBOX." t1, ".TABLE_MEMBER." t2 WHERE from_id=".$member_a." AND to_id=".$member_b." AND t1.from_id = t2.id";
		$messages2 = DBConnect::assoc_query_2D($sql);

		$messages = array_merge($messages1,$messages2);

		foreach($messages as $key => $value)
		{
			$datetime[$key] = $value['datetime'];
		}
		array_multisort($datetime, SORT_DESC, $messages);

		//Get only $num messages
		if($num > 0)
			$messages = array_slice($messages, $start, $num);
		return $messages;
	}

	static function getProfileByUsername($username)
	{
		$username = funcs::check_input($username);

		$sql = "select * from member where (username='".trim($username)."')";

		$query = @mysql_query($sql);
		if(@mysql_num_rows($query)>0){
			$rs = @mysql_fetch_assoc($query);
		}

		return $rs;
	}

	static function getloneyByUsername($username)
	{
		$username = funcs::check_input($username);

		$sql  = "select lha.*, m.username, m.birthday, m.picturepath, m.city,m.appearance, m.civilstatus, m.description, m.height from lonely_heart_ads as lha ";
		$sql .= "inner join member as m on m.id=lha.userid where (m.username='".trim($username)."')";

		$query = @mysql_query($sql);
		if(@mysql_num_rows($query)>0){
		   $rs = @mysql_fetch_assoc($query);
		}

		return $rs;
	}

	static function deleteOldMessages($days)
	{
		$days = funcs::check_input($days);

		/*$sql = "SELECT id,datetime FROM ".TABLE_MESSAGE_INBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		print_r(DBConnect::assoc_query_2D($sql));*/

		$sql = "DELETE FROM ".TABLE_MESSAGE_INBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		DBConnect::execute($sql);
		$sql = "DELETE FROM ".TABLE_MESSAGE_OUTBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		DBConnect::execute($sql);
	}

	static function dateDiff($dformat, $endDate, $beginDate)
	{
		if(strpos($beginDate, " "))
		{
			$temp = explode(" ", $beginDate);
			$beginDate = $temp[0];
		}

		if(strpos($endDate, " "))
		{
			$temp = explode(" ", $endDate);
			$endDate = $temp[0];
		}
		$date_parts1=explode($dformat, $beginDate);
		$date_parts2=explode($dformat, $endDate);
		$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
		$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
		return $end_date - $start_date;
	}

	static function saveLog($path, $arr)
	{
		if(!$arr)
		{
			return false;
		}
		exec("cd ".WEB_DIR);
		//$script_name = basename($_SERVER["SCRIPT_FILENAME"]);
		//$server_path = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],$script_name));
		$server_path = WEB_DIR;

		$dirLogs  = "logs/".$path;
		$LogsYear =  date('Y');
		$LogsMonth =  date('m');
		$LogsDate =  date('d');
		// Folder login
		if(!is_dir($dirLogs))
		{
			exec("mkdir ".$server_path.$dirLogs);
			exec("chmod 777 ".$server_path.$dirLogs);
		}
		// Folder Year
		$dirLogs .= '/'.$LogsYear;
		if(!is_dir($dirLogs))
		{
			exec("mkdir ".$server_path.$dirLogs);
			exec("chmod 777 ".$server_path.$dirLogs);
		}
		// Folder Month
		$dirLogs .= '/'.$LogsMonth;
		if(!is_dir($dirLogs))
		{
			exec("mkdir ".$server_path.$dirLogs);
			exec("chmod 777 ".$server_path.$dirLogs);
		}
		// Files Name
		$fileLogs = $dirLogs.'/'.$LogsYear.$LogsMonth.$LogsDate.'.txt';
		$txtLogs = "\r\n";
		foreach($arr as $item)
		{
			$txtLogs .= $item." ||| ";
		}
		$handle = @fopen($server_path.$fileLogs, 'a');
		@fwrite($handle, $txtLogs);
		exec("chmod 777 ".$fileLogs);
	}

	/**
	* ...
	* @param $userid
	*/
	static function getLog($path, $date, $arr)
	{
		$LogsYear =  date("Y", $date);
		$LogsMonth =  date("m", $date);
		$LogsDate =  date("d", $date);
		$dirLogs = "logs/".$path.'/'.$LogsYear.'/'.$LogsMonth ;
		if(is_dir($dirLogs))
		{
		  // Files Name
			$fileLogs = $dirLogs.'/'.$LogsYear.$LogsMonth.$LogsDate.'.txt';
			if(is_file($fileLogs))
			{
				$handle = @fopen($fileLogs, "r");
				$i = 0;
				while (!feof($handle))
				{
					$contents= fgets($handle, 4096);
					$expContents = explode('|||',$contents);
					if(trim($expContents[0]) != '')
					{
						$j = 0;
						foreach($arr as $name)
						{
							$list[$i][$name] = trim($expContents[$j]);
							$j++;
						}
						$i++;
					}
				}
				fclose($handle);
			} //File
		} // Folder

		if(is_array($list))
		{
			return $list;
		}
		else
		{
			return array();
		}
	}

	static function deleteProfile($id)
	{
		$id = funcs::check_input($id);

		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$id."'");

		/*$sql = "DELETE FROM ".TABLE_MEMBER." WHERE id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_MEMBER_SESSION." WHERE member_id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_ADMIN_MESSAGE_INBOX." WHERE from_id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_ADMIN_MESSAGE_OUTBOX." WHERE to_id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM card_mail WHERE parent_id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_FAVORITE." WHERE (parent_id='".$id."') OR (child_id='".$id."')";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_FOTOALBUM." WHERE userid='".$id."'";
		DBConnect::execute($sql);

		//$sql = "DELETE FROM ".TABLE_HISTORY." WHERE user_id='".$id."'";
		//DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_LONELYHEART." WHERE userid='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_MESSAGE_INBOX." WHERE (to_id='".$id."') OR (from_id='".$id."')";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_MESSAGE_OUTBOX." WHERE (to_id='".$id."') OR (from_id='".$id."')";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_PAYMENT_LOG." WHERE username='".$username."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_SUGGESTION_INBOX." WHERE to_id='".$id."'";
		DBConnect::execute($sql);

		$sql = "DELETE FROM ".TABLE_SUGGESTION_OUTBOX." WHERE from_id='".$id."'";
		DBConnect::execute($sql);*/

/*		$sql = "UPDATE member SET isactive=0 AND validation_code='".funcs::randomPassword(6)."' WHERE id=".$id;
		DBConnect::execute($sql);
*/
		$sql = "UPDATE payment_log SET cancelled='1' AND cancelled_date = NOW() WHERE username='".$username."'";
		DBConnect::execute($sql);

/*		include("./libs/nusoap.php");
			$message_assoc_array= array('profileID'=>$id,'serverID'=>SERVER_ID);
			$parameters = array('msg'=>$message_assoc_array);
			$soapclient = new soapclient(SERVER_URL);
			$array = $soapclient->call('deleteprofile',$parameters);
*/

		$sql = "UPDATE member SET isactive_datetime = NOW() WHERE id=".$id;
		DBConnect::execute($sql);

	}

	static function getDateDiff($interval, $datefrom, $dateto, $using_timestamps = false) {
		/*
		$interval can be:
		yyyy - Number of full years
		q - Number of full quarters
		m - Number of full months
		y - Difference between day numbers
		(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
		d - Number of full days
		w - Number of full weekdays
		ww - Number of full weeks
		h - Number of full hours
		n - Number of full minutes
		s - Number of full seconds (default)
		*/

		if (!$using_timestamps) {
		$datefrom = strtotime($datefrom, 0);
		$dateto = strtotime($dateto, 0);
		}
		$difference = $dateto - $datefrom; // Difference in seconds

		switch($interval) {

		case 'yyyy': // Number of full years

		$years_difference = floor($difference / 31536000);
		if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
		$years_difference--;
		}
		if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
		$years_difference++;
		}
		$datediff = $years_difference;
		break;

		case "q": // Number of full quarters

		$quarters_difference = floor($difference / 8035200);
		while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
		$months_difference++;
		}
		$quarters_difference--;
		$datediff = $quarters_difference;
		break;

		case "m": // Number of full months

		$months_difference = floor($difference / 2678400);
		while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
		$months_difference++;
		}
		$months_difference--;
		$datediff = $months_difference;
		break;

		case 'y': // Difference between day numbers

		$datediff = date("z", $dateto) - date("z", $datefrom);
		break;

		case "d": // Number of full days

		$datediff = floor($difference / 86400);
		break;

		case "w": // Number of full weekdays

		$days_difference = floor($difference / 86400);
		$weeks_difference = floor($days_difference / 7); // Complete weeks
		$first_day = date("w", $datefrom);
		$days_remainder = floor($days_difference % 7);
		$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
		if ($odd_days > 7) { // Sunday
		$days_remainder--;
		}
		if ($odd_days > 6) { // Saturday
		$days_remainder--;
		}
		$datediff = ($weeks_difference * 5) + $days_remainder;
		break;

		case "ww": // Number of full weeks

		$datediff = floor($difference / 604800);
		break;

		case "h": // Number of full hours

		$datediff = floor($difference / 3600);
		break;

		case "n": // Number of full minutes

		$datediff = floor($difference / 60);
		break;

		default: // Number of full seconds (default)

		$datediff = $difference;
		break;
		}

		return $datediff;

	}

	static function getHistoryDetails($username){
		$username = funcs::check_input($username);

		//$sql_history = "SELECT * FROM history WHERE (user_id='".$user_id."') AND (end_date >= CURDATE()) ORDER BY end_date DESC, id DESC LIMIT 1";
		$sql_history = "SELECT h.* FROM ".TABLE_PAY_LOG." as h inner join ".TABLE_MEMBER." as m on m.username=h.username where (m.username='".trim($username)."') AND (new_paid_until < CURDATE())";

		$rs = DBConnect::assoc_query_1D($sql_history);
		return $rs;
	}

	static function changeFormatDate($originaldate){

		if($originaldate > 0){
			$arrDate = array(
				"01"=>"January",
				"02"=>"February",
				"03"=>"March",
				"04"=>"April",
				"05"=>"May",
				"06"=>"June",
				"07"=>"July",
				"08"=>"August",
				"09"=>"September",
				"10"=>"October",
				"11"=>"November",
				"12"=>"December");
		}

		return $arrDate[$originaldate];
	}

	static function get_message_extend($username,$type,$extend,$pay,$message) {
		$username = funcs::check_input($username);

		$sql  = "select * from payment_log where username = '".$username."'";
		$rs = DBConnect::assoc_query_1D($sql);

		/*
		//print_r($rs);
		$arr = array(2 => "Gold",3 => "Silver");
		$customer_type = $arr[$type];
		if($message != "") {
		$message = "$rs[ID] $rs[real_name ] $rs[real_street] $rs[real_city] $rs[real_plz] $rs[] $rs[] $rs[] $rs[]";
		}else {
		$message = "Hello.<br><br>";
		$message .= "$rs[ID]  desired membership is ". $customer_type .".Lovely Singles extened duration ".$extend. ". Payment is ".$pay." Euro.<br><br>";
		}*/
		return $rs;

	}

	static function send_memberExtend_admin(&$smarty,$email,$subject,$array) {

		$mail_from = "no-reply@lovely-singles.com";
		$mail_subject = $subject;
		$smarty->assign('member',$array);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend_admin.tpl');

		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
		//Buchhaltung
		funcs::sendMail('ph@mintnet.de', $mail_subject, $mail_message, $mail_from);
	}

	static function send_memberExtend_customer(&$smarty,$username,$email,$type,$extend,$pay)
	{
		$arr = array(2 => "VIP",3 => "Premium");
		$customer_type = $arr[$type];

		$message ="Hallo ".$username ."!<br><br> Deine ". $customer_type ."-Mitgliedschaft bei Lovely Singles wurde heute von uns bis zum ".$extend. " verl&auml;ngert. <br><br>Die Kosten daf&uuml;r betragen ".$pay." Euro. <br><br>
					Bitte &uuml;berweise binnen 7 Tagen diesen Betrag auf das Konto der TMP CallCenter Service Nord GmbH Nr.: <b>303185119</b> bei der <b>Volksbank Bautzen (Blz.: 855 900 00)</b>. <br><br>
					Vielen Dank f&uuml;r das in uns entgegen gebrachte Vertrauen und weiterhin viel Spa&szlig; auf Lovely Singles! <br> <br> Liebe Gr&uuml;&szlig;e, <br> Dein Lovely Singles Team!";
		$mail_from = "no-reply@lovely-singles.com";
		$mail_subject = "Lovely Singles - Deine Mitgliedschaft wurde verl&auml;ngert!";
		$smarty->assign('message',$message);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend.tpl');

		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}

	static function send_memberExtend_customer_paid_via_4(&$smarty,$username,$email,$type,$extend,$pay)
	{
		$arr = array(2 => "VIP",3 => "Premium");
		$customer_type = $arr[$type];

		$message ="Hallo ".$username ."!<br><br> Deine ". $customer_type ."-Mitgliedschaft bei Lovely Singles wurde heute von uns bis zum ".$extend. " verl&auml;ngert. <br><br>Die Kosten daf&uuml;r betragen ".$pay." Euro. <br><br>
					Der Betrag wird automatisch durch das Elektronische Lastschriftverfahren bei dir eingezogen. <br><br>
					Vielen Dank f&uuml;r das in uns entgegen gebrachte Vertrauen und weiterhin viel Spa&szlig; auf Lovely Singles! <br> <br> Liebe Gr&uuml;&szlig;e, <br> Dein Lovely Singles Team!";
		$mail_from = "no-reply@lovely-singles.com";
		$mail_subject = "Lovely Singles - Deine Mitgliedschaft wurde verlà¸£à¸„ngert!";


		$smarty->assign('message',$message);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend.tpl');

		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}

	static function send_memberExtend_customer_3_days(&$smarty,$username,$email,$type,$extend,$pay)
	{
		$arr = array(2 => "VIP",3 => "Premium");
		$customer_type = $arr[$type];

		$message ="Hallo ".$username ."!<br><br> Deine ". $customer_type ."-Mitgliedschaft bei Lovely Singles wurde heute von uns bis zum ".$extend. " verl&auml;ngert. <br><br>Die Kosten daf&uuml;r betragen ".$pay." Euro. <br><br>
					Bitte &uuml;berweise binnen 7 Tagen diesen Betrag auf das Konto der TMP CallCenter Service Nord GmbH Nr.: <b>303185119</b> bei der <b>Volksbank Bautzen (Blz.: 855 900 00)</b>. <br><br>
					Dein Mitgliedskonto wird anschlie&szlig;end zu einem VIP Status Konto hochgestuft.<br><br>
					Vielen Dank f&uuml;r das in uns entgegen gebrachte Vertrauen und weiterhin viel Spa&szlig; auf Lovely Singles! <br> <br> Liebe Gr&uuml;&szlig;e, <br> Dein Lovely Singles Team!";
		$mail_from = "no-reply@lovely-singles.com";
		$mail_subject = "Lovely Singles - Deine Mitgliedschaft wurde verlà¸£à¸„ngert!";


		$smarty->assign('message',$message);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend.tpl');

		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}

	static function send_memberCancel_admin(&$smarty,$email,$subject,$array) {

		$mail_from = "no-reply@lovely-singles.com";
		$mail_subject = $subject;
		$smarty->assign('member',$array);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_cancel_admin.tpl');

		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}

	static function sendMemberCancelEmail($id, $log_id, &$smarty)
	{
		$id = funcs::check_input($id);
		$log_id = funcs::check_input($log_id);

		$mail_from = "forderungsmanagement@tmp-sn.de";
		$sql = "SELECT t1.*, t2.id as member_id, t2.mobileno as member_mobileno, t2.email FROM payment_log t1, member t2 WHERE t1.username = t2.username AND t1.ID=".$log_id." ORDER BY t1.payday DESC";

		$entry = DBConnect::assoc_query_1D($sql);

		$entry['site'] = "Lovely Singles";
		$entry['site_url'] = URL_WEB;
		$entry['booking_number'] = CENTER_ID."-".$id."-".$log_id;
		$entry['until'] = date("d.m.Y", time()+7*24*60*60);
		$entry['total'] = $entry['sum_paid']+15;

		$sql = "SELECT * FROM member WHERE id = $id";
		$profile = DBConnect::assoc_query_1D($sql);
		$smarty->assign('profile', $profile);

		$smarty->assign('entry', $entry);
		$message =  $smarty->fetch('membership_cancel_email.tpl');

		//funcs::sendMail("hs@mintnet.de", "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
		//funcs::sendMail($mail_from, "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
		return funcs::sendMail($profile['email'], "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
	}

	function sendMemberCancelEmail30Days($id, $log_id, &$smarty)
	{
		$id = funcs::check_input($id);
		$log_id = funcs::check_input($log_id);

		$mail_from = "forderungsmanagement@tmp-sn.de";
		$sql = "SELECT t1.*, t2.id as member_id, t2.mobileno as member_mobileno, t2.email FROM payment_log t1, member t2 WHERE t1.username = t2.username AND t1.ID=".$log_id." ORDER BY t1.payday DESC";

		$entry = DBConnect::assoc_query_1D($sql);

		$entry['site'] = "Lovely Singles";
		$entry['site_url'] = URL_WEB;
		$entry['booking_number'] = CENTER_ID."-".$id."-".$log_id;
		$entry['until'] = date("d.m.Y", time()+7*24*60*60);
		$entry['total'] = $entry['sum_paid'] + 15;
		if($entry['new_type'] == 3)
			$entry['price_3_months'] = SILVER_DURATION_PRICE_3;
		elseif($entry['new_type'] == 2)
			$entry['price_3_months'] = GOLD_DURATION_PRICE_3;

		$sql = "SELECT * FROM member WHERE id = $id";
		$profile = DBConnect::assoc_query_1D($sql);
		$smarty->assign('profile', $profile);

		$smarty->assign('entry', $entry);
		$message =  $smarty->fetch('membership_cancel_email_30days.tpl');

		//funcs::sendMail("hs@mintnet.de", "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
		return funcs::sendMail($profile['email'], "Zahlungsaufforderung ".$entry['site'].".", $message, $mail_from);
	}

	static function logProfileAction($userid,$action)
	{
		$userid = funcs::check_input($userid);
		$action = funcs::check_input($action);

		//$action:										$origin:
		//			1 create										0 Kontaktmarkt
		//		 	2 edit										 	1 Verwaltungstool
		//			3 delete

		if ($_SESSION['sess_id'] != $userid){

			$origin = 0;

			/*if $_SESSION['Anim'] != '' {
				$id = $_SESSION['AnimID'];
				$origin = 1;
			}*/

			$sql = "INSERT INTO action_log
				SET animID = ".$_SESSION['sess_id'].",
				profileID = ".$userid.",
				action_date = NOW(),
				action_type = ".$action.",
				action_origin = ".$origin;

			DBconnect::execute($sql);

		}
	}

	static function removeEncodingProbs($text){

		$text = str_replace ("à¸£à¸„", "&auml;", $text);
		$text = str_replace ("à¸£ï¿½", "&Auml;", $text);
		$text = str_replace ("à¸£à¸–", "&ouml;", $text);
		$text = str_replace ("à¸£â€“", "&Ouml;", $text);
		$text = str_replace ("à¸£à¸œ", "&uuml;", $text);
		$text = str_replace ("à¸£ï¿½", "&Uuml;", $text);
		$text = str_replace ("à¸£ï¿½", "&szlig;", $text);

		$text = str_replace ("à¸£ï¿½à¸¢à¸„", "&auml;", $text);
		$text = str_replace ("à¸£ï¿½à¸¢à¸–", "&ouml;", $text);
		$text = str_replace ("à¸£ï¿½à¸¢à¸œ", "&uuml;", $text);
		$text = str_replace ("à¸£ï¿½à¸¥à¸˜", "&szlig;", $text);

		$text = str_replace ("&Atilde;&frac14;", "&uuml;", $text);
		$text = str_replace ("&Atilde;&curren;", "&auml;", $text);
		$text = str_replace ("&Atilde;&para;", "&ouml;", $text);

		return $text;
	}

	static function lookForSpecialChars($text){

		$counter = 0;
		$counter += substr_count($text,"à¸£à¸„");
		$counter += substr_count($text,"à¸£à¸œ");
		$counter += substr_count($text,"à¸£à¸–");
		$counter += substr_count($text,"à¸£ï¿½");
		$counter += substr_count($text,"à¸£ï¿½");
		$counter += substr_count($text,"à¸£â€“");
		$counter += substr_count($text,"à¸£ï¿½");

		if ($counter > 0){
			return true;
		}
		else{
			return false;
		}
	}

	static function externalLogin ($sessionID){
		//include_once("./libs/nusoap.php");
		//$soapclient = new soapclientnusoap(SERVER_URL);
		//$isValid = $soapclient->call('checkSession',$sessionID);
		//if ($isValid) echo "HURRA";
		//else echo "FUCK OFF";
		$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
		$isValid = $client->checkSession($sessionID);
		 return $isValid;
	}

	static function updateConfigs($name,$val){

		$name = funcs::check_input($name);
		$val = funcs::check_input($val);

		$sql = "UPDATE config SET value ='".$val."' WHERE name = '".$name."' limit 1";
		@mysql_query($sql);

		return @mysql_affected_rows();
	}

	static function prepareMembershipPage(&$smarty)
	{
		$payment_history = funcs::getPaymentHistory($_SESSION['sess_id']);
		$smarty->assign('payment_history', $payment_history);
		$smarty->assign('today',date("Y-m-d"));

		$silver_price = array(	"1" => SILVER_DURATION_PRICE_1,
								"2" => SILVER_DURATION_PRICE_2,
								"3" => SILVER_DURATION_PRICE_3,
								"4" => SILVER_DURATION_PRICE_4
								);

		$gold_price = array(	"2" => GOLD_DURATION_PRICE_2,
								"3" => GOLD_DURATION_PRICE_3,
								"4" => GOLD_DURATION_PRICE_4,
								"5" => GOLD_DURATION_PRICE_5,
								"6" => GOLD_DURATION_PRICE_6,
                                "7" => GOLD_DURATION_PRICE_7,
                                "8" => GOLD_DURATION_PRICE_8
								);

		if(($payment_history['new_paid_until'] != "0000-00-00 00:00:00") && ($payment_history['new_paid_until'] != ""))
		{
			$remain = (strtotime($payment_history['new_paid_until']) - mktime()) / 86400;
		}
		else
			$remain = -1;

		$duration_type = funcs::checkForMembershipDurationType($payment_history['payday'], $payment_history['new_paid_until']);

		$smarty->assign('remain',$remain);
		$smarty->assign('duration_type',$duration_type);
		$smarty->assign('silver_price',$silver_price);
		$smarty->assign('gold_price',$gold_price);
	}

	static function randomEmail() {
		$possible = "abcdfghjkmnpqrstvwxyz";
		for ($j = 1; $j <= 6; $j++) {
			$address_str .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		};
		$address_str .= "@";
		for ($j = 1; $j <= 4; $j++) {
			$address_str .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		};
		$address_str .= ".";
		for ($j = 1; $j <= 4; $j++) {
			$address_str .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
		};
		return $address_str;

	}

	static function randomMobileno() {
		$possible = "0123456789";
		$len = mt_rand(7,10);
		for ($j = 1; $j <= $len; $j++) {
			$mobileno .= substr($possible,mt_rand(0,strlen($possible)-1),1);
		}
		return $mobileno;
	}

	static function checkForMembershipDurationType($start, $end)
	{
		$duration = funcs::dateDiff("-", $end, $start);
		if(($duration > 1) && ($duration < 4))
			return 1;
		elseif($duration < 40)
			return 2;
		elseif($duration < 100)
			return 3;
		else
			return 4;
	}

	static function updatePhotoToTemp($userid, $picturepath){
		$userid = funcs::check_input($userid);
		$picturepath = funcs::check_input($picturepath);

		$sql = "insert into phototemp (userid,picturepath,datetime,status,site)values('$userid','$picturepath','".funcs::getDatetime()."','1','1')";
		DBconnect::execute($sql);
	}

	// Edited by Singh 08042008
	static function getPhotoTemp($id)
	{
		$id = funcs::check_input($id);

		$sql = "select p.*,
				m.username as username
				from phototemp as p,member as m
				WHERE m.id=p.userid
				order by p.id desc";
		$result = DBconnect::assoc_query_2D($sql);
		$photoresult = $returnResult = array();
		if(count($result)>0){
			foreach($result as $key=>$value){
				$photoresult["id"] = $value["id"];
				$photoresult["userid"] = $value["userid"];
				$photoresult["username"] = $value["username"];
				$photoresult["picturepath"] = $value["picturepath"];
				$photoresult["datetime"] = $value["datetime"];
				$photoresult["status"] = $value["status"];
				$photoresult["site"] = $id;
				$returnResult[] = $photoresult;
			}//end foreach
		}//end if
		return $returnResult;
	}//end function

	/*
	static function getPhotoTemp($id){
		$sql = "select * from phototemp group by userid order by id desc";
		$result = DBconnect::assoc_query_2D($sql);

		if(count($result)>0){
			$photoresult = array();
			for($i=0;$i<count($result);$i++){
				$PhotoProfile = DBconnect::assoc_query_2D("select picturepath from phototemp where (userid='".$result[$i][userid]."') and (status='1')");

				$photoresult[$i][id] = $result[$i][id];
				$photoresult[$i][userid] = $result[$i][userid];
				$photoresult[$i][picturepath] = $PhotoProfile[$i][picturepath];
				$photoresult[$i][datetime] = $result[$i][datetime];
				$photoresult[$i][status] = $result[$i][status];
				$photoresult[$i][site] = $id;
			}
		}
		return $photoresult;
	}
	*/
	static function getPhotoProfileTempOfEmailChatID($EmailChatID){
		$EmailChatID = funcs::check_input($EmailChatID);

		if($EmailChatID!="" && $EmailChatID > 0){ $cond = " and (userid='$EmailChatID')"; }

		$sql = "select * from phototemp where (status='1') $cond";
		$result = DBconnect::assoc_query_2D($sql);

		return $result;
	}

	static function getPhotoAlbumTempOfEmailChatID($EmailChatID){
		$EmailChatID = funcs::check_input($EmailChatID);

		if($EmailChatID!="" && $EmailChatID > 0){ $cond = " and (p.userid='$EmailChatID')"; }

		//$sql = "select * from phototemp where (status='2') $cond";
		$sql = "select p.*, m.username as username from phototemp p left join member m on m.id=p.userid where p.status='2' $cond order by p.id desc";
		$result = DBconnect::assoc_query_2D($sql);
		return $result;
	}

	static function getPhotoTempStatus($userid, $status){
		$userid = funcs::check_input($userid);
		$status = funcs::check_input($status);

		$sql = "select count(*) as num from phototemp where (userid='$userid') and (status='$status')";
		$result = DBconnect::assoc_query_1D($sql);

		return $result[num];
	}

	static function approvePhoto($userid,$photo){
		$userid = funcs::check_input($userid);

		for($i=0;$i<count($photo);$i++){
			$photo_id = funcs::check_input($photo[$i]);

			$result = DBconnect::assoc_query_2D("select * from phototemp where (id='".$photo_id."')");
			if($result != 0){
				if($result[0][status]==1){
					DBconnect::execute("update member set picturepath='".$result[0][picturepath]."' where (id='$userid')");
					DBconnect::execute("delete from phototemp where (id='".$photo_id."')");
				}elseif($result[0][status]==2){
					DBconnect::execute("insert into fotoalbum (userid,picturepath)values('$userid','".$result[0][picturepath]."')");
					DBconnect::execute("delete from phototemp where (id='".$photo_id."')");
				}
			}
		}

	}

	static function deninePhoto($userid,$photo){
		for($i=0;$i<count($photo);$i++){
			$photo_id = funcs::check_input($photo[$i]);

			DBconnect::execute("delete from phototemp where (id='".$photo_id."')");
		}
	}

	static function array_remove_key($arr, $key_remove)
	{
		$temp = array();
		foreach($arr as $key => $val)
		{
			if($key != $key_remove)
				$temp[$key] = $val;
		}

		return $temp;
	}

    static function getPayList($anfang, $ende, $jahr)
    {
		$anfang = funcs::check_input($anfang);
		$ende = funcs::check_input($ende);
		$jahr = funcs::check_input($jahr);

        //echo "Lovely Singles: getPayList aufgerufen...<br>";
        /**
	     * Geldeingà¸£à¸„nge, Mahngebà¸£à¸œhren, Sicherheit
	     */

        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            //Daten ermitteln
            $sql = "select sum(sum_paid), sum(reminder_costs) from payment_log where payment_complete = 1 and payday >= '".$jahr."-".$i."-01' and payday <= '".$jahr."-".$i."-31'";

            $res = DBConnect::assoc_query_1D($sql);
            $einbrutto = $res['sum(sum_paid)'];
            $mahn = $res['sum(reminder_costs)'];

            $list[$j] = array('monat' => $jahr."-".$i."-01", 'einbrutto' => $einbrutto, 'mahn' => $mahn);
            $j++;
        }

        /**
	     * Rà¸£à¸œcklastschriften
	     */
        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            //Daten ermitteln
            $sql = "select sum(sum_paid), sum(recall) from payment_log where payment_complete = 1 and payday >= '".$jahr."-".$i."-01' and payday <= '".$jahr."-".$i."-31' and recall = 1";

            $res = DBConnect::assoc_query_1D($sql);
            $rulabrutto = $res['sum(sum_paid)'];
            $anzrula = $res['sum(recall)'];

            $list[$j][rulabrutto] = $rulabrutto;
            $list[$j][anzrula] = $anzrula;
            $list[$j][dbbrutto] = $list[$j][einbrutto] - $rulabrutto;
            $j++;

        }

        /**
	     * Webcam
	     */
        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            $sql = "select count(id) from webcam_log where use_date >= '".$jahr."-".$i."-01' and use_date <= '".$jahr."-".$i."-31'";

            $webcam = DBConnect::retrieve_value($sql);
            $list[$j][webcam] = $webcam;
            $j++;
        }

        /**
	     * SMS
	     */
        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            $sql = "select count(id) from sms_log where send_date >= '".$jahr."-".$i."-01' and send_date <= '".$jahr."-".$i."-31'";

            $sms = DBConnect::retrieve_value($sql);

            $list[$j][sms] = $sms;
            $j++;
        }

        /**
	     * Paypal
	     */
        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$jahr."-".$i."-01' and payday <= '".$jahr."-".$i."-31' and paid_via = 2";

            $res = DBConnect::assoc_query_1D($sql);
            $wpaypal = $res['sum(sum_paid)'];
            $anzpaypal = $res['count(id)'];

            $list[$j][wpaypal] = $wpaypal;
            $list[$j][anzpaypal] = $anzpaypal;
            $j++;

        }

        /**
	     * iPayment
	     */
        $j = 0;
        //Monate aktuelles Jahr
        for ($i = $anfang; $i <= $ende; $i++) {

            $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$jahr."-".$i."-01' and payday <= '".$jahr."-".$i."-31' and paid_via = 4";

            $res = DBConnect::assoc_query_1D($sql);
            $wipay = $res['sum(sum_paid)'];
            $anzipay = $res['count(id)'];

            $list[$j][wipay] = $wipay;
            $list[$j][anzipay] = $anzipay;
            $j++;
        }

        return $list;
    }

    static function getUserStat($date, $type)
    {
		$date = funcs::check_input($date);
		$type = funcs::check_input($type);

		$sqldate = strftime("%Y-%m-%d", strtotime($date));
		$day = strftime("%d", strtotime($date));
		$weekday = strftime("%u", strtotime($date));

		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));

		$weekdate_to = $year."-".$month."-".($day + 7 - $weekday);
		$weekdate_from = $year."-".$month."-".($day - $weekday + 1);

		//Statistikdaten des Marktes holen

		//Anzahl aller angemeldeten User (auch Standard) zum angegebenen Zeitpunkt (Tag)
		$sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) <= '".$sqldate."'";

		//zur angegebenen Woche
		if ($type == 'woche')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) <= '".$weekdate_to."'";

		//zum angegebenen Monat
		if ($type == 'monat')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) <= '".$year."-".$month."-31'";

		$list[userAll] = DBConnect::retrieve_value($sql);


		//Anzahl der neu angemeldeten User (auch Standard) zum angegebenen Zeitpunkt (Tag)
		$sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) = '".$sqldate."'";

		//in angegebener Woche
		if ($type == 'woche')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) >= '".$weekdate_from."' and date(signup_datetime) <= '".$weekdate_to."'";

		//im angegebenen Monat
		if ($type == 'monat')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and date(signup_datetime) >= '".$year."-".$month."-01' and date(signup_datetime) <= '".$year."-".$month."-31'";

		$list[userNeu] = DBConnect::retrieve_value($sql);


		//Anzahl der neu angemeldeten User Typ 2 = VIP zum angegebenen Zeitpunkt (Tag)
		$sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '2' and date(signup_datetime) = '".$sqldate."'";

		//in angegebener Woche
		if ($type == 'woche')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '2' and date(signup_datetime) >= '".$weekdate_from."' and date(signup_datetime) <= '".$weekdate_to."'";

		//im angegebenen Monat
		if ($type == 'monat')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '2' and date(signup_datetime) >= '".$year."-".$month."-01' and date(signup_datetime) <= '".$year."-".$month."-31'";

		$list[typ2] = DBConnect::retrieve_value($sql);


		//Anzahl der neu angemeldeten User Typ 3 = Premium zum angegebenen Zeitpunkt (Tag)
		$sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '3' and date(signup_datetime) = '".$sqldate."'";

		//in angegebener Woche
		if ($type == 'woche')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '3' and date(signup_datetime) >= '".$weekdate_from."' and date(signup_datetime) <= '".$weekdate_to."'";

		//im angegebenen Monat
		if ($type == 'monat')
		   $sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '3' and date(signup_datetime) >= '".$year."-".$month."-01' and date(signup_datetime) <= '".$year."-".$month."-31'";

		$list[typ3] = DBConnect::retrieve_value($sql);


		//Anzahl der neu angemeldeten User Typ 4 = Standard
		//$sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '4' and signup_datetime >= '".$monat."-01 00:00:00' and signup_datetime <= '".$monat."-31 24:00:00'";
		//$list[typ4] = DBConnect::retrieve_value($sql);

		//Anzahl neuer Abos
		//$sql = "select count(*) from payment_log where old_paid_until = '0000-00-00 00:00:00' and payday >= '".$monat."-01 00:00:00' and payday <= '".$monat."-31 24:00:00'";
		//$list[abos_neu] = DBConnect::retrieve_value($sql);

		//Anzahl gekà¸£à¸œndigter Abos
		//$sql = "select count(*) from payment_log where cancelled = '1' and cancelled_date >= '".$monat."-01 00:00:00' and cancelled_date <= '".$monat."-31 24:00:00'";
		//$list[abos_cancel] = DBConnect::retrieve_value($sql);

		//Anzahl verlà¸£à¸„ngerter Abos
		//$sql = "select count(*) from payment_log where prolonging = '1' and payment_complete = '1' and payday >= '".$monat."-01 00:00:00' and payday <= '".$monat."-31 24:00:00'";
		//$list[abos_prolonging] = DBConnect::retrieve_value($sql);


		//Summe Einzahlungen (EUR) am angegebenen Tag
		$sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) = '".$sqldate."'";

		//in angegebener Woche
		if ($type == 'woche')
		   $sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) >= '".$weekdate_from."' and date(payday) <= '".$weekdate_to."'";

		//im angegebenen Monat
		if ($type == 'monat')
		   $sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) >= '".$year."-".$month."-01' and date(payday) <= '".$year."-".$month."-31'";

		$list[ein] = DBConnect::retrieve_value($sql);


		//Summe Rà¸£à¸œcklastschriften (EUR) am angegebenen Tag
		$sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) = '".$sqldate."' and recall = 1";

		//in angegebener Woche
		if ($type == 'woche')
		   $sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) >= '".$weekdate_from."' and date(payday) <= '".$weekdate_to."' and recall = 1";

		//im angegebenen Monat
		if ($type == 'monat')
		   $sql = "select sum(sum_paid) from payment_log where payment_complete = 1 and date(payday) >= '".$year."-".$month."-01' and date(payday) <= '".$year."-".$month."-31' and recall = 1";

		$list[rueck] = DBConnect::retrieve_value($sql);

		//Summe Ausschà¸£à¸œttung (EUR)
		//$sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '4' and signup_datetime >= '".$monat."-01 00:00:00' and signup_datetime <= '".$monat."-31 24:00:00'";
		//$list[typ4] = DBConnect::retrieve_value($sql);

		//Summe fà¸£à¸œr uns ("Heiko") (EUR)
		//$sql = "select count(*) from member where isactive = '1' and fake = '0' and type = '4' and signup_datetime >= '".$monat."-01 00:00:00' and signup_datetime <= '".$monat."-31 24:00:00'";
		//$list[typ4] = DBConnect::retrieve_value($sql);

		return $list;

   }

	//PaymentStatistik eines emailchats fà¸£à¸œr einen Zeitraum holen
	// $date = Datum innerhalb des Zeitraums
	// $type = 'tag' / 'woche' / 'monat'
	function getPayData($date, $type)
	{
		$date = funcs::check_input($date);
		$type = funcs::check_input($type);

		$sqldate = strftime("%Y-%m-%d", strtotime($date));
		$day = strftime("%d", strtotime($date));
		$weekday = strftime("%u", strtotime($date));

		$month = date("m", strtotime($date));
		$year = date("Y", strtotime($date));

		$weekdate_to = $year."-".$month."-".($day + 7 - $weekday);
		$weekdate_from = $year."-".$month."-".($day - $weekday + 1);


		 /**
		* Geldeingà¸£à¸„nge, Mahngebà¸£à¸œhren, Sicherheit
		*/
		$sql = "select sum(sum_paid), sum(reminder_costs) from payment_log where payment_complete = 1 and payday = '".$sqldate."'";

		if ($type == 'woche')
		   $sql = "select sum(sum_paid), sum(reminder_costs) from payment_log where payment_complete = 1 and payday >= '".$weekdate_from."' and payday <= '".$weekdate_to."'";

		if ($type == 'monat')
		   $sql = "select sum(sum_paid), sum(reminder_costs) from payment_log where payment_complete = 1 and payday >= '".$year."-".$month."-01' and payday <= '".$year."-".$month."-31'";


		$res = DBConnect::assoc_query_1D($sql);

		$list['einbrutto'] = $res['sum(sum_paid)'];
		$list['mahn'] = $res['sum(reminder_costs)'];

		/**
		 * Rà¸£à¸œcklastschriften
		 */
		$sql = "select sum(sum_paid), sum(recall) from payment_log where payment_complete = 1 and payday = '".$sqldate."' and recall = 1";

		if ($type == 'woche')
		   $sql = "select sum(sum_paid), sum(recall) from payment_log where payment_complete = 1 and payday >= '".$weekdate_from."' and payday <= '".$weekdate_to."' and recall = 1";

		if ($type == 'monat')
		   $sql = "select sum(sum_paid), sum(recall) from payment_log where payment_complete = 1 and payday >= '".$year."-".$month."-01' and payday <= '".$year."-".$month."-31' and recall = 1";

		$res = DBConnect::assoc_query_1D($sql);

		$list[rulabrutto] = $res['sum(sum_paid)'];
		$list[anzrula] = $res['sum(recall)'];
		$list[dbbrutto] = $list[einbrutto] - $list[rulabrutto];

		/**
		* Webcam
		*/
		$sql = "select count(id) from webcam_log where use_date >= '".$sqldate."'";

		if ($type == 'woche')
		   $sql = "select count(id) from webcam_log where use_date >= '".$weekdate_from."' and use_date <= '".$weekdate_to."'";

		if ($type == 'monat')
		   $sql = "select count(id) from webcam_log where use_date >= '".$year."-".$month."-01' and use_date <= '".$year."-".$month."-31'";

		$webcam = DBConnect::retrieve_value($sql);
		$list[webcam] = $webcam;

		/**
		 * SMS
		 */
		$sql = "select count(id) from sms_log where send_date >= '".$sqldate."'";

		if ($type == 'woche')
		   $sql = "select count(id) from sms_log where send_date >= '".$weekdate_from."' and send_date <= '".$weekdate_to."'";

		if ($type == 'monat')
		   $sql = "select count(id) from sms_log where send_date >= '".$year."-".$month."-01' and send_date <= '".$year."-".$month."-31'";

		$list[sms] = DBConnect::retrieve_value($sql);

		/**
		 * Paypal
		 */
		$sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$sqldate."' and paid_via = 2";

		if ($type == 'woche')
		   $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$weekdate_from."' and payday <= '".$weekdate_to."' and paid_via = 2";

		if ($type == 'monat')
		   $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$year."-".$month."-01' and payday <= '".$year."-".$month."-31' and paid_via = 2";


		$res = DBConnect::assoc_query_1D($sql);

		$list[wpaypal] = $res['sum(sum_paid)'];;
		$list[anzpaypal] = $res['count(id)'];

		/**
		 * iPayment
		 */
		$sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$sqldate."' and paid_via = 4";

		if ($type == 'woche')
		   $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$weekdate_from."' and payday <= '".$weekdate_to."' and paid_via = 4";

		if ($type == 'monat')
		   $sql = "select sum(sum_paid), count(id) from payment_log where payment_complete = 1 and payday >= '".$year."-".$month."-01' and payday <= '".$year."-".$month."-31' and paid_via = 4";

		$res = DBConnect::assoc_query_1D($sql);

		$list[wipay] = $res['sum(sum_paid)'];
		$list[anzipay] = $res['count(id)'];


		return $list;
	}

	#Noi paste this function
	static function getTopSpendCoinUser($days_before, $start=0, $limit=1000)
	{
		$days_before = funcs::check_input($days_before);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$username = $_SESSION['sess_username'];
		$SuperUser = array('superadmin','cyric','heiko','Kleopatra33');
		if(!in_array($username,$SuperUser)){
			$condman = " (".TABLE_MEMBER_COUNT." <=".MALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 1)";
			$condwoman = " (".TABLE_MEMBER_COUNT." <=".FEMALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 2)";
			$CONDITION  = " AND ( $condman OR $condwoman ) AND ".TABLE_MEMBER_FLAG." != 1" ;
		}

		$sqlGetRecords = "
						SELECT
							t1.".TABLE_MEMBER_USERNAME." AS username,
							t1.signup_datetime as registred,
							t1.id as user_id,
							t4.name as ".TABLE_MEMBER_CITY.",
							t3.name as ".TABLE_MEMBER_STATE.",
							t2.name as ".TABLE_MEMBER_COUNTRY.",
							SUM(c.coin) * -1 AS spent_coin,
							t1.coin AS remain_coin,
							MIN(c.log_date) as spend_date
						";
		$sqlGetTotal = "
						SELECT
							count(t1.id)
					   ";
		$sqlCondition = "
						FROM
							coin_log c
						INNER JOIN
							".TABLE_MEMBER." t1 ON c.member_id = t1.id
						LEFT OUTER JOIN xml_countries t2
							ON t1.country = t2.id
						LEFT OUTER JOIN xml_states t3
							ON t1.state=t3.id
						LEFT OUTER JOIN xml_cities t4
							ON t1.city=t4.id
						WHERE
							c.coin_field != 'bonus'
						AND
							t1.".TABLE_MEMBER_ISACTIVE." = 1 AND t1.fake=0 AND c.coin<0 $CONDITION
						AND
							log_date >= DATE_ADD(NOW(), INTERVAL -$days_before DAY)
						GROUP BY
							t1.id

						";
		$sqlLimit =		"
						ORDER BY
							spent_coin DESC
						LIMIT $start, $limit
						";

		/*echo $sqlGetTotal.$sqlCondition;
		echo "<br/><br/>";*/

		$result['data'] = DBconnect::assoc_query_2D($sqlGetRecords.$sqlCondition.$sqlLimit);
		$result['total'] = count(DBConnect::assoc_query_2D($sqlGetTotal.$sqlCondition));

		//return $result;

		/*echo "<pre>";
		print_r($result);
		echo "</pre>";*/

		return $result;
	}

	static function getUsersListForBonus($arr)
	{
		$country = funcs::check_input($arr['co']);
		$city = funcs::check_input($arr['ci']);
		$state = funcs::check_input($arr['s']);
		$search_username = funcs::check_input($arr['u']);
		$fromdate = funcs::check_input($arr['fromdate']);
		$todate = funcs::check_input($arr['todate']);
		$username = $_SESSION['sess_username'];

		$sqlGetMember = "SELECT	t1.".TABLE_MEMBER_USERNAME." AS username,
								t1.signup_datetime as registred,
								t1.id as user_id,
								t4.name as ".TABLE_MEMBER_CITY.",
								t3.name as ".TABLE_MEMBER_STATE.",
								t2.name as ".TABLE_MEMBER_COUNTRY.",
								SUM(c.coin) * -1 AS spent_coin,
								t1.coin AS remain_coin,
								MIN(c.log_date) as spend_date

								FROM ".TABLE_MEMBER." t1 LEFT JOIN ( SELECT * FROM coin_log WHERE coin < 0) c
									ON c.member_id = t1.id
								LEFT OUTER JOIN xml_countries t2
									ON t1.country = t2.id
								LEFT OUTER JOIN xml_states t3
									ON t1.state=t3.id
								LEFT OUTER JOIN xml_cities t4
									ON t1.city=t4.id
								WHERE (t1.".TABLE_MEMBER_ISACTIVE." = 1) AND t1.fake=0 $CONDITION"; // AND (t1.".TABLE_MEMBER_PICTURE." !='')";

		if($country!=0 && $country!=''){
			 $sqlGetMember .= " AND (t1.country='$country')";
		}

		if($city!=0 && $city!=''){
			$sqlGetMember .= " AND (t1.city='$city')";
		}

		if($state!=0 && $state!=''){
			$sqlGetMember .= " AND (t1.state='$state')";
		}

		if($search_username!=""){
			$sqlGetMember .= " AND (t1.username like '$search_username%')";
		}

		if($fromdate!=""){
			$sqlGetMember .= " AND DATE(t1.signup_datetime) >= '".$fromdate."'";
		}

		if($todate!=""){
			$sqlGetMember .= " AND DATE(t1.signup_datetime) <= '".$todate."'";
		}

		$sqlGetMember .= " GROUP BY t1.id ORDER BY spent_coin DESC";

		$countMember = count(DBconnect::assoc_query_2D($sqlGetMember));

		$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();

		$data = DBconnect::assoc_query_2D($sqlGetMember);
		//print_r($data);

		return array("data" => $data, "count" => $countMember);
	}

	static function getCoinLog($userid, $days_before, $start, $limit)
	{
		$userid = funcs::check_input($userid);
		$days_before = funcs::check_input($days_before);
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sqlGetRecords = "
						SELECT
							c.log_date,
							c.coin_field,
							m.username AS send_to_user,
							c.coin,
							c.coin_remain
						FROM
							coin_log c
						LEFT JOIN
							".TABLE_MEMBER." m ON c.send_to = m.id
						";
		$sqlGetTotal = "
						SELECT
							count(*)
						FROM
							coin_log c
					   ";
		$sqlCondition = "
						WHERE
							c.member_id = '$userid'
						AND
							c.coin_field != 'bonus'
						AND
							c.log_date >= DATE_ADD(NOW(), INTERVAL -$days_before DAY)
						";
		$sqlLimit =		"
						ORDER BY
							c.log_date DESC
						LIMIT $start, $limit
						 ";
		$result['data'] = DBconnect::assoc_query_2D($sqlGetRecords.$sqlCondition.$sqlLimit);
		$result['total'] = DBConnect::retrieve_value($sqlGetTotal.$sqlCondition);

		return $result;
	}

	static function addBonus($userid, $coins)
	{
		$userid = funcs::check_input($userid);
		$coins = funcs::check_input($coins);
		$code = substr(time(), 6);

		$sql = "INSERT INTO coin_bonus (member_id, coin_plus, status, vcode, vcode_insert_time) VALUES ('$userid',".mysql_real_escape_string($coins).",'0', '".$code."', NOW())";
		DBconnect::execute($sql);

		return $code;
	}

	static function getMobileNo($userid)
	{
		$userid = funcs::check_input($userid);

		$no = DBconnect::retrieve_value("SELECT mobileno FROM ".TABLE_MEMBER." WHERE id = '".$userid."'");

		return $no;
	}

	static function getCurrentUserMobileNo()
	{
		$userid = $_SESSION['sess_id'];
		$mobile_no = DBconnect::retrieve_value("SELECT mobileno FROM ".TABLE_MEMBER." WHERE id = '".$userid."'");

		if($mobile_no=="")
		{
			$waitver_no = DBconnect::retrieve_value("SELECT waitver_mobileno FROM ".TABLE_MEMBER." WHERE id = '".$userid."'");
			if($waitver_no=="")
			{
				return "Step2";
			}
			else
			{
				return "Step3";
			}
		}
		else
			return "Verified";
	}

	static function setNulCurrentUserMobileNo()
	{
		$userid = $_SESSION['sess_id'];
		$sqlSetNull = "UPDATE ".TABLE_MEMBER." SET waitver_mobileno = '', vcode_mobile = '' WHERE id = '".$userid."'";
		DBconnect::execute($sqlSetNull);

		return "1";
	}

	static function checkBonus($userid)
	{
		$userid = funcs::check_input($userid);

		$bonusid = DBconnect::retrieve_value("SELECT id FROM coin_bonus WHERE member_id = '".$userid."' AND status = '0' AND vcode_insert_time >= DATE_ADD(NOW(), INTERVAL -7 DAY)");

		return $bonusid;
	}

	static function verifyMemberBonus($userid, $bonuscode)
	{
		$userid = funcs::check_input($userid);
		$bonuscode = funcs::check_input($bonuscode);

		if(ctype_digit($userid) && strlen(trim($bonuscode)) > 0)
		{
			$data = DBConnect::assoc_query_1D("SELECT id, coin_plus, vcode_insert_time, DATE_ADD(NOW(), INTERVAL -7 DAY) AS expire_time, status FROM coin_bonus WHERE member_id = '".$userid."' AND vcode = '".$bonuscode."'");

			if(($data['coin_plus'] != '') && ($data['coin_plus'] > 0)){
				if($data['vcode_insert_time'] >= $data['expire_time'])
				{
					if($data['status'] != '1')
					{
						$sqlUpdateBonus = "UPDATE ".TABLE_MEMBER." SET coin = coin + ".$data['coin_plus']." WHERE id = '".$userid."'";
						DBconnect::execute($sqlUpdateBonus);

						$sqlSetBonusStatus = "UPDATE coin_bonus SET vcode_verify_time = NOW(), status = '1' WHERE id = '".$data['id']."'";
						DBconnect::execute($sqlSetBonusStatus);

						$coinVal = funcs::checkCoin($_SESSION['sess_username']);

						$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('1','$userid','bonus',".$data['coin_plus'].",".$coinVal.", NOW())";
						DBconnect::execute($sqlAddCoinLog);

						return 1;
					}
					else
					{
						return 4; //verified
					}
				}
				else
				{
					return 3; //time out
				}
			}
			else{
				return 2; //invalid code
			}
		}
		else{
			return 2;
		}
	}

	static function getBonusHistory($start, $limit)
	{
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sqlGetRecords = "
						SELECT
							m.username,
							c.coin_plus,
							c.vcode_insert_time,
							CASE WHEN c.vcode_verify_time <> '0000-00-00 00:00:00' THEN
								c.vcode_verify_time
							ELSE
								''
							END AS verify_time,
							CASE WHEN c.status = 1 THEN
								'Verified'
							ELSE
								'Waiting'
							END AS status_text
						FROM
							coin_bonus c
						LEFT JOIN
							member m
						ON
							c.member_id = m.id
						ORDER BY
							c.id DESC
						LIMIT $start, $limit
						";

		$result['data'] = DBconnect::assoc_query_2D($sqlGetRecords);
		$result['total'] = DBConnect::retrieve_value("SELECT count(*) FROM coin_bonus");

		return $result;
	}

	static function getCoinPackage()
	{
		$sql = "SELECT * FROM coin_package ORDER BY currency_price ASC";
		$result = DBconnect::assoc_query_2D($sql);
		
		return $result;
	}

	static function getConfCurrency()
	{
		$sql = "select value from config where name = 'CURRENCY'";
		$result = DBconnect::assoc_query_1D($sql);

		return $result;
	}

	static function deleteCoinPackage($id)
	{
		$id = funcs::check_input($id);

		$sql = "delete from coin_package where id = $id limit 1";
		DBconnect::execute($sql);

		return true;
	}

	static function getConfigCurrency()
	{
		$sql = "select a.value,b.id from config as a left join currency as b on a.value = b.name where a.name = 'CURRENCY'";
		$result = DBconnect::assoc_query_1D($sql);

		return $result;
	}

	static function updateConfigCurrency($type)
	{
		$type = funcs::check_input($type);

		$sql = "update config set value ='".$type."' where name = 'CURRENCY'";
		if(DBconnect::execute($sql))
		{
			return true;
		}
		else
		{
			$sql = "insert into config (name, value) values ('CURRENCY', '$type')";
			DBconnect::execute($sql);

			return true;
		}


	}

	static function getCurrencyName()
	{
		$sql = "select id, name from currency";
		$reCurr = DBConnect::assoc_query_2D($sql);

		return $reCurr;
	}

	static function getCurrency($start, $limit)
	{
		$start = funcs::check_input($start);
		$limit = funcs::check_input($limit);

		$sql = "select * from coin_package order by id asc limit $start, $limit";

		$result['data'] = DBconnect::assoc_query_2D($sql);
		$result['total'] = DBConnect::retrieve_value("select count(*) from coin_package");

		return $result;
	}

	static function getZodiac($bdate)
	{
		$pyear = '1999';
		$byear = '2000';
		$nyear = '2001';

		if(strtotime($byear.'-'.$bdate) >= strtotime($pyear.'-'.'12-22') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'01-19'))
			$Zodiac = '12';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'01-20') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'02-18'))
			$Zodiac = '1';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'02-19') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'03-20'))
			$Zodiac = '2';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'03-21') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'04-19'))
			$Zodiac = '3';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'04-20') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'05-20'))
			$Zodiac = '4';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'05-21') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'06-20'))
			$Zodiac = '5';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'06-21') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'07-22'))
			$Zodiac = '6';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'07-23') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'08-23'))
			$Zodiac = '7';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'08-24') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'09-22'))
			$Zodiac = '8';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'09-23') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'10-22'))
			$Zodiac = '9';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'10-23') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'11-21'))
			$Zodiac = '10';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'11-22') && strtotime($byear.'-'.$bdate) <= strtotime($byear.'-'.'12-21'))
			$Zodiac = '11';
		else if(strtotime($byear.'-'.$bdate) >= strtotime($byear.'-'.'12-22') && strtotime($byear.'-'.$bdate) <= strtotime($nyear.'-'.'01-19'))
			$Zodiac = '12';


		return $Zodiac;

		/*
		$ZodiacArray = array(

							'Aquarius'		=> array('start' => '01-20', 'end' => '02-18'), // ( Jan 20 - Feb 18 )
							'Pisces'		=> array('start' => '02-19', 'end' => '03-20'), // ( Feb 19 - Mar 20 )
							'Aries'			=> array('start' => '03-21', 'end' => '04-19'), // ( Mar 21 - Apr 19 )
							'Taurus'		=> array('start' => '04-20', 'end' => '05-20'), // ( Apr 20 - May 20 )
							'Gemini'		=> array('start' => '05-21', 'end' => '06-20'), // ( May 21 - Jun 20 )
							'Cancer'		=> array('start' => '06-21', 'end' => '07-22'), // ( Jun 21 - Jul 22 )
							'Leo'			=> array('start' => '07-23', 'end' => '08-23'), // ( Jul 23 - Aug 23 )
							'Virgo'			=> array('start' => '08-24', 'end' => '09-22'), // ( Aug 24 - Sep 22 )
							'Libra'			=> array('start' => '09-23', 'end' => '10-22'), // ( Sep 23 - Oct 22 )
							'Scorpio'		=> array('start' => '10-23', 'end' => '11-21'), // ( Oct 23 - Nov 21 )
							'Sagittarius'	=> array('start' => '11-22', 'end' => '12-21'), // ( Nov 22 - Dec 21 )
							'Capricorn'		=> array('start' => '12-22', 'end' => '01-19')  // ( Dec 22 - Jan 19 )
							);*/
	}

	static function getCountryCode($country_id, $hidden_field = false)
	{
		$country_id = funcs::check_input($country_id);
		$hidden_field = funcs::check_input($hidden_field);

		if($hidden_field == true)
			$selected_field = 'country_prefix_hidden';
		else
			$selected_field = 'country_prefix';

		$country_id = funcs::check_input($country_id);
		$country_code = DBConnect::retrieve_value("SELECT $selected_field FROM xml_countries WHERE id = '$country_id'");

		return $country_code;
	}

	static function getCountryCodeByUsername($username, $hidden_field = false)
	{
		$username = funcs::check_input($username);
		$hidden_field = funcs::check_input($hidden_field);

		if($hidden_field == true)
			$selected_field = 'country_prefix_hidden';
		else
			$selected_field = 'country_prefix';

		$country_id = funcs::check_input($username);
		$country_code = DBConnect::retrieve_value("SELECT $selected_field FROM xml_countries c LEFT JOIN member m ON c.id = m.country WHERE m.username = '$username'");

		return $country_code;
	}

	static function ajaxFormIncompleteInfo($mobileno)
	{
		$mobileno = funcs::check_input($mobileno);

		$userid = funcs::getUserid($_SESSION['sess_username']);
		$profile = funcs::getProfile($userid);	//get profile data

		$zeropos = strpos($mobileno,'0');

		if($zeropos === false || $zeropos > 0)
			$clr_mobileno = $mobileno;
		else
			$clr_mobileno = substr($mobileno,1,strlen($mobileno)-1);

		$postArray['waitver_mobileno'] = funcs::getCountryCode($profile['country'], true) . $clr_mobileno; // . $_POST['phone_number']; $_POST['country']

		//sms
		if(funcs::checkVcodeCount($userid))
		{
			funcs::completeInfo($postArray);

			if($postArray['waitver_mobileno'] != ""){
				$dial_number = $postArray['waitver_mobileno'];
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
				sendSMSCode($dial_number, $smsmsg);
				funcs::increaseVcodeCount($userid);
				return true;
			}
		}
		else
			return funcs::getText($_SESSION['lang'], '$limit_code_resend');

	}

	static function ajaxFormMobileVerify($vercode){
		switch(funcs::verifyMobile($_SESSION['sess_username'],$vercode)){
			case 1://complete comfirm
				return "1"; //for checking on script.js
				break;
			case 2://blank code
				return funcs::getText($_SESSION['lang'], '$err_blank_valid_code');
				break;
			case 3://wrong verify code
				return funcs::getText($_SESSION['lang'], '$err_valid_code');
				break;
			case 4://timeout
				return funcs::getText($_SESSION['lang'], '$err_valid_code_timeout');
				break;
		}
	}

	static function ajaxFormResendVerify()
	{
		$userid = funcs::getUserid($_SESSION['sess_username']);
		if(funcs::checkVcodeCount($userid))
		{
			$waitMobileNo = funcs::getWaitVerifyMobile($_SESSION['sess_username']);
			$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
			sendSMSCode($waitMobileNo, $smsmsg);
			funcs::increaseVcodeCount($userid);
			return funcs::getText($_SESSION['lang'], '$valid_code_resend');
		}
		else
		{
			return funcs::getText($_SESSION['lang'], '$limit_code_resend');
		}
	}
}
?>