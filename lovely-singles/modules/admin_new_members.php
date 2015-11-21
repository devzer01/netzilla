<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(!in_array($_GET['r'], array('today','yesterday','week','month','search')))
{
	header("location: ?action=admin_new_members&r=today");
}

if($_GET['do']=='delete')
{
	$user_id = funcs::getUserid($_GET['username']);
	funcs::deleteProfile($user_id);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit();
}
elseif($_GET['do']=='end_payment')
{
	$user_id = funcs::getUserid($_GET['username']);
	$payment = funcs::getPaymentHistory($user_id);
	funcs::cancelPaymentHistory($payment['id'], $user_id);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit();
}
elseif($_GET['do']=='checkWithChatServer')
{
	$soapserver = SERVER_URL;
	$client = new SoapClient(null, array('location' => $soapserver, 'uri' => "urn://kontaktmarkt"));
	$result = $client->checkIsCustomerInChatServer(4, array($_POST['id']));
	//print_r($result);
	$return = array();
	$return['id'] = $_POST['id'];
	if($result[$_POST['id']]['result']==2)
	{
		$return['message'] = "Completely syncronized";
		$return['span_close'] = 'check';
		$return['span_open'] = '';
	}
	elseif($result[$_POST['id']]['result']==1)
	{
		$return['message'] = "Need to be syncronize";
		$return['span_close'] = 'check';
		$return['span_open'] = 'sync';
	}
	else
	{
		$return['message'] = "No profile in the chat server.";
		$return['span_close'] = 'check';
		$return['span_open'] = 'resend';
	}
	echo json_encode($return);
	exit();
}
elseif($_GET['do']=='checkAllWithChatServer')
{
	switch($_GET['r'])
	{
		case "today":
			$list = DBConnect::row_retrieve_2D_Conv_1D("SELECT id FROM member WHERE fake=0 AND isactive=1 AND DATE(signup_datetime)=DATE(NOW())");
			break;
		case "yesterday":
			$list = DBConnect::row_retrieve_2D_Conv_1D("SELECT id FROM member WHERE fake=0 AND isactive=1 AND DATE(signup_datetime)=DATE(NOW()-INTERVAL 1 DAY)");
			break;
		case "week":
			$list = DBConnect::row_retrieve_2D_Conv_1D("SELECT id FROM member WHERE fake=0 AND isactive=1 AND DATE(signup_datetime)>DATE(NOW()-INTERVAL 1 WEEK)");
			break;
		case "month":
			$list = DBConnect::row_retrieve_2D_Conv_1D("SELECT id FROM member WHERE fake=0 AND isactive=1 AND DATE(signup_datetime)>DATE(NOW()-INTERVAL 1 MONTH)");
			break;
	}
	if(is_array($list) && count($list))
	{
		$soapserver = SERVER_URL;
		$client = new SoapClient(null, array('location' => $soapserver, 'uri' => "urn://kontaktmarkt"));
		$result = $client->checkIsCustomerInChatServer(4, $list);
		//print_r($result);
		$return = array();
		$return['message'] = "	Not in chat => ".$result['result0']."
								In chat, but need to be synchronized => ".$result['result1']."
								In chat, synchronized => ".$result['result2'];
		echo json_encode($return);
	}
	exit();
}
elseif($_GET['do']=='syncWithChatServer')
{
	$soapserver = SERVER_URL;
	$client = new SoapClient(null, array('location' => $soapserver, 'uri' => "urn://kontaktmarkt"));
	$result = $client->syncProfile(4, $_POST['id']);
	$return = array();
	$return['id'] = $_POST['id'];
	if($result)
	{
		$return['message'] = "Done";
		$return['span_close'] = 'check';
	}
	else
	{
		$return['message'] = "Failed. ".print_r($result,true);
	}
	echo json_encode($return);
	exit();
}
elseif($_GET['do']=='resendNewRegistrationMessage')
{
	$to_id = funcs::randomStartProfile($_POST['id']);
	funcs::sendMessage($_POST['id'],$to_id,'New registration','New registration',3,true);
	$return = array();
	$return['id'] = $_POST['id'];
	$return['message'] = "Sent.";
	$return['span_close'] = 'resend';
	echo json_encode($return);
	exit();
}

