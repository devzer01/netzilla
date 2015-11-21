<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

define('QUEUE_TYPE_BONUS', 1);

if(isset($_POST['coins']))
{
	$error = "";
	/*if(!is_array($_POST['username']))
	{
		$error .= "Please select at least 1 user.\r\n";
	}*/
	if(!isset($_POST['email_subject']) || ($_POST['email_subject']==""))
	{
		$error .= "Please enter EMAIL subject.\r\n";
	}
	if(!isset($_POST['email_body']) || ($_POST['email_body']==""))
	{
		$error .= "Please enter EMAIL body.\r\n";
	}
	if(isset($_POST['send_via_sms']) && ($_POST['send_via_sms']=="1"))
	{
		if(!isset($_POST['sms_body']) || ($_POST['sms_body']==""))
		{
			$error .= "Please enter SMS body.\r\n";
		}
	}

	if(!$error)
	{
		$sql = "INSERT INTO queue (type, post, get, session, server, created) VALUES "
		     . " (" . QUEUE_TYPE_BONUS .", '" . mysql_real_escape_string(serialize($_POST)) . "',"
		     . " '" . mysql_real_escape_string(serialize($_GET)) . "'," 
		     . " '" . mysql_real_escape_string(serialize($_SESSION)) . "',"
		     . " '" . mysql_real_escape_string(serialize($_SERVER)) . "',"
		     . " NOW()) ";
		DBConnect::execute($sql);
		$return['resulttext'] = "Bonus Operation was Queued ";
	}
	else
	{
		$return['result'] = 0;
		$return['resulttext'] = $error;
	}
	echo json_encode($return);
	exit;
}
elseif(isset($_GET['u']))
{
	if(($_GET['fromdate'] != "") && ($_GET['todate'] != ""))
	{
		if(strtotime($_GET['fromdate']) > strtotime($_GET['todate']))
		{
			header("location: ?action=".$_GET['action']."&g=".$_GET['g']."&co=".$_GET['co']."&s=".$_GET['s']."&ci=".$_GET['ci']."&u=".$_GET['u']."&fromdate=".$_GET['todate']."&todate=".$_GET['fromdate']);
			exit;
		}
	}
	$users_per_page = isset($_COOKIE['users_per_page'])?$_COOKIE['users_per_page']:20;
	//smarty paging
	SmartyPaginate::connect();
	SmartyPaginate::setLimit($users_per_page); //smarty paging set records per page
	SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']."&g=".$_GET['g']."&co=".$_GET['co']."&s=".$_GET['s']."&ci=".$_GET['ci']."&u=".$_GET['u']."&fromdate=".$_GET['fromdate']."&todate=".$_GET['todate']); //smarty paging set URL

	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record

	$result = funcs::getUsersListForBonus($_GET);
	$countMember = $result['count'];

	SmartyPaginate::setTotal($countMember);
	SmartyPaginate::assign($smarty);
	$smarty->assign('search_result',$result['data']);
}
else
{
	$days_before = 30;
	$limit_number = 10;

	$result2 = funcs::getTopSpendCoinUser($days_before);

	//send data to template//
	$smarty->assign('userrec',$result2['data']);
	$smarty->assign('limit_number',$limit_number);
}
//select template file//
$smarty->display('admin.tpl');
?>