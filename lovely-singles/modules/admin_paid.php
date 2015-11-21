<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if(!in_array($_GET['o'], array('','successful', 'callcenter','error','revoked','reminder')))
{
	header("location: ?action=admin_paid");
}

if(isset($_GET['do']) && ($_GET['do'] == 'delete'))
{
	if(DBConnect::retrieve_value("SELECT ID FROM ".TABLE_PAY_LOG." WHERE ID='".$_GET['id']."' AND copy_from <> '0'"))
	{
		DBConnect::execute("DELETE FROM ".TABLE_PAY_LOG." WHERE ID='".$_GET['id']."'");
	}
	header("location: ".$_SERVER['HTTP_REFERER']);
}
elseif(isset($_GET['do']) && ($_GET['do'] == 'cancel'))
{
	DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET recall='1' WHERE ID='".$_GET['id']."'");
	header("location: ".$_SERVER['HTTP_REFERER']);
}
elseif(isset($_GET['do']) && ($_GET['do'] == 'finish'))
{
	DBConnect::execute("UPDATE ".TABLE_PAY_LOG." SET payment_complete='1' WHERE ID='".$_GET['id']."'");

	$info = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID='".$_GET['id']."'");
	DBConnect::execute("UPDATE ".TABLE_MEMBER." SET payment_received=NOW(), type='".$info['new_type']."', payment='".$info['new_paid_until']."' WHERE username='".$info['username']."'");

	$userid = DBConnect::retrieve_value("SELECT id FROM ".TABLE_MEMBER." WHERE username='".$info['username']."'");
	/*$value_names = "user_id, start_date, end_date, membership_type, paid_via";
	$values = "'$userid', NOW(), '".$info['new_paid_until']."', '".$info['new_type']."', '".$info['paid_via']."'";
	DBconnect::insert_row("history", $value_names, $values);*/

	header("location: ".$_SERVER['HTTP_REFERER']);
}

if(!isset($_GET['next']))
	SmartyPaginate::reset();

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=admin_paid&o=".$_GET['o']."&order=".$_GET['order']."&type=".$_GET['type']); //smarty paging set URL

switch($_GET['o'])
{
	case 'successful':
		$type=" WHERE t1.username = t2.username AND t1.payment_complete=1 AND t1.sum_paid >0";
		break;
	case 'callcenter':
		$type=" WHERE t1.username = t2.username AND ((t1.payment_complete=1 AND t1.recall=1) OR t1.copy_from <> 0)";
		break;
	case 'error':
		$type=" WHERE t1.username = t2.username AND t1.payment_complete=0 AND t1.errormsg <> ''";
		break;
	case 'revoked':
		$type=" WHERE t1.username = t2.username AND t1.recall=1 AND t2.in_storno = 1";
		break;
	case 'reminder':
		$type=" WHERE t1.username = t2.username AND t1.reminder_costs <> 0";
		break;
	default:
		$type=" WHERE t1.username = t2.username AND t1.ip_address != ''";
		break;
}

if(in_array($_GET['type'], array('asc','desc')))
	$order_type .= $_GET['type'];
else
	$order_type .= "ASC";

switch($_GET['order'])
{
	case 'username':
		$order = " ORDER BY t1.username ".$order_type;
		break;	
	case 'name':
		$order = " ORDER BY t1.real_name ".$order_type.", t1.username ASC";
		break;
	case 'street':
		$order = " ORDER BY t1.real_street ".$order_type.", t1.username ASC";
		break;
	case 'plz':
		$order = " ORDER BY t1.real_plz ".$order_type.", t1.username ASC";
		break;
	case 'city':
		$order = " ORDER BY t1.real_city ".$order_type.", t1.username ASC";
		break;
	case 'ip':
		$order = " ORDER BY t1.ip_address ".$order_type.", t1.username ASC";
		break;
	case 'payday':
		$order = " ORDER BY t1.payday DESC, copy_from ".$order_type.", t1.username ASC";
		break;
	case 'until':
		$order = " ORDER BY t1.new_paid_until ".$order_type.", t1.username ASC";
		break;
	case 'type':
		$order = " ORDER BY t1.new_type ".$order_type.", t1.username ASC";
		break;
	case 'via':
		$order = " ORDER BY t1.paid_via ".$order_type.", t1.username ASC";
		break;
	case 'sum_paid':
		$order = " ORDER BY t1.sum_paid ".$order_type.", t1.username ASC";
		break;		
	case 'payment_complete':
		$order = " ORDER BY t1.payment_complete ".$order_type.", t1.username ASC";
		break;		
	case '';
	default:
		$order = " ORDER BY t1.payday DESC, t1.copy_from DESC";
		break;		
}

$num = DBConnect::retrieve_value("SELECT count(*) FROM ".TABLE_PAY_LOG." t1,".TABLE_MEMBER." t2 ".$type);
$userrec = DBConnect::assoc_query_2D("SELECT t1.* FROM ".TABLE_PAY_LOG." t1, ".TABLE_MEMBER." t2 ".$type.$order." LIMIT ".SmartyPaginate::getCurrentIndex().",".SmartyPaginate::getLimit());

$today = date("Y-m-d");
if(is_array($userrec))
{
	foreach($userrec as &$user)
	{
		$sql = "SELECT count(*) FROM ".TABLE_PAY_LOG." WHERE copy_from='".$user['ID']."'";
		if(DBConnect::retrieve_value($sql))
		{
			$user['copied'] = 1;
		}
		else
			$user['copied'] = 0;

		if($user['payday'] != "")
		{
			$payday = split(" ", $user['payday']);
			$days_ago = funcs::dateDiff("-", $today, $payday[0]);
			$user['days_ago'] = $days_ago;
		}
	}
	SmartyPaginate::setTotal($num);
}

SmartyPaginate::assign($smarty);

$smarty->assign('userrec', $userrec);
$smarty->display('admin.tpl');
