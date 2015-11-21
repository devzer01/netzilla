<?php
// get domain name from $_SERVER-array (predefined php-var)
$domain = $_SERVER['SERVER_NAME'];
if ( substr ( $domain, 0, 4 ) == "www.") {
	$domain = substr ( $domain, 4 );
}

if (isset($_GET['ref'])) {
	setcookie('ref', $_GET['ref'], time()+60*60*24*365);
	$_SESSION['ref'] = $_GET['ref'];
}

require_once('classes/top.class.php');

// save requested page url to database
if(!$_POST)
{
	//DBConnect::execute_q("INSERT INTO pages_stat (url, datetime, ip, username) VALUES('".funcs::curPageURL()."',NOW(),'".funcs::getRealIpAddr()."','".$_SESSION['sess_username']."')");
}
$smarty->assign("domain",$domain);
$smarty->debugging = false;

$allow_action_array = array("validCode", "terms", "terms-2", "policy","imprint","faqs","membership","question","logout");
if(isset($_SESSION['sess_permission']))
{
	if(in_array($_SESSION['sess_permission'], array("2","3","4")))
	{
		if(funcs::checkmobile($_SESSION['sess_username']) && !funcs::checkvalidated($_SESSION['sess_username']) && (!in_array($_GET['action'], $allow_action_array)) && (FREE_SMS_ENABLE == 1))
		{
			header('location: ?action=validCode');
		}
	}
}
/*
///check mobile if null redirect to register step 2
if((isset($_SESSION['registered'])) && ($_SESSION['registered'] != "")){	
	$userid = funcs::getUserid($_SESSION['registered']);
	$step = funcs::checkIncompleteInfoById($userid);
	if($step != '1')
	{
		$allow = array("mobileverify","incompleteinfo","validCode", "terms", "terms-2", "policy","imprint","faqs","membership","question","logout");
		if(!in_array($_GET['action'],$allow)){
			
			switch($step)
			{
				case '3':
					$redirect = 'mobileverify';
					break;
				case '2':
					$redirect = 'incompleteinfo';
					break;
			}
			header("location: ?action=".$redirect);
			exit();
		}
	}
}
*/
$_GET['action'] = isset($_GET['action'])?$_GET['action']:'';

if (!isset($_GET['type'])) $_GET['type'] = '';

