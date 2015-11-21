<?php
require_once('config.php');
class funcs
{
	#Pakin Change this function
	function activateMember($username, $password, $code)
	{
		$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER."
				   WHERE ".TABLE_MEMBER_USERNAME."='".$username."'
						AND ".TABLE_MEMBER_PASSWORD."='".$password."'
						AND ".TABLE_MEMBER_VALIDATION."='".$code."'
						AND ".TABLE_MEMBER_ISACTIVE."=0";
		$row = DBconnect::get_nbr($sql);
		if($row > 0)
		{
			$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE."=1
						WHERE ".TABLE_MEMBER_USERNAME."='".$username."' LIMIT 1";
			DBconnect::execute($sql);

			$userid = funcs::getUserid($username);
			$subject = funcs::getText($_SESSION['lang'], '$first_time_inbox_subject');
			$message = funcs::getText($_SESSION['lang'], '$first_time_inbox_message');
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$userid.",
					".TABLE_MESSAGE_INBOX_FROM."=1,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message."',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute($sql);
			#Pakin Change this function
			self::NewSorting($username);
			return true;
		}
		else
			return false;
	}
	#Pakin Change this function => new!!!
	function NewSorting($username)
	{
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
		$sql = "SELECT ".TABLE_MEMBER_USERNAME." AS user
					FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."= '$userid' ";
		$rec = DBconnect::assoc_query_1D ($sql);
		$username = $rec['user'];
		return $username;
	}
	static function addFavorite($parentid, $childid)
	{
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
		//send message to email chat tool
		funcs::sendMessage($save[TABLE_LONELYHEART_USERID], 'Kontaktanzeige', $save[TABLE_LONELYHEART_HEADLINE], $save[TABLE_LONELYHEART_TEXT], 5);
		
		//get column names
		$colnames = array_flip(DBconnect::get_col_names(TABLE_LONELYHEART));
		//delete everything that is not in the database
		$member_post = array_intersect_key($save, $colnames);
		//create the member and get the id from the creation
		return DBconnect::assoc_insert_1D($member_post, TABLE_LONELYHEART);
	}

	static function addLonelyHeartSoap($userid, $target, $category, $headline, $message)
	{
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
				$list .= TABLE_MESSAGE_INBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function admin_addSuggestion($subject, $message)
	{
		$sql = "INSERT INTO ".TABLE_ADMIN_SUGGESTION."
				SET ".TABLE_ADMIN_SUGGESTION_SUBJECT."='".$subject."',
				".TABLE_ADMIN_SUGGESTION_MESSAGE."='".$message."',
				".TABLE_ADMIN_SUGGESTION_DATETIME."='".funcs::getDateTime()."'";

		return DBconnect::execute_q($sql);
	}

	static function admin_checkMessage($mid,$type)
	{
		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_ADMIN_MESSAGE_INBOX." SET status = '1' where 	".TABLE_ADMIN_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_ADMIN_MESSAGE_OUTBOX." SET status = '1' where 	".TABLE_ADMIN_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}

	static function admin_deleteMessage_inbox($messageid)
	{
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
				$sql .= TABLE_ADMIN_SUGGESTION_ID."=".$suggestion_id[$n];
			}
			$sql .= ")";
			return DBconnect::execute_q($sql);
		}

