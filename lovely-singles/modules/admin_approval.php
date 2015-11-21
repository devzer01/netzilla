<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$types_arr = array("profile", "gallery", "description", "delete_account");
$_GET['type'] = isset($_GET['type'])?trim($_GET['type']):"";
if(!in_array($_GET['type'], $types_arr))
	$_GET['type'] = $types_arr[0];

function deletePicture($id)
{
	global $smarty;
	$pic = DBConnect::assoc_query_1D("SELECT userid, picturepath, status, datetime FROM phototemp WHERE id='".$id."'");
	$userdata = DBConnect::assoc_query_1D("SELECT username, email FROM member WHERE id='".$pic['userid']."'");
	$subject = funcs::getText($_SESSION['lang'], '$email_delete_picture_subject');	//get subject message
	$smarty->assign('username', $userdata['username']);
	$smarty->assign('url_web', URL_WEB);
	$smarty->assign('pic', $pic['picturepath']);
	$message = $smarty->fetch('email_delete_picture.tpl');
	
	funcs::sendMail($userdata['email'], $subject, $message, MAIL_FROM);	//send message to email

	/*
	$filename = $pic['picturepath'];
	if($filename)
	{
		if(is_file(UPLOAD_DIR.$filename))
			unlink(UPLOAD_DIR.$filename);
	}*/
	DBConnect::execute_q("DELETE FROM phototemp WHERE id='".$id."'");
}

function approvePicture($id)
{
	$pic = DBConnect::assoc_query_1D("SELECT userid, picturepath, status, datetime FROM phototemp WHERE id='".$id."'");
	if($pic['status'] == 1)
		DBConnect::execute_q("UPDATE member SET picturepath='".$pic['picturepath']."' WHERE id='".$pic['userid']."'");
	else
		DBConnect::execute_q("INSERT INTO fotoalbum (userid, picturepath, datetime) VALUES ('".$pic['userid']."', '".$pic['picturepath']."','".$pic['datetime']."')");
	DBConnect::execute_q("DELETE FROM phototemp WHERE id='".$id."'");
}

function rotatePicture($id, $direction)
{
	$pic = DBConnect::assoc_query_1D("SELECT picturepath FROM phototemp WHERE id='".$id."'");
	if($pic['picturepath']!="")
	{
		$picturepath = $pic['picturepath'];
	
		// File and rotation
		$filename = UPLOAD_DIR.$picturepath; //"sites/flirt48.net/thumbs/1/1370419090_images.jpg"; //
		if($direction=="left")
			$degrees = 90;
		else
			$degrees = -90;
		
		// Load
		$source = imagecreatefromjpeg($filename);
		
		// Rotate
		$rotate = imagerotate($source, $degrees, 0);
		
		// Output
		imagejpeg($rotate, $filename);
		
		// Free the memory
		imagedestroy($source);
		imagedestroy($rotate);
	}
}

function approveDescription($id)
{
	$description = DBConnect::assoc_query_1D("SELECT userid, description, datetime FROM description_temp WHERE id='".$id."'");
	$description['description'] = funcs::removeEmailAddressFromText($description['description']);

	DBConnect::execute_q("UPDATE member SET description='".addslashes($description['description'])."' WHERE id=".$description['userid']);
	DBConnect::execute_q("DELETE FROM description_temp WHERE id='".$id."'");
}

function deleteDescription($id)
{
	DBConnect::execute_q("DELETE FROM description_temp WHERE id='".$id."'");
}

if(isset($_GET['delete_picture']) && ($_GET['delete_picture']!=""))
{
	deletePicture($_GET['delete_picture']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['approve_picture']) && ($_GET['approve_picture']!=""))
{
	approvePicture($_GET['approve_picture']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['rotate_left_picture']) && ($_GET['rotate_left_picture']!=""))
{
	rotatePicture($_GET['rotate_left_picture'],"left");
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['rotate_right_picture']) && ($_GET['rotate_right_picture']!=""))
{
	rotatePicture($_GET['rotate_right_picture'],"right");
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['delete_description']) && ($_GET['delete_description']!=""))
{
	deleteDescription($_GET['delete_description']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_GET['approve_description']) && ($_GET['approve_description']!=""))
{
	approveDescription($_GET['approve_description']);
	header("location: ".$_SERVER['HTTP_REFERER']);
	exit;
}
elseif(isset($_POST['submit']) && ($_POST['submit']!=""))
{
	$status = "2";
	switch($_GET['type'])
	{
		case "profile":
		case "gallery":
			foreach($_POST['id'] as $id)
			{
				if($_POST['submit']=="Delete")
				{
					deletePicture($id);
				}
				else
				{
					approvePicture($id);
				}
			}
			break;
		case "description":
			foreach($_POST['id'] as $id)
			{
				if($_POST['submit']=="Delete")
				{
					deleteDescription($id);
				}
				else
				{
					approveDescription($id);
				}
			}
			break;
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

	$status = "2";
	switch($_GET['type'])
	{
		case "profile":
			$status = "1";
		case "gallery":
			$from = "FROM phototemp f LEFT JOIN member m ON f.userid=m.id";
			$where = "WHERE m.isactive=1 AND f.status=".$status;
			$order = "ORDER BY f.id ASC";
			$countMember = DBConnect::retrieve_value("SELECT count(*) $from $where");
			$list = DBConnect::assoc_query_2D("SELECT m.username, f.id, f.picturepath $from $where $order LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit());
			$smarty->assign('list',$list);
			break;
		case "description":
			$from = "FROM description_temp f LEFT JOIN member m ON f.userid=m.id LEFT JOIN xml_countries c ON m.country = c.id";
			$where = "WHERE m.isactive=1 AND c.status=1";
			$order = "ORDER BY f.id ASC";
			$countMember = DBConnect::retrieve_value("SELECT count(*) $from $where");
			$list = DBConnect::assoc_query_2D("SELECT m.username, f.id, f.description $from $where $order LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit());
			$smarty->assign('descriptions',$list);
			break;
		case "delete_account":
			$from = "FROM delete_account d LEFT JOIN member m ON d.userid=m.id";
			$where = "WHERE m.isactive=1";
			$order = "ORDER BY d.delete_datetime DESC";
			$countMember = DBConnect::retrieve_value("SELECT count(*) $from $where");
			$list = DBConnect::assoc_query_2D("SELECT m.username, d.id, d.delete_datetime $from $where $order LIMIT ".SmartyPaginate::getCurrentIndex().", ".SmartyPaginate::getLimit());
			$smarty->assign('delete_accounts',$list);
			break;
	}

	SmartyPaginate::setTotal($countMember);
	SmartyPaginate::assign($smarty);
	$smarty->assign('submenu','admin_approval');
	$smarty->display('admin.tpl');
}
?> 