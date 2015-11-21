<?php 

$app->get("/", function () use ($app, $smarty) {
	
	$username = $_SESSION['sess_username'];
	$member = getProfileWithText($username);
	$member['desc_pending'] = isDescPendingApproval($username);
	$member['foto_pending'] = isPhotoPendingApproval($username);
	$smarty->assign('member', $member);
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$smarty->assign('fotoalbum', array_merge($dbo_member->getPhotoAlbum($username), $dbo_member->getTempPhotoAlbum($username)));
	
	$smarty->display('private/profile.tpl');
});

$app->post("/picture", function () use ($app, $smarty) {
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$userid = $dbo_member->getId($_SESSION['sess_username']);
	
	$uploaddir = UPLOAD_DIR . "/" . $userid;
	if(!is_dir($uploaddir))	mkdir($uploaddir, 0777); 
	
	$filename = time().'_'.$_FILES['profilepic']['name'];
	@move_uploaded_file($_FILES['profilepic']['tmp_name'], $uploaddir . "/" . $filename);
	
	$member = $dbo_member->getBasicMemberInfo($_SESSION['sess_username']);
	
	if(is_file(UPLOAD_DIR . "/" . $pic_old['picturepath'])) unlink(UPLOAD_DIR . "/" . $pic_old['picturepath']);
	
	$picturepath = $userid . "/" . $filename;
	
	if(PHOTO_APPROVAL == 1){
		$dbo_member->setPendingProfilePic($_SESSION['sess_username'], $picturepath);
		$picturepath = "";
	}	
});

$app->post("/fotoalbum", function () use ($app, $smarty) {
	$filename = explode('.', $_FILES['upload_file']['name']);
	$lastname = strtolower($filename[count(filename)]);
	
	if(strpos(PICTURE_EXTENSION, $lastname) !== false)
	{
		funcs::uploadFotoAlbum($_FILES['upload_file'], $_SESSION['sess_id']);
	}
	header("location: ?action=profile");	//when upload completely
});

$app->get("/mobile", function () use ($app, $smarty) {
	$smarty->display('private/profile/mobile.tpl');
});

$app->post("mobile", function () use ($app, $smarty) {

});

$app->get("/favorite", function () use ($app, $smarty) {
	
	$username = $_SESSION['sess_username'];
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	if (($favorites = $dbo_member->getFavorites($username)) === false) $favorites = array();
	$smarty->assign('favorites', $favorites);
	$smarty->display('private/profile/favorite.tpl');
});

$app->get("/edit", function () use ($app, $smarty) {
	require_once 'controller/profile/edit/get.php';
});

$app->post("/edit", function () use ($app, $smarty) {
	require_once 'controller/profile/edit/post.php';
});

$app->get("/mygifts", function () use ($app, $smarty) {
	
	$smarty->assign('mygifts', getMyGifts());
	
	$smarty->display('private/profile/mygifts.tpl');
});

$app->get("/view/:username", function ($username) use ($app, $smarty) {
	
	$username = revealUsername($username);
	$member = getProfileWithText($username);
	$smarty->assign('member', $member);
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$smarty->assign('fotoalbum', $dbo_member->getPhotoAlbum($username));
	
	$isfavorite = 0;
	if ($dbo_member->isFavorite($_SESSION['sess_username'], $username)) $isfavorite = 1;
	$smarty->assign('isfavorite', $isfavorite);
	
	$smarty->display('private/profile/view.tpl');
});

$app->get("/viewgift/:username", function ($receiver) use ($app, $smarty) {
	
	$receiver = revealUsername($receiver);
	$member = getProfileWithText($receiver);
	$smarty->assign('member', $member);
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$smarty->assign('gifts', $dbo_member->getGiftToUser($_SESSION['sess_username'], $receiver));
	
	$isfavorite = 0;
	if ($dbo_member->isFavorite($_SESSION['sess_username'], $receiver)) $isfavorite = 1;
	$smarty->assign('isfavorite', $isfavorite);
	
	$smarty->display('private/viewgift.tpl');
});


function getMyGifts()
{
	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$gifts = $dbo_member->getGifts($username);
	$mygifts = [];
	
	if (empty($gifts)) return $mygifts;
	
	foreach ($gifts as $r) {
		if(empty($mygifts[$r['gift_id']]['info'])){
			$mygifts[$r['gift_id']]['info'] = $r;
		}
		$mygifts[$r['gift_id']]['senders'][$r['sender_id']] = $r;
	}
	
	return $mygifts;
}