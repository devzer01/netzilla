<?php
//when click submit for send data//
if(isset($_POST['f_email'])){
	if(!empty($_POST['f_email'])){
		//get username, password by email//
		$data = funcs::getResendActivation_email(trim($_POST['f_email']));

		if($data[TABLE_MEMBER_USERNAME] != '')
		{
			if(($data['isactive']=="0") && ($data['signin_datetime']== '0000-00-00 00:00:00'))
			{
				$subject = funcs::getText($_SESSION['lang'], '$email_testmember_subject');	//get subject message
				$message = funcs::getMessageEmail_membership($smarty, $data[TABLE_MEMBER_USERNAME]);

				funcs::sendMail($_POST['f_email'], $subject, $message, MAIL_FROM);	//send message to email
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$complete'));	//send complete alert to template
			}
			else
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$resend_activation_error'));
		}
		else
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$email_not_in_database'));	//send error alert to template
	}
	else{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_require'));
	}
}
//select template file//
$smarty->display('index.tpl');
?>