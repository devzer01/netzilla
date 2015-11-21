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
			$_SESSION['mobileverify_redirect'] = "?action=editprofile";
			funcs::loginSite($username, $password);	//automatic login
			if(funcs::getCountryCode(DBConnect::retrieve_value("SELECT country FROM member WHERE username='".$username."'")))
			{
				$_SESSION['registration_completed_redirect']="?action=incompleteinfo_skip";
				header('location:?action=registration_completed');
				exit();
			}
			else
			{
				$_SESSION['registration_completed_redirect']=".";
				header('location:?action=registration_completed');
				exit();
			}
		}
		else
		{
			header("location: .");
			exit();
		}
	}
	/*else if(funcs::checkInCompleteInfo($username, $password, $code))
	{
		//funcs::activateMember($username, $password, $code, $adv);
		//funcs::loginSite($username, $password);	//automatic login
		//funcs::logoutSite();
		//echo "1";
		header('Location:?action=incompleteinfo');
		exit();
	}
	else if(funcs::checkMobileVerify($username, $password, $code))
	{
		//funcs::loginSite($username, $password);	//automatic login
		//funcs::logoutSite();
		//echo "2";
		header('Location:?action=mobileverify');
		exit();
	}*/
	else if(funcs::chekActivateMember($username, $password, $code))	//check activate complete?
	{
		//funcs::logoutSite();
		funcs::loginSite($username, $password);	//automatic login
		//echo "4";
		
		if(isset($_GET['url_action']))
		{
			//echo $_GET['url_action'];
			//echo "<br/>";
			$new_url = str_replace('::','&',$_GET['url_action']);
			$new_url = str_replace(':','=',$new_url);
			//echo $new_url;
			header("location:?action=".$new_url);	//go to first page
		}
		else
		{
			header("location: .");	//go to first page
		}
		exit();
	}
	else
	{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_alert'));	//show activate error
		//select template file//
		$smarty->display('index.tpl');
	}
?>