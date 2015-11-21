<?php
if(isset($_SESSION['mobileverify_redirect']))
	unset($_SESSION['mobileverify_redirect']);

$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$userid = $_SESSION['sess_id'];
$profile = funcs::getProfile($userid);	//get profile data
$allowed_keys = array(
	"gender", "city",'state', 'country', 'picturepath', 'lookmen', 'lookwomen', 'description', 'birthday'
);

if(isset($_GET['proc']) && ($_GET['proc']=="delete_profile_picture"))
{
	funcs2::removePic_profile($userid);
	header("location: ?action=profile");
	exit;
}
elseif(isset($_FILES['profilepic']['tmp_name']))
{
	$uploaddir = UPLOAD_DIR.$userid.'/';
	if(!is_dir($uploaddir))	//check have my user id directory
		mkdir($uploaddir, 0777); //create my user id directory
	
	$filename = time().'_'.$_FILES['profilepic']['name'];
	@move_uploaded_file($_FILES['profilepic']['tmp_name'], $uploaddir.$filename);
	
	$pic_old = DBconnect::assoc_query_1D_param($userid, TABLE_MEMBER_ID, TABLE_MEMBER, TABLE_MEMBER_PICTURE);
	
	if(is_file(UPLOAD_DIR.$pic_old['picturepath']))
		unlink(UPLOAD_DIR.$pic_old['picturepath']);

	$picturepath = $userid."/".$filename;

	if(PHOTO_APPROVAL == 1){
			funcs::updatePhotoToTemp($userid, $userid."/".$filename);
			$picturepath = "";
	}

	DBConnect::execute_q("UPDATE member SET picturepath='".$picturepath."' WHERE id=".$userid);
	header("location: ?action=profile");
	exit;
}
elseif(isset($_POST['save_profile']) && ($_POST['save_profile']==$userid))
{
	$_POST['description']=addslashes($_POST['description']);
	$_POST['birthday'] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
	$save = $_POST;

	if($_FILES['picturepath']['tmp_name'] != ''){
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

		if(PHOTO_APPROVAL == 1){
				funcs::updatePhotoToTemp($userid, $save['picturepath']);
				$save['picturepath'] = "";
			//---------------------------		
		}
	}

	$old_data=DBConnect::assoc_query_1D("SELECT description FROM member WHERE id=".$userid);
	if($old_data['description'] != $save['description'])
	{
		if(DESCRIPTION_APPROVAL == 1)
		{
			DBConnect::execute_q("DELETE FROM description_temp WHERE userid=".$userid);
			DBConnect::execute_q("INSERT INTO description_temp (userid, description, datetime) VALUES (".$userid.", '".$_POST['description']."', NOW())");
			$save['description'] = "";
		}
		else
		{
			$_POST['description']=funcs::removeEmailAddressFromText($_POST['description']);
		}
	}
		
	$profile = array_intersect_key($save, array_flip($allowed_keys));
	funcs::updateProfile($userid, $profile);
	header("location: ?action=profile");
}
else
{
	if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		header("location: ?action=profile#editprofile");
		exit;
	}
	else
	{
		if($profile['lookmen']==2)
		{
			$profile['lookmen'] = ($profile['gender']==2)?1:0;
		}
		if($profile['lookwomen']==2)
		{
			$profile['lookwomen'] = ($profile['gender']==2)?0:1;
		}
		$description_temp = DBConnect::retrieve_value("SELECT description FROM description_temp WHERE userid=".$userid." ORDER BY id DESC LIMIT 1");
		if($description_temp && ($profile['description']==''))
		{
			$profile['description'] = $description_temp;
		}

		$profile['return_url'] = $_POST['return_url'];
		list($profile['year'], $profile['month'], $profile['date']) = explode('-', $profile['birthday']);	//get date, month and year from birthday

		//send choice to template//
		if($_SESSION['show_mobileverify']==1)
		{
			$smarty->assign('show_mobileverify', 1);
			unset($_SESSION['show_mobileverify']);
		}
		$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
		$smarty->assign('date', funcs::getRangeAge(1,31));
		$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
		$smarty->assign('year', funcs::getYear(90,19));
		$smarty->assign('yesno', funcs::getChoice($_SESSION['lang'],'','$yesno'));

		$bhd_array = explode("-",$profile['birthday']);
		$profile['date'] = $bhd_array[2];
		$profile['month'] = $bhd_array[1];
		$profile['year'] = $bhd_array[0];
		$smarty->assign('save', $profile);
		$smarty->display('editprofile.tpl');
	}
}





























////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////
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
?>