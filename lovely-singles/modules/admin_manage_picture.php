<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

function deleteProfilePicture($id)
{
	$filename = DBConnect::retrieve_value("SELECT picturepath FROM member WHERE id='".$id."'");
	if($filename)
	{
		if(is_file("thumbs/".$filename))
			unlink("thumbs/".$filename);
		DBConnect::execute_q("UPDATE member SET picturepath='' WHERE id='".$id."'");
	}
}

function deleteGalleryPicture($id)
{
	$filename = DBConnect::retrieve_value("SELECT picturepath FROM fotoalbum WHERE id='".$id."'");
	if($filename)
	{
		if(is_file("thumbs/".$filename))
			unlink("thumbs/".$filename);
		DBConnect::execute_q("DELETE FROM fotoalbum WHERE id='".$id."'");
	}
}

if(isset($_GET['delete_profile_picture']) && ($_GET['delete_profile_picture']!=""))
{
	deleteProfilePicture($_GET['delete_profile_picture']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['delete_gallery_picture']) && ($_GET['delete_gallery_picture']!=""))
{
	deleteGalleryPicture($_GET['delete_gallery_picture']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_POST['submit']) && ($_POST['submit']!=""))
{
	if($_POST['type']=="profile")
	{
		foreach($_POST['id'] as $id)
		{
			deleteProfilePicture($id);
		}
	}
	elseif($_POST['type']=="gallery")
	{
		foreach($_POST['id'] as $id)
		{
			deleteGalleryPicture($id);
		}
	}
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
else
{
	SmartyPaginate::connect();
	SmartyPaginate::setLimit(100); //smarty paging set records per page
	SmartyPaginate::setPageLimit(40); //smarty paging set limit pages show
	SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL
	if(!isset($_GET['next']))
		SmartyPaginate::setCurrentItem(1); //go to first record

	if(isset($_GET['type']) && ($_GET['type']=='gallery'))
	{
		$from = "FROM fotoalbum f LEFT JOIN member m ON f.userid=m.id LEFT JOIN xml_countries c ON m.country = c.id";
		$where = "WHERE m.isactive=1 AND c.status=1";
		$order = "ORDER BY m.fake ASC, m.username ASC";
		$countMember = DBConnect::retrieve_value("SELECT count(*) $from $where");
		$list = DBConnect::assoc_query_2D("SELECT m.username, f.id, f.picturepath $from $where $order LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit());
	}
	elseif(isset($_GET['type']) && ($_GET['type']=='profile'))
	{
		$from = "FROM member m LEFT JOIN xml_countries c ON m.country = c.id";
		$where = "WHERE m.picturepath != '' AND m.isactive=1 AND c.status=1";
		$order = "ORDER BY m.fake ASC, m.username ASC";
		$countMember = DBConnect::retrieve_value("SELECT count(*) $from $where");
		$list = DBConnect::assoc_query_2D("SELECT m.id, m.username, m.picturepath $from $where $order LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit());
	}
	else
	{
		header("location: ?action=admin_manage_picture&type=profile");
		exit;
	}

	SmartyPaginate::setTotal($countMember);
	SmartyPaginate::assign($smarty);
	$smarty->assign('list',$list);
	$smarty->assign('submenu','admin_manage_picture');
	$smarty->display('admin.tpl');
}
?> 