<?php
//check permission type//
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

	if($_GET['from'] == 'message') {
		if(isset($_GET['id']) && isset($_GET['type'])){
			funcs::checkMessage($_GET['id'],$_GET['type']);
		}
	}
	
switch($_GET['type']){
	case 'archive':
		//delete message archive//
		if(isset($_POST['act']) && $_POST['act'] == 'del' && (count($_POST['messageid'])>0)){
			funcs::deleteMessage_inbox($_SESSION['sess_id'], $_POST['messageid']);
			header("Location: ?action=mymessage&type=".$_GET['type']);
		}

		//get message archive//
		$smarty->assign('message', funcs::getMessage_inbox($_SESSION['sess_id'], $_GET['id'], 1));
		break;
	case 'inbox':
		if(isset($_GET[username]) && $_GET[username] != ""){
			$ext_sql = ($_GET[username] == 'System Admin') ? ' WHERE id = 1':" WHERE username = '".$_GET[username]."'";
			$path = DBConnect::retrieve_value("SELECT picturepath FROM ".TABLE_MEMBER.$ext_sql);
		}
		//move message to archive//
		if(isset($_POST['act']) && $_POST['act'] == 'archive' && (count($_POST['messageid'])>0)){
			funcs::addMessage_archive($_SESSION['sess_id'], $_POST['messageid']);
			header("Location: ?action=mymessage&type=".$_GET['type']);
		}

		//delete message inbox//
		if(isset($_POST['act']) && $_POST['act'] == 'del' && (count($_POST['messageid'])>0)){	
			funcs::deleteMessage_inbox($_SESSION['sess_id'], $_POST['messageid']);
			header("Location: ?action=mymessage&type=".$_GET['type']);
		}
				
		//get message inbox//
		$message = funcs::getMessage_inbox($_SESSION['sess_id'], $_GET['id'], 0);
		$smarty->assign('path',$path);
		$smarty->assign('message', $message);
		$user_id = ($_GET[username] == 'System Admin') ? '1' : funcs::getUserid($message['username']);
		$message_before = funcs::getMessageHistory($_SESSION['sess_id'], $user_id, 1, 1);
		$smarty->assign('message_before', $message_before);
		break;
	case 'outbox':
		//delete message outbox//
		if(isset($_POST['act']) && $_POST['act'] == 'del' && (count($_POST['messageid'])>0)){
			funcs::deleteMessage_outbox($_SESSION['sess_id'], $_POST['messageid']);
			header("Location: ?action=mymessage&type=".$_GET['type']);
		}

		//get message outbox//
		$smarty->assign('message', funcs::getMessage_outbox($_SESSION['sess_id'], $_GET['id'], 0));
		break;
	default:
		header("Location: ./");
}

$smarty->display('index.tpl'); //select template file
?>