<?php

if (count($_POST['email']) == 0) exit;

foreach ($_POST['email'] as $email) {
	
	//insert into member_invite table
	DBconnect::execute_q("INSERT INTO member_invite (member_id, email_address, created_date) VALUES (" . $_SESSION['sess_id'] . ", '" . mysql_real_escape_string($email) . "', NOW())");

	$receiver_token = md5($_SESSION['sess_id'] . $email);
	//send email
	
	$sql = "SELECT username FROM member WHERE id = " . $_SESSION['sess_id'];
	$customer_name = DBconnect::retrieve_value($sql);
	
	$message = funcs::getInvitationEmail($smarty, $receiver_token, $customer_name);
	
	funcs::sendMailRegister($email, $customer_name . " hat dich eingeladen zu " . URL_WEB . ", du kannst ihn noch heute dort treffen!", $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">");
}

header("Content-Type: application/json");
echo json_encode(array());