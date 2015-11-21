<?php

function runBonusQueue() {
	foreach($_POST['username'] as $username)
	{
		$userid = funcs::getUserid($username);
		if(($userid != "") && ($userid>0) && ($_POST['coins']>0))
		{
			$code = funcs::addBonus($userid, $_POST['coins']);
	
			if(isset($_POST['send_via_sms']) && ($_POST['send_via_sms']=="1"))
			{
				$mobileno = funcs::getMobileNo($userid);
				if($mobileno != "")
				{
					$sms_msg = str_replace('[URL]', URL_WEB, str_replace('[bonus_code]', $code, nl2br($_POST['sms_body'])));
					sendSMSCode($mobileno, $sms_msg);
				}
			}
	
			$km_website = funcs::getText($_SESSION['lang'], '$KM_Website');
			$email_subject =  nl2br($_POST['email_subject']);
			$email_body = str_replace("[URL_WEB]",URL_WEB, str_replace('[bonus_code]', $code, str_replace('[URL]',URL_WEB.'?action=bonusverify', nl2br($_POST['email_body']))));
			$email_body = str_replace('[KM_Website]', $km_website, $email_body);
	
			funcs::sendBonusEmail($username, $email_subject, $email_body);
			funcs::saveLog("bonus", array($username,$_POST['coins'], date("Y-m-d H:i:s")));
		}
	}
}