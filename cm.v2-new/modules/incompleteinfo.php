<?php
//if((isset($_SESSION['sess_username'])) && ($_SESSION['sess_username'] != ""))
//{
	$permission_lv = array(1, 4, 8, 9); //jeab edited
	funcs::checkPermission($smarty, $permission_lv);	//check permission

	$userid = funcs::getUserid($_SESSION['sess_username']);
	$profile = funcs::getProfile($userid);	//get profile data
	//$permission_lv = array(1, 4);	//define type permission can open this page.
		
	//funcs::checkPermission($smarty, $permission_lv);	//check permission

	/*$smarty->assign('appearance', funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'));
	$smarty->assign('eyescolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$eyes_color'));
	$smarty->assign('haircolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_color'));
	$smarty->assign('hairlength', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_length'));
	$smarty->assign('beard', funcs::getChoice($_SESSION['lang'],'$nocomment','$beard'));
	$smarty->assign('zodiac', funcs::getChoice($_SESSION['lang'],'$nocomment','$zodiac'));
	$smarty->assign('status', funcs::getChoice($_SESSION['lang'],'$nocomment','$status'));
	$smarty->assign('sexuality', funcs::getChoice($_SESSION['lang'],'$nocomment','$sexuality'));
	$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));*/

	if(isset($_POST['submit_form'])){
		$unwantArray = array('submit_form','looking_for','phone_code','phone_code2','phone_number');
		$postArray = array();
		
		foreach($_POST as $key => $val)
		{
			if(!(in_array($key, $unwantArray)))
			{
				$postArray[$key] = $val;
			}
		}

		//$postArray['waitver_mobileno'] = $_POST['phone_code'] . $_POST['phone_code2'] . $_POST['phone_number'];

		$str = $_POST['phone_code2'];
		$zeropos = strpos($str,'0'); 

		if($zeropos === false || $zeropos > 0)
			$clr_phone_code = $str;
		else
			$clr_phone_code = substr($str,1,strlen($str)-1);

		$postArray['waitver_mobileno'] = funcs::getCountryCode($profile['country'], true) . $clr_phone_code . $_POST['phone_number']; //$_POST['country']
		
		if($_POST['looking_for']=='1')
			$postArray['lookmen'] = 1;
		else
			$postArray['lookwomen'] = 1;
		
		/*echo "<pre>";
		print_r($postArray);
		echo "</pre>";
		exit();*/
		funcs::completeInfo($postArray);
		//sms
		if(funcs::checkVcodeCount($userid))
		{
			if($postArray['waitver_mobileno'] != ""){
				$dial_number = $postArray['waitver_mobileno'];
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($_SESSION['sess_username']);
				sendSMSCode($dial_number, $smsmsg);
				funcs::increaseVcodeCount($userid);
			}
		}

		/*$next_url = $_GET['nextstep'];
		header('Location:?action='.$next_url);

		//header('Location:?action=mobileverify');
		exit;*/
	}

	$vcodeCount = funcs::checkVcodeCount($userid);
	if(!$vcodeCount)
		$smarty->assign('text4', funcs::getText($_SESSION['lang'], '$limit_code_resend'));
	
	$save['country'] = $profile['country'];
	$save['state'] = $profile['state'];
	$save['city'] = $profile['city'];
	$save['gender'] = $profile['gender'];

	if($profile['lookmen']=='1')
	{
		$save['looking_for'] = 1;
	}
	else if($profile['lookwomen']=='1')
	{
		$save['looking_for'] = 2;
	}

	$save['countrycode'] = funcs::getCountryCode($profile['country']);
	$smarty->assign('save', $save);
	$smarty->display('incompleteinfo.tpl');
	//$smarty->display('index.tpl');
/*}
else
{
	header('location: .');
	exit;
}*/
?>