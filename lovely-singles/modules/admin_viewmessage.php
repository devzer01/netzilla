<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_GET['id']) && isset($_GET['type']))
{
	funcs::admin_checkMessage($_GET['id'],$_GET['type']);	//check message has?
}
switch($_GET['type'])
{
	case 'inbox':
		//move message to archive//
		if(isset($_POST['reply_button']) && !empty($_POST['reply_button']) && (count($_POST['messageid'])>0))
		{	
			header("Location: ?action=admin_message&type=reply&messageid=".$_GET['id']);
		}
		//delete message inbox//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
		{	
			funcs::admin_deleteMessage_inbox($_POST['messageid']);
			header("Location: ?action=admin_message&type=".$_GET['type']);
		}				
		//get message inbox//
		$smarty->assign('message', funcs::admin_getMessage_inbox($_GET['id']));
		break;
	case 'outbox':
		//delete message outbox//
		if(isset($_POST['delete_button']) && !empty($_POST['delete_button']) && (count($_POST['messageid'])>0))
		{
			funcs::admin_deleteMessage_outbox($_POST['messageid']);
			header("Location: ?action=admin_message&type=".$_GET['type']);
		}
		//get message outbox//
		$smarty->assign('message', funcs::admin_getMessage_outbox($_GET['id']));
		break;
	default:
		header("Location: ?action=administrator");	//go to administrator default page
}
//select template file//
$smarty->display('admin.tpl');
?>
