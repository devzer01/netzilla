<?php
error_reporting(0);
require_once('classes/top.class.php');
$smarty->debugging = false;

if (isset($_GET['ref'])) {
	setcookie('ref', $_GET['ref'], time()+60*60*24*365);
    $_SESSION['ref'] = $_GET['ref'];
}

// save requested page url to database
if(!$_POST && !isset($_GET['crc']))
{
	//DBConnect::execute_q("INSERT INTO pages_stat (url, datetime, ip, username) VALUES('".funcs::curPageURL()."',NOW(),'".funcs::getRealIpAddr()."','".$_SESSION['sess_username']."')");
}

$_GET['action'] = isset($_GET['action'])?$_GET['action']:"";

$allow_action_array = array("validCode", "terms", "terms-2", "policy","imprint","faqs","membership","question","logout", "login");
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

if(substr_count($_SERVER['REQUEST_URI'], '?')>1)
{
	$url = str_replace("?","&", basename($_SERVER['REQUEST_URI']));
	$url = substr_replace($url,"?",strpos($url,"&"),1);
	header("location: ".$url);
	exit;
}

$_GET['action'] = isset($_GET['action'])?$_GET['action']:'';
if(isset($_SESSION['sess_id']) && !USERNAME_CONFIRMED)
{
	$allow_actions = array("activate", "username_confirmation", "registration_completed", "logout");
	if(!in_array($_GET['action'], $allow_actions))
	{
		header("location: ?action=username_confirmation");
		exit;
	}
}

require_once ("modules/common.php");

switch($_GET['action'])
{
	case '':
	case 'faqs':
	case 'membership':
	case 'membershipfront': include_once('modules/index.php'); break;
	case 'admin_manage_contents': include_once('modules/admin_manage_contents.php'); break;
	case 'administrator': header("Location: ?action=admin_manageuser"); break;
	//case 'adv_search':include_once('modules/advance_search.php'); break;
	case 'terms':
	case 'terms-2':
	case 'policy':
	case 'policy-2':
	case 'refund':
	case 'refund-2':
	case 'imprint': include_once('modules/content.php'); break;
	case 'fotoalbum_view': include_once('modules/fotoalbum.php'); break;
	case 'lonely_heart_ads':
	case 'lonely_heart_ads_view': include_once('modules/lonelyHeart.php'); break;
	case 'mymessage':
		if($_GET['type']=="writemessage")
		{
			header("Location: ?action=chat&username=".$_GET['username']);
		}
		else
			include_once('modules/mymessage.php');
		break;
	case 'sendSMS':
	case 'SMS':
	case 'validCode': include_once('modules/sms.php'); break;
	case 'validCode2': include_once('modules/sms_validcode2.php'); break;	
	default :
		if(file_exists("modules/".$_GET['action'].".php")) {
			include_once("modules/".$_GET['action'].".php");
		} else
			header("Location: ./");
		break;
}
