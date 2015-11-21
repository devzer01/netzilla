<?php
// $random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
// $smarty->assign("random_contacts", $random_contacts);

$sql = "SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 ORDER BY RAND() LIMIT 1";
$rs = mysql_query($sql);
$smarty->assign('random_profile', mysql_fetch_assoc($rs));

//session id is generated when ???????
if(isset($_SESSION['sess_id'])) {
	header("location: .");
	exit;
}

if (isset($_GET['token'])) {
	$_SESSION['token'] = $_GET['token'];
}

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
		
		if (isset($save['confirm_password'])) unset($save['confirm_password']);
		
		if (isset($_SESSION['ref'])) {
			$save['ref'] = $_SESSION['ref'];
		}
		
		if (!isset($save['ref']) && isset($_COOKIE['ref'])) {
			$save['ref'] = $_COOKIE['ref'];
		}
		
		//reffered_by_member_id
		if (isset($_SESSION['token'])) {
			$save['refby_member_id'] = funcs::decryptToken($_SESSION['token']);
		}
		
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
// 			$message = funcs::getMessageEmail_membership($smarty, $save[TABLE_MEMBER_USERNAME]);
// 			$message_text = funcs::getMessageEmail_membershipText($smarty, $save[TABLE_MEMBER_USERNAME]);
// 			$retval = funcs::sendMailRegister($save[TABLE_MEMBER_EMAIL], funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $save['username'], $message_text);
			
			if($save['waitver_mobileno'] != ""){
				$smsmsg = funcs::getText($_SESSION['lang'], '$mobile_verify_message') . funcs::getTextSMS($save[TABLE_MEMBER_USERNAME]);
				sendSMSCode($save['waitver_mobileno'], $smsmsg);
			}
			
			$_SESSION['registerred_email'] = $save['email'];
			$_SESSION['register_username'] = $save['username'];
			header("location: ?action=register_waiting_confirmation");
			
			
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
		$smarty->assign('username', $_SESSION['register_username']);
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

		if(isset($_POST['username']) && isset($_POST['email']))
		{
			$username = funcs::check_input(trim($_POST['username']));
			$email = funcs::check_input(trim($_POST['email']));
			DBConnect::execute_q("INSERT INTO member_potential (username, email, signup_datetime) VALUES ('".$username."','".$email."', NOW())");
		}
		/*****************************************************************************************/
	}
	//select template file//
	if (isset($_SESSION['deviceType']) && $_SESSION['deviceType'] == 'phone' && !isset($_SESSION['registerred_email'])) {
		$smarty->display('mobile/register.tpl');
	} else {
		$smarty->display('index.tpl');
	}