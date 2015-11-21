<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$userid = $_SESSION['sess_id'];

$profile = funcs::getProfile($userid);	//get profile data

$phototemp = DBConnect::retrieve_value("SELECT picturepath FROM phototemp WHERE userid=".$userid." AND status=1 ORDER BY id DESC LIMIT 1");
if($phototemp && ($profile['picturepath']==''))
{
	$profile['picturepath'] = $phototemp;
	$profile['approval'] = 1;
}
$profile['gender'] = funcs::getAnswerChoice($_SESSION['lang'],'', '$gender', $profile['gender']);
$profile['country'] = funcs::getAnswerCountry($_SESSION['lang'], $profile['country']);
$profile['state'] = funcs::getAnswerState($_SESSION['lang'], $profile['state']);
$profile['city'] = funcs::getAnswerCity($_SESSION['lang'], $profile['city']);

$bhd_array = explode("-",$profile['birthday']);
$smarty->assign('profile', $profile);
$smarty->display('index.tpl');
?>