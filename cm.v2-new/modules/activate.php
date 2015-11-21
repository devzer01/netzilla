<?php
	$username = $_GET['username'];
	$password = $_GET['password'];
	$code = $_GET['code'];
	if(isset($_GET['adv']))
		$adv = $_GET['adv'];
	else
		$adv = 0;
		
	if(!(funcs::chekActivateMember($username, $password, $code)))
	{
		//$_SESSION['registered'] = 1; //session after registration for GA in footer.tpl
		$_SESSION['sess_mobile_ver'] = true; //session for complete-profile.tpl (progress bar)
		if(funcs::activateMember($username, $password, $code, $adv))
		{
			$_SESSION['mobileverify_redirect'] = ".";
			$_SESSION['registerred_email'] = funcs::getEmail(funcs::getUserid($username));
			funcs::loginSite($username, $password);	//automatic login

			$_SESSION['show_mobileverify'] = 1;
			$_SESSION['registration_completed_redirect']="?action=profile#editprofile";
			header('location:?action=profile#editprofile');
			exit;
		}
		else {
			header("location:?action=profile#editprofile");
			exit;
		}
	}
	else if(funcs::chekActivateMember($username, $password, $code))	//check activate complete?
	{
		funcs::loginSite($username, $password);	//automatic login
		header("location:?action=profile#editprofile");
		exit;
	}
	else
	{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_alert'));	//show activate error
		//select template file//
		$smarty->display('index.tpl');
	}