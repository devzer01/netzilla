<?php
//check permission type//
if($_GET['action'] == 'fotoalbum')
{
	$permission_lv = array(1, 2, 8);	//define type permission can open this page.
	$userid = $_SESSION['sess_id'];
}
if($_GET['action'] == 'fotoalbum_view')
{
	$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
	$userid = funcs::getUserid($_GET['username']);
}
funcs::checkPermission($smarty, $permission_lv);	//check permission
//delete foto album//
if(isset($_POST['delete_button']) and ($_POST['delete_button'] != ''))
{
	if(funcs::deleteFotoAlbum($_POST['fotoid'], $_SESSION['sess_id']))
		header("Location: ?action=foto_album");
}
//upload foto album//
if(isset($_POST['upload_button']) and ($_POST['upload_button'] != ''))
{
	$filename = explode('.', $_FILES['upload_file']['name']);
	$lastname = strtolower($filename[count(filename)]);

	if(strpos(PICTURE_EXTENSION, $lastname) !== false)
	{
		if(funcs::uploadFotoAlbum($_FILES['upload_file'], $_SESSION['sess_id']))
			header("Location: ?action=fotoalbum");	//when upload completely
	}
	else
		$smarty->assign('text', funcs::getText($_SESSION['lang'], '$fotoalbum_alert'));	//when can not upload
}
//send data to template//	
	$fotoalbum = funcs::getAllFotoAlbum($userid);
	if(empty($_GET['site_id'])){
	$fotoalbum = funcs::getAllFotoAlbum($userid);
	foreach($fotoalbum as &$image)
		$image['picturepath'] = ExtCall::getSiteUrl('HERZOASE')."/thumbs/{$image['picturepath']}";
	}else
	$fotoalbum = ExtCall::getExtAlbum($_GET['username'], $_GET['site_id']);

	$fotoalbumTemp = funcs::getAllFotoAlbumFromTemp($userid);
	$numData = count($fotoalbum)+count($fotoalbumTemp);

	if(count($fotoalbumTemp)>0){
		
		$resultTemp = array();
		for($numTemp=0;$numTemp<count($fotoalbumTemp);$numTemp++){
			$resultTemp[$numTemp][id] = $fotoalbumTemp[$numTemp][id];
			$resultTemp[$numTemp][userid] = $fotoalbumTemp[$numTemp][userid];
			$resultTemp[$numTemp][picturepath] = "../thumbnails.php?file=default_upload.jpg";
			$resultTemp[$numTemp][datetime] = $fotoalbumTemp[$numTemp][datetime];
		}

		$fotoalbum = array_merge($fotoalbum, $resultTemp);
	}

	$smarty->assign('fotoalbum', $fotoalbum);
	$smarty->assign('total', $numData);
	

//select template file//
	$smarty->display('index.tpl');
?>