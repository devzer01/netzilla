<?php
//check permission type//
if(FREE_SMS_ENABLE == 0)
{
	$smarty->assign('text', "No Free SMS service available");
	$smarty->assign('section', 'blank');
	$smarty->display('index.tpl');
}
else
{
// 	$permission_lv = array(1, 2, 3, 8);	//define type permission can open this page.
	$permission_lv = array(1, 2, 3, 4, 8); //jeab edited

	$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));

	if (!funcs::checkmobile($_SESSION['sess_username']))
	{
		if (!$_POST['phone_code'] && !$_POST['phone_number'] )
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
?>