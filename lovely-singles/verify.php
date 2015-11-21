<?php
$allow_guest = false;
require_once('classes/top.class.php');

$verified = DBConnect::retrieve_value("SELECT mobile_number FROM ".TABLE_USER." WHERE id=".$_SESSION['master_userid']);
if($verified)
{
	$smarty->assign('messages','Deine Handy Nummer ist bereits registriert.');
	$smarty->assign('check_verify',2);
}	
else
{
	if(isset($_POST['mobile_number']) && ($_POST['mobile_number']!=''))
	{
		
			// Do SMS Function here.
			$smarty->assign('check_verify',1);
			$smarty->assign('mobnr_temp',$_POST['mobile_number']);
			// Store mobile phone number in database.
			$verify_code = substr(time(), 6);
			$msg = "Herzlich Willkommen bei 5Comy! Dein Freischaltcode lautet: ".$verify_code." Einfach eingeben und dann viel Spass auf wap.5comy.de";
			sendSMS_BULK($_POST['mobile_number'], "5Comy", $msg,0);
			DBConnect::execute("UPDATE ".TABLE_USER." SET verify_code='".$verify_code."'  WHERE id=".$_SESSION['master_userid']);
			$smarty->assign('messages','Eine Bestätigungs-SMS ist an dein Handy unterwegs');
	}
	else
	{
		if(isset($_POST['code']) && ($_POST['code']!='')){	
			$code_db = DBConnect::retrieve_value("SELECT verify_code FROM ".TABLE_USER." WHERE id=".$_SESSION['master_userid']);
			if($_POST['code'] == $code_db){
				DBConnect::execute("UPDATE ".TABLE_USER." SET mobile_number='".$_POST['mobnr_temp']."'  WHERE id=".$_SESSION['master_userid']);
				//For validation log
				Mobile_Help::saveLog("validation", array(date('Y/m/d H:i:s'), $_SESSION['master_userid'], $_POST['mobile_number']));
				$smarty->assign('messages','Deine Handynummer wurde best&auml;tigt.');
				$smarty->assign('check_verify',2);
			}
			else
			{
				$smarty->assign('messages','Fehlerhafter Freischaltcode');
				$smarty->assign('mobnr_temp',$_POST['mobnr_temp']);
				$smarty->assign('check_verify',1);
			}
		}

	}
}
$smarty->display('verify.tpl');
?>