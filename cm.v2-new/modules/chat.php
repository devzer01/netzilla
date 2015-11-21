<?php
//funcs::deleteOldMessages(30);
define('ADMIN_USERNAME', DBConnect::retrieve_value("SELECT username FROM member WHERE id=1 LIMIT 1"));
//define('ADMIN_USERNAME', "bigbrother");
//ini_set('display_error', 1);
//error_reporting(E_ALL);

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

$_GET['type'] = isset($_GET['type'])?$_GET['type']:"";

$emoticons = array();
$rs = mysql_query("SELECT id, text_version, image_path, active FROM emoticon");
while ($r = mysql_fetch_assoc($rs)) {
	$emoticons[] = $r;
}
$smarty->assign('emoticons', $emoticons);

if(isset($_SESSION['sess_smalladmin']) && $_SESSION['sess_smalladmin'])
	$own_id = 1;
else
	$own_id = $_SESSION['sess_id'];

switch($_GET['type'])
{
	case 'writemessage':
		//check permission type//
		$permission_lv = array(1, 4, 8, 9); //jeab edited

		funcs::checkPermission($smarty, $permission_lv);	//check permission

		$commands = "";

		//send message//
		if(isset($_POST['act']) && $_POST['act'] == 'writemsg')
		{
			$_POST['attachments']['coins'] = (isset($_POST['attachments']['coins']) && is_numeric($_POST['attachments']['coins']))?$_POST['attachments']['coins']:0;
			$_POST['attachments']['gift'] = (isset($_POST['attachments']['gift']) && is_numeric($_POST['attachments']['gift']))?$_POST['attachments']['gift']:0;

			$isfree = false;
			if($_POST['to']==ADMIN_USERNAME_DISPLAY)
			{
				$isfree = true;
				$_POST['to'] = ADMIN_USERNAME;
			}

			if(isset($_SESSION['sess_smalladmin']) && $_SESSION['sess_smalladmin'])
			{
				$isfree = true;
			}

			$total_coins = 0;

			if(in_array($_POST['attachments']['coins'], array(0, 30, 100)))
			{
				if($_POST['attachments']['coins'] > 0)
				{
					$already_topup = DBConnect::retrieve_value("SELECT 1 FROM purchases_log WHERE user_id = ".$own_id." AND purchase_finished=1 LIMIT 1");
					if($already_topup)
					{
						$total_coins = $_POST['attachments']['coins'];
						if($_GET['send_via_sms']=='1')
						{
							$total_coins += ($isfree)?0:funcs::getMinusCoin('COIN_SMS');
						}
						else
						{
							$total_coins += ($isfree)?0:funcs::getMinusCoin('COIN_EMAIL');
						}
					}
					else
					{
						$commands = "window.location='?action=pay-for-coins';";
					}
				}

				if ($_POST['attachments']['gift'] > 0) {
					$already_topup = DBConnect::retrieve_value("SELECT 1 FROM purchases_log WHERE user_id = ".$own_id." AND purchase_finished=1 LIMIT 1");
					if ($already_topup) {
						$gift_cost = DBConnect::retrieve_value("SELECT coins FROM gift WHERE id = " . mysql_real_escape_string($_POST['attachments']['gift']) . " LIMIT 1");
						$total_coins += $gift_cost;
					} else {
						$commands = "window.location='?action=pay-for-coins';";
					}
				}

				if($commands=="")
				{
					if((funcs::checkCoin($_SESSION['sess_username'])>=$total_coins) || ($isfree))
					{
						if(isset($_POST['attachments']['coins']) && ($_POST['attachments']['coins']>0))
						{
							$userid = funcs::getUserid($_POST['to']);
							sendCoins($_POST['attachments']['coins'], $own_id, $userid, $_POST['to']);
						}

						if(isset($_POST['attachments']['gift']) && ($_POST['attachments']['gift']>0))
						{
							$userid = funcs::getUserid($_POST['to']);
							sendGift($_POST['attachments']['gift'], $own_id, $userid);
						}

						$_POST['sms'] = strip_tags($_POST['sms']);
						if(isset($_GET['send_via_sms']) && $_GET['send_via_sms']=='1')
						{
							$phonenumber = funcs::getCurrentUserMobileNo();
							if((isset($phonenumber)) && ($phonenumber=="Verified"))
							{
								$subject = funcs::getText($_SESSION['lang'], '$sms_subject');
								if(funcs::sendMessageViaSMS($own_id, $_POST['to'], $subject, $_POST['sms'], $_POST['attachments'], $isfree))
								{
									$commands = "loadMessagesHistory(jQuery('#to').val());
												jQuery('#sms').val('');
												removeAllAttachments();
												jQuery('#countdown').val('".MAX_CHARACTERS."');
												coinsBalance();
												sending = false;";
								}
								else
								{
									$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$own_id."'");
									$currentCoin = funcs::checkCoin($username);
									$minusSms = funcs::getMinusCoin('COIN_SMS');

									if($currentCoin >= $minusSms) {
										$commands = "alert('".funcs::getText($_SESSION['lang'], '$writemessage_error')."');"; //send error
									}
									else
									{
										$commands = "window.location='?action=pay-for-coins';";
									}
								}
							}
							else
								$commands = "alert('".funcs::getText($_SESSION['lang'], '$mobile_ver_required')."');"; //send error
						}
						else
						{
							$is_gift = (isset($_GET['send_gift']) && $_GET['send_gift'] == 1) ? 1 : 0;

							$_SESSION['save_message'] = "";
							if ($is_gift) $_SESSION['save_message'] = $_POST['sms'];

							$subject = '';

							if (isset($_POST['subject'])) {
								$subject = $_POST['subject'];
							}

							if(funcs::sendMessage($own_id, $_POST['to'], $subject, $_POST['sms'], 0, $_POST['attachments'], $isfree, $is_gift))
							{
								$commands = "loadMessagesHistory(jQuery('#to').val());
											jQuery('#sms').val('" . $_SESSION['save_message'] . "');
											removeAllAttachments();
											jQuery('#countdown').val('".MAX_CHARACTERS."');
											coinsBalance();
											sending = false;";
							}
							else
							{
								$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$own_id."'");
								$currentCoin = funcs::checkCoin($username);

								$minusEmail = funcs::getMinusCoin('COIN_EMAIL');


								if($currentCoin >= $minusEmail) {
									$commands = "alert('".funcs::getText($_SESSION['lang'], '$writemessage_error')."');"; //send error
								}
								else
								{
									$commands = "window.location='?action=pay-for-coins';";
								}
							}
						}
					}
					else
					{
						$commands = "window.location='?action=pay-for-coins';";
					}

					if($_POST['to']==ADMIN_USERNAME)
					{
						funcs::sendQuestionMessage($own_id, $subject, $_POST['sms'], 2,$smarty);
					}
				}
			}
			else
			{
				$commands = "alert('Coins failed!'); removeAllAttachments(); sending = false;";
			}

			echo json_encode(array("commands" => $commands));
		}
		else
		{
			if($_GET['username']) $ext = "&username=".$_GET['username'];
			header("location: ?action=chat".$ext);
		}
		exit;
		break;
	case 'coinsBalance':
		$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$own_id."'");
		echo funcs::checkCoin($username);
		exit;
		break;
	case 'getMessages':
		$total = 0;
		$userinfo = getRealUserInfo($_GET['from']);
		$username = $userinfo['username'];
		$from =  $userinfo['id'];
		//$username = $_GET['from']==ADMIN_USERNAME_DISPLAY?ADMIN_USERNAME:DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$_GET['from']."'");
		if($username)
		{
			$return = false;
			//$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");

			$inbox = DBconnect::retrieve_value("SELECT count(*) FROM message_inbox WHERE from_id=".$from." AND to_id=".$own_id);

			$outbox = DBconnect::retrieve_value("SELECT count(*) FROM message_outbox WHERE to_id=".$from." AND from_id=".$own_id);

			$total = $inbox+$outbox;
			if($_GET['total']=='undefined')
			{
				$return = true;
			}
			else
			{
				if ($total != $_GET['total']) $return = true;
			}

			if($return)
			{
				$username_text = "";
				$to = "to ";
				if($_SESSION['lang']=="ger")
				{
					$to = "an ";
				}

				if($username==ADMIN_USERNAME)
				{
					$username_text = $to.ADMIN_USERNAME_DISPLAY;
				}
				else
				{
					$username_text = $to.$username;
				}

				$coin_charge_email_text =  str_replace('[PROFILE_NAME]',$username_text, funcs::getText($_SESSION['lang'], '$sendmessage_email_coin'));
				$coin_charge_sms_text =  str_replace('[PROFILE_NAME]',$username_text, funcs::getText($_SESSION['lang'], '$sendmessage_sms_coin'));

				if(($username==ADMIN_USERNAME) || (isset($_SESSION['sess_smalladmin']) && $_SESSION['sess_smalladmin']))
				{
					$coin_conts = array(array('coin_email'=>0, 'coin_sms'=>0));
					$coin_charge_email_text = str_replace('Coins', 'Coin', $coin_charge_email_text);
					$coin_charge_sms_text = str_replace('Coins', 'Coin', $coin_charge_sms_text);
				}
				else
				{
					$coin_conts = funcs::getCoinData();
				}

				$smarty->assign('coin_charge_msg', str_replace('[COIN_COSTS]', $coin_conts[0]['coin_email'], $coin_charge_email_text));
				$smarty->assign('coin_charge_sms', str_replace('[COIN_COSTS]', $coin_conts[0]['coin_sms'], $coin_charge_sms_text));

				$messages = getMessages($own_id, $username, $_GET['part']);
				$already_topup = DBConnect::retrieve_value("SELECT 1 FROM purchases_log WHERE user_id=".$own_id." AND purchase_finished=1 LIMIT 1");

// 				if (isset($_SESSION['save_message']) && trim($_SESSION['save_message']) != "") {
// 					$save['message'] = $_SESSION['save_message'];
// 					unset($_SESSION['save_message']);
// 					$smarty->assign('save', $save);
// 				}

				$smarty->assign('already_topup', $already_topup);
				$smarty->assign('coin_conts', $coin_conts);
				$smarty->assign('messages', $messages);
				$smarty->assign('total', $total);
				$smarty->assign('username', ($username==ADMIN_USERNAME)?ADMIN_USERNAME_DISPLAY:$username);
				$smarty->assign('part', $_GET['part']);
				echo $smarty->fetch("chat_list.tpl");
			}
		}
		exit;
		break;
	case 'deleteContact':
		$userinfo = getRealUserInfo($_GET['username']);
		$username = $userinfo['username'];
		$from =  $userinfo['id'];
		//$username = $_GET['username']==ADMIN_USERNAME_DISPLAY?ADMIN_USERNAME:DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$_GET['username']."'");

		if($username)
		{
			//$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");

			DBConnect::execute_q("DELETE FROM message_inbox WHERE from_id=".$from." AND to_id=".$own_id);
			DBConnect::execute_q("DELETE FROM message_outbox WHERE to_id=".$from." AND from_id=".$own_id);

			echo "DELETED";
		}
		exit;
		break;
	case 'markAsRead':
		$userinfo = getRealUserInfo($_GET['username']);
		$username = $userinfo['username'];
		$from =  $userinfo['id'];
		//$username = $_GET['username']==ADMIN_USERNAME_DISPLAY?ADMIN_USERNAME:DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$_GET['username']."'");

		if($username)
		{
			//$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");

			DBConnect::execute_q("UPDATE message_inbox SET status = 1, read_date = NOW() WHERE from_id=".$from." AND to_id=".$own_id);
		}
		exit;
		break;
	case 'inbox':
	default:
		//check permission type//
		$permission_lv = array(1, 4, 8, 9); //jeab edited	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		if(isset($_GET['username']) && ($_GET['username']==$_SESSION['sess_username']))
		{
			header("location: ?action=chat");
			exit;
		}
		else
		{
			$contactList = getAllContact($own_id);
			$crc = crc32(serialize($contactList));

			$smarty->assign("contactList", $contactList);
			$smarty->assign("currentContact", $contactList[key($contactList)]);
			$smarty->assign("crc", $crc);

			//ADDED Oct 10th 2013. Because it was missing for some reason
			if (isset($_GET['username'])) $smarty->assign('username', $_GET['username']);

			if(isset($_GET['crc']) && ($_GET['crc']!=""))
			{
				if($_GET['crc'] != $crc)
				{
					echo $smarty->fetch("chat_contact.tpl");
				}
				exit;
			}
		}
}
//select template file//
$smarty->display('index.tpl');

