<?php
funcs::deleteOldMessages(30);

//smarty paging//
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

switch($_GET['type'])
{
	case 'archive':
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 5);	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		//delete message archive//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::deleteMessage_inbox($_SESSION['sess_id'], $_POST['messageid']);

		//get archive message//
		$mymessage_total = funcs::getNumAllMessage_inbox($_SESSION['sess_id'], 1);
		$mymessage = funcs::getAllMessage_inbox($_SESSION['sess_id'], 1, SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data archive message to template//
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
		break;
	case 'outbox':
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 5);	//define type permission can open this page.
		funcs::checkPermission($smarty, $permission_lv);	//check permission

		//delete message outbox//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::deleteMessage_outbox($_SESSION['sess_id'], $_POST['messageid']);

		//get outbox message//
		$mymessage_total = funcs::getNumAllMessage_outbox($_SESSION['sess_id']);
		$mymessage = funcs::getAllMessage_outbox($_SESSION['sess_id'], SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data outbox message to template//
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
		break;
	case 'reply':
		//check permission type//
		$permission_lv = array(1, 2);	//define type permission can open this page.
		if(in_array($_SESSION['sess_permission'], $permission_lv))
		{
			if(isset($_POST['send_button']) && !empty($_POST['send_button']))
			{
				$error = 0;
				for($n = 0; $_POST['messageid'][$n]; $n++)
				{
					if(!funcs::replyMessage($_POST['messageid'][$n], $_POST['subject'], $_POST['message']))
						$error = 1;
				}
				if(!$error)
						header("Location: ?action=mymessage&type=complete");
				else
				{
					$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
					$smarty->assign('save', $_POST);
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
			for($n=0; $_POST['messageid'][$n]; $n++)
			{
				$get_username = funcs::getUsername_Message($_POST['messageid'][$n]);
				if(!in_array($get_username, $username))
					array_push($username, $get_username);
			}
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

			if(isset($_POST['send_button']) && !empty($_POST['send_button']))
			{
				/*$subject = $_SESSION['sess_username'].' ';
				$subject .= funcs::getText($_SESSION['lang'], '$reply_message');
				$message = $subject;*/
				$subject = $save['subject'];
				$message = $save['message'][$_POST['message_id']-1];

				//send message//
				//loop reply message//
				$error = 0;
				for($n=0; $_POST['messageid'][$n]; $n++)
				{
					if(!funcs::replyMessage($_POST['messageid'][$n], $subject, $message))
						$error = 1; //if message can not send
				}

				if(!$error)
					header("Location: ?action=mymessage&type=complete");
				else
				{
					$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
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
			$permission_lv = array(3);	//define type permission can open this page.
			funcs::checkPermission($smarty, $permission_lv);	//check permission

			if(is_array($_POST['messageid']))
			{
				$username = array();
				for($n=0; $_POST['messageid'][$n]; $n++)
				{
					$get_username = funcs::getUsername_Message($_POST['messageid'][$n]);
					if(!in_array($get_username, $username))
						array_push($username, $get_username);
				}
			}
			else
			{
				$username = array($_POST['username']);
			}

			$i=1;
			$reply_message = array();
			$total = 3;
			for($i=1; $i<($total+1); $i++)
			{
				$temp = funcs::getText($_SESSION['lang'],'$standard_message'.$i);
				if(count($username) == 1)
					$temp = str_replace("\$receiver", $username[0], $temp);
				else
					$temp = str_replace("\$receiver", "", $temp);
				$temp = str_replace("\$sender", $_SESSION['sess_username'], $temp);
				array_push($reply_message, $temp);
			}

			$save = array('message' => $reply_message, 'subject' => funcs::getText($_SESSION['lang'],'$reply_subject2'), 'total' => $total);

			if(isset($_POST['send_button_x']) && !empty($_POST['send_button_x']))
			{
				$message = $reply_message[$_POST['message_id']];
				if(funcs::sendMessage($_SESSION['sess_id'], $_POST['to'], $_POST['subject'], $message, 0))
					header("Location: ?action=mymessage&type=complete");
					//$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete')); //send complete
				else
				{
					$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
					$smarty->assign('save', $_POST);
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
			$permission_lv = array(1, 2);	//define type permission can open this page.
			funcs::checkPermission($smarty, $permission_lv);	//check permission

			//send message//
			if(isset($_POST['send_button_x']) && !empty($_POST['send_button_x']))
			{
				if(funcs::sendMessage($_SESSION['sess_id'], $_POST['to'], $_POST['subject'], $_POST['message'], 0))
					header("Location: ?action=mymessage&type=complete");
					//$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete')); //send complete
				else
				{
					$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //send error
					$smarty->assign('save', $_POST);
				}
			}
		}
		break;
	case 'inbox':
	default:
		//check permission type//
		$permission_lv = array(1, 2, 3, 4, 5);	//define type permission can open this page.
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
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
}
//select template file//
$smarty->display('index.tpl');
?>
