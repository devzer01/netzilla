<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//smarty paging//
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record
	
switch($_GET['type'])
{	
	case 'outbox':
		//delete message outbox//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::admin_deleteMessage_outbox($_POST['messageid']);
		
		//get outbox message//
		$mymessage_total = funcs::admin_getNumAllMessage_outbox();	//get count all message outbox
		$mymessage = funcs::admin_getAllMessage_outbox(SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());	//get limit all message outbox
		
		$smarty->assign('message', $mymessage);	//send data outbox message to template
		SmartyPaginate::setTotal($mymessage_total);	//define data total
		SmartyPaginate::assign($smarty);	//send data about smarty paging to template
		break;
	case 'reply':
		//send message//
		if(isset($_POST['send_button']) && !empty($_POST['send_button'])){			
			$error = 0;
			//loop reply message//
			for($n=0; $_POST['messageid'][$n]; $n++){
				if(!funcs::admin_replyMessage($_POST['messageid'][$n], $_POST['subject'], $_POST['message']))
					$error = 1; //if message can not send
			}
			
			if(!$error)
				header("Location: ?action=admin_message&type=complete");	//when send completely
			else{
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //when send error show text alert
				$smarty->assign('save', $_POST);
			}
		}
		$username = array();
		$messageid = '';
		//get username from message id//
		for($n=0; $_POST['messageid'][$n]; $n++){
			$get_username = funcs::admin_getUsername_Message($_POST['messageid'][$n]);	
			if(!in_array($get_username, $username))
				array_push($username, $get_username);
		}

		//if can not get username from message id go to message inbox page//
		if(count($username) <= 0)
			header("Location: ?action=admin_message&type=inbox");
		
		//send data to template//
		$smarty->assign('messageid', $_POST['messageid']);
		$smarty->assign('username', $username);
		break;
	case 'writemessage':
		//send message//
		if(isset($_POST['send_button']) && !empty($_POST['send_button']))
		{
			$usernameto = str_replace(" ", "", $_POST['to']);
			$usernameto = explode(",", $usernameto);
			$error = 0;
			
			//loop send message//
			for($n=0; $usernameto[$n]; $n++)
			{
				if(!funcs::admin_sendMessage($usernameto[$n], $_POST['subject'], $_POST['message']))
					$error = 1;	//when send error
			}
			if(!$error)
				header("Location: ?action=admin_message&type=complete");	//when send message completely
			else
			{
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$writemessage_error')); //when send message error show error message
				$smarty->assign('save', $_POST);
			}
		}
		break;
	case 'inbox':			
	default:
		//delete message//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
			funcs::admin_deleteMessage_inbox($_POST['messageid']);
		
		//get inbox message from database//
		$mymessage_total = funcs::admin_getNumAllMessage_inbox();
		$mymessage = funcs::admin_getAllMessage_inbox(SmartyPaginate::getCurrentIndex(), SmartyPaginate::getLimit());
		//send data inbox message to template//
		$smarty->assign('message', $mymessage);
		SmartyPaginate::setTotal($mymessage_total);
		SmartyPaginate::assign($smarty);
}

//select template file//
$smarty->display('admin.tpl');
?>
