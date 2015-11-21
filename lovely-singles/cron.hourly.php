<?php
require_once('classes/top.class.php');

/////////////////////////////////////////////////////////////////////////////////////////////
// send the reminder email if the user is not read the message in the inbox after 6 hours.
/////////////////////////////////////////////////////////////////////////////////////////////

$list = DBConnect::assoc_query_2D("
									SELECT i.*, m.username, m.password, m.validation_code AS code, m.email, 
										(SELECT CASE WHEN id = 1 THEN 'System Admin' ELSE username END AS username FROM member WHERE id = i.from_id LIMIT 0,1) AS sentfrom 
									FROM message_inbox i 
									LEFT JOIN member m ON i.to_id=m.id 
									WHERE i.status = 0 
									AND m.isactive = 1 
									AND i.reminded = 0 
									AND m.username IS NOT NULL 
									AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(i.datetime) > (60*60*9) 
									GROUP BY i.to_id ORDER BY i.id DESC
								 ");

//$start = 1;
foreach($list as $msg)
{
	/**
	 * SENDING EMAIL TO USER
	 **/

	//$message = funcs::getMessageEmail_membership(&$smarty, $msg['username']);
	$sentfrom = (isset($msg['sentfrom']))? $msg['sentfrom']:'Somebody';
	$smarty->assign('username', $msg['username']);
	$smarty->assign('password', $msg['password']);
	$smarty->assign('code', $msg['code']);
	$smarty->assign('sentfrom', $sentfrom);
	$smarty->assign('url_web', URL_WEB);
	//$smarty->assign('url_action', 'viewmessage::type:inbox::id:'.$msg['id'].'::from:message::username:'.$sentfrom);
	$smarty->assign('url_action', 'mymessage::type:inbox');
	$message = $smarty->fetch('unread_remind_message.tpl');
	funcs::sendMail($msg['email'], funcs::getText($_SESSION['lang'], '$email_reminder_subject'), $message, self_name." <".MAIL_FROM_REGISTER.">");

	/*echo $message;
	echo "<br/>";
	echo "<br/>";
	echo $start ." : ".$msg['username'];
	echo "<br/>";
	echo "<pre>";
	print_r($msg);
	echo "</pre>";
	$start++;*/
	
	/**
	 * SET reminded FIELD TO 1
	 **/
	$sql = "UPDATE message_inbox SET reminded = 1 WHERE to_id = '".$msg['to_id']."' AND UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(datetime) > (60*60*9)";
	DBconnect::execute_q($sql);
}
echo "Done";
?>