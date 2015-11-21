<?php
//check permission type//
//$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
//funcs::checkPermission($smarty, $permission_lv);	//check permission
$userid = funcs::getUserid($_GET['username']);
$_SERVER['HTTP_REFERER'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:".";

$sql = "SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 ORDER BY RAND() LIMIT 1";
$rs = mysql_query($sql);
$smarty->assign('random_profile', mysql_fetch_assoc($rs));


/**
 * User's gifts
 */

$gifts = array();

if (isset($_SESSION['sess_id'])) {

	$sql = "SELECT mg.*, g.*, ms.username AS sender, ms.picturepath, COUNT( * ) AS times FROM `member_gift` AS mg "
	. "LEFT JOIN `gift` AS g ON g.id = mg.gift_id "
	. "LEFT JOIN `member` AS ms ON ms.id = mg.sender_id "
	. "WHERE mg.member_id = " . $userid . " AND mg.sender_id = " . $_SESSION['sess_id'] . " GROUP BY g.id, mg.sender_id "
	. "ORDER BY `mg`.`created` DESC,`times` DESC LIMIT 6";
	$rs = mysql_query($sql);
	$gifts = array();
	$aaa = array();
	while ($r = mysql_fetch_assoc($rs)) {
		if(!in_array($r['gift_id'], $aaa)){
			$aaa[] = $r['gift_id'];
			$gifts[] = $r;
		}
	}
	
}
$smarty->assign('my_user_gifts', $gifts);

//if username is valid.
if($userid == $_SESSION['sess_id'])
{
	header("location: ?action=profile");
	exit;
}
elseif(isset($_GET['proc']) && ($_GET['proc']=="delete_profile_picture"))
{
	funcs2::removePic_profile($userid);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif($userid && ($userid>0))
{
	//get profile//
	$profile = funcs::getProfile($userid);
// 	$random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=".$profile['gender']." AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT 16");
// 	$smarty->assign("random_contacts", $random_contacts);

	//get answer//
	$profile['gender'] = funcs::getAnswerChoice($_SESSION['lang'],'', '$gender', $profile['gender']);
	$profile['country'] = funcs::getAnswerCountry($_SESSION['lang'], $profile['country']);
	$profile['state'] = funcs::getAnswerState($_SESSION['lang'], $profile['state']);
	$profile['city'] = funcs::getAnswerCity($_SESSION['lang'], $profile['city']);
	$smarty->assign('profile', $profile);
	$smarty->assign('fotoalbum',funcs::getAllFotoAlbum($userid));
	$smarty->assign('favorited', DBConnect::retrieve_value("SELECT id FROM favorite WHERE child_id='".$userid."' and parent_id='".$_SESSION['sess_id']."'"));

	$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('country', funcs::getChoiceCountry());
	$smarty->assign('age', funcs::getRangeAge());//Singh
}
else
{
	$url = $_SERVER['HTTP_REFERER']?$_SERVER['HTTP_REFERER']:".";
	header("location: ".$url);
	exit;
}

if(isset($_GET['part']) && ($_GET['part']=='partial'))
{
	$smarty->display($_GET['action'].'.tpl');
}
else
{
	if(isset($_GET['from']) && ($_GET['from'] == 'admin'))
		$smarty->display('admin.tpl');
	else
		$smarty->display('index.tpl');
		
	//print "<!-- UserID=$userid //-->";
}
?>