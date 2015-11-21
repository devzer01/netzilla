<?php
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$userid = funcs::getUserid($_GET['username']);

//if username is valid.
if($userid)
{
	//get profile//
	$profile = funcs::getProfile($userid);

	$smarty->assign('thumbpath', UPLOAD_DIR);
	//get answer//
	$profile[TABLE_MEMBER_GENDER] = funcs::getAnswerChoice($_SESSION['lang'],'', '$gender', $profile[TABLE_MEMBER_GENDER]);
	$profile[TABLE_MEMBER_COUNTRY] = funcs::getAnswerCountry($_SESSION['lang'], $profile[TABLE_MEMBER_COUNTRY]);
	$profile[TABLE_MEMBER_STATE] = funcs::getAnswerState($_SESSION['lang'], $profile[TABLE_MEMBER_STATE]);
	$profile[TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $profile[TABLE_MEMBER_CITY]);
	$profile[TABLE_MEMBER_HEIGHT] = ($profile[TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height', $profile[TABLE_MEMBER_HEIGHT]) : "";
	$profile[TABLE_MEMBER_WEIGHT] = ($profile[TABLE_MEMBER_WEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$weight', $profile[TABLE_MEMBER_WEIGHT]) : "";
	$profile[TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $profile[TABLE_MEMBER_APPEARANCE]);
	$profile[TABLE_MEMBER_EYE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$eyes_color', $profile[TABLE_MEMBER_EYE]);
	$profile[TABLE_MEMBER_HAIRCOLOR] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$hair_color', $profile[TABLE_MEMBER_HAIRCOLOR]);

	$profile[TABLE_MEMBER_HAIRLENGTH] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$hair_length', $profile[TABLE_MEMBER_HAIRLENGTH]);
	$profile[TABLE_MEMBER_BEARD] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$beard', $profile[TABLE_MEMBER_BEARD]);
	$profile[TABLE_MEMBER_ZODIAC] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$zodiac', $profile[TABLE_MEMBER_ZODIAC]);
	$profile[TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $profile[TABLE_MEMBER_CIVIL]);
	$profile[TABLE_MEMBER_SEXUALITY] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$sexuality', $profile[TABLE_MEMBER_SEXUALITY]);
	$profile[TABLE_MEMBER_TATTOS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_TATTOS]);
	$profile[TABLE_MEMBER_SMOKING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_SMOKING]);
	$profile[TABLE_MEMBER_GLASSES] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_GLASSES]);
	$profile[TABLE_MEMBER_HANDICAPPED] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_HANDICAPPED]);
	$profile[TABLE_MEMBER_PIERCINGS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PIERCINGS]);
	$profile[TABLE_MEMBER_LOOKMEN] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKMEN]);
	$profile[TABLE_MEMBER_LOOKWOMEN] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKWOMEN]);
	$profile[TABLE_MEMBER_LOOKPAIRS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LOOKPAIRS]);
	$profile[TABLE_MEMBER_RELATIONSHIP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_RELATIONSHIP]);
	$profile[TABLE_MEMBER_ONENIGHTSTAND] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_ONENIGHTSTAND]);
	$profile[TABLE_MEMBER_AFFAIR] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_AFFAIR]);
	$profile[TABLE_MEMBER_FRIENDSHIP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_FRIENDSHIP]);
	$profile[TABLE_MEMBER_CYBERSEX] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_CYBERSEX]);
	$profile[TABLE_MEMBER_PICTURE_SWAP] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PICTURE_SWAP]);
	$profile[TABLE_MEMBER_LIVE_DATING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_LIVE_DATING]);
	$profile[TABLE_MEMBER_ROLE_PLAYING] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_ROLE_PLAYING]);
	$profile[TABLE_MEMBER_S_M] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_S_M]);
	$profile[TABLE_MEMBER_PARTNER_EX] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_PARTNER_EX]);
	$profile[TABLE_MEMBER_VOYEURISM] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_VOYEURISM]);
	//$profile[TABLE_MEMBER_TATTOS] = funcs::getAnswerChoice($_SESSION['lang'],'', '$yesno', $profile[TABLE_MEMBER_TATTOS]);
	//$lonelyheart[TABLE_LONELYHEART_TARGET] = funcs::getAnswerChoice($_SESSION['lang'],'', '$targetGroup', $lonelyheart[TABLE_LONELYHEART_TARGET]);
	//$lonelyheart[TABLE_LONELYHEART_CATEGORY] = funcs::getAnswerChoice($_SESSION['lang'],'', '$category', $lonelyheart[TABLE_LONELYHEART_CATEGORY]);

	$smarty->assign('profile', $profile);
	$smarty->assign('messageid', $_GET['id']);
	$smarty->assign('fotoalbum',funcs::getAllFotoAlbum($userid));
	$smarty->assign('lonelyheartads',funcs::getAllLonely_Heart($userid,"",""));
	$smarty->assign('favorited', DBConnect::retrieve_value("SELECT id FROM favorite WHERE child_id='".$userid."' and parent_id='".$_SESSION['sess_id']."'"));
}
else
{
	header("location: ".$_SERVER['HTTP_REFERER']);
}

if($_GET['from'] == 'admin')
	$smarty->display('admin.tpl');
else
	$smarty->display('index.tpl');
	
//print "<!-- UserID=$userid //-->";
?>