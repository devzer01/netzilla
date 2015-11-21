<?php
require_once('classes/top.class.php');

switch($_REQUEST['action'])
{
	case 'addFavorite': 
		$userid = funcs::getUserid($_POST['username']);
		funcs::addFavorite($_SESSION['sess_id'], $userid); 
		break;
	case 'removeFavorite': 
		$userid = funcs::getUserid($_POST['username']);
		if(DBConnect::execute_q("DELETE FROM favorite WHERE child_id='".$userid."' and parent_id='".$_SESSION['sess_id']."'"))
			echo 1;
		else
			echo 0;
		break;
	case 'isUsername': echo funcs::isUsername($_POST['username']); break;
	case 'isPhoneNumber':
		if($code = DBConnect::retrieve_value("SELECT c.country_prefix_hidden FROM xml_countries c LEFT JOIN member m ON c.id=m.country WHERE m.id=".$_SESSION['sess_id']))
			$fullnumber = $code.$_POST['phone_number'];
		echo funcs::isPhoneNumber($fullnumber);
		//echo $_POST['phone_number'];
		break;
	case 'isUsernamesignup': echo funcs::isUsername($_POST['username']); break;
	case 'isEmail': echo funcs::isEmail($_POST['email']); break;
	case 'isEmailsignup': echo funcs::isEmail($_POST['email']); break;
	case 'loadOptionCountry': 
		header("Content-type: text/xml");
		echo funcs::getLocationXML();
		//echo funcs::getText($_SESSION['lang'], '$country');
		//funcs::getChoiceCountryXML();
		break;
	case 'loadOptionCountry_with_test_country': 
		header("Content-type: text/xml");
		echo funcs::getLocationXML_with_test_country();
		//echo funcs::getText($_SESSION['lang'], '$country');
		//funcs::getChoiceCountryXML();
		break;
	case 'login': 
		if(funcs::loginSite($_POST['username'], $_POST['password'], $_POST['remember']))
			echo true;
		else
			echo false;
		break;
	case 'loginmobile':
		if(funcs::loginSite($_POST['username'], $_POST['password'], $_POST['remember']))
			echo 1;
		else
			echo 0;
		break;
	case 'updateEdit_datetime': funcs2::updateEdit_datetime($_POST['username']); break;
	case 'getMessageHistory':
		if($_POST['username'] == 'System Admin')
			$userId = 1;
		else
			$userId = funcs::getUserid($_POST['username']);

		$messages = funcs::getMessageHistory($_SESSION['sess_id'],$userId , 0, 0);
		$smarty->assign("messages",$messages);
		echo $smarty->fetch("message_history.tpl");
		break;
	case 'getZodiac':
		echo funcs::getZodiac($_POST['bdate']); break;
	case 'getCountryCode':
		echo funcs::getCountryCode($_POST['country_id']); break;
	case 'getCurrentUserMobileNo':
		echo funcs::getCurrentUserMobileNo(); break;
	case 'ajaxFormIncompleteInfo':
		echo funcs::ajaxFormIncompleteInfo($_POST['mobileNo']); break;
	case 'ajaxFormMobileVerify':
		echo funcs::ajaxFormMobileVerify($_POST['verCode']); break;
	case 'setNulCurrentUserMobileNo':
		echo funcs::setNulCurrentUserMobileNo(); break;
	case 'ajaxFormResendVerify':
		echo funcs::ajaxFormResendVerify(); break;
	case 'fetchAllStatus':
		$allStatus = funcs::getCountAllNewMessage_inbox();
		echo json_encode($allStatus);

		break;
	case 'getRandomUser':
		if($_SESSION['sess_username']!="")
		{
			$row = funcs::getRandomUser();// array('Noi', 'noi.jpg');
			$arrProfile = array($row['username'], $row['picturepath']);
			$_SESSION['last_username'] = $row['username'];
		}
		else
		{
			$arrProfile = array('','');
			unset($_SESSION['last_username']);
		}
		echo json_encode($arrProfile);
		break;
}
?>