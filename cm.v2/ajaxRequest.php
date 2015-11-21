<?php
date_default_timezone_set("Europe/Berlin");
require_once('classes/top.class.php');

switch($_REQUEST['action'])
{
	case 'sendActivateEmail':
		$email = $_GET['email'];
		$username = $_GET['username'];
		$userid = funcs::getUserid($_GET['username']);
		if ($userid !== false && funcs::getUserEmail($username) == $email) {
			$message = funcs::getMessageEmail_membership($smarty, $username);
			$message_text = funcs::getMessageEmail_membershipText($smarty, $username);
			$retval = funcs::sendMailRegister($email, funcs::getText($_SESSION['lang'], '$email_testmember_subject'), $message, funcs::getText($_SESSION['lang'], '$KM_Website')." <".MAIL_FROM_REGISTER.">", $username, $message_text);
			$json = array();
			header("Content-Type: application/json");
			echo json_encode($json);
		}
		break;
	case 'addFavorite': 
		$userid = funcs::getUserid($_POST['username']);
		if(funcs::addFavorite($_SESSION['sess_id'], $userid))
		{
			$picturepath = DBConnect::retrieve_value("SELECT picturepath FROM member WHERE username='".$_POST['username']."'");
			echo json_encode(array("result" => "FINISHED", "picturepath" => $picturepath));
		}
		break;
	case 'removeFavorite': 
		$userid = funcs::getUserid($_POST['username']);
		if(DBConnect::execute_q("DELETE FROM favorite WHERE child_id='".$userid."' and parent_id='".$_SESSION['sess_id']."'"))
			echo 1;
		else
			echo 0;
		break;
	case 'loadFavorite':
		if(isset($_SESSION['sess_id']))
		{
			$result = DBConnect::assoc_query_2D("SELECT m.username, m.picturepath FROM favorite f LEFT JOIN member m ON f.child_id=m.id WHERE m.isactive=1 AND f.parent_id=".$_SESSION['sess_id']." ORDER BY f.id DESC");
			if(is_array($result) && count($result))
			{
				$smarty->assign("style", $_GET['style']);
				$smarty->assign("nofavorite", "true");
				$smarty->assign("result", $result);
				$smarty->display("profile_list.tpl");
			}
		}
		exit;
		break;
	case 'deletePhoto':
		funcs::deleteFotoAlbum($_POST['fotoid'], $_SESSION['sess_id'], $_POST['approval']);
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
	case 'verify':
		$json = json_decode(file_get_contents("http://46.244.18.249/email/verify/" . $_GET['email']), true);
		header("Content-Type: application/json");
		$return = array('status' => 0, 'debug' => $json['code']);
		if (preg_match("/^250/", $json['code'])) {
			$return = array('status' => 1, 'debug' => $json['code']);
		}
		echo json_encode($return);
		break;
	case 'updateEdit_datetime': funcs2::updateEdit_datetime($_POST['username']); break;
	case 'getMessageHistory':
		if($_POST['username'] == ADMIN_USERNAME_DISPLAY)
			$userId = 1;
		else
			$userId = funcs::getUserid($_POST['username']);

		if(isset($_SESSION['sess_smalladmin']) && $_SESSION['sess_smalladmin'])
			$own_id = 1;
		else
			$own_id = $_SESSION['sess_id'];

		$messages = funcs::getMessageHistory($own_id,$userId , 0, 0);
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