		return false;
	}

	static function admin_getAllMessage_inbox($start, $limit)
	{
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
		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME.", t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_ADMIN_MESSAGE_INBOX." t2 WHERE t2.".TABLE_ADMIN_MESSAGE_INBOX_ID."=".$id." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_ADMIN_MESSAGE_INBOX_FROM;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function admin_getMessage_outbox($id)
	{
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
		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." t1, ".TABLE_ADMIN_MESSAGE_INBOX." t2 WHERE t1.".TABLE_MEMBER_ID."=".TABLE_ADMIN_MESSAGE_INBOX_FROM." AND t2.".TABLE_ADMIN_MESSAGE_INBOX_ID."=".$messageid;

		return DBconnect::retrieve_value($sql);
	}

	static function admin_replyMessage($messageid, $subject, $message)
	{
		$data = funcs::admin_getMessage_inbox($messageid);
		if($data[TABLE_ADMIN_MESSAGE_INBOX_FROM] != '')
		{
			$sql = "INSERT INTO ".TABLE_ADMIN_MESSAGE_OUTBOX."
					SET ".TABLE_ADMIN_MESSAGE_OUTBOX_TO."=".$data[TABLE_ADMIN_MESSAGE_INBOX_FROM].",
					".TABLE_ADMIN_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_MESSAGE."='".$message."',
					".TABLE_ADMIN_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);

			echo $sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$data[TABLE_ADMIN_MESSAGE_INBOX_FROM].",
					".TABLE_MESSAGE_INBOX_FROM."=".$_SESSION['sess_id'].",
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
		$userid = funcs::getUserid($to);
		if($userid != '')
		{
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
		$sql = "UPDATE ".TABLE_ADMIN_SUGGESTION."
				SET ".TABLE_ADMIN_SUGGESTION_SUBJECT."='".$subject."',
				".TABLE_ADMIN_SUGGESTION_MESSAGE."='".$message."'
				WHERE ".TABLE_ADMIN_SUGGESTION_ID."=".$suggestion_id;
		return DBconnect::execute_q($sql);
	}

	static function adv_search($save)
	{
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
		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_MESSAGE_INBOX." SET status = '1' where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_MESSAGE_OUTBOX." SET status = '1' where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}

	static function checkSugges($mid,$type)
	{
		if($type == "inbox")
		{
			$sql = "UPDATE ".TABLE_SUGGESTION_INBOX." SET status = '1' where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
		else if($type == "outbox")
		{
			$sql = "UPDATE ".TABLE_SUGGESTION_OUTBOX." SET status = '1' where 	".TABLE_MESSAGE_INBOX_ID." = '".$mid."'";
			return DBconnect::execute_q($sql);
		}
	}


	static function checkPermission(&$smarty, $permission)
	{
		if(!in_array($_SESSION['sess_permission'], $permission))
		{
			$text = funcs::getText($_SESSION['lang'], '$allow');
			
			for($n=0; $permission[$n]; $n++)
			{
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
					case 9:
						$text .= "Studiadmin";						
						break;
				}

			}
			$text .= '.';

			$smarty->assign('text', $text);
			$smarty->assign('section', 'blank');
			$smarty->assign('payment_history',funcs::getPaymentHistory($_SESSION['sess_id']));

			$smarty->display('index.tpl');
			exit();
		}
	}

	static function checkFor1DayGold($userid){
		
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
	
	static function DeleteCard($cardid){
		$picpath = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDPATH,TABLE_CARD_ID,$cardid);
		$pictmp = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDTMP,TABLE_CARD_ID,$cardid);
		@unlink($picpath);
		@unlink($pictmp);
		$cond = "WHERE ".TABLE_CARD_ID."= '$cardid'";
		DBconnect::delete_data (TABLE_CARD,$cond);
	}

	static function deleteFavorite($userid, $username)
	{
		$id = funcs::getUserid($username);
		$sql = "DELETE FROM ".TABLE_FAVORITE." WHERE ".TABLE_FAVORITE_CHILD."=".$id." AND ".TABLE_FAVORITE_PARENT."=".$userid." LIMIT 1";
		DBconnect::execute_q($sql);
	}

	static function deleteFotoAlbum($fotoid, $userid)
	{
		$data = funcs::getFotoAlbum($fotoid, $userid);
		$pic = UPLOAD_DIR.$data[TABLE_FOTOALBUM_PICTUREPATH];
		if(is_file($pic))
			unlink($pic);
		$sql = "DELETE FROM ".TABLE_FOTOALBUM." WHERE ".TABLE_FOTOALBUM_ID."=".$fotoid." AND ".TABLE_FOTOALBUM_USERID."=".$userid;

		DBconnect::execute_q($sql);
	}

	static function deleteLonely_Heart($userid, $lonelyid)
	{
		$sql = "DELETE FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid;
		for($n=0; $lonelyid[$n]; $n++)
		{
			if($n == 0)
				$sql .= " AND (";
			else
				$sql .= " OR ";
			$sql .= TABLE_LONELYHEART_ID."=".$lonelyid[$n];
		}
		$sql .= ")";

		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_inbox($userid, $messageid)
	{
		$list = '';
		$sql = "DELETE FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_MESSAGE_INBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_outbox($userid, $messageid)
	{
		$list = '';
		$sql = "DELETE FROM ".TABLE_MESSAGE_OUTBOX." WHERE ".TABLE_MESSAGE_OUTBOX_FROM."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_MESSAGE_OUTBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_suggestionInbox($userid, $messageid)
	{
		$list = '';
		$sql = "DELETE FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_TO."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_SUGGESTION_INBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function deleteMessage_suggestionOutbox($userid, $messageid)
	{
		$list = '';
		$sql = "DELETE FROM ".TABLE_SUGGESTION_OUTBOX." WHERE ".TABLE_SUGGESTION_OUTBOX_FROM."='".$userid."'";
		if(count($messageid)>0)
		{
			$sql .= " AND (";
			foreach($messageid as $value)
			{
				if($list != '')
					$list .= " OR ";
				$list .= TABLE_SUGGESTION_OUTBOX_ID."=".$value;
			}
			$sql .= $list;
			$sql .= ")";
		}
		return DBconnect::execute_q($sql);
	}

	static function editLonelyHeart($userid, $lonelyid, $data)
	{
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
		$sql = "SELECT * FROM ".TABLE_FOTOALBUM."
				WHERE ".TABLE_FOTOALBUM_USERID."=".$userid."
				ORDER BY ".TABLE_FOTOALBUM_DATETIME;
		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllLonely_Heart($userid, $start, $limit)
	{
		$sql = "SELECT * FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid."
				ORDER BY ".TABLE_LONELYHEART_DATETIME." DESC ";
		if(!(empty($start)&&empty($limit)))
			$sql .= " LIMIT ".$start.", ".$limit;

		return DBconnect::assoc_query_2D($sql);
	}

	static function getAllMessage_inbox($userid, $archive, $start, $limit)
	{
		$sql = "SELECT m1.".TABLE_MEMBER_USERNAME.",
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

	static function getAllMessage_outbox($userid, $start, $limit)
	{
		$sql = "SELECT m1.".TABLE_MEMBER_USERNAME.",
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
		include_once('conf/'.$lang.'.php');

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
		$sql = "SELECT name FROM xml_cities WHERE id=".$answer;
		return DBconnect::retrieve_value($sql);
	}
	
	static function getAnswerArea($city)
	{
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
		$sql = "SELECT name FROM xml_countries WHERE id=".$answer;
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
		$sql = "SELECT name FROM xml_states WHERE id=".$answer;
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
		$sql = "SELECT ".TABLE_MEMBER_EMAIL." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."=".$userid;
		return DBconnect::retrieve_value($sql);
	}

	static function getFotoAlbum($fotoid, $userid)
	{
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
		$sql = "SELECT * FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid."
				AND ".TABLE_LONELYHEART_ID."=".$lonelyid;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_inbox($userid, $id, $archive)
	{
		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME.", t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_MESSAGE_INBOX." t2 WHERE t2.".TABLE_MESSAGE_INBOX_ID."=".$id." AND t2.".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_MESSAGE_INBOX_FROM." AND t2.".TABLE_MESSAGE_INBOX_TO."=".$userid;
		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_outbox($userid, $id)
	{
		$sql = "SELECT t1.username, t2.* FROM ".TABLE_MEMBER." t1, ".TABLE_MESSAGE_OUTBOX." t2 WHERE t2.".TABLE_MESSAGE_OUTBOX_ID."=".$id." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_MESSAGE_OUTBOX_TO." AND t2.".TABLE_MESSAGE_OUTBOX_FROM."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_suggestionInbox($userid, $id)
	{
		$sql = "SELECT * FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_ID."=".$id." AND ".TABLE_SUGGESTION_INBOX_TO."=".$userid;

		return DBconnect::assoc_retrieve_2D_conv_1D($sql);
	}

	static function getMessage_suggestionOutbox($userid, $id)
	{
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

	static function getNamePass_email($email)
	{
		$sql = "SELECT ".TABLE_MEMBER_USERNAME.", ".TABLE_MEMBER_PASSWORD." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_EMAIL."='".$email."' LIMIT 1";

		return DBconnect::assoc_query_1D($sql);
	}

	static function getNewest($gender, $limit, $withoutID)
	{
		/*$gender
		*0 : Any
		*1 : Men
		*2 : Women
		*3 : Pairs
		*/
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
	}

	static function getNumAllMessage_inbox($userid, $archive)
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."=".$userid." AND ".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive);
	}

	static function getNumAllMessage_outbox($userid)
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_MESSAGE_OUTBOX." WHERE ".TABLE_MESSAGE_OUTBOX_TO."=".$userid);
	}

	static function getNumAllMessage_suggestionInbox($userid)
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_SUGGESTION_INBOX." WHERE ".TABLE_SUGGESTION_INBOX_TO."=".$userid);
	}

	static function getNumAllMessage_suggestionOutbox($userid)
	{
		return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_SUGGESTION_OUTBOX." WHERE ".TABLE_SUGGESTION_OUTBOX_FROM."=".$userid);
	}

	static function getNumListFavorite($userid, $char)
	{
		$sql = "SELECT COUNT(*) FROM ".TABLE_MEMBER." t1, ".TABLE_FAVORITE." t2 WHERE t2.".TABLE_FAVORITE_PARENT."=".$userid." AND t1.".TABLE_MEMBER_ID."=t2.".TABLE_FAVORITE_CHILD;
		if(!(($char == 'All') || ($char == '')))
			$sql .= " AND t1.".TABLE_MEMBER_USERNAME." LIKE '".$char."%' ";
		return DBconnect::get_nbr($sql);
	}

	static function getNumLonelyHeart($userid)
	{
		$sql = "SELECT COUNT(*) FROM ".TABLE_LONELYHEART."
				WHERE ".TABLE_LONELYHEART_USERID."=".$userid;

		return DBconnect::get_nbr($sql);
	}

	static function getOfDay($gender, $limit)
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
	}

	static function getProfile($id)
	{
		$sql = "SELECT * FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_ID."`=\"$id\"";

		return DBconnect::assoc_query_1D($sql);
	}

	static function getAdvanceProfile($id, $array_type)
	{
		$sql = "SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." WHERE id=".$id;

		if($array_type == 1)
		{
			$data = DBconnect::assoc_query_1D($sql);

			$data[TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[TABLE_MEMBER_GENDER]);
			$data[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[TABLE_MEMBER_CITY]);
			$data[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[TABLE_MEMBER_CIVIL]);
			$data[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[TABLE_MEMBER_APPEARANCE]);
		}
		else
		{
			$data = DBconnect::assoc_query_2D($sql);

			for($n=0; $data[$n]; $n++)
			{
				$data[$n][TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'], '', '$gender', $data[$n][TABLE_MEMBER_GENDER]);
				$data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $data[$n][TABLE_MEMBER_CITY]);
				$data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'], '', '$status', $data[$n][TABLE_MEMBER_CIVIL]);
				$data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $data[$n][TABLE_MEMBER_APPEARANCE]);
			}
		}
		return $data;
	}

	static function getRangeAge($begin, $finish)
	{
		$ages = array();
		$age = range($begin, $finish);
		foreach($age as $age1)
			$ages[$age1] = $age1;

		return $ages;
	}

	static function getSuggestion($suggestion_id)
	{
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
		$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE DAYOFMONTH(".TABLE_MEMBER_BIRTHDAY.") = DAYOFMONTH('".$date."') AND MONTH(".TABLE_MEMBER_BIRTHDAY.") = MONTH('".$date."')";

		return DBconnect::assoc_query_2D($sql);
	}

	static function getUserid($username)
	{
		$sql = "SELECT ".TABLE_MEMBER_ID." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";

		return DBconnect::retrieve_value($sql);
	}

	static function getUsername_Message($messageid)
	{
		$sql = "SELECT t1.".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." t1, ".TABLE_MESSAGE_INBOX." t2 WHERE t1.".TABLE_MEMBER_ID."=".TABLE_MESSAGE_INBOX_FROM." AND t2.".TABLE_MESSAGE_INBOX_ID."=".$messageid;

		return DBconnect::retrieve_value($sql);
	}

	static function getYear($sub_begin, $sub_finish)
	{
		$year = array();
		$year_now = date("Y", time());
		$year_range = range($year_now-$sub_begin, $year_now-$sub_finish);
		foreach($year_range as $year_range1)
			$year[$year_range1] = $year_range1;

		return $year;
	}

	static function isEmail($email)
	{
		return DBconnect::retrieve_value("SELECT COUNT(*) FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_EMAIL."`=\"$email\"");
	}

	static function isUsername($username)
	{
		return DBconnect::retrieve_value("SELECT COUNT(*) FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_USERNAME."`=\"$username\"");
	}

	static function isPhoneNumber($phone_number)
	{
		return DBconnect::retrieve_value("SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE mobileno = '$phone_number'");
	}

	static function loginSite($username, $password)
	{
		$member = DBconnect::login($password, $username, TABLE_MEMBER, TABLE_MEMBER_PASSWORD, TABLE_MEMBER_USERNAME);
		if(((int)$member[TABLE_MEMBER_ID] > 0) && ($member[TABLE_MEMBER_ISACTIVE] == 1))
		{
			$_SESSION['sess'] = session_id();
			$_SESSION['sess_id'] = $member[TABLE_MEMBER_ID];
			$status = $member[TABLE_MEMBER_STATUS];
			
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
				break;
				case  2:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;					
				break;
				case  3:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;					
				break;
				case  4:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;					
				break;
				case  5:
					$_SESSION['sess_admin'] = 0;
					$_SESSION['sess_mem'] = 0;
					$_SESSION['sess_superadmin'] = 0;
				break;
				case  9:
					$_SESSION['sess_admin'] = 1;
					$_SESSION['sess_mem'] = 1;
					$_SESSION['sess_superadmin'] = 0;					
				break;				
				
			}
			$_SESSION['sess_username'] = $username;
			if($member[TABLE_MEMBER_SIGNIN_DATETIME] == '0000-00-00 00:00:00')
			{
				$_SESSION['sess_first'] = 1;
				if($member['description'] != "")
				{
					funcs::addLonelyHeartFromDescription($member);
				}
			}
			else
				$_SESSION['sess_first'] = 0;

			/*----------------------Random visitor from database -------------------*/
			if((date("Y-m-d",time()) > $member[rand_visitor_datetime]) || ($member[rand_visitor] == ""))
			{
			$sql="SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM member WHERE ((".TABLE_MEMBER_STATUS." != 1 and isactive='1' and picturepath != '')";
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
					$sql .= " and gender=1";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and gender=2";
				}
				else
				{
					$sql .= " and gender=2";
				}
			}
			else
			{
				if($member['sexuality'] == 1)
				{
					$sql .= " and gender = '2'";
				}
				elseif($member['sexuality'] == 2)
				{
					$sql .= " and gender = '1'";
				}
				else
				{
					$sql .= " and gender = '1'";
				}
			}

			$limit = rand(2,4);
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
						  WHERE `".TABLE_MEMBER_ID."` = '".$member[id]."'";
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
					"Suche wsa Verrücktes",
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
                                $soapclient = new soapclient(SERVER_URL);
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
								funcs::emailAfterEmail($userid,$from,$message);
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
		$sql = "SELECT city FROM member WHERE id =".$userid;
		$city = DBconnect::retrieve_value($sql);

		$sql = "SELECT plz FROM xml_cities WHERE id =".$city;
		$area = DBconnect::retrieve_value($sql);
		$areacode = substr($area,0,1);

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
		
		$gender=($gender == 1)?2:1;
		
		$sql="SELECT username FROM member WHERE fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND area LIKE '$areacode%' AND city !='$city' AND birthday BETWEEN '$birthdayfrom' AND '$birthdayto' ORDER BY last_action_from ASC LIMIT 1";
		$result = DBconnect::assoc_query_1D($sql);
		if($result){
			return array_shift($result);
		}
		else {
			$sql="SELECT username FROM member WHERE fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND city !='$city' AND birthday BETWEEN '$birthdayfrom' AND '$birthdayto' ORDER BY last_action_from ASC LIMIT 1";
			$result = DBconnect::assoc_query_1D($sql);			
			if($result){
				return array_shift($result);
			}
			else{
				$birthdayfrom = $birthdayfrom + 4;
				$sql="SELECT username FROM member WHERE fake= '1' AND flag='1' and gender = $gender and picturepath != '' AND city !='$city' AND birthday BETWEEN '$birthdayfrom' AND '$birthdayto' ORDER BY last_action_from ASC LIMIT 1";			
				$result = DBconnect::assoc_query_1D($sql);
				if($result){
					return array_shift($result);
				}
				else{
					return false;	
				}
			}
		}
	}

	static function registerMember($save)
	{
		//get column names
		$colnames = array_flip(DBconnect::get_col_names(TABLE_MEMBER));
		//delete everything that is not in the database
		$member_post = array_intersect_key($save, $colnames);
		//create the member and get the id from the creation
		return DBconnect::assoc_insert_1D($member_post, TABLE_MEMBER);
	}

	static function searchMember($search_for, $gender, $picture, $state, $city, $area, $min_age, $max_age)
	{
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
	}

	function sendMail($email, $subject, $message, $from)
	{
		$header  = 'MIME-Version: 1.0' . "\r\n";
		$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n".
		'From: '.$from . "\r\n" .
   	 	'Reply-To:'.$from. "\r\n" .
    		'X-Mailer: PHP/' . phpversion();

		//echo funcs::getEmail($userid);
		//mail($email,$subject,$message,$header);
		if (!(mail($email,$subject,$message,$header))) {
			 return false;
		}else{
			return true;}


		/*$recipients = $email;
		$params["host"] = "mail.westkit.com";
		$params["port"] = "25";
		$params["auth"] = true;
		$params["username"] = "visanu";
		$params["password"] = "cf54ah21";
		$headers['MIME-Version'] = '1.0';
		$headers['Content-type'] = 'text/html; charset=utf8';
		$headers['From'] = $from;
		$headers['To'] = $email;
		$headers['Subject'] = $subject;

		$mail = Mail::factory("smtp", $params);
		$result = $mail->send($recipients, $headers, $message);

		if (PEAR::isError($result))
			return false;
		else
			return true;

		//echo funcs::getEmail($userid);
		mail($email,$subject,$message,$header); */

	}

	static function getFake($id)
	{
		$sql = "SELECT fake FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."=".$id;
		//echo $sql;
		return DBconnect::retrieve_value($sql);
	}
	
	static function sendMessage($from, $to, $subject, $message, $mtype)
	{
		/*mtype 0--> Normal
				1--> Kummerkasten
				2--> Technik
				3--> Neuanmeldung
				4--> Abo läuft aus
				5--> Neue Kontaktanzeige
		*/
			
		$userid = funcs::getUserid($to);

		if($userid != '')
		{
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
				$soapclient = new soapclient(SERVER_URL);
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
				DBconnect::execute($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute($sql);
				
				return true;
			}
			else
			{
				funcs::emailAfterEmail($userid,$from,$message);
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

				$sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$userid;
				DBconnect::execute($sql);
				$sql= "UPDATE member SET last_action_from = NOW() WHERE id = ".$from;
				DBconnect::execute($sql);				
			}
		}
		else
			return false;
	}

	static function sendfakeMessage($from, $to, $subject, $message, $mtype)
	{
		funcs::emailAfterEmail($to,$from,$message);
		
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
		$col_member = array_flip(DBconnect::get_col_names(TABLE_MEMBER));
		foreach($col_member as $key => $value) {
			echo $value[$key];
		}
		$save_post = array_intersect_key($save, $col_member);
		#Pakin Change this static function
		$username =  self::findUserName($userid);
		self::NewSorting($username);
		funcs::logProfileAction($userid,2);
		return DBconnect::update_1D_row_with_1D_array(TABLE_MEMBER, $save_post, TABLE_MEMBER_ID, $userid);
	}

	static function uploadFotoAlbum($file, $userid)
	{
		$data = funcs::getAllFotoAlbum($userid);
		if(count($data) < 4)
		{
			$file_name = time().'_'.$file['name'];
			$path = $userid.'/foto/';
			//echo UPLOAD_DIR.$path;
			if(!is_dir(UPLOAD_DIR.$path))
				mkdir(UPLOAD_DIR.$userid);
				mkdir(UPLOAD_DIR.$path);

			$path = $path;
			if(move_uploaded_file($file['tmp_name'], UPLOAD_DIR.$path.$file_name))
			{
				$sql = "INSERT INTO ".TABLE_FOTOALBUM."
						SET ".TABLE_FOTOALBUM_USERID."=".$_SESSION['sess_id'].",
						".TABLE_FOTOALBUM_PICTUREPATH."='".$path.$file_name."',
						".TABLE_FOTOALBUM_DATETIME."='".funcs::getDatetime()."'";

				return DBconnect::execute_q($sql);
			}
		}
		return false;
	}

	static function getFreesms($username)
	{
		$sql = "SELECT sms FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		return DBconnect::retrieve_value($sql);
	}

	static function getTextSMS($username)
	{
		$code = substr(time(), 6);
		$query = "UPDATE ".TABLE_MEMBER." SET vcode_mobile='$code' WHERE ".TABLE_MEMBER_USERNAME."='".$username."'";
		DBconnect::execute_q($query);
		$message = "Herzlich Willkommen! Dein Freischaltcode lautet:".$code;

		return $message;
	}
	static function checkmobile($user)
	{
		$sql = "SELECT mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$no = DBconnect::retrieve_value($sql);

		return $no;
	}

	static function checkvalidated($user)
	{
		$sql = "SELECT validated FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$validated = DBconnect::retrieve_value($sql);

		return $validated;
	}

	static function sendFreesms($from, $to, $msg)
	{
		$sql = "SELECT mobileno FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$from."'";
		$no = DBconnect::retrieve_value($sql);
		//echo $sql;
		$no=preg_replace("/^0049/","0",$no);
		$to=preg_replace("/^0049/","0",$to);
		sendSMS_BULK($to,$no,$msg);
		$sql = "UPDATE ".TABLE_MEMBER." SET sms=sms+1 WHERE ".TABLE_MEMBER_USERNAME."='".$from."' LIMIT 1";
		return DBconnect::execute_q($sql);
	}

	static function checkcode($code,$mobnr,$user)
	{
		$sql = "SELECT vcode_mobile FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
		$vcode = DBconnect::retrieve_value($sql);
		$userid = funcs::getUserid($user);
		if($vcode == $code){
			$query = "UPDATE ".TABLE_MEMBER." SET mobileno='$mobnr' WHERE ".TABLE_MEMBER_USERNAME."='".$user."'";
						include("./libs/nusoap.php");
				$message_assoc_array= array('profileID'=>$userid,'serverID'=>SERVER_ID,'param'=>"mobileNr", 'val'=>$mobnr);
				$parameters = array('msg'=>$message_assoc_array);
				$soapclient = new soapclient(SERVER_URL);
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
		$sql = "SELECT 1 FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='".$user."' AND payment > NOW()";
		$check = DBconnect::retrieve_value($sql);
		if($check == 1){
			return true;
		}
		else{
			return false;
		}
	}

	static function insertpayment($userid,$membership,$rate,$paid_via)
	{
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

			if ($rate==1){
				$end_date = " + INTERVAL 3 DAY";
			}elseif ($rate==2){
				$end_date = " + INTERVAL 1 MONTH";
			}elseif ($rate==3){
				$end_date = " + INTERVAL 3 MONTH";
			}elseif ($rate==4){
				$end_date = " + INTERVAL 1 YEAR";
			}

			$member_data = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_MEMBER." WHERE id='".$userid."'");
			$sql = "INSERT INTO ".TABLE_PAY_LOG."
			SET ".TABLE_MEMBER_USERNAME."=\"$member_data[username]\",
				".TABLE_MEMBER_PASSWORD."=\"$member_data[password]\",
				".TABLE_MEMBER_MOBILENO."=\"$member_data[mobileno]\",
				".TABLE_PAYLOG_OLDTYPE."=\"$member_data[type]\",
				".TABLE_PAYLOG_NEWTYPE."=\"$membership\",
				".TABLE_PAYLOG_OLDPAYMENT."=\"$history[new_paid_until]\",
				".TABLE_PAYLOG_NEWPAYMENT."= $start $end_date,
				".TABLE_PAYLOG_PAIDVIA."= $paid_via,
				".TABLE_PAYLOG_SUM."= 0,
				".TABLE_PAYLOG_PAYDAY."= NOW(),
				payment_complete = 1";

			DBconnect::execute($sql);

			$sql = "UPDATE ".TABLE_MEMBER." SET type = $membership, payment_received = NOW(), payment = $start $end_date WHERE id = ".$userid;
			DBConnect::execute($sql);
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
		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$user_id."'");

		$sql = "SELECT *, DATE(payday) as start_date, DATE(new_paid_until) as end_date, new_type as membership_type FROM ".TABLE_PAY_LOG." WHERE username='".$username."' AND payment_complete = '1' ORDER BY id DESC LIMIT 1";

		$payment_history = DBConnect::assoc_query_1D($sql);		
		
		$arr = array(1=> 'Admin',2 => "Gold", 3 => "Silber", 4=> "Bronze");
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
			
			//return $payment_history;
		}
		return $payment_history;
	}

	//=============== [End] Phai changed this function on 20/7/2550 ===================

	static function cancelPaymentHistory($id, $user_id)
	{
		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$user_id."'");

		$sql = "SELECT username FROM ".TABLE_PAY_LOG." WHERE ID=".$id;
		if(DBConnect::retrieve_value($sql) == $username)
		{
			DBConnect::execute_q("UPDATE ".TABLE_PAY_LOG." SET cancelled=1, cancelled_date=NOW() WHERE ID=".$id);
			//DBConnect::execute_q("UPDATE ".TABLE_MEMBER." SET type=4 WHERE id=".$user_id);

			//logout ang login again.
			//$info = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_MEMBER." WHERE id=".$user_id);
			//$_SESSION = null;
			//funcs::loginSite($info['username'], $info['password']);
		}
	}

	static function getPaymentData($userid)
	{
		$sql = "Select id, type, payment, payment_received FROM ".TABLE_MEMBER." WHERE id=".$userid;
		$result = DBconnect::assoc_query_1D($sql);
		if($result){
			$expire = $result['payment'];
			$today = date("d-m-Y",time());
			list($day1,$month1,$year1)=explode("-",$today);
			list($year2,$month2,$day2)=explode("-",$expire);
			$tdate1=mktime(0,0,0,$month1,$day1,$year1);
			$tdate2=mktime(0,0,0,$month2,$day2,$year2);
			$diffdays=round(($tdate2-$tdate1)/86400);
			$array_expire = array('expires' => $diffdays);
			$result = array_merge($result,$array_expire);
			return $result;
		}
		else{
			return false;
		}
	}

	static function emailAfterEmail($userid,$from,$str)
	{
	$sql = "SELECT ".TABLE_MEMBER_USERNAME." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$userid'";
	$rec =  DBconnect::retrieve_value($sql);

	$sql = "SELECT * FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."='$from'";
	$sender= DBconnect::assoc_query_2D($sql);

	$mess = funcs::split_word($str);
	if(count($sender) > 0) {
	foreach($sender as $key => $value)
	{
	$city = funcs::getAnswerCity($_SESSION['lang'], $value[TABLE_MEMBER_CITY]);
	$gender = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$gender', $value[TABLE_MEMBER_GENDER]);
	$temp = split('-',$value['birthday']);
	$age=date(Y)-$temp[0];
	$user = $value['username'];
		$message = "
		<html><head></head><body>
		<table width='100%' border='0' height='100%' cellspacing='0' cellpadding='0' bgcolor='#99c6dd'>
			<tr><td><img src='images/dot.gif' height='5' width='1' border='0'></td></tr>
			<tr><td width='100%' align='center'>
			<table width='650' border='0' cellspacing='0' cellpadding='0' bgcolor='#fffff0'>
				<tr><td><img src='".URL_WEB."images/bg_head.gif.jpg' height='125' width='650'></td></tr>
				<tr><td align='center'>
				<table width='650' border='0' cellspacing='0' cellpadding='2'>
					<tr><td height='10'></td></tr>
					<tr><td align='center'><h3>Nachrichteneingang bei Herzoase</h3></td></tr>"."\r\n\r\n"."
					<tr><td><br>Hallo <b>$rec,</b><br><br>du hast eine neue Nachricht von <b>$user</b> auf Sonaflirt erhalten.<br></td></tr>
					<tr><td height='20'></td></tr>"."\r\n\r\n"."
				</table></td></tr>
				<tr><td align='center'>
				<table width='650' border='0' cellspacing='0' cellpadding='0'>
					<tr><td width='650'>
					<table width='650' height='110' border='0' cellpadding='0' cellspacing='0'>
						<td width='10'><img src='".URL_WEB."images/pic_top_l.gif' width='10' height='5' /></td>
						<td background='".URL_WEB."images/pic_top_c.gif'></td>
						<td width='10'><img src='".URL_WEB."images/pic_top_r.gif' width='10' height='5' /></td>
						<tr><td width='10' height='110' background='".URL_WEB."images/p_c_l.gif'></td>
							<td bgcolor='#D1E1C9'>
							<div align='center'>
							<table width='450' border='0' cellspacing='0' cellpadding='0'>
								<tr><td height='100%' valign='top'>
								<table width='450' border='0' cellspacing='0' cellpadding='2' align='center'>
									<tr><td height='35'>Name: <strong>$user</strong></td></tr>"."\r\n\r\n"."
									<tr><td height='35'>Alter: <strong>$age</strong></td></tr>"."\r\n\r\n"."
									<tr><td height='35'>Geschlecht: <strong>$gender</strong></td></tr>"."\r\n\r\n"."
									<tr><td height='35'>Stadt: <strong>$city</strong></td></tr>"."\r\n\r\n"."
									<tr><td height='35'>Nachricht: <strong>$mess</strong></td></tr>"."\r\n\r\n"."
								</table></td>
								<td width='200'>
								<div align='right'>"."\r\n\r\n"."
								<table cellspacing='0' cellpadding='0' border='0' width='100%'>
									<tr><td width='200'>
									<table width='78' border='1' align='right' cellpadding='0' cellspacing='0' bordercolor='#79BEE1'>";
										if($value['picturepath'] != "") {
											$message .=	"<tr><td width='78' height='98' bgcolor='#FFFFFF' align='right'><img src='".URL_WEB."thumbs/thumbs/".$value['picturepath']."' width='100'></td></tr>";
										}
										else {
											$message .=	"<tr><td width='78' height='98' bgcolor='#FFFFFF' align='right'><img src='".URL_WEB."thumbs/default.jpg' width='100'></td></tr>";
										}
										$message .=	"  
									</table></td>
									<td><img src='images/dot.gif' width='17' height='1' border='0'></td></tr>
								</table>
								</div>
								</td></tr>
							</table>
							</div>
							</td>
							<td width='10' height='101' background='".URL_WEB."images/p_c_r.gif'></td>"."\r\n\r\n"."
							</tr>
							<tr>
							<td width='10'><img src='".URL_WEB."images/p_foot_l.jpg' width='10' height='5' /></td>
							<td background='".URL_WEB."images/p_foot_c.jpg'></td>
							<td width='10'><img src='".URL_WEB."images/p_foot_r.jpg' width='10' height='5' /></td>
							</tr>
						</table>
						</td></tr>
						<tr><td align='center'>
						<table>
							<tr><td height='20'></td></tr>
							<tr><td>Komplette Nachricht lesen? Hier geht es zu Deinem <a href='http://www.herzoase.com'>Postfach.</a></td></tr>
						</table>
						</td></tr>
						<tr><td align='center'>
						<table bgcolor='#fffff0'>
							<tr><td height='15'></td></tr>
							<tr><td>Viel Spa&szlig; w&uuml;nscht dir dein ".funcs::getText($_SESSION['lang'], '$KM_Name')."-Team."."\r\n\r\n"."
									Hinweis: Du erh&auml;ltst diese Nachricht, da du dich bei ".funcs::getText($_SESSION['lang'], '$KM_Name')." angemeldet hast. Diese Nachricht wurde automatisch erzeugt, antworte bitte nicht darauf.</td>
							</tr>
							<tr><td height='15'></td></tr>
						</table>
						</td></tr>
					</table>
					</td></tr>
				</table></body></html>";
	}
	}
	$header  = 'MIME-Version: 1.0'."\r\n";
	$header .= 'Content-type: text/html; charset=utf-8'."\r\n".
		'From: no-reply@herzoase.com'."\r\n" .
   	 	'Reply-To: no-reply@herzoase.com'."\r\n" .
    	'X-Mailer: PHP/'.phpversion();

		//echo funcs::getEmail($userid);
	$subj ="Herzoase - Du hast eine neue Nachricht von $user";
	mail(funcs::getEmail($userid),$subj,$message,$header);
	}

	static function getChoiceCountryXML()
	{
		$sql = "SELECT * FROM xml_countries ORDER BY name";
		$country = DBconnect::assoc_query_2D($sql);
		$sql = "SELECT * FROM xml_states ORDER BY name";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
			$state[$row['parent']][$row['id']] = $row['name'];
		$sql = "SELECT * FROM xml_cities ORDER BY name";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result))
			$city[$row['parent']][$row['id']] = $row['name'];

		echo "<category>";
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
		$xml = "<category>\n";
		$countries = DBConnect::assoc_query_2D("SELECT * FROM xml_countries ORDER BY name");
		foreach($countries as $country)
		{
			$xml .= "<country>\n<id>".$country['id']."</id>\n<name>".$country['name']."</name>\n";

			$states = DBConnect::assoc_query_2D("SELECT * FROM xml_states WHERE parent=".$country['id']." ORDER BY name");
			foreach($states as $state)
			{
				$xml .= "<state>\n<id>".$state['id']."</id>\n<name>".$state['name']."</name>\n";

				$cities = DBConnect::assoc_query_2D("SELECT * FROM xml_cities WHERE parent=".$state['id']." ORDER BY name");
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
		$country = $arr['co'];
		$city = $arr['ci'];
		$state = $arr['s'];
		$gender = $arr['g'];
		$search_username = $arr['u'];
		$order = $arr['order'];
		$type = $arr['type'];
		$date_range = $_GET['r'];
		$fake = $arr['f'];
		$from = $arr['from'];
		$to = $arr['to'];
		#Pakin Change this static function
		$username = $_SESSION['sess_username'];
		$SuperUser = array('superadmin','cyric','heiko','Kleopatra33');
		if(!in_array($username,$SuperUser)){
			$condman = " (".TABLE_MEMBER_COUNT." <=".MALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 1)";
			$condwoman = " (".TABLE_MEMBER_COUNT." <=".FEMALE_MEMBERS_FLAG_PER_CITY.") AND (".TABLE_MEMBER_GENDER."= 2)";
			$CONDITION  = " AND ( $condman OR $condwoman ) AND ".TABLE_MEMBER_FLAG." != 1" ;
		}
	
		$sqlGetMember = "SELECT t1.".TABLE_MEMBER_USERNAME." AS username, t1.flag as flag, t1.signup_datetime as registred, t1.id as user_id, t1.mobileno as mobileno,
										RIGHT(t1.picturepath,1) as picturepath, t4.name as ".TABLE_MEMBER_CITY.",
										t3.name as ".TABLE_MEMBER_STATE.", t2.name as ".TABLE_MEMBER_COUNTRY.",
										t1.type as type
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

		if($search_username!=""){
			$sqlGetMember .= " AND (t1.username like '$search_username%')";
		}

		if(($fake == '0') || ($fake == '1'))
		{
			$sqlGetMember .= " AND (t1.fake = '$fake')";
		}

		if($date_range == 'today')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) = CURDATE())";
		elseif($date_range == 'yesterday')
			$sqlGetMember .= " AND (DATE(t1.signup_datetime) = CURDATE() - INTERVAL 1 DAY)";
		elseif($date_range == 'week')
			$sqlGetMember .= " AND (WEEK(t1.signup_datetime) = WEEK(CURDATE()))";
		elseif($date_range == 'month')
			$sqlGetMember .= " AND (MONTH(t1.signup_datetime) = MONTH(CURDATE()))";
		elseif($date_range == 'search')
			$sqlGetMember .= " AND ((t1.signup_datetime >= '".$from."') AND (t1.signup_datetime <= '".$to."'))";

		switch($order)
		{
			case 'city':

				$sqlGetMember .= " ORDER BY t4.name";
				switch($type)
				{
					case 'desc':
						$sqlGetMember .= " DESC";
						break;
					default:
						$sqlGetMember .= " ASC";
						break;
				}
				$sqlGetMember .= ", picturepath DESC, username ASC";

				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'state':

				$sqlGetMember .= " ORDER BY t3.name";
				switch($type)
				{
					case 'desc':
						$sqlGetMember .= " DESC";
						break;
					default:
						$sqlGetMember .= " ASC";
						break;
				}
				$sqlGetMember .= ", picturepath DESC, username ASC";

				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'country':

				$sqlGetMember .= " ORDER BY t2.name";
				switch($type)
				{
					case 'desc':
						$sqlGetMember .= " DESC";
						break;
					default:
						$sqlGetMember .= " ASC";
						break;
				}
				$sqlGetMember .= ", picturepath DESC, username ASC";

				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'flag':

				$sqlGetMember .= " ORDER BY t1.flag";
				switch($type)
				{
					case 'desc':
						$sqlGetMember .= " DESC";
						break;
					default:
						$sqlGetMember .= " ASC";
						break;
				}
				$sqlGetMember .= ", picturepath DESC, username ASC";

				$sqlCountMember = $sqlGetMember;
				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;
			case 'name':

				$sqlGetMember .= " ORDER BY username";
				switch($type)
				{
					case 'desc':
						$sqlGetMember .= " DESC";
						break;
					default:
						$sqlGetMember .= " ASC";
				}
				$sqlGetMember .= ", picturepath DESC, username ASC";

				$sqlCountMember = $sqlGetMember;

				$sqlGetMember .= " LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit();
				break;

			default:

				$sqlGetMember .= " ORDER BY picturepath";

				switch($type)
				{
					case 'asc':
						$sqlGetMember .= " ASC";
						break;
					default:
						$sqlGetMember .= " DESC";
				}
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

		$countMember = count(DBconnect::assoc_query_2D($sqlCountMember));
		//print_r($data);
		
		return array("data" => $data, "count" => $countMember);
	}

	function getVisitor()
	{
		$rand_visitor = DBConnect::retrieve_value("SELECT rand_visitor FROM ".TABLE_MEMBER." WHERE username='{$_SESSION['sess_username']}'");

		$temp = str_replace(";", ",",$rand_visitor);
		if($temp != '')
		{
			$sql = "SELECT *, (YEAR(CURDATE())-YEAR(".TABLE_MEMBER_BIRTHDAY."))-(RIGHT(CURDATE(),5) < RIGHT(".TABLE_MEMBER_BIRTHDAY.",5)) AS age FROM ".TABLE_MEMBER." WHERE id IN (".$temp.")";
			$data = DBconnect::assoc_query_2D($sql);
			if(count($data) > 0)
			{
				foreach($data as &$item)
				{
					$item[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $item[TABLE_MEMBER_CITY]);
					$item[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $item[TABLE_MEMBER_CIVIL]);
					$item[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $item[TABLE_MEMBER_APPEARANCE]);
				}
			}
		}
		return $data;
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
		  if(count($str_arr) > 80 )
		 {
			for($i=0;$i<count($str_arr);$i++)
			{
				if($i < 80)
				{
					$message  .=  $str_arr[$i];
				}else if(($i == 80)){
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
		$sql = "SELECT t1.*,t2.username FROM ".TABLE_MESSAGE_INBOX." t1, ".TABLE_MEMBER." t2 WHERE from_id=".$member_b." AND to_id=".$member_a." AND t1.from_id = t2.id";
		$messages1 = DBConnect::assoc_query_2D($sql);

		$sql = "SELECT t1.*,t2.username FROM ".TABLE_MESSAGE_OUTBOX." t1, ".TABLE_MEMBER." t2 WHERE from_id=".$member_a." AND to_id=".$member_b." AND t1.to_id = t2.id";
		$messages2 = DBConnect::assoc_query_2D($sql);

		$messages = array_merge($messages1,$messages2);

		foreach($messages as $key => $value)
		{
			$datetime[$key] = $value['datetime'];
		}
		array_multisort($datetime, SORT_DESC, $messages);

		//Get only last 3 messages
		$messages = array_slice($messages, $start, $num);
		return $messages;
	}

	static function getProfileByUsername($username)
	{

	   $sql = "select * from member where (username='".trim($username)."')";

	   $query = @mysql_query($sql);
	   if(@mysql_num_rows($query)>0){
	       $rs = @mysql_fetch_assoc($query);
	   }

	   return $rs;
	}

	static function getloneyByUsername($username)
	{
		$sql  = "select lha.*, m.username, m.birthday, m.picturepath from lonely_heart_ads as lha ";
		$sql .= "inner join member as m on m.id=lha.userid where (m.username='".trim($username)."')";

		$query = @mysql_query($sql);
		if(@mysql_num_rows($query)>0){
		   $rs = @mysql_fetch_assoc($query);
		}

		return $rs;
	}

	static function deleteOldMessages($days)
	{
		/*$sql = "SELECT id,datetime FROM ".TABLE_MESSAGE_INBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		print_r(DBConnect::assoc_query_2D($sql));*/

		$sql = "DELETE FROM ".TABLE_MESSAGE_INBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		DBConnect::execute($sql);
		$sql = "DELETE FROM ".TABLE_MESSAGE_OUTBOX." WHERE datetime < (NOW() - INTERVAL ".$days." DAY)";
		DBConnect::execute($sql);
	}

	static function dateDiff($dformat, $endDate, $beginDate)
	{
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
		$username = DBConnect::retrieve_value("SELECT username FROM ".TABLE_MEMBER." WHERE id='".$id."'");

		$sql = "DELETE FROM ".TABLE_MEMBER." WHERE id='".$id."'";
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

		/*$sql = "DELETE FROM ".TABLE_HISTORY." WHERE user_id='".$id."'";
		DBConnect::execute($sql);*/

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
		DBConnect::execute($sql);
		
		include("./libs/nusoap.php");
			$message_assoc_array= array('profileID'=>$id,'serverID'=>SERVER_ID);
			$parameters = array('msg'=>$message_assoc_array);
			$soapclient = new soapclient(SERVER_URL);
			$array = $soapclient->call('deleteprofile',$parameters);		
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
		$message .= "$rs[ID]  desired membership is ". $customer_type .".Herzoase extened duration ".$extend. ". Payment is ".$pay." Euro.<br><br>";
		}*/
		return $rs;
	
	}
	
	static function send_memberExtend_admin(&$smarty,$email,$type,$array) {
	
		$mail_from = "no-reply@herzoase.com";
		$mail_subject = "Herzoase - Mitgliedschaft wurde verlängert!";
		$smarty->assign('member',$array);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend_admin.tpl');
		
		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}
	
	static function send_memberExtend_customer(&$smarty,$username,$email,$type,$extend,$pay) {
	
		$arr = array(2 => "Gold",3 => "Silber");
		$customer_type = $arr[$type];
		
		$message ="Hallo ".$username ."!<br><br> Deine ". $customer_type ."-Mitgliedschaft bei Herzoase wurde heute von uns bis zum ".$extend. " verl&auml;ngert. <br><br>Die Kosten daf&uuml;r betragen ".$pay." Euro. <br><br>
					Bitte &uuml;berweise binnen 7 Tagen diesen Betrag auf das Konto der Loox Gmbh Nr.: <b>17092590</b> bei der <b>Flensburger Sparkasse (Blz.: 21550050)</b>. <br><br>
					Vielen Dank f&uuml;r das in uns entgegen gebrachte Vertrauen und weiterhin viel Spa&szlig; auf Sonalflirt! <br> <br> Liebe Gr&uuml;&szlig;e, <br> Dein Herzoase-Team!";
		$mail_from = "no-reply@herzoase.com";
		$mail_subject = "Herzoase - Deine Mitgliedschaft wurde verlängert!";
		$smarty->assign('message',$message);
		$smarty->assign('url_web', URL_WEB);
		$mail_message =  $smarty->fetch('membership_extend.tpl');
		
		funcs::sendMail($email, $mail_subject, $mail_message, $mail_from);
	}

	static function logProfileAction($userid,$action){
		
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
		
		$text = str_replace ("ä", "&auml;", $text);
		$text = str_replace ("Ä", "&Auml;", $text);
		$text = str_replace ("ö", "&ouml;", $text);
		$text = str_replace ("Ö", "&Ouml;", $text);
		$text = str_replace ("ü", "&uuml;", $text);
		$text = str_replace ("Ü", "&Uuml;", $text);
		$text = str_replace ("ß", "&szlig;", $text);
		
		$text = str_replace ("Ã¤", "&auml;", $text);
		$text = str_replace ("Ã¶", "&ouml;", $text);
		$text = str_replace ("Ã¼", "&uuml;", $text);
		$text = str_replace ("ÃŸ", "&szlig;", $text);
			
		$text = str_replace ("&Atilde;&frac14;", "&uuml;", $text);
		$text = str_replace ("&Atilde;&curren;", "&auml;", $text);			
		$text = str_replace ("&Atilde;&para;", "&ouml;", $text);
	
		return $text;
	}
	
	static function lookForSpecialChars($text){
		
		$counter = 0;
		$counter += substr_count($text,"ä");
		$counter += substr_count($text,"ü");
		$counter += substr_count($text,"ö");		
		$counter += substr_count($text,"ß");
		$counter += substr_count($text,"Ä");
		$counter += substr_count($text,"Ö");
		$counter += substr_count($text,"Ü");		

		if ($counter > 0){
			return true;
		}
		else{
			return false;
		}
	}
	
	static function externalLogin ($sessionID){
		
		include_once("./libs/nusoap.php");
		$soapclient = new soapclient(SERVER_URL);
		$isValid = $soapclient->call('checkSession',$sessionID);		
	    return $isValid;
	}
	
}
?>