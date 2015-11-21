<?php
if(isset($_SESSION['mobileverify_redirect']))
	unset($_SESSION['mobileverify_redirect']);

//check permission type//
if($_GET['from']=='admin')
	$permission_lv = array(1, 8, 9);	//define type permission can open this page.
else
	$permission_lv = array(1, 2, 3, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

//get userid for get profile from database//
if(($_GET['proc'] == 'edit') && ($_SESSION['sess_admin'] == 1))
	$userid = funcs::getUserid($_GET['user']);
else
	$userid = $_SESSION['sess_id'];
//check who edit this user//
$edit_session = funcs2::checkEdit_user($userid);
if($edit_session['userid'] != '')
{
	$timestamp = funcs2::convertTo_timestamp($edit_session['datetime']);
	$timestamp_now = funcs2::convertTo_timestamp(date("Y-m-d h:m:s", time()));	
}

if($_GET['type'] == 'removepic')
{
	funcs2::removePic_profile($userid);
	$url = "?action=".$_GET['action'];
	if(isset($_GET['user']))
		$url .= "&user=".$_GET['user']."&proc=edit&from=admin";
	header("Location: ".$url);
	exit;
	//header("location ".$_SERVER['HTTP_REFERER']);
}

/**
 * This function resizes the main image by keeping aspect, the "left over"
 * areas will be colored white.
 *
 * @param string $fileName The file to work with.
 * @param int $constraint The constraining amount.
 * @param string $flag The dimenstion to use 'width' or 'height'.
 * @param int $tot_width The width of the total area, including white areas
 * @param int $tot_height The height of the total area, including white areas
 * @return boolean true on success, false otherwise
 */
function resizeImageWhiteBkg($fileName, $constraint, $flag, $tot_width, $tot_height){
	$background = imagecreatetruecolor($tot_width, $tot_height);
	$white = imagecolorallocate($background, 255, 255, 255);
	imagefill($background, 0, 0, $white);
	if(!resizeImage($fileName, $constraint, $flag))
		return false;
	list($front_width, $front_height, $type) = getimagesize($fileName);
	$front_image = imagecreatefromjpeg($fileName);
	$bkg_xpos = round(($tot_width - $front_width) / 2);
	$bkg_ypos = round(($tot_height - $front_height) / 2);
	imagecopy($background, $front_image, $bkg_xpos, $bkg_ypos, 0, 0, $front_width, $front_height);
	imagejpeg($background, $fileName, 100);
	return true;
}

/**
* Resizes images by using a constraint on one side. 
* 
* If I for instance set the flag variable to "width" and the constraint 
* variable to 200 when I call the function on an image that is 600*400 the
* result will be an image with the resolution 200*133.
*
* @param string $fileName The path to the file to use.
* @param int $constraint The length to resize one of the sides to.
* @param string $flag The side to use, width or height.
* @return bool Returns true on success.
*/
function resizeImage($fileName, $constraint, $flag){
	//we retrieve the info from the current image
	list($orig_width, $orig_height, $type) = getimagesize($fileName);
  	$new_width	='';
  	$new_height	='';
	if($flag == "width"){
  		if($orig_width > $constraint){
	   		$new_height=round(( $constraint * $orig_height) / $orig_width);
	   		$new_width = $constraint;
  		}else{
  			$new_height = $orig_height;
  			$new_width = $orig_width;
  		}
  	}else{
  		if($orig_height > $constraint){
	   		$new_width=round(( $constraint * $orig_width) / $orig_height);
   			$new_height = $constraint;
  		}else{
  			$new_height = $orig_height;
  			$new_width = $orig_width;
  		}
	}		

    //we create a new image template
    $image_p = imagecreatetruecolor($new_width, $new_height);
    //we create a variable that will hold the new image
    $image = null;
    //only the three first of all the possible formats are supported, the original image is loaded if it is one of them
	switch($type){
	     case 1: //GIF
			$image = imagecreatefromgif($fileName);
			break;
	     case 2: //JPEG
	      	$image = imagecreatefromjpeg($fileName);
	      	break;
	     case 3: //PNG
	      	$image = imagecreatefrompng($fileName);
	      	break;
	     default;
			exec("convert ".$fileName." ".$fileName.".jpg");
			exec("rm ".$fileName);
			exec("mv ".$fileName.".jpg ".$fileName);
	      	$image = imagecreatefromjpeg($fileName);
	     	break;
	}
	//we copy the resized image from the original into the new one and save the result as a jpeg   
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
	imagejpeg($image_p, $fileName, 100);
	return true;
}

$profile = funcs::getProfile($userid);	//get profile data
$_POST['description']=addslashes($_POST['description']);
$_POST['description']=funcs::removeEmailAddressFromText($_POST['description']);

if($_FILES['picturepath']['tmp_name'] != ''){
	$save = $_POST;
	$uploaddir = UPLOAD_DIR.$profile['id'].'/';
	if(!is_dir($uploaddir))	//check have my user id directory
		mkdir($uploaddir, 0777); //create my user id directory
	
	$filename = time().'_'.$_FILES['picturepath']['name'];
	
	@move_uploaded_file($_FILES['picturepath']['tmp_name'], $uploaddir.$filename);
	
	//resizeImageWhiteBkg($uploaddir.$filename, 105, 'width', 105, 120);
	
	$pic_old = DBconnect::assoc_query_1D_param($profile['id'], TABLE_MEMBER_ID, TABLE_MEMBER, TABLE_MEMBER_PICTURE);
	
	if(is_file(UPLOAD_DIR.$pic_old['picturepath']))
		unlink(UPLOAD_DIR.$pic_old['picturepath']);

	$save['picturepath'] = $profile['id']."/".$filename;
	
	$old_data=DBConnect::assoc_query_1D("SELECT description FROM member WHERE id=".$userid);
	if(($old_data['description'] == '') && ($save['description'] != ''))
	{
		$save['id'] = $userid;
		funcs::addLonelyHeartFromDescription($save);
	}
	
	if(PHOTO_APPROVAL == 1){
		//---- Update Photo to Temp
			funcs::updatePhotoToTemp($userid, $save['picturepath']);
			$save = funcs::array_remove_key($photo, "picturepath");
		//---------------------------		
	}
	funcs::updateProfile($userid, $save);
	if($_GET['from']=="admin")
		header("Location: ?action=".$_GET['action']."&user=".$_GET['user']."&proc=".$_GET['proc']."&from=admin");
	else
		header("Location:?action=editprofile");
	exit;
}
elseif(isset($_POST['submit_button']))
{
	$save = $_POST;	
	$save[TABLE_MEMBER_BIRTHDAY] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];

	if(($save['picturepath'] != '') && ($profile['picturepath'] != $save['picturepath']))
	{
		$filename = basename($save['picturepath']);

		$uploaddir = UPLOAD_DIR.$userid.'/';
		if(!is_dir($uploaddir))	//check have my user id directory
			mkdir($uploaddir, 0777); //create my user id directory

		if(file_exists(PROFILE_PICS_PATH.$save['picturepath']))
		{
			$uploadfile = $uploaddir.$filename;
			copy(PROFILE_PICS_PATH.$save['picturepath'],$uploadfile);

			if(is_file($uploadfile))
			{
				unlink(PROFILE_PICS_PATH.$save['picturepath']);
				$save['picturepath'] = $userid."/".$filename;
			}
			
			$pic_old = DBconnect::assoc_query_1D_param($profile['id'], TABLE_MEMBER_ID, TABLE_MEMBER, TABLE_MEMBER_PICTURE);	//get old pic from database

			$uploadfile = $uploaddir.$filename;

			if(is_file(UPLOAD_DIR.$pic_old['picturepath']))	//check have old pic file
				unlink(UPLOAD_DIR.$pic_old['picturepath']);	//delete old pic file
		}
		else
			$save['picturepath'] = "";
	}

	if(($_GET['from'] == 'admin') && ($_GET['tool'] != 'new_members'))
	{	
		$query = mysql_query("UPDATE ".TABLE_MEMBER." SET flag='1' WHERE ".TABLE_MEMBER_ID."='$userid'");
		
		//LOGGING		
		$userid = $_SESSION['sess_id'];
		$realname = '';
		
		switch ($userid) {
		case 8:
		    $realname = "Silke";
		    break;
		case 14:
		    $realname = "Barbara";
		    break;
		case 18:
		    $realname = "Timo";
		    break;
		case 38:
		    $realname = "Ivone";
		    break;   
		case 45:
		    $realname = "Hauke";
		    break;
		case 50:
		    $realname = "Laura";
		    break;   
		case 54:
		    $realname = "Heiko";
		    break;
		case 67:
		    $realname = "HaukeP6";
		    break;   
		case 69:
		    $realname = "HaukeP7";
		    break; 
		case 3389:
		    $realname = "Anja";
		    break;    
		case 7459:
		    $realname = "Sina";
		    break; 
		case 7518:
		    $realname = "HaukeP8";
		    break; 
		case 7749:
		    $realname = "HaukeP9";
		    break;     
		case 8225:
		    $realname = "HaukeP10";
		    break;
		case 8399:
		    $realname = "Marko";
		    break;	       
		case 8584:
		    $realname = "Lisa";
		    break;
		case 8651:
		    $realname = "Markus";
		    break;         
		case 11004:
		    $realname = "HaukeP4";
		    break;    
		case 11005:
		    $realname = "Martin";
		    break;
		case 11008:
		    $realname = "Susanne";
		    break;    
		case 11014:
		    $realname = "Sonja";
		    break;
		case 11023:
		    $realname = "HaukeP3";
		    break;   
		case 59014:
		    $realname = "Mike";
		    break;
		case 71637:
		    $realname = "HaukeP5";
		    break;    
		}
		
		
		$sql1 = "SELECT date, edited FROM stud_logging WHERE userid = '".$userid."' AND date ='".date("m.d.y")."'";
		
		$rec = DBconnect::assoc_query_1D($sql1);
		
		$counter = $rec['edited'] + 1;
		if ($rec['date'] != ''){
			$sql2 = "UPDATE stud_logging SET logoff ='".date("H:i:s")."' , edited = ".$counter." WHERE userid ='".$userid."' AND date ='".date("m.d.y")."'";
		}
		else {
			$sql2 ="INSERT INTO stud_logging(userid,date,login,logoff,edited,realname) VALUES ('$userid','".date("m.d.y")."','".date("H:i:s")."','".date("H:i:s")."',1,'$realname')";
		}		
		DBconnect::execute($sql2);			
	}

	$old_data=DBConnect::assoc_query_1D("SELECT description FROM member WHERE id=".$userid);
	if(($old_data['description'] == '') && ($save['description'] != ''))
	{
		$save['id'] = $profile['id'];
		funcs::addLonelyHeartFromDescription($save);
	}

	$save['zodiac'] = funcs::getZodiac($_POST['month'].'-'.$_POST['date']);
	funcs::updateProfile($profile['id'], $save);
	
	//=======================================//
	if(($_SESSION['sess_admin']==1) && ($_GET['from']=='admin'))
	{		
		if($_GET['tool'] != 'new_members')
		{
			$no = 0;
			foreach($_SESSION['list'] as $member)
			{
				if($member['username'] == $_GET['user'])
				{
					break;
				}
				$no++;
			}
			$next = $_SESSION['list'][$no+1]['username'];
		
			$url = $_SERVER['HTTP_REFERER'];
			$get = funcs::getVariablesFromURL($url);
			$url="";
			foreach($get as $key => $value)
			{
				if(($key == "user") && ($next != ''))
					$url .= $key."=".$next."&";
				else
					$url .= $key."=".$value."&";
			}	
			$url = "action=editprofile&user=".$_POST['username']."&proc=edit&from=admin&";
			header("location: index.php?".$url);
		}
		else
		{
			header("location: index.php?action=admin_new_members&r=search");
		}
		exit();
	}
	else
	{
		header("Location: ?action=editprofile");
		exit();
	}

	//=======================================//
}