switch($_GET['action'])
{
	case '': include_once('modules/index.php'); break;
	case 'activate': include_once('modules/activate.php'); break;
	case 'activate2': include_once('modules/activate2.php'); break;
	case 'admin_history': include_once('modules/admin_history.php'); break;
	case 'admin_managecard': include_once('modules/managecard.php'); break;
	case 'admin_manageuser': include_once('modules/admin_manageuser.php'); break;
	case 'admin_manage_bonus': include_once('modules/admin_manage_bonus.php'); break;
	case 'admin_manage_bonus_popup': include_once('modules/admin_manage_bonus_popup.php'); break;
	case 'admin_bonus_history': include_once('modules/admin_bonus_history.php'); break;
	case 'admin_managecoin': include_once('modules/admin_managecoin.php'); break;
	case 'admin_coin_statistics': include_once('modules/admin_coin_statistics.php'); break;
	case 'admin_coin_statistics_details': include_once('modules/admin_coin_statistics_details.php'); break;
	case 'admin_manage_package': include_once('modules/admin_manage_package.php'); break;
	case 'admin_manage_package_popup': include_once('modules/admin_manage_package_popup.php'); break;
	case 'admin_manage_contents': include_once('modules/admin_manage_contents.php'); break;
	case 'admin_message': include_once('modules/admin_message.php'); break;
	case 'admin_adduser': include_once('modules/admin_adduser.php'); break;
	case 'admin_new_members': include_once('modules/admin_new_members.php'); break;
	case 'admin_paid': include_once('modules/admin_paid.php'); break;
	case 'admin_paid_edit': include_once('modules/admin_paid_edit.php'); break;
	case 'admin_paid_copy': include_once('modules/admin_paid_copy.php'); break;
	case 'admin_suggestionbox': include_once('modules/admin_suggestionbox.php'); break;
	case 'admin_viewmessage': include_once('modules/admin_viewmessage.php'); break;
	case 'administrator': header("Location: ?action=admin_manageuser"); break;
	case 'adsearch':include_once('modules/adsearch.php'); break;
	case 'adv_search':include_once('modules/advance_search.php'); break;
	case 'terms': include_once('modules/content.php'); break;
	case 'terms-2': include_once('modules/content.php'); break;
	case 'terms-popup': include_once('modules/terms-popup.php'); break;
	case 'policy': include_once('modules/content.php'); break;
	case 'policy-2': include_once('modules/content.php'); break;
	case 'policy-popup': include('modules/policy-popup.php'); break;
	case 'imprint': include_once('modules/content.php'); break;
	case 'membership': include_once('modules/index.php'); break;
	case 'membershipfront': include_once('modules/index.php'); break;
	case 'birthday': include_once('modules/birthday.php'); break;
	case 'fotoalbum': include_once('modules/fotoalbum.php'); break;
	case 'fotoalbum_view': include_once('modules/fotoalbum.php'); break;
	case 'ecard': include_once('modules/ecard.php'); break;
	case 'editprofile': include_once('modules/editprofile.php'); break;
	case 'changepassword': include_once('modules/changepassword.php'); break;
	case 'faqs': include_once('modules/index.php'); break;
	case 'favorite': include_once('modules/favorite.php'); break;
	case 'forget': include_once('modules/forget.php'); break;
	case 'logout': include_once('modules/logout.php'); break;
	case 'lonely_heart_ads': include_once('modules/lonelyHeart.php'); break;
	case 'lonely_heart_ads_view': include_once('modules/lonelyHeart.php'); break;
	case 'mymessage':
		if($_GET['type']=="writemessage")
		{
			header("Location: ?action=chat&username=".$_GET['username']);
		}
		else
		{
			header("Location: ?action=chat");
		}
		break;
	case 'newest': include_once('modules/newest.php'); break;
	case 'payportal': include_once('modules/payportal.php'); break;
	case 'pay-for-coins': include_once('modules/pay-for-coins.php'); break;
	case 'evnValidation': include_once('modules/payportal.php'); break;
	case 'giroPay': include_once('modules/payportal_gp.php'); break;
	case 'webcam': include_once('modules/webcam.php'); break;
	case 'register':
		switch($_GET['type']){
			case 'upgrade':
				include_once('modules/upgrade.php');
			break;
			default:
				include_once('modules/register.php');
				
		}
		break;
	case 'incompleteinfo_skip': include_once('modules/incompleteinfo_skip.php'); break;
	case 'mobileverify_skip': include_once('modules/mobileverify_skip.php'); break;
	//case 'mobileverify_successful': include_once('modules/mobileverify_successful.php'); break;
	case 'incompleteprofile': include_once('modules/incompleteprofile.php');
	case 'bonusverify': include('modules/bonusverify.php'); break;
	case 'search':
		/*if(in_array(strtolower($_SESSION['sess_username']), array("zerocoolz", "cyric")))
			include_once('modules/search_all_sites.php');
		else*/
			include_once('modules/search.php');
		break;
	case 'search_admin': include_once('modules/search_admin.php'); break;
	case 'search_gender': include_once('modules/search_gender.php'); break;
	case 'sendcard': include_once('modules/sendcard.php'); break;
	case 'sendcard_to': include_once('modules/sendcard_to.php'); break;
	case 'sendmail': include_once('modules/sendmail.php'); break;
	case 'show_advsearch':include_once('modules/show_advsearch.php'); break;
	case 'suggestion_box': include_once('modules/suggestion.php'); break;
	case 'sendSMS':
	case 'SMS': include_once('modules/sms.php'); break;
	case 'thankyou': include_once('modules/thankyou.php'); break;
	case 'validCode': include_once('modules/sms.php'); break;
	case 'validCode2': include_once('modules/sms_validcode2.php'); break;	
	case 'viewmessage': include_once('modules/viewmessage.php'); break;
	case 'viewprofile': include_once('modules/viewprofile.php'); break;
	case 'viewcard': include_once('modules/viewcard.php'); break;
	case 'viewcard_mail': include_once('modules/viewcard_mail.php'); break;
	case 'writecard': include_once('modules/writecard.php'); break;
	case 'suggestionalbum':include_once('modules/suggestionalbum.php'); break;
	case 'image_dir':include_once('modules/image_dir.php'); break;
	case 'question':include_once('modules/question.php'); break;
	case 'resendactivation': include_once('modules/resendactivation.php');
	default :
		if(file_exists("modules/".$_GET['action'].".php"))
			include_once("modules/".$_GET['action'].".php");
		else
			header("Location: ./");
}

?>
<?php
//echo $_SESSION['registered'];
if(isset($_SESSION['registered']))
	unset($_SESSION['registered']);


/*echo "<pre>";
//print_r($_SESSION);
echo "-----------<br/>";
print_r($_COOKIE);
echo "</pre>";*/
?>