<?php
//session id is generated when ???????
if(!$_SESSION['sess_id'])
{
	$smarty->assign('save', $_POST);
	$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('country', funcs::getChoiceCountry());
	$smarty->assign('age', funcs::getRangeAge());//Singh

	if(isset($_POST['submit_form']))
	{
		$_POST['phone_number'] = trim($_POST['phone_number']);
		$_POST['username'] = trim($_POST['username']);
		//$_POST['description'] = htmlentities($_POST['description'],'','UTF-8');
		if (isset($_POST['phone_number']) && $_POST['phone_number'] != '')
			$_POST['waitver_mobileno'] = $_POST['phone_code'].$_POST['phone_number'];
		$save = $_POST;
		$save[TABLE_MEMBER_BIRTHDAY] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
		$save['zodiac'] = funcs::getZodiac($_POST['month'].'-'.$_POST['date']);
		$save[TABLE_MEMBER_SIGNUP_DATETIME] = funcs::getDateTime();
		$save['lookmen'] = $save['gender']=="1"?"0":"1";
		$save['lookwomen'] = $save['gender']=="1"?"1":"0";
		switch($_GET['type'])
		{
			case 'membership':
			default:
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
			$message = funcs::getMessageEmail_membership($smarty, $save[TABLE_MEMBER_USERNAME]);//"Test...";//
			funcs::sendMailRegister($save[TABLE_MEMBER_EMAIL], funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">");

			if($save['waitver_mobileno'] != ""){
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($save[TABLE_MEMBER_USERNAME]);
				sendSMSCode($save['waitver_mobileno'], $smsmsg);
			}
			
			$_SESSION['registerred_email'] = $save['email'];
			
			
			header("location: ?action=register");
			exit;
		}
		else
		{
			$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register2'));
			$smarty->assign('save', $save);
		}
	}
	elseif($_SESSION['registerred_email'])
	{
		$smarty->assign('text1', funcs::getText($_SESSION['lang'], '$register_testmembership_complete1'));
		$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$register_testmembership_complete2'));
		$smarty->assign('mailbox', $_SESSION['registerred_email']);
		$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$register_testmembership_complete3'));
		$smarty->assign('section', 'regis-step1-result');
		//unset($_SESSION['registerred_email']);
	}
	else
	{
		/********************************* Get Profile Data ************************************/
		if($_GET['cate']=="profile")
		{
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
		}
		elseif($_GET['cate']=="lonely")
		{
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
		/*****************************************************************************************/
	}
	//select template file//
	$smarty->display('index.tpl');
}
else
{
	header("location: .");
}
?>