<?php

$action = $_GET['action'];
if(isset($_GET['section']))
	$section = $_GET['section'];
else
	$section = '';
$username = $_SESSION['sess_username'];
$smarty->assign('message',$_GET['msg']);


if ($section == '')  {

	$smarty->assign('username',$_SESSION['sess_username']);
	$smarty->assign('password',$_SESSION['sess_password']);

// 	$permission_lv = array(1, 2, 3, 8);	//define type permission can open this page.
	$permission_lv = array(1, 4, 8, 9); //jeab edited

	$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));

	if (!funcs::checkmobile($_SESSION['sess_username']))
	{
		if (!$_POST['phone_code'] && !$_POST['phone_number'])
		{
			$section = 'validation';
			$smarty->assign("section",$section);
			$smarty->display('index.tpl');
		}
		else
		{
			$_POST['mobileno'] = $_POST['phone_code'].trim($_POST['phone_number']);
			if(DBconnect::retrieve_value("SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE mobileno = '".$_POST['mobileno']."' AND username != '".$_SESSION['sess_username']."' AND isactive='1'") > 0)
			{
				echo $_POST['mobileno'];
				$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$register1'));
				$section = 'validation';
			}
			else
			{
				DBConnect::execute("UPDATE member SET mobileno='".$_POST['mobileno']."', vcode_mobile='".funcs::randomPassword(6)."', validated='0' WHERE username = '".$_SESSION['sess_username']."'");
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
				sendSMSCode($_POST['mobileno'], $smsmsg);
				$smarty->assign("mobnr",$_POST['mobileno']);
				$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$sms_validcode1'));
				$section = 'validCode';
			}
			$smarty->assign("section",$section);
			$smarty->display('index.tpl');
		}
	}
	else
	{
		if(!funcs::checkvalidated($_SESSION['sess_username']))
		{
			if ($_POST['field_code'] != '')
			{
				// This line is hacked to turn off validation mobile number stuff
				//$_POST['field_code'] = DBConnect::retrieve_value("SELECT vcode_mobile FROM member WHERE username='".$_SESSION['sess_username']."'");

				if(funcs::checkcodeVal($_SESSION['sess_username'], $_POST['field_code']))
				{
					$section = 'okay';
					$smarty->assign("section",$section);
					$smarty->display('index.tpl');
				}
				else
				{
					$smarty->assign("mobnr",$_POST['mobnr']);
					$section = 'validCode';
					$smarty->assign("section",$section);
					$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$sms_validcode2'));
					$smarty->display('index.tpl');
				}
			}
			elseif(($_POST['phone_code'] != '') && (trim($_POST['phone_number']) != ''))
			{
				$_POST['mobileno'] = $_POST['phone_code'].trim($_POST['phone_number']);
				if(DBconnect::retrieve_value("SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE mobileno = '".$_POST['mobileno']."' AND username != '".$_SESSION['sess_username']."' AND isactive='1'") > 0)
				{
					$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$register1'));
				}
				else
				{
					DBConnect::execute("UPDATE member SET mobileno='".$_POST['mobileno']."', vcode_mobile='".funcs::randomPassword(6)."', validated='0' WHERE username = '".$_SESSION['sess_username']."'");
					$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
					sendSMSCode($_POST['mobileno'], $smsmsg);
					$smarty->assign("mobnr",$_POST['mobileno']);
					$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$sms_validcode3'));
				}
				$section = 'validCode';
				$smarty->assign("section",$section);
				$smarty->display('index.tpl');
			}
			else
			{
				$section = 'validCode';
				$smarty->assign("section",$section);
				$smarty->display('index.tpl');
			}
		}
		else
		{
			if(strtolower($_GET['action']) != "sms")
			header("location: ?action=SMS");
			funcs::checkPermission(&$smarty, $permission_lv); //check permission
			$gesendet =  funcs::getFreesms($_SESSION['sess_username']);
			//print_r($_SESSION);
			$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));
			$smarty->assign('min_Sms_length', SMS_MIN_CHAR);
			$smarty->assign('max_Sms_length', SMS_MAX_CHAR);
			if(isset($_REQUEST['message']) && ($_REQUEST['message']!=''))
			{
				if((3-$gesendet) > 0)
				{
					funcs::sendFreesms($_SESSION['sess_username'],$_REQUEST['phone_code'].$_REQUEST['phone_number'], $_REQUEST['message']);

					funcs::saveLog("free_sms", array($_SESSION['sess_id'], $_REQUEST['phone_code'].$_REQUEST['phone_number'],  date("Y-m-d H:i:s")));

					$gesendet++;
					$smarty->assign('receiver',$_REQUEST['phone_code'].$_REQUEST['phone_number']);

					$sql = "INSERT INTO sms_log
							SET send_date = NOW(),
							userid = ".$_SESSION['sess_id'];

					DBconnect::execute($sql);
				}
			}
			$smarty->assign('freesms', (3-$gesendet));
			$section = 'sendSMS';
			$smarty->assign("section",$section);
			$smarty->display('index.tpl');
		}
	}
}

