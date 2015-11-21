<?php
session_start();	//session start
require_once('Mobile_Detect.php');
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');

$_SESSION['deviceType'] = $deviceType;

if(!isset($_SESSION['state']) || !$_SESSION['state'])
	$_SESSION['state'] = md5(uniqid(rand(), TRUE));

header('P3P:CP="NOI DSP COR NID BUS"');
/* set default language is english
* eng : english
* ger : germany
*/
setlocale(LC_TIME, 'de_DE.UTF8');
ini_set('allow_call_time_pass_reference', '1');

//if(!isset($_SESSION['lang']) || empty($_SESSION['lang']))
//$_SESSION['lang'] = 'ger';
/*if(isset($_REQUEST['lang']) && $_REQUEST['lang']!="")
{
	if(in_array(isset($_SESSION['lang']), array('ger','eng')))
	{
		session_unregister('lang');
		$_SESSION['lang'] = $_REQUEST['lang'];
	}
}
else
{
	if(!in_array(isset($_SESSION['lang']), array('ger','eng')))
	{
		$_SESSION['lang'] = 'ger';
	}
}*/
$_SESSION['lang'] = 'ger';
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['request'] = $_SERVER['REQUEST_URI'];
/*if(!in_array(isset($_SESSION['lang']), array('ger','eng')))
{
	if(strpos($_SERVER["HTTP_ACCEPT_LANGUAGE"],'de')!==FALSE)
		$_SESSION['lang'] = 'ger';
	else
		$_SESSION['lang'] = 'eng';
}*/

if (!function_exists('array_intersect_key'))
{
  function array_intersect_key($isec, $keys)
  {
    $argc = func_num_args();
    if ($argc > 2)
    {
      for ($i = 1; !empty($isec) && $i < $argc; $i++)
      {
        $arr = func_get_arg($i);
        foreach (array_keys($isec) as $key)
        {
          if (!isset($arr[$key]))
          {
            unset($isec[$key]);
          }
        }
      }
      return $isec;
    }
    else
    {
      $res = array();
      foreach (array_keys($isec) as $key)
      {
        if (isset($keys[$key]))
        {
          $res[$key] = $isec[$key];
        }
      }
      return $res;
    }
  }
}

//require classes and libs//
require_once('configs/'.$_SESSION['lang'].'.php');
require_once('libs/SmartyPaginate.class.php');
require_once('classes/DBconnect.php');
require_once('classes/funcs.php');
require_once('classes/funcs2.php');
require_once('Mail.php');
require_once('Mail/mime.php');
require_once('classes/smarty_web.php');
require_once('classes/sms.php');
require_once('classes/search.class.php');
require_once('classes/class.phpmailer.php');

//if(!isset($_SESSION['sess_id']) && isset($_GET['asession'])){
if(isset($_GET['asession'])){
  $animID = funcs::externalLogin($_GET['asession']);
  if($animID){
      $userInfo=funcs::getProfile(1);

      funcs::loginSite($userInfo['username'], $userInfo['password']);
      $_SESSION['sess_externuser']=$animID;
  }
}
elseif( isset($_GET['session']) ){
  $animID = funcs::externalLogin($_GET['session']);
  if($animID){
    $userInfo=funcs::getProfile(2);
    funcs::loginSite($userInfo['username'], $userInfo['password']);
    $_SESSION['sess_externuser']=$animID;
  }
}

if(!isset($_SESSION['sess_id']))
{
	if(isset($_COOKIE['username']) && isset($_COOKIE['password']))
	{
		funcs::checkCookie();
	}
}

$smarty = new smarty_web();	//call smarty class

//require config language file//
$smarty->config_load($_SESSION['lang'].'.conf');

//send choice to template//
$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));
$smarty->assign('picyesno', funcs::getChoice($_SESSION['lang'],'','$picyesno'));
$smarty->assign('age', funcs::getRangeAge());
$submenu = array(	"mymessage"					=> "mymessage",
				"viewmessage"					=> "mymessage",
				"sendcard"						=> "mymessage",
				"editprofile"					=> "editprofile",
				"changepassword"				=> "editprofile",
				"fotoalbum"						=> "editprofile",
				"lonely_heart_ads"				=> "lonely_heart_ads",
				"adsearch"						=> "lonely_heart_ads",
				"suggestion_box"				=> "suggestion_box",
				"suggestionalbum"				=> "suggestion_box",
				"admin_new_members"				=> "admin_new_members",
				"admin_paid"					=> "admin_paid",
				"membership"					=> "membership",
				"admin_coin_statistics"			=> "admin_coin_statistics",
				"admin_coin_statistics_details"	=> "admin_coin_statistics",
				"admin_manage_bonus"			=> "admin_bonus",
				"admin_bonus_history"			=> "admin_bonus"
			  );
if(isset($_GET['action']))
{
	if(isset($submenu[$_GET['action']]))
		$menu = $submenu[$_GET['action']];
	else
		$menu = $_GET['action'];
	$smarty->assign("submenu", $menu);
}

/*coin*/
if(isset($_SESSION['sess_username']))
{
	$_SESSION['last_access'] = isset($_SESSION['last_access'])?$_SESSION['last_access']:0;
	if($_SESSION['last_access'] < time()-60)
	{
		if(DBConnect::retrieve_value("SELECT isactive FROM member WHERE id=".$_SESSION['sess_id'])=="0")
		{
			funcs::logoutSite();
			exit;
		}
		else
		{
			$_SESSION['last_access'] = time();
		}
	}

	//$coinVal = funcs::checkCoin($_SESSION['sess_username']);
	//$smarty->assign("coin",$coinVal);
	//$arrCoin = array("coin"=>$coinVal);

	if(!isset($_SESSION['MOBILE_VERIFIED']))
	{
		$mobile_verified = DBConnect::retrieve_value("SELECT 1 FROM member WHERE id='".$_SESSION['sess_id']."' and 
		mobileno!=''");
		$_SESSION['MOBILE_VERIFIED'] = $mobile_verified;
	}
	else
	{
		$mobile_verified = $_SESSION['MOBILE_VERIFIED'];
	}
	$smarty->assign("mobile_verified",$mobile_verified);
}
$smarty->assign('newmessage', funcs::getCountNewMessage_inbox()); //get new message for current user
?>