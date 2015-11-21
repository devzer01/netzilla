<?php
//check permission type//
if($_GET['action'] == 'fotoalbum')
{
	$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
	$userid = $_SESSION['sess_id'];
}
elseif($_GET['action'] == 'fotoalbum_view')
{
	$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
	$userid = funcs::getUserid($_GET['username']);
}
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_FILES['upload_file']) and ($_FILES['upload_file']['name'] != ''))
{
	$filename = explode('.', $_FILES['upload_file']['name']);
	$lastname = strtolower($filename[count(filename)]);

	if(strpos(PICTURE_EXTENSION, $lastname) !== false)
	{
		funcs::uploadFotoAlbum($_FILES['upload_file'], $_SESSION['sess_id']);
	}
	header("location: ?action=profile");	//when upload completely
	exit();
}
else
{
	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		header("location: ?action=profile#fotoalbum");
		exit;
	}
	else
	{
		//send data to template//	
		$fotoalbum = funcs::getAllFotoAlbum($userid);
		$fotoalbumTemp = funcs::getAllFotoAlbumFromTemp($userid);
		$numData = count($fotoalbum)+count($fotoalbumTemp);

		if(count($fotoalbumTemp)>0){
			
			$resultTemp = array();
			for($numTemp=0;$numTemp<count($fotoalbumTemp);$numTemp++){
				$resultTemp[$numTemp]['id'] = $fotoalbumTemp[$numTemp]['id'];
				$resultTemp[$numTemp]['userid'] = $fotoalbumTemp[$numTemp]['userid'];
				//$resultTemp[$numTemp]['picturepath'] = "fotoalbum_approval.jpg";
				$resultTemp[$numTemp]['picturepath'] = $fotoalbumTemp[$numTemp]['picturepath'];
				$resultTemp[$numTemp]['datetime'] = $fotoalbumTemp[$numTemp]['datetime'];
				$resultTemp[$numTemp]['approval'] = 1;
			}

			$fotoalbum = array_merge($fotoalbum, $resultTemp);
		}

		$smarty->assign('fotoalbum', $fotoalbum);
		$smarty->assign('total', $numData);

		//select template file//
		$smarty->display('fotoalbum.tpl');
	}
}
?>