function getAllContact($userid)
{
	$userid = funcs::check_input($userid);
	$sql_current_username = "";
	$_GET['username'] = isset($_GET['username'])?$_GET['username']:"";

	if(defined("MESSAGE_HISTORY_PERIOD"))
	{
		$sql_message_history_preiod_inbox = " AND i.datetime > NOW()-INTERVAL ".MESSAGE_HISTORY_PERIOD;
		$sql_message_history_preiod_outbox = " AND o.datetime > NOW()-INTERVAL ".MESSAGE_HISTORY_PERIOD;
	}
	else
	{
		$sql_message_history_preiod_inbox = "";
		$sql_message_history_preiod_outbox = "";
	}

	if($_GET['username']!='')
	{
		if($_GET['username']==ADMIN_USERNAME_DISPLAY)
		{
			$sql_current_username = "AND m.username != '".ADMIN_USERNAME."'";
		}
		else
		{
			$sql_current_username = "AND m.username != '".$_GET['username']."'";
		}
	}

	$inbox = DBconnect::assoc_query_2D("SELECT 'inbox' as type, CASE WHEN i.from_id = 1 THEN '".ADMIN_USERNAME_DISPLAY."' ELSE m.username END AS username , m.id, m.picturepath, i.datetime, i.status, f.parent_id as isFavorited FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id LEFT JOIN (SELECT * FROM favorite WHERE parent_id=".$userid.") f on m.id=f.child_id WHERE i.to_id=".$userid." $sql_current_username and m.username IS NOT NULL $sql_message_history_preiod_inbox ORDER BY i.datetime DESC");

	$outbox = DBconnect::assoc_query_2D("SELECT 'outbox' as type, CASE WHEN o.to_id = 1 THEN '".ADMIN_USERNAME_DISPLAY."' ELSE m.username END AS username , m.id, m.picturepath, o.datetime, o.status, f.parent_id as isFavorited FROM message_outbox o LEFT JOIN member m ON o.to_id=m.id LEFT JOIN (SELECT * FROM favorite WHERE parent_id=".$userid.") f on m.id=f.child_id WHERE o.from_id=".$userid." $sql_current_username and m.username IS NOT NULL $sql_message_history_preiod_outbox ORDER BY o.datetime DESC");

	$result = array();
	if(is_array($inbox))
		$result = array_merge($result, $inbox);

	if(is_array($outbox))
		$result = array_merge($result, $outbox);

	if(is_array($result) && count($result))
	{
		foreach ($result as $key => $row) {
			$dates[$key]  = $row["datetime"];
		}

		array_multisort($dates, SORT_DESC, $result);
	}

	$unique_array = array();
	foreach($result as $element)
	{
		$hash = $element["username"];
		if(empty($unique_array[$hash]))
		{
			$unique_array[$hash] = $element;
			$unique_array[$hash]['count'] = 0;
		}

		if(($element['status']==0) && ($element['type']=='inbox'))
			$unique_array[$hash]['count']++;
	}

	$result = $unique_array;

	if($_GET['username']!='')
	{
		$current = DBConnect::assoc_query_2D("SELECT m.username, m.id, m.picturepath, f.parent_id as isFavorited FROM member m LEFT JOIN (SELECT * FROM favorite WHERE parent_id=".$userid.") f on m.id=f.child_id WHERE username='".($_GET['username']==ADMIN_USERNAME_DISPLAY?ADMIN_USERNAME:$_GET['username'])."' LIMIT 1");

		if($_GET['username']==ADMIN_USERNAME_DISPLAY)
		{
			$current[0]['username'] = ADMIN_USERNAME_DISPLAY;
		}

		$result = array_merge($current, $result);
	}

	$result = array_slice($result, 0, 50);

	return $result;
}

