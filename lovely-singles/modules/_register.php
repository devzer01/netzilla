<?php
	//send choice to template//
	//step1//
	$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('city', funcs::getChoice($_SESSION['lang'],'','$city'));
	$smarty->assign('country', funcs::getChoiceCountry());
	$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));
	//step2//
	$smarty->assign('appearance', funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'));
	$smarty->assign('eyescolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$eyes_color'));
	$smarty->assign('haircolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_color'));
	$smarty->assign('hairlength', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_length'));
	$smarty->assign('beard', funcs::getChoice($_SESSION['lang'],'$nocomment','$beard'));
	$smarty->assign('zodiac', funcs::getChoice($_SESSION['lang'],'$nocomment','$zodiac'));
	$smarty->assign('status', funcs::getChoice($_SESSION['lang'],'$nocomment','$status'));
	$smarty->assign('sexuality', funcs::getChoice($_SESSION['lang'],'$nocomment','$sexuality'));
	$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));
	//step3//
	/////$smarty->assign('age', funcs::getRangeAge(18, 99));//Singh
	$smarty->assign('age', funcs::getRangeAge());//Singh
	
	if(isset($_POST['submit_form'])){
		$_POST['phone_number'] = trim($_POST['phone_number']);
		if (isset($_POST['phone_number']) && $_POST['phone_number'] != '')
			$_POST['waitver_mobileno'] = $_POST['phone_code'].$_POST['phone_number'];
		//$_POST['description'] = htmlentities($_POST['description'],'','UTF-8');
		$_POST['username'] = trim($_POST['username']);
		$save = $_POST;
		$save[TABLE_MEMBER_BIRTHDAY] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
		$save['zodiac'] = funcs::getZodiac($_POST['month'].'-'.$_POST['date']);
		$save[TABLE_MEMBER_SIGNUP_DATETIME] = funcs::getDateTime();
		switch($_GET['type'])
		{
			case 'test_membership':
				$save[TABLE_MEMBER_STATUS] = 3;
				$save[TABLE_MEMBER_ISACTIVE] = 0;
				$save[TABLE_MEMBER_VALIDATION] = funcs::randomPassword(6);
				$save[TABLE_MEMBER_PASSWORD] = funcs::randomPassword(6);
			break;
			case 'membership':
				$save[TABLE_MEMBER_ISACTIVE] = 0;
				$save[TABLE_MEMBER_STATUS] = 4;	//fix status
				$save[TABLE_MEMBER_VALIDATION] = funcs::randomPassword(6);
			break;
		}
		
		if(preg_match('/[^a-z0-9ÄäÖöÜüß]/i',$save['username'])){
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_usrname_format'));
			$smarty->assign('save', $save);
		}
		elseif(funcs::isUsername($save['username']) > 0)
		{
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register_error'));
			$smarty->assign('save', $save);
		}
		elseif(strlen(trim($save['email'])) == 0 || funcs::isEmail($save['email']) > 0)
		{
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register_error'));
			$smarty->assign('save', $save);
		}
		elseif(($save['waitver_mobileno'] != "") && (funcs::isPhoneNumber($save['waitver_mobileno']) > 0))
		{
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register1'));
			$smarty->assign('save', $save);
		}
		elseif(funcs::ageVerify($save[TABLE_MEMBER_BIRTHDAY]) == 0){/////Singh start
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$err_age_limit'));
			$smarty->assign('save', $save);
		}/////Singh end
		elseif(funcs::registerMember($save))
		{
			$message = funcs::getMessageEmail_membership($smarty, $save[TABLE_MEMBER_USERNAME]);
			/*if($save[TABLE_MEMBER_EMAIL]=="cm.testarea@gmail.com")
			{
				funcs::sendMailTOnline($save[TABLE_MEMBER_EMAIL], funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, self_name." <".MAIL_FROM_REGISTER.">"); // sendMailGmail sendMail
			}
			else*/
			{
				funcs::sendMailGmail($save[TABLE_MEMBER_EMAIL], funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, self_name." <".MAIL_FROM_REGISTER.">"); // sendMailGmail sendMail
			}

			if($save['waitver_mobileno'] != ""){
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($save[TABLE_MEMBER_USERNAME]);
				sendSMSCode($save['waitver_mobileno'], $smsmsg);
			}
			$smarty->assign('text1', funcs::getText($_SESSION['lang'], '$register_testmembership_complete1'));
			$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$register_testmembership_complete2'));
			$smarty->assign('mailbox', $save['email']);
			$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$register_testmembership_complete3'));
			//$smarty->assign('section', 'blank_alert');
			$smarty->assign('section', 'regis-step1-result');
			//Singh end
		}
		else
		{
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register2'));
			$smarty->assign('save', $save);
		}
	}

/*********************************** Get Profile Data **************************************/
if($_GET['cate']=="profile"){
    $userProfile = funcs::getProfileByUsername($_GET[username]);
	if(is_array($userProfile))
	{
		$userProfile[city] = funcs::getAnswerCity($_SESSION['lang'], $userProfile[city]);
		$userProfile[appearance] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $userProfile[appearance]);
		$userProfile[civilstatus] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $userProfile[civilstatus]);
		$userProfile[height] = ($userProfile[height]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $userProfile[height]) : "";

		$smarty->assign('thisyear',date('Y'));
		$smarty->assign('userProfile', $userProfile);
	}
	else
	{
		header("location: ?action=register&type=".$_GET['type']);
		exit();
	}
}elseif($_GET['cate']=="lonely"){
    $lonelyProfile = funcs::getloneyByUsername($_GET[username]);
    if(is_array($lonelyProfile))
	{
		$lonelyProfile[city] = funcs::getAnswerCity($_SESSION['lang'], $userProfile[city]);
		$lonelyProfile[appearance] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $lonelyProfile[appearance]);
		$lonelyProfile[civilstatus] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $lonelyProfile[civilstatus]);
		$lonelyProfile[height] = ($lonelyProfile[height]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $lonelyProfile[height]) : "";

		$smarty->assign('thisyear',date('Y'));
		$smarty->assign('lonelyProfile', $lonelyProfile);
	}
	else
	{
		header("location: ?action=register&type=".$_GET['type']);
		exit();
	}
}
/*******************************************************************************************/

	//select template file//
	$smarty->display('index.tpl');
?>