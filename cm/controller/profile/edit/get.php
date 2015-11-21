<?php 
/**
 * PREVENT DIRECT ACCESS
 */
if (!defined('APP_PATH')) die('Go Away');

/**
 * @module profile
 * @action edit
 * @method get
 * @desc Renders edit profile screen
 */

include_once 'lib/dbo/member.php';
$dbo_member = new dbo_member();
$username = $_SESSION['sess_username'];
$member = $dbo_member->getBasicMemberInfo($username);

if (trim($member['picturepath']) == '' && $dbo_member->getPendingProfilePicture($username) !== false) {
	$member['picturepath'] = $dbo_member->getPendingProfilePicture($username);
	$member['approval'] = 1;
}

$member['descstatus'] = "approved";
if($member['description'] == "" && DESCRIPTION_APPROVAL == 1 && ($desctemp = $dbo_member->getPendingProfileDesc($username)) !== false)
{
	$member['descstatus'] = "awaiting";
	$member['description'] = $desctemp;
}

include_once 'lib/dbo/config.php';
$dbo_config = new dbo_config();
$smarty->assign('countries', initSmartyOptionsArray($dbo_config->getCountryList(), 'id', 'name'));
$smarty->assign('states', initSmartyOptionsArray($dbo_config->getStateList($member['country']), 'id', 'name'));
if ($member['state'] == 0) {
	$smarty->assign('cities', initSmartyOptionsArray($dbo_config->getAllCities(), 'id', 'name'));
} else {
	$smarty->assign('cities', initSmartyOptionsArray($dbo_config->getCityList($member['state']), 'id', 'name'));
}

$smarty->assign('yesno', array('0' => _("No"), '1' => _("Yes")));
$smarty->assign('gender', array('1' => _("Male"), '2' => _("Female")));
$smarty->assign('year', initSmartyOptionsArray(range(date("Y")-90, date("Y") - 18)));
$smarty->assign('day', initSmartyOptionsArray(range(1, 31)));
$smarty->assign('month', array(1 => _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("Auguest"), _("September"), _("October"), _("November"), _("December")));

$member['byear'] = date("Y", strtotime($member['birthday']));
$member['bmonth'] = date("n", strtotime($member['birthday']));
$member['bday'] = date("j", strtotime($member['birthday']));

$smarty->assign('member', $member);
$smarty->display('private/profile/edit.tpl');