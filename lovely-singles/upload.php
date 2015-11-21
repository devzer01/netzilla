<?php
session_start();

//require file//
require_once('classes/top.class.php');	

$sess_id = $_REQUEST[sess_id];
$uploaddir = UPLOAD_DIR.$_SESSION['sess_id'].'/';
$thumbdir = UPLOAD_DIR.'thumbs/'.$_SESSION['sess_id'].'/';
$pic_old = DBconnect::assoc_query_1D_param($_SESSION['sess_id'], TABLE_MEMBER_ID, TABLE_MEMBER, TABLE_MEMBER_PICTURE);	//get old pic from database
$pic_new = $_SESSION['sess_id'].'/'.basename($_FILES['Filedata']['name']);
$img_name = $_FILES['Filedata']['name'];

if(!is_dir($uploaddir))	//check have my user id directory
	mkdir($uploaddir, 0777); //create my user id directory
if(!is_dir($thumbdir))	//check have my user id directory
	mkdir($thumbdir, 0777); //create my user id directory
	
$uploadfile = $uploaddir.basename($_FILES['Filedata']['name']);
$thumbfile = $thumbdir.basename($_FILES['Filedata']['name']);

if(copy($_FILES['Filedata']['tmp_name'], "thumbs/$img_name"))	//copy new pic file to server
{
  copy($_FILES['Filedata']['tmp_name'], "thumbs/$sess_id/foto/$img_name");
  
	funcs::ImageResize($_FILES['Filedata']['tmp_name'],array(300,165) ,$uploadfile) ; 
	funcs::ImageResize($_FILES['Filedata']['tmp_name'],array(78,98) ,$thumbfile) ; 
	if(is_file(UPLOAD_DIR.$pic_old['picturepath']));	//check have old pic file
		unlink(UPLOAD_DIR.$pic_old['picturepath']);	//delete old pic file
		
	DBconnect::update_field(TABLE_MEMBER, TABLE_MEMBER_PICTURE, $img_name, TABLE_MEMBER_ID, $sess_id);	//update picture path in database
} 

?> 
