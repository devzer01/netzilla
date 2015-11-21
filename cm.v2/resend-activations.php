<?php
require_once('classes/top.class.php');
error_reporting(E_ALL);

//$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND signup_datetime < NOW() - INTERVAL 1 hour AND signup_datetime > NOW() - INTERVAL 2 hour");

$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND signup_datetime < NOW() - INTERVAL 3 hour AND DATE(signup_datetime) >= NOW()-INTERVAL 3 WEEK AND resent_activation_datetime IS NULL ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);

	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], $subject, $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 1 WHERE email='".$data['email']."'");
}

$list2 = DBConnect::assoc_query_2D("SELECT l.user_id, m.username, m.email, l.purchase_datetime FROM purchases_log l LEFT JOIN member m ON l.user_id=m.id WHERE m.isactive=1 AND purchase_datetime < NOW() - INTERVAL 3 hour AND DATE(purchase_datetime) >= NOW()-INTERVAL 2 DAY AND purchase_finished_date = '0000-00-00 00:00:00' AND reminder_email_datetime IS NULL GROUP BY user_id  ORDER BY purchase_datetime DESC LIMIT 30");

foreach($list2 as $data)
{
	if($data["email"]!="")
	{
		$subject = funcs::getText($_SESSION['lang'], '$email_payment_reminder_subject');	//get subject message
		$username = funcs::check_input($data['username']);

		$smarty->assign('username', $username);
		$smarty->assign('url_web', URL_WEB);
		$message = $smarty->fetch('email_payment_reminder.tpl');
		$message_text = $smarty->fetch('email_payment_reminder_text.tpl');

		if(funcs::sendMail($data["email"], $subject, $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">"))	//send message to email
		{
			$message_text = nl2br($message_text);
			$sql = "INSERT INTO ".TABLE_MESSAGE_INBOX."
					SET ".TABLE_MESSAGE_INBOX_TO."=".$data['user_id'].",
					".TABLE_MESSAGE_INBOX_FROM."=1,
					".TABLE_MESSAGE_INBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_INBOX_MESSAGE."='".$message_text."',
					attachment_coins='0',
					".TABLE_MESSAGE_INBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);
			
			$sql = "INSERT INTO ".TABLE_MESSAGE_OUTBOX."
					SET ".TABLE_MESSAGE_OUTBOX_TO."=".$data['user_id'].",
					".TABLE_MESSAGE_OUTBOX_FROM."=1,
					".TABLE_MESSAGE_OUTBOX_SUBJECT."='".$subject."',
					".TABLE_MESSAGE_OUTBOX_MESSAGE."='".$message_text."',
					attachment_coins='0',
					".TABLE_MESSAGE_OUTBOX_DATETIME."='".funcs::getDateTime()."'";
			DBconnect::execute_q($sql);
			
			DBconnect::execute_q($sql= "UPDATE member SET last_action_to = NOW() WHERE id = ".$data['user_id']);
			DBconnect::execute_q("UPDATE member SET last_action_from = NOW() WHERE id = 1");

			echo "Sent payment reminder email to ".$data['username']."<br/>";
		}
		else
		{
			echo "Sending payment reminder email to ".$data['username']." failed.<br/>";
		}
		DBConnect::execute_q("UPDATE purchases_log SET reminder_email_datetime=NOW() WHERE user_id='".$data['user_id']."' AND purchase_finished_date='0000-00-00 00:00:00'");
	}
}


//24 hours later
$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND TIME_TO_SEC(TIMEDIFF(NOW(), signup_datetime)) > (60 * 60 * 24) AND reg_reminder_count = 1 ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);

	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], $subject, $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 2 WHERE email='".$data['email']."'");
}

//7 days
$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND TIME_TO_SEC(TIMEDIFF(NOW(), signup_datetime)) > (60 * 60 * 24 * 7) AND reg_reminder_count = 2 ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);

	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], $subject, $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 3 WHERE email='".$data['email']."'");
}

//30 days
$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND TIME_TO_SEC(TIMEDIFF(NOW(), signup_datetime)) > (60 * 60 * 24 * 30) AND reg_reminder_count = 3 ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);

	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], $subject, $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 4 WHERE email='".$data['email']."'");
}

