<?php
funcs::deleteOldMessages(30);

//smarty paging//
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

switch($_GET['type'])
{
	case 'archive':
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 5, 8);	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		//delete message archive//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::deleteMessage_inbox($_SESSION['sess_id'], $_POST['messageid']);

		//get archive message//
		$mymessage_total = funcs::getNumAllMessage_inbox($_SESSION['sess_id'], 1);
		$mymessage = funcs::getAllMessage_inbox($_SESSION['sess_id'], 1, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data archive message to template//
		$smarty->assign('mymessage_total', $mymessage_total);
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
		break;
	case 'outbox':
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 5, 8);	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		//delete message outbox//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::deleteMessage_outbox($_SESSION['sess_id'], $_POST['messageid']);

		//get outbox message//
		$mymessage_total = funcs::getNumAllMessage_outbox($_SESSION['sess_id']);
		$mymessage = funcs::getAllMessage_outbox($_SESSION['sess_id'], SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data outbox message to template//
		$smarty->assign('mymessage_total', $mymessage_total);
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
		break;
	case 'reply':
		//check permission type//
// 		$permission_lv = array(1, 2, 8);	//define type permission can open this page.
		$permission_lv = array(1, 2, 3, 4, 8); //jeab edited
		if(in_array($_SESSION['sess_permission'], $permission_lv))
		{
			if(isset($_POST['send_button']) && !empty($_POST['send_button']))
			{
				$error = 0;
				$receivers = array();
				$from = $_SESSION['sess_id'];
				for($n = 0; $n<count($_POST['messageid']); $n++)
				{
					$data = funcs::getMessage_inbox($from, $_POST['messageid'][$n], 0);
					if($data[TABLE_MESSAGE_INBOX_FROM] != '')
					{
						$userid = $data[TABLE_MESSAGE_INBOX_FROM];
						$username=DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$userid);
						if(!in_array($username,$receivers))
							$receivers[] = $username;
					}

				}

				$currentCoin = funcs::checkCoin(DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$from));
				$minusSms = funcs::getMinusCoin('COIN_SMS');

				if($currentCoin<($minusSms*count($receivers)))
				{
					$smarty->assign('text', funcs::getText($_SESSION['lang'], '$mymsg1')); //send error
					$smarty->assign('save', $_POST);
				}
				else
				{
					for($n = 0; $n<count($receivers); $n++)
					{
						if($_POST['send_via_sms']=="1")
						{
							$subject = funcs::getText($_SESSION['lang'], '$sms_subject');
							if(!funcs::sendMessageViaSMS($from, $receivers[$n], $subject, $_POST['sms']))
								$error = 1;

							$sentby = "sms";

							if($n > 0)
								$username_list .= ", ";

							$username_list .= $receivers[$n];
						}
						else
						{
							$website = funcs::getText($_SESSION['lang'], '$KM_Website');
							$subj_ext = str_replace('[PROFILE_NAME]', $_SESSION['sess_username'], str_replace('[KM_Website]', $website, funcs::getText($_SESSION['lang'], '$emailAfterEmail_subject')));
							if(!funcs::sendMessage($from, $receivers[$n], $subj_ext, $_POST['sms'], 0))
								$error = 1;

							$sentby = "message";

							if($n > 0)
								$username_list .= ", ";

							$username_list .= $receivers[$n];
						}
					}
					
					if(!$error)
							header("Location: ?action=mymessage&type=complete&sentby=".$sentby."&username=".$username_list);
					else
					{
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
						$smarty->assign('save', $_POST);
					}
				}
			}
			$username = array();
			$messageid = '';
			for($n=0; $_POST['messageid'][$n]; $n++)
			{
				$get_username = funcs::getUsername_Message($_POST['messageid'][$n]);
				if(!in_array($get_username, $username))
					array_push($username, $get_username);
			}
			
			if(count($username) <= 0)
				header("Location: ?action=mymessage&type=inbox");

			$smarty->assign('messageid', $_POST['messageid']);
			$smarty->assign('username', $username);
		}
		else
		{
			$username = array();
			$messageid = '';
			for($n=0; $_POST['messageid'][$n]; $n++)
			{
				$get_username = funcs::getUsername_Message($_POST['messageid'][$n]);
				if(!in_array($get_username, $username))
					array_push($username, $get_username);
			}
			$get_username = funcs::getUsername_Message($messageid);
			if(!in_array($get_username, $username))
				array_push($username, $get_username);

			$i=1;
			$reply_message = array();
			$total = 3;
			for($i=1; $i<($total+1); $i++)
			{
				$temp = funcs::getText($_SESSION['lang'],'$reply_message'.$i);
				if(count($username) == 1)
					$temp = str_replace("\$receiver", $username[0], $temp);
				else
					$temp = str_replace("\$receiver", "", $temp);
				$temp = str_replace("\$sender", $_SESSION['sess_username'], $temp);
				array_push($reply_message, $temp);
			}

			$save = array('message' => $reply_message, 'subject' => funcs::getText($_SESSION['lang'],'$reply_subject'), 'total' => $total);

			if(isset($_POST['send_button_x']) && !empty($_POST['send_button_x'])||isset($_POST['answer_button_x']))
			{
				/*$subject = $_SESSION['sess_username'].' ';
				$subject .= funcs::getText($_SESSION['lang'], '$reply_message');
				$message = $subject;*/
				$subject = $save['subject'];
				$message = $save['message'][$_POST['message_id']-1];

				// Get standard reply sent today.
				$logs = funcs::getLog("standard_reply",time(),array("user_id","username","datetime"));
				$i = 0;
				foreach($logs as $log)
				{
					if($log['user_id'] == $_SESSION['sess_id'])
						$i++;
				}

				$quotas_left = 3-$i;
				// Standard Reply Quotas left
				if((($_SESSION['sess_permission'] == 4) && ($quotas_left >= count($username))) || ($_SESSION['sess_permission'] == 3))
				{
					//send message//
					//loop reply message//
					$error = 0;
					for($n=0; $_POST['messageid'][$n]; $n++)
					{
						if(!funcs::replyMessage($_POST['messageid'][$n], $subject, $message))
						{
							$error = 1; //if message can not send
							$error_message = funcs::getText($_SESSION['lang'], '$writemessage_error');
						}
						else
						{
							funcs::saveLog("standard_reply", array($_SESSION['sess_id'], funcs::getUsername_Message($_POST['messageid'][$n]), date("Y-m-d H:i:s")));
						}
					}
				}
				else // No more standard reply left.
				{
					$error = 1;
					$error_message = "Not enough quotas to send standard reply message. You need ".count($username).", just ".$quotas_left." left.";
				}

				if(!$error)
					header("Location: ?action=mymessage&type=complete");
				else
				{
					$smarty->assign('text', $error_message); //send error
					$smarty->assign('section', 'blank');
				}
			}
			else
			{
				if(count($username) <= 0)
					header("Location: ?action=mymessage&type=inbox");

				$smarty->assign('messageid', $_POST['messageid']);
				$smarty->assign('username', $username);

				$smarty->assign('save',$save);
				$smarty->assign('mode', 'standard_message');
			}
		}
		break;
	case 'writemessage':
		if(isset($_POST['answer_button_x']) && !empty($_POST['answer_button_x']))
		{
			//check permission type//
// 			$permission_lv = array(1,3);	//define type permission can open this page.
			$permission_lv = array(1, 2, 3, 4, 8); //jeab edited
			funcs::checkPermission($smarty, $permission_lv);	//check permission

			if(is_array($_POST['messageid']))
			{
				$username = array(funcs::getUsername_Message($_POST['messageid'][0]));
			}
			if($_POST['username'][0])
			{
				$username = array($_POST['username'][0]);	
			}
			elseif($_POST['to'])
			{
				$username = array($_POST['to']);
			}

			$i=1;
			$reply_message = array();
			$total = 3;
			for($i=1; $i<($total+1); $i++)
			{
				$temp = funcs::getText($_SESSION['lang'],'$standard_message'.$i);
				$temp = str_replace("\$receiver", $username[0], $temp);
				$temp = str_replace("\$sender", $_SESSION['sess_username'], $temp);
				array_push($reply_message, $temp);
			}

			$save = array('message' => $reply_message, 'subject' => funcs::getText($_SESSION['lang'],'$reply_subject2'), 'total' => $total);
			
			if(isset($_POST['act']) && $_POST['act'] == 'writemsg'){
				// Get standard message sent today.
				$logs = funcs::getLog("standard_message",time(),array("user_id","username","datetime"));
				$i = 0;
				foreach($logs as $log)
				{
					if($log['user_id'] == $_SESSION['sess_id'])
						$i++;
				}

				$quotas_left = 3-$i;
				// Standard message Quotas left
				if($quotas_left >= count($username))
				{
					$message = $reply_message[$_POST['message_id']];
					if(!funcs::sendMessage($_SESSION['sess_id'], $username[0], $save['subject'], $message, 0))
					{
						$error = 1; //if message can not send
						$error_message = funcs::getText($_SESSION['lang'], '$writemessage_error');
					}
					else
					{
						funcs::saveLog("standard_message", array($_SESSION['sess_id'], $username[0], date("Y-m-d H:i:s")));
					}
				}
				else // No more standard message left.
				{
					$error = 1;
					$error_message = funcs::getText($_SESSION['lang'], '$mymsg3');
				}

				if(!$error)
					header("Location: ?action=mymessage&type=complete");
				else
				{
					$smarty->assign('text', $error_message); //send error
					$smarty->assign('section', 'blank');
				}
			}
			else
			{
				$smarty->assign('messageid', $_POST['messageid']);
				$smarty->assign('username', $username);

				$smarty->assign('save',$save);
				$smarty->assign('mode', 'standard_answer');
			}
		}
		else
		{
			//check permission type//
// 			$permission_lv = array(1, 2, 8);	//define type permission can open this page.
			$permission_lv = array(1, 2, 3, 4, 8); //jeab edited
			funcs::checkPermission($smarty, $permission_lv);	//check permission

			//send message//
			/////if(isset($_POST['send_button_x']) && !empty($_POST['send_button_x'])){////Singh
			if(isset($_POST['act']) && $_POST['act'] == 'writemsg')
			{ 
				$smarty->assign('save', $_POST);

				if($_GET['send_via_sms']=='1')
				{
					$phonenumber = funcs::getCurrentUserMobileNo();
					if((isset($phonenumber)) && ($phonenumber=="Verified"))
					{
						$subject = funcs::getText($_SESSION['lang'], '$sms_subject');
						if(funcs::sendMessageViaSMS($_SESSION['sess_id'], $_POST['to'], $subject, $_POST['sms'], 0))
						{
							
							//header("Location: ?action=mymessage&type=complete");
							header("Location: ?action=mymessage&type=complete&sentby=sms&username=".$_POST['to']);
							//$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete')); //send complete
						}
						else
						{
							$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$_SESSION['sess_id']."'");
							$currentCoin = funcs::checkCoin($username);
							$minusSms = funcs::getMinusCoin('COIN_SMS');
							
							if($currentCoin >= $minusSms) {
								$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
							}
							else
							{
								//$smarty->assign('text', "You don't have enought COIN to send SMS, please refill the COIN first."); //send error
								header("location: ?action=pay-for-coins&type=not-enough");
							}
							$smarty->assign('save', $_POST);
						}
					}
					else
						$smarty->assign('text', funcs::getText($_SESSION['lang'], '$mobile_ver_required')); //send error
				}
				else
				{
					$website = funcs::getText($_SESSION['lang'], '$KM_Website');
					//$subj_ext = str_replace('[PROFILE_NAME]', $_SESSION['sess_username'], str_replace('[KM_Website]', $website, funcs::getText($_SESSION['lang'], '$emailAfterEmail_subject')));
					if(funcs::sendMessage($_SESSION['sess_id'], $_POST['to'], $_POST['subject'], $_POST['sms'], 0))
					{
						
						//header("Location: ?action=mymessage&type=complete");
						header("Location: ?action=mymessage&type=complete&sentby=message&username=".$_POST['to']);
						//$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete')); //send complete
					}
					else
					{
						$username = DBConnect::retrieve_value("SELECT username from member WHERE id='".$_SESSION['sess_id']."'");
						$currentCoin = funcs::checkCoin($username);
						$minusEmail = funcs::getMinusCoin('COIN_EMAIL');
						
						if($currentCoin >= $minusEmail) {
							$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
						}
						else
						{
							//$smarty->assign('text', "You don't have enought COIN to send the message, please refill the COIN first."); //send error
							header("location: ?action=pay-for-coins&type=not-enough");
						}
						$smarty->assign('save', $_POST);
					}
				}
			}
			/***** Singh Start *****//////
			elseif(isset($_GET['username'])){
				$usrMobile = funcs::checkmobile($_GET['username']);
			}
			$smarty->assign('usrMobile',$usrMobile);
			unset($usrMobile);
			/***** Singh end *****/

			$username_text = "";
			if($_GET['username']!="")
			{
				if($_SESSION['lang']=="eng")
					$username_text = "to " . $_GET['username'];
				else
					$username_text = "an " . $_GET['username'];
			}
			
			$coin_charge_email_text =  str_replace('[PROFILE_NAME]',$username_text, funcs::getText($_SESSION['lang'], '$sendmessage_email_coin'));
			$coin_charge_sms_text =  str_replace('[PROFILE_NAME]',$username_text, funcs::getText($_SESSION['lang'], '$sendmessage_sms_coin'));

			$coin_conts = funcs::getCoinData();

			$smarty->assign('coin_charge_msg', str_replace('[COIN_COSTS]', $coin_conts[0]['coin_email'], $coin_charge_email_text));
			$smarty->assign('coin_charge_sms', str_replace('[COIN_COSTS]', $coin_conts[0]['coin_sms'], $coin_charge_sms_text));
		}
		break;
	case 'inbox':
	default:
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 8); //jeab edited	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		//delete message//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::deleteMessage_inbox($_SESSION['sess_id'], $_POST['messageid']);

		//add message to archive//
		if(isset($_POST['archive_button']) && !empty($_POST['archive_button']) && (count($_POST['messageid'])>0))
			funcs::addMessage_archive($_SESSION['sess_id'], $_POST['messageid']);

		//get inbox message//
		$mymessage_total = funcs::getNumAllMessage_inbox($_SESSION['sess_id'], 0);
		$mymessage = funcs::getAllMessage_inbox($_SESSION['sess_id'], 0, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data inbox message to template//
		$smarty->assign('mymessage_total', $mymessage_total);
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
}
//select template file//
$smarty->display('index.tpl');
?>