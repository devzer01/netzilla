<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$proc = isset($_GET['proc'])?trim($_GET['proc']):"";
$user = isset($_GET['user'])?trim($_GET['user']):"";

$_SERVER['HTTP_REFERER'] = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:".";

if(($proc=='del')&&($_SESSION['sess_permission']==1))
{
	$userid = DBconnect::retrieve_value("SELECT id FROM member WHERE username = '".$user."'");
	$files = glob(UPLOAD_DIR.$userid."/foto/*");
	foreach($files as $file)
	{
		if(is_file($file))
			unlink($file);
	}
	if(is_dir(UPLOAD_DIR.$userid."/foto"))
		rmdir(UPLOAD_DIR.$userid."/foto");

	$files = glob(UPLOAD_DIR.$userid."/*");
	foreach($files as $file)
	{
		if(is_file($file))
			unlink($file);
	}
	if(is_dir(UPLOAD_DIR.$userid))
		rmdir(UPLOAD_DIR.$userid);

	DBconnect::execute("DELETE FROM admin_message_inbox WHERE from_id = '$userid'");
	DBconnect::execute("DELETE FROM admin_message_outbox WHERE to_id = '$userid'");
	DBconnect::execute("DELETE FROM message_inbox WHERE from_id = '$userid'");
	DBconnect::execute("DELETE FROM message_inbox WHERE to_id = '$userid'");
	DBconnect::execute("DELETE FROM message_outbox WHERE from_id = '$userid'");
	DBconnect::execute("DELETE FROM message_outbox WHERE to_id = '$userid'");
	DBconnect::execute("DELETE FROM favorite WHERE parent_id = '$userid'");
	DBconnect::execute("DELETE FROM favorite WHERE child_id = '$userid'");
	DBconnect::execute("DELETE FROM fotoalbum WHERE userid = '$userid'");
	DBconnect::execute("DELETE FROM delete_account WHERE userid = '$userid'");
	DBconnect::execute("DELETE FROM coin_log WHERE member_id = '$userid'");
	DBconnect::execute("DELETE FROM coin_log WHERE send_to = '$userid'");
	DBconnect::execute("DELETE FROM sms_trace WHERE from_id = '$userid'");
	DBconnect::execute("DELETE FROM warning_sms WHERE userid = '$userid'");
	DBconnect::execute("DELETE FROM purchases_log WHERE userid = '$userid'");
	DBconnect::execute("DELETE FROM member WHERE id = '$userid'");
	if(url_exists(SERVER_URL))
	{
		$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
		$result = $client->deleteProfile(array('profileID'=>$userid,'serverID'=>SERVER_ID));
	}
	
	funcs::logProfileAction($userid,3);
	
	#Pakin Change this process
	funcs::NewSorting($user);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='block') && ($_SESSION['sess_permission']==1))
{ 
	$sql = "SELECT id FROM member WHERE username = '".$user."'";
	$userid = DBconnect::retrieve_value($sql);
	
	$sql = "UPDATE member SET isactive=0 WHERE ".TABLE_MEMBER_USERNAME." = '$user'";
	DBconnect::execute($sql);

	$client = new SoapClient(null, array('location' => SERVER_URL, 'uri' => "urn://kontaktmarkt"));
	$result = $client->deleteProfile(array('profileID'=>$userid,'serverID'=>SERVER_ID));
	
	funcs::logProfileAction($userid,4);
	
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='resetphone') && ($_SESSION['sess_permission']==1))
{
	DBConnect::execute("UPDATE member set vcode_mobile_insert_time=0, vcode_mobile='', vcode_count=0, waitver_mobileno='', mobileno='' WHERE username='".$user."'");
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(($proc=='sendcoins') && ($_SESSION['sess_permission']==1))
{
	if(is_numeric($_GET['coins']) && ($_GET['coins']>0))
	{
		DBConnect::execute("UPDATE member set coin=coin+".$_GET['coins']." WHERE username='".$user."'");
		$_SESSION['admin_manageuser_error'] = "Sent ".$_GET['coins']." coins to ".$user.".";

		$user_id = DBConnect::retrieve_value("SELECT id FROM member WHERE username = '".$user."'");
		$coinVal = funcs::checkCoin($_SESSION['sess_username']);

		DBconnect::execute("INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('".$_SESSION['sess_id']."','".$user_id."','send coins',".$_GET['coins'].",".$coinVal.", NOW())");
	}
	else
	{
		$_SESSION['admin_manageuser_error'] = "Please enter correct value.";
	}
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
elseif($proc=='getProfile')
{
	if(isset($_GET['username']) && ($_GET['username'] != ""))
	{
		//$_GET['username'] = htmlentities($_GET['username']);
		$sql = "SELECT * FROM member WHERE username='".$_GET['username']."'";
		$profile = DBConnect::assoc_query_1D($sql);

		if(!$profile)
		{
			$_GET['username'] = htmlentities(utf8_decode($_GET['username']));
			$sql = "SELECT * FROM member WHERE username='".$_GET['username']."'";
			$profile = DBConnect::assoc_query_1D($sql);
		}

		if($profile)
		{
			if((($profile['fake']==0) && ($_SESSION['sess_permission']==1)) || ($profile['fake']==1))
			{
				list($profile['year'], $profile['month'], $profile['date']) = explode("-", $profile['birthday']);
				$smarty->assign('username', $_GET['username']);
				$smarty->assign('date', funcs::getRangeAge(1,31));
				$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
				$smarty->assign('year', funcs::getYear());
				$smarty->assign("profile", $profile);
				echo $smarty->fetch("admin_manageuser_edit.tpl");
			}
			else
			{
				echo "<div style='text-align: center'><h1 style='color:red'>Permission denied.</h1></div>";
			}
		}
		else
		{
		}
	}
	exit;
}
elseif(($proc=='saveProfile'))
{
	if(isset($_POST['id']) && ($_POST['id'] != ""))
	{
		$id = $_POST['id'];
		$sql = "SELECT * FROM member WHERE id='".$id."'";
		$profile = DBConnect::assoc_query_1D($sql);

		if($profile)
		{
			if((($profile['fake']==0) && ($_SESSION['sess_permission']==1)) || ($profile['fake']==1))
			{
				$_POST['birthday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
				unset($_POST['year']);
				unset($_POST['month']);
				unset($_POST['date']);
				unset($_POST['id']);
				unset($_POST['username']);
				unset($_POST['email']);
				$_POST['description'] = addslashes($_POST['description']);

				$colnames = array_flip(DBconnect::get_col_names("member"));
				$member_post = array_intersect_key($_POST, $colnames);

				DBconnect::update_1D_row_with_1D_array("member", $member_post, "id", $id);
			}
			else
			{
				echo "<div style='text-align: center'><h1 style='color:red'>Permission denied.</h1></div>";
				exit;
			}
		}
	}
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
else
{
	$_GET['action']=isset($_GET['action'])?trim($_GET['action']):"";
	$_GET['order']=isset($_GET['order'])?trim($_GET['order']):"";
	$_GET['f']=isset($_GET['f'])?trim($_GET['f']):"";
	$_GET['g']=isset($_GET['g'])?trim($_GET['g']):"";
	$_GET['lg']=isset($_GET['lg'])?trim($_GET['lg']):"";
	$_GET['co']=isset($_GET['co'])?trim($_GET['co']):"";
	$_GET['s']=isset($_GET['s'])?trim($_GET['s']):"";
	$_GET['ci']=isset($_GET['ci'])?trim($_GET['ci']):"";
	$_GET['min_age']=isset($_GET['min_age'])?trim($_GET['min_age']):"";
	$_GET['max_age']=isset($_GET['max_age'])?trim($_GET['max_age']):"";
	$_GET['u']=isset($_GET['u'])?trim($_GET['u']):"";

	if($_SESSION['sess_permission']!=1)
	{
		$_GET['f']="1";
	}

	//smarty paging
	SmartyPaginate::connect();
	SmartyPaginate::setLimit(20); //smarty paging set records per page
	SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show
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
	if(isset($_SESSION['admin_manageuser_error']) && ($_SESSION['admin_manageuser_error']!=""))
	{
		$smarty->assign('admin_manageuser_error', $_SESSION['admin_manageuser_error']);
		unset($_SESSION['admin_manageuser_error']);
	}
	$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard", 9 => "StudiAdmin"));
	$smarty->assign('userrec',$data);
	//select template file//
	$smarty->display('admin.tpl');
}

function url_exists($url) {
    $h = get_headers($url);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
    return ($status[1] == 200);
}
?> 