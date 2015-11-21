<?php
//check permission type//
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

$EmailChatID = $_REQUEST[EmailChatID];
$userid = $_SESSION[sess_id];

if(isset($_POST[approve]) || isset($_POST[deny])){
	if(isset($_POST[approve])){
		//Approve Photo
			if(count($_POST[ch_photo])>0){
				Photo::ApprovePhoto($EmailChatID,$_POST[ch_photo],$_POST[ch_fsk18]);
			}
	}elseif(isset($_POST[deny])){
		//Denine Photo
			if(count($_POST[ch_photo])>0){
				Photo::DeninePhoto($EmailChatID,$_POST[ch_photo]);
			}
	}
}

//--- Photo Album
$PhotoAlbumDetails = Photo::getPhotoAlbumByEmailChatID($EmailChatID,$userid);
if($PhotoAlbumDetails != 0){
	$smarty->assign("PhotoAlbumDetails", $PhotoAlbumDetails);
	$smarty->assign("numPhotoAlbumDetails", count($PhotoAlbumDetails));	
}else{
	header("location: ./?action=photoapprove");	
}
//------------------------------

$smarty->display('admin.tpl');
?>