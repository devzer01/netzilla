<?php

class dbo_queue extends dbo {
	
	public function queueEmail($email, $subject, $message, $from = null, $name = null, $text = null)
	{
		$sql = "INSERT INTO global.mail_queue (smtp_host, smtp_user, smtp_password, smtp_port, from_email, from_email_name, to_email, subject, body, flag, created) "
		     . "VALUES (:smtp_host, :smtp_user, :smtp_password, :smtp_port, :from_email, :from_email_name, :to_email, :subject, :body, 0, :created) ";
		
		$sth = $this->dbo->prepare($sql);
		$sth->execute(
				array(
					':smtp_host' => MAIL_REGISTER_HOST,
					':smtp_user' => MAIL_REGISTER_USERNAME,
					':smtp_password' => MAIL_REGISTER_PASSWORD,
					':smtp_port' => MAIL_REGISTER_PORT,
					':from_email' => MAIL_REPLYTO_EMAIL,
					':from_email_name' => MAIL_REPLYTO_NAME,
					':to_email' => $email,
					':subject' => $subject,
					':body' => $message,
					':created' => time()
				)	
			);
	}
	
	public function queueMessage($to, $from, $subject, $message, $type, $mtype, $coins, $gift, $server_id)
	{
		
		$sql = "INSERT INTO global.message_queue (to_id, from_id, subject, message, type, mtype, attachment_coins, gift_id, datetime, server_id) "
			 . "VALUES (:to_id, :from_id, :subject, :message, :type, :mtype, :coins, :gift, NOW(), :server) ";
		$sth = $this->dbo->prepare($sql);
		$sth->execute(array(':to_id' => $to, ':from_id' => $from, ':subject' => $subject, ':message' => $message, ':type' => $type, ':mtype' => $mtype, ':coins' => $coins, ':gift' => $gift, ':server' => $server_id));
		if ($sth->rowCount() == 0) return false;
		return true;
	}
	
}