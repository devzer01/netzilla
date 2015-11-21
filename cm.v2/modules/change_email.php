<?php
//when click submit for send data//
if(isset($_POST['email']))
{
	if(!empty($_POST['email']))
	{
		if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			if(funcs::isEmail($_POST['email'])===0)
			{
				$username = DBConnect::retrieve_value("SELECT username FROM member WHERE email='".$_SESSION['registerred_email']."' AND isactive=0 AND isactive_datetime IS NULL");
			
				if($username)
				{
					$random = funcs::randomPassword(6);
					DBConnect::execute_q("UPDATE member SET email='".$_POST['email']."', validation_code='".$random."' WHERE username='".$username."'");

					$message = funcs::getMessageEmail_membership($smarty, $username);
					funcs::sendMailRegister($_POST['email'], funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">");

					$_SESSION['registerred_email'] = $_POST['email'];

					echo "CHANGED";
				}
				else
				{
					//echo funcs::getText($_SESSION['lang'], '$change_email_error');
					echo "ERROR";
				}
			}
			else
			{
				echo "DUPLICATED";
			}
		}
		else
		{
			echo "INVALID EMAIL";
		}
	}
	else{
		echo funcs::getText($_SESSION['lang'], '$err_require');
	}
}
else
{
	$smarty->display('change_email.tpl');
}
?>