function getMessages($userid, $username, $part)
{
	$userid = funcs::check_input($userid);
	$username = funcs::check_input($username);
	$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");

	if(defined("MESSAGE_HISTORY_PERIOD"))
	{
		$sql_message_history_preiod_inbox = " AND i.datetime > NOW()-INTERVAL ".MESSAGE_HISTORY_PERIOD;
		$sql_message_history_preiod_outbox = " AND o.datetime > NOW()-INTERVAL ".MESSAGE_HISTORY_PERIOD;
	}
	else
	{
		$sql_message_history_preiod_inbox = "";
		$sql_message_history_preiod_outbox = "";
	}

	$inbox = DBconnect::assoc_query_2D("SELECT CASE WHEN i.from_id = 1 THEN '".ADMIN_USERNAME_DISPLAY."' ELSE m.username END AS username, m.picturepath, i.* FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.from_id=".$from." AND i.to_id=".$userid.$sql_message_history_preiod_inbox);

	//$outbox = array();
	$outbox = DBconnect::assoc_query_2D("SELECT CASE WHEN o.from_id = 1 THEN '".ADMIN_USERNAME_DISPLAY."' ELSE m.username END AS username, m.picturepath, o.* FROM message_outbox o LEFT JOIN member m ON o.from_id=m.id WHERE o.from_id=".$userid." AND o.to_id=".$from.$sql_message_history_preiod_outbox);

	$mdarray = array();
	if(is_array($inbox))
		$mdarray = array_merge($mdarray, $inbox);

	if(is_array($outbox))
		$mdarray = array_merge($mdarray, $outbox);

	if(is_array($mdarray) && count($mdarray))
	{
		foreach ($mdarray as $key => $row) {
			$dates[$key]  = $row["datetime"];
		}

		array_multisort($dates, SORT_DESC, $mdarray);
	}

	$post_mdarray = array();
	foreach ($mdarray as $message) {
			$message['message'] = convetSmiley($message['message']);


			if (!is_null($message['gift_id']) && $message['gift_id'] > 0) {
				$message['gift_path'] = DBConnect::retrieve_value("SELECT image_path FROM gift WHERE id = " . $message['gift_id']);
			}

			$post_mdarray[] = $message;

	}

	/*if(count($mdarray)>15)
		$mdarray = array_slice($mdarray,0,15);*/

	if($part=="all")
	{
		DBConnect::execute_q("UPDATE message_inbox SET status = 1, read_date = NOW() WHERE from_id=".$from." AND to_id=".$userid." AND status=0");
	}
	DBConnect::execute_q("UPDATE message_outbox SET status = 1, read_date = NOW() WHERE to_id=".$from." AND from_id=".$userid." AND status=0");

	return $post_mdarray;
}

function convetSmiley($text) {
	$needles = array();
	$replace = array();

	$rs = mysql_query("SELECT id, text_version, image_path, active FROM emoticon");

	while ($r = mysql_fetch_assoc($rs)) {
		$needles[] = "/" . preg_quote($r['text_version']) . "/";
		$replace[] = "<img src='../" . $r['image_path'] . "' height='35' width='35' />";
	}

	return preg_replace($needles, $replace, $text);
}

function getNumAllMessage_inbox($userid, $archive)
{
	$userid = funcs::check_input($userid);
	$archive = funcs::check_input($archive);

	return DBconnect::get_nbr("SELECT COUNT(*) FROM ".TABLE_MESSAGE_INBOX." WHERE ".TABLE_MESSAGE_INBOX_TO."=".$userid." AND ".TABLE_MESSAGE_INBOX_ARCHIVE."=".$archive);
}

function getAllMessage_inbox($userid, $archive, $start, $limit)
{
	$userid = funcs::check_input($userid);
	$archive = funcs::check_input($archive);
	$start = funcs::check_input($start);
	$limit = funcs::check_input($limit);

	$sql = "SELECT
			CASE WHEN m1.id = 1 THEN '".ADMIN_USERNAME_DISPLAY."'
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

function sendCoins($coins, $from_id, $to_id, $to_username)
{
	$fakesender = DBConnect::retrieve_value("SELECT fake FROM member WHERE id=".$from_id);
	$fakereceiver = DBConnect::retrieve_value("SELECT fake FROM member WHERE id=".$to_id);

	if(!$fakesender)
		DBConnect::execute_q("UPDATE member SET coin=coin-".$coins." WHERE id=".$from_id);
	if(!$fakereceiver)
		DBConnect::execute_q("UPDATE member SET coin=coin+".$coins." WHERE id=".$to_id);

	DBconnect::execute("INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('".$from_id."','".$to_id."','send coins',".$coins.",".funcs::checkCoin($from_id).", NOW())");
}

function sendGift($gift_id, $from_id, $to_id)
{
	DBconnect::execute("INSERT INTO member_gift (member_id, sender_id, gift_id, created) VALUES (" . $to_id . "," . $from_id . ", ". $gift_id.", NOW())");
	#DBconnect::execute("INSERT INTO gift_log (member_id, send_to, gift_id, log_date) VALUES ('".$from_id."','".$to_id."',".$gift_id.", NOW())");
}

function getRealUserInfo($from_user)
{
	if($from_user!=ADMIN_USERNAME_DISPLAY)
	{
		$arr = DBConnect::assoc_query_1D("SELECT id, username FROM member WHERE username='".$from_user."'");
		$username = $arr['username'];
		$from = $arr['id'];
	}
	else
	{
		$username = ADMIN_USERNAME;
		$from = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'");
	}
	return array('id'=>$from,'username'=>$username);
}
?>