$profile['return_url'] = $_POST['return_url'];
list($profile['year'], $profile['month'], $profile['date']) = explode('-', $profile['birthday']);	//get date, month and year from birthday

//send choice to template//
//step1//
$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('year', funcs::getYear());

//step2//
$smarty->assign('height', funcs::getChoice($_SESSION['lang'],'','$height'));
$smarty->assign('weight', funcs::getChoice($_SESSION['lang'],'','$weight'));
$smarty->assign('appearance', funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'));
$smarty->assign('eyescolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$eyes_color'));
$smarty->assign('haircolor', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_color'));
$smarty->assign('hairlength', funcs::getChoice($_SESSION['lang'],'$nocomment','$hair_length'));
$smarty->assign('beard', funcs::getChoice($_SESSION['lang'],'$nocomment','$beard'));
$smarty->assign('zodiac', funcs::getChoice($_SESSION['lang'],'$nocomment','$zodiac'));
$smarty->assign('status', funcs::getChoice($_SESSION['lang'],'$nocomment','$status'));
$smarty->assign('sexuality', funcs::getChoice($_SESSION['lang'],'$nocomment','$sexuality'));

$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));

$smarty->assign('config_image', PICTURE_EXTENSION);
//send data profile to template//	
//----- Find Profile Picture
		if($save['picturepath'] == ""){
			$numPhotoTemp = funcs::getPhotoProfileTempOfEmailChatID($profile['id']);			
				if(count($numPhotoTemp) > 0){
					$profile['picturepath'] = "default_upload.jpg";
				}
		}
	//----------------------------------
/**
 * START PROGRESS BAR
 **/
include('modules/checkprofile.php');
$smarty->assign('total_score', number_format($total_score));
$smarty->assign('progress_score', $progress_final);
/**
 * END PROGRESS BAR
 */
$bhd_array = explode("-",$profile['birthday']);
$profile['date'] = $bhd_array[2];
$profile['month'] = $bhd_array[1];
$profile['year'] = $bhd_array[0];
$smarty->assign('save', $profile);
//select template file//
if(isset($_GET['from']) && ($_GET['from'] == 'admin'))
	$smarty->display('admin.tpl');
else
	$smarty->display('index.tpl');
?>