elseif ($section == 'send')  {

	$forname = $_POST['forname'];
	$surname = $_POST['surname'];
	$city = $_POST['city'];
	$street = $_POST['street'];
	$phone_code = $_POST['phone_code'];
	$phone_number = $_POST['phone_number'];
	$password = $_POST['password'];

	$real_name = $forname.' '.$surname;

	if(($phone_code != '') && (trim($phone_number) != ''))
	{
		$mobileno = $phone_code.trim($phone_number);
	}
	$gueltig_bis = date("Y-m-d H:i:s",time()+3*24*60*60);


	//  ... EXIST USER ...

	//	Eintrag 'member' (table)
	//	*****************************************************************************************
	$sql = "SELECT id FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_USERNAME."='$username' LIMIT 1";
	$uid = DBconnect::retrieve_value($sql);

	if($mobileno != '')
	{
		if(DBconnect::retrieve_value("SELECT COUNT(*) FROM ".TABLE_MEMBER." WHERE mobileno = '".$mobileno."' AND username != '".$username."' AND isactive='1'") > 0)
		{
			$msg = funcs::getText($_SESSION['lang'], '$register1');
			$smarty->assign('msgAlert', $msg);
			header("location: ?action=validCode2&msg=".$msg);	//go to first page
			break;
		}
		else
		{
			DBConnect::execute("UPDATE member SET mobileno='".$mobileno."', vcode_mobile='".funcs::randomPassword(6)."', validated='0' WHERE username = '".$username."'");
			$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
			sendSMSCode($_POST['mobileno'], $smsmsg);
			$smarty->assign("mobnr",$mobileno);
			$smarty->assign('msgAlert', funcs::getText($_SESSION['lang'], '$sms_validcode3'));
		}
	}
	
	$sql = "UPDATE ".TABLE_MEMBER." SET "."payment"."='$gueltig_bis',
										"."forname"."='$forname',
										"."surname"."='$surname'
							  	  WHERE ".TABLE_MEMBER_ID."='$uid'";
	DBconnect::execute($sql);


	//	Eintrag 'payment_log' (table)
	//	*****************************************************************************************
	$membership = 3;
	$rate = 1;
	$paid_via = 3;
	$payment_complete = 1;
	$today = date("Y-m-d");
	
	funcs::insertpayment($uid,$membership,$rate,$paid_via, $payment_complete);

	$sql = "UPDATE ".TABLE_PAY_LOG." SET "."real_name"."='$real_name',
									 	 "."real_street"."='$street',
										 "."real_city"."='$city',
										 "."sum_paid"."='0',
										 "."cancelled"."='1',
										 "."cancelled_date"."='$today'
							   	   WHERE "."username"."='$username'";
	DBconnect::execute($sql);

	$sql = "UPDATE ".TABLE_MEMBER." SET "."type"."='$membership',
										"."signup_datetime=now(),
										"."advertise_regist='2'
							  	  WHERE ".TABLE_MEMBER_ID."='$uid'";
	DBconnect::execute($sql);

	header("location: .");	//go to first page
}

else  {

	echo '*** not available ***';
}

?>