<?php

function runBonusQueue() {
	switch($_POST['version']) {
		case '2':
			foreach($_POST['username'] as $username) {
					
				$userid = funcs::getUserid($username);
				$recent_bonus = DBConnect::retrieve_value("SELECT 1 FROM coin_bonus WHERE member_id=".$userid." AND status=0 AND vcode_insert_time>NOW() - INTERVAL ".BONUS_AGE." DAY");
					
				if(!$recent_bonus)
				{
					if(($userid != "") && ($userid>0) && ($_POST['coins']>0))
					{
						$code = funcs::addBonus($userid, $_POST['coins']);
							
						if(isset($_POST['send_via_sms']) && ($_POST['send_via_sms']=="1"))
						{
							$mobileno = funcs::getMobileNo($userid);
							if($mobileno != "")
							{
								$sms_msg = str_replace(array("[URL]", "[bonus_code]"), array(URL_WEB, $code), nl2br($_POST['sms_body']));
								sendSMSCode($mobileno, $sms_msg);
								//funcs::queueSMS($mobileno, $sms_msg);
							}
						}
							
						$km_website = funcs::getText($_SESSION['lang'], '$KM_Website');
						$email_subject =  nl2br($_POST['email_subject']);
						$email_body = str_replace(array("[URL_WEB]", "[bonus_code]", "[URL]"), array(URL_WEB, $code, URL_WEB.'#bonusverify'), nl2br($_POST['email_body']));
						$email_body = str_replace('[KM_Website]', $km_website, $email_body);
							
						funcs::sendBonusEmail($username, $email_subject, $email_body);
						funcs::saveLog("bonus", array($username,$_POST['coins'], date("Y-m-d H:i:s")));
					}
					$return['resulttext'] = "Sent bonus to ".$username;
				}
				else
				{
					$return['resulttext'] = $username." has another bonus already.";
				}
			}
			$return['resulttext'] = 'Completed';
			break;
		default:
			$username = $_POST['username'];
			set_time_limit(15);
			$userid = funcs::getUserid($username);
			$recent_bonus = DBConnect::retrieve_value("SELECT 1 FROM coin_bonus WHERE member_id=".$userid." AND status=0 AND vcode_insert_time>NOW() - INTERVAL ".BONUS_AGE." DAY");
			if(!$recent_bonus)
			{
				if(($userid != "") && ($userid>0) && ($_POST['coins']>0))
				{
					$code = funcs::addBonus($userid, $_POST['coins']);
	
					if(isset($_POST['send_via_sms']) && ($_POST['send_via_sms']=="1"))
					{
						$mobileno = funcs::getMobileNo($userid);
						if($mobileno != "")
						{
							$sms_msg = str_replace(array("[URL]", "[bonus_code]"), array(URL_WEB, $code), nl2br($_POST['sms_body']));
							sendSMSCode($mobileno, $sms_msg);
							//funcs::queueSMS($mobileno, $sms_msg);
						}
					}
	
					$km_website = funcs::getText($_SESSION['lang'], '$KM_Website');
					$email_subject =  nl2br($_POST['email_subject']);
					$email_body = str_replace(array("[URL_WEB]", "[bonus_code]", "[URL]"), array(URL_WEB, $code, URL_WEB.'#bonusverify'), nl2br($_POST['email_body']));
					$email_body = str_replace('[KM_Website]', $km_website, $email_body);
	
					funcs::sendBonusEmail($username, $email_subject, $email_body);
					funcs::saveLog("bonus", array($username,$_POST['coins'], date("Y-m-d H:i:s")));
				}
				$return['resulttext'] = "Sent bonus to ".$username;
			}
			else
			{
				$return['resulttext'] = $username." has another bonus already.";
			}
			$return['result'] = 1;
			break;
	}
}