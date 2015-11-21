<?php
//when click submit for send data//
if(isset($_POST['f_email'])){
	if(!empty($_POST['f_email'])){
		//get username, password by email//
		$data = funcs::getNamePass_email($_POST['f_email']);
	
		if($data[TABLE_MEMBER_USERNAME] != '')
		{
			$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
			$message = funcs::getMessageEmail_Forgot($smarty,$data[TABLE_MEMBER_USERNAME], $data[TABLE_MEMBER_PASSWORD]);	//get message
			
			funcs::sendMail($_POST['f_email'], $subject, $message, MAIL_FROM);	//send message to email
			echo "SENT";
		}
		else
		{
			echo funcs::getText($_SESSION['lang'], '$forget_error');
		}
	}
	else{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_require'));
	}
}
else
{
	$smarty->display('forget.tpl');
}
?>