<?php 
/**
 * PREVENT DIRECT ACCESS
 */
if (!defined('APP_PATH')) die('Go Away');

/**
 * 
 */

$username = $_SESSION['sess_username'];
$_POST['birthday'] = date("Y-m-d", strtotime($_POST['year'].'-'.$_POST['month'].'-'.$_POST['day']));

require_once 'lib/dbo/member.php';
$dbo_member = new dbo_member();
$member = $dbo_member->getBasicMemberInfo($username);

if($member['description'] != $_POST['description'])
{
	if(DESCRIPTION_APPROVAL == 1)
	{
		$dbo_member->setPendingProfileDesc($username, $_POST['description']);
		$_POST['description'] = "";
	}
	else
	{
		$_POST['description'] = removeEmailAddressFromText($_POST['description']);
	}
}

$dbo_member->updateBasicMemberInfo($username, $_POST['gender'], $_POST['birthday'], $_POST['country'],
		$_POST['state'], $_POST['city'], $_POST['lookmen'], $_POST['lookwomen'], $_POST['description']);

$app->redirect(APP_PATH . "/profile/");