<?php
require_once('classes/top.class.php');

//$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND signup_datetime < NOW() - INTERVAL 1 hour AND signup_datetime > NOW() - INTERVAL 2 hour");

$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND signup_datetime < NOW() - INTERVAL 3 hour AND DATE(signup_datetime) >= NOW()-INTERVAL 3 WEEK AND resent_activation_datetime IS NULL ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);
	
	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], funcs::getText('ger', '$email_testmember_subject'), $message, "Lovely-Singles.com <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 1 WHERE email='".$data['email']."'");
}

//24 hours later
$list = DBConnect::assoc_query_2D("SELECT username, password, email FROM member WHERE isactive=0 AND isactive_datetime IS NULL AND TIME_TO_SEC(TIMEDIFF(NOW(), signup_datetime)) > (60 * 60 * 24) AND reg_reminder_count = 1 ORDER BY signup_datetime DESC LIMIT 30");

foreach($list as $data)
{
	$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
	$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME], true);
	$message_text = funcs::getMessageEmail_membershipText($smarty, $data[TABLE_MEMBER_USERNAME], true);
	
	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], funcs::getText('ger', '$email_testmember_subject'), $message, "Lovely-Singles.com <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
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
	
	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], funcs::getText('ger', '$email_testmember_subject'), $message, "Lovely-Singles.com <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
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
	
	if(funcs::sendMailRegister($data[TABLE_MEMBER_EMAIL], funcs::getText('ger', '$email_testmember_subject'), $message, "Lovely-Singles.com <".MAIL_FROM_REGISTER.">", $data['username'], $message_text))	//send message to email
	{
		echo "Resent activation email to ".$data['email']."<br/>";
	}
	else
	{
		echo "Sending email to ".$data['email']." failed.<br/>";
	}
	DBConnect::execute_q("UPDATE member SET resent_activation_datetime=NOW(), reg_reminder_count = 4 WHERE email='".$data['email']."'");
}