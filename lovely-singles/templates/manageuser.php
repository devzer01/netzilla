<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$proc = $_GET['proc'];
$user = $_GET['user'];

if(($proc=='del')&&($_SESSION['sess_permission']==1)){ 
// 	$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE." = 0 WHERE ".TABLE_MEMBER_USERNAME." = '$user'  AND ".TABLE_MEMBER_STATUS." != 1 AND fake = '{$_GET['f']}'";
//	$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE." = 0 WHERE ".TABLE_MEMBER_USERNAME." = '$user'";

	$sql = "SELECT id FROM member WHERE username = '".$user."'";
	$userid = DBconnect::retrieve_value($sql);
	
	$sql = "DELETE FROM member WHERE ".TABLE_MEMBER_USERNAME." = '$user'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM admin_message_inbox WHERE from_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM admin_message_outbox WHERE to_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM message_inbox WHERE from_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM message_outbox WHERE to_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM fotoalbum WHERE userid = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM fotoalbum WHERE userid = '$userid'";
	DBconnect::execute($sql);

	$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
	$result = $client->deleteProfile(array('profileID'=>$userid,'serverID'=>SERVER_ID));
	
	funcs::logProfileAction($userid,3);
	
	#Pakin Change this process
	funcs::NewSorting($user);
	if(isset($_GET['redirectto']) && ($_GET['redirectto']!=''))
		header("Location: ?action=".$_GET['redirectto']);
	else
		header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='block') && ($_SESSION['sess_permission']==1)){ 
	$sql = "SELECT id FROM member WHERE username = '".$user."'";
	$userid = DBconnect::retrieve_value($sql);
	
	$sql = "UPDATE member SET isactive=0 WHERE ".TABLE_MEMBER_USERNAME." = '$user'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM admin_message_inbox WHERE from_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM admin_message_outbox WHERE to_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM message_inbox WHERE from_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM message_outbox WHERE to_id = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM fotoalbum WHERE userid = '$userid'";
	DBconnect::execute($sql);

	$sql = "DELETE FROM fotoalbum WHERE userid = '$userid'";
	DBconnect::execute($sql);

	$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
	$result = $client->deleteProfile(array('profileID'=>$userid,'serverID'=>SERVER_ID));
	
	funcs::logProfileAction($userid,4);
	
	if(isset($_GET['redirectto']) && ($_GET['redirectto']!=''))
		header("Location: ?action=".$_GET['redirectto']);
	else
		header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_POST['change_type']) && ($_POST['change_type'] == "OK"))
{
	funcs::insertpayment($_POST['id'],$_POST['type'],2,0,1);
	//DBConnect::execute("UPDATE member set type=".$_POST['type']." WHERE id=".$_POST['id']);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
else
{
	//smarty paging
	SmartyPaginate::connect();
	SmartyPaginate::setLimit(20); //smarty paging set records per page
	SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']."&order=".$_GET['order']."&f=".$_GET['f']."&g=".$_GET['g']."&lg=".$_GET['lg']."&co=".$_GET['co']."&s=".$_GET['s']."&ci=".$_GET['ci']."&min_age=".$_GET['min_age']."&max_age=".$_GET['max_age']."&u=".$_GET['u']); //smarty paging set URL

	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record

	$country = $_GET['co'];
	$city = $_GET['ci'];
	$state = $_GET['s'];
	$gender = $_GET['g'];
	$search_username = $_GET['u'];
	//$_GET['f'] = '1';
	//$_GET['order'] = 'signup';
	//$_GET['type'] = 'desc';

	$arr = funcs::getUsersList($_GET);
	//print_r($arr);
	$data = $arr['data'];

	//check for correct membership type
	/*foreach($data as &$val)
	{
		if($val['type'] != 1 and $val['type'] != 9)
		{
			$end = DBConnect::retrieve_value("SELECT new_paid_until FROM ".TABLE_PAY_LOG." WHERE username='".$val['username']."' AND payment_complete = 1 ORDER BY new_paid_until DESC LIMIT 1");
			if($end && (funcs::dateDiff("-", date("Y-m-d"), $end) <= 0))
			{
				$payment_history = funcs::getPaymentHistory($val['user_id']);
				$val['expired'] = 0;
				$val['end_date'] = $end;
				$val['type'] = $payment_history['new_type'];
			}
			else
			{
				$val['expired'] = 1;
				if($val['type'] != 1)
					$val['type'] = 4;
			}
		}
	}*/
	$countMember = $arr['count'];

	/*$_GET['getAll'] = true;
	$temp = funcs::getUsersList($_GET);
	foreach($temp['data'] as &$member)
	{
		$member = array("username" => $member['username']);
	}
	$_SESSION['list'] = $temp['data'];*/


	//$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_ISACTIVE." = 0 WHERE ".TABLE_MEMBER_USERNAME." = '$user'  AND ".TABLE_MEMBER_STATUS." != 1";
	//DBconnect::execute($sql);

	SmartyPaginate::setTotal($countMember);
	SmartyPaginate::assign($smarty);

	//send data to template//
	$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard", 9 => "StudiAdmin"));
	$smarty->assign('userrec',$data);
	//select template file//
	$smarty->display('admin.tpl');
}
?> 