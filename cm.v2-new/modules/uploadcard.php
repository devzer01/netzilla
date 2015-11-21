<?php 
session_start(); 
//require file//
require_once('classes/top.class.php');
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

$uploaddir =  UPLOAD_DIR_CARD; 
if(!is_dir($uploaddir)){	//check have my user id directory
	mkdir($uploaddir, 0777); //create my user id directory
} 
$arrpath = array();
if($_FILES['Filedata']['name']){ 
	$max = DBconnect::get_max_value(TABLE_CARD,TABLE_CARD_ID);
	$img = $_FILES['Filedata']['tmp_name'];  
	$ImgSize = @getimagesize($img);     
	$mime = explode('/', $ImgSize[mime]);
	$ImgType = $mime[1];
	$ImgName = "img-".($max+1).".$ImgType";
	$uploadfile = $uploaddir.$ImgName ;
	$thumb = $uploaddir."thumb/".$ImgName ;
	$LimitSize = array('600','300'); 
	$LimitSmallSize = array('200','100'); 
	funcs2::ImageResize($_FILES['Filedata']['tmp_name'],$LimitSize ,$uploadfile) ; 
	funcs2::ImageResize($_FILES['Filedata']['tmp_name'],$LimitSmallSize ,$thumb) ; 
	$arrpath[TABLE_CARD_CARDPATH] = $uploadfile;  
	$arrpath[TABLE_CARD_CARDTMP] = $thumb;  
	DBconnect::assoc_insert_1D($arrpath, TABLE_CARD);
    echo '<html><head><title>-</title></head><body>';
    echo '<script language="JavaScript" type="text/javascript">'."\n";
    echo 'alert("Completed.");window.parent.location.reload(true);';
    echo "\n".'</script></body></html>';
}
?> 