if(!isset($_GET['next']))
	SmartyPaginate::reset();

//smarty paging
SmartyPaginate::connect();
SmartyPaginate::setLimit(30); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show
SmartyPaginate::setUrl("?action=admin_new_members&r=".$_GET['r']."&order=".$_GET['order']."&type=".$_GET['type']); //smarty paging set URL

// [Start] Check date format for newest members search page
if($_GET['r']=='search')
{
	if(($_GET['u'] == '') && ($_GET['l'] == '') && ($_GET['e'] == '') && ($_GET['mt'] == ''))
	{
		$redirect = 0;
		$const_number = 2454310;
		if($_GET['from'] == '')
		{
			$_GET['from'] = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$redirect = 1;
		}
		elseif(funcs::dateDiff('-', date("Y-m-d"), $_GET['from']) == $const_number)
		{
			$_GET['from'] = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")));
			$redirect = 1;
		}

		if($_GET['to'] == '')
		{
			$_GET['to'] = date("Y-m-d");
			$redirect = 1;
		}
		elseif(funcs::dateDiff('-', date("Y-m-d"), $_GET['to']) == $const_number)
		{
			$_GET['to'] = date("Y-m-d");
			$redirect = 1;
		}

		// If from > to. Do swap.
		if(!(funcs::dateDiff('-', $_GET['to'], $_GET['from']) >= 0))
		{
			$temp = $_GET['from'];
			$_GET['from'] = $_GET['to'];
			$_GET['to'] = $temp;
		}

		if($redirect == 1)
		{
			header("location: ?action=admin_new_members&r=search&u=".$_GET['u']."&l=".$_GET['l']."&e=".$_GET['e']."&order=".$_GET['order']."&type=".$_GET['type']."&from=".$_GET['from']."&to=".$_GET['to']."&mt=".$_GET['mt']);
			exit();
		}
	}
	else
	{
		$_GET['from'] = '0000-00-00';
		$_GET['to'] = date('Y')."-".date('m').'-'.date('d');
	}
	SmartyPaginate::setUrl("?action=admin_new_members&r=search&u=".$_GET['u']."&l=".$_GET['l']."&e=".$_GET['e']."&from=".$_GET['from']."&to=".$_GET['to']."&order=".$_GET['order']."&type=".$_GET['type']."&mt=".$_GET['mt']); //smarty paging set URL
}
// [END] Check date format

//Get only fake = 0
$_GET['f'] = '0';
$_GET['showNotActivated'] = '1';

$arr = funcs::getUsersList($_GET);
$data = $arr['data'];

//check for correct membership type
/*foreach($data as &$val)
{
	if($val['type'] != 1)
	{
		$end = DBConnect::retrieve_value("SELECT end_date FROM history WHERE user_id=".$val['user_id']." ORDER BY end_date DESC LIMIT 1");
		if($end && (funcs::dateDiff("-", date("Y-m-d"), $end) <= 0))
		{
			$val['expired'] = 0;
			$val['end_date'] = $end;
			$val['type'] = DBConnect::retrieve_value("SELECT membership_type FROM history WHERE user_id=".$val['user_id']." ORDER BY end_date DESC LIMIT 1");
		}
		else
		{
			$val['expired'] = 1;
			if($val['type'] != 1)
				$val['type'] = 4;
		}
	}
}*/

/*foreach($data as &$val)
{
	$payment = funcs::getPaymentHistory($val['user_id']);
	if($payment['cancelled'] == 1)
		$val['payment_cancelled'] = 1;
}*/

$countMember = $arr['count'];
SmartyPaginate::setTotal($countMember);
SmartyPaginate::assign($smarty);

$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard"));
$smarty->assign('userrec',$data);
$smarty->assign('countMember',$countMember);

$smarty->display('admin.tpl');
?>