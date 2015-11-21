<?php
	$username = $_GET['username'];
	$password = $_GET['password'];
	$code = $_GET['code'];
	if(isset($_GET['adv']))
		$adv = $_GET['adv'];
	else
		$adv = 0;
	
	if(funcs::checkInCompleteInfo($username, $password, $code))
	{
		//funcs::activateMember($username, $password, $code, $adv);
		funcs::loginSite($username, $password);	//automatic login
		header('Location:?action=incompleteinfo');
		exit();	
	}
	else if(funcs::activateMember($username, $password, $code, $adv))	//check activate complete?
	{
		/*/////
		if (!funcs::checkmobile($username)){
			funcs::loginSite($username, $password);	//automatic login
			header("location: .");	//go to first page		
		}
		*/
		if(funcs::checkMobileVerify($username)){
			funcs::loginSite($username, $password);	//automatic login
			header('Location:?action=mobileverify');
			exit();	
		}
		else
		{
			/*/////
			if (!funcs::checkvalidated($username)){
				funcs::loginSite($username, $password);	//automatic login
				header("location: ?action=validCode");	//go to first page			
			}
			else {
			*/////
				funcs::loginSite($username, $password);	//automatic login
				header("location: .");	//go to first page	
				exit();
			/////}
		}
	}
	else if(funcs::checkMobileVerify($username))
	{
		funcs::loginSite($username, $password);	//automatic login
		header('Location:?action=mobileverify');
		exit();	
	}
	else 
	{
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$activate_alert'));	//show activate error
		//select template file//
		$smarty->display('index.tpl');
	}
?>