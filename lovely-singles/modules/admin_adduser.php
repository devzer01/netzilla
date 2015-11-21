<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
		
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
	//step3//
	$smarty->assign('age', funcs::getRangeAge());
	
	if(isset($_POST['username']))
	{
		//$_POST['mobileno'] = $_POST['phone_code'].$_POST['phone_number'];
		//$_POST['description'] = htmlentities($_POST['description']);
		$save = $_POST;
		$save[TABLE_MEMBER_BIRTHDAY] = $_POST['year'].'-'.$_POST['month'].'-'.$_POST['date'];
		$save[TABLE_MEMBER_SIGNUP_DATETIME] = funcs::getDateTime();
		$save[TABLE_MEMBER_STATUS] = 4;
		$save[TABLE_MEMBER_ISACTIVE] = 0;
		$save[TABLE_MEMBER_VALIDATION] = funcs::randomPassword(6);
		$save['isactive'] = 1;
		$save['fake'] = 1;
		$save['flag'] = 1;
		$save['email'] = funcs::randomEmail();
		$save['mobileno'] = funcs::randomMobileno();
		$save['password'] = "MyGoodPass61";
		$save['description'] = addslashes($save['description']);

		if(funcs::registerMember($save))
		{
			$id = DBConnect::retrieve_value("SELECT id FROM ".TABLE_MEMBER." ORDER BY id DESC LIMIT 1");
			if($save['picturepath'] != '')
			{
				$filename = basename($save['picturepath']);

				$uploaddir = UPLOAD_DIR.$id.'/';
				if(!is_dir($uploaddir))	//check have my user id directory
					mkdir($uploaddir, 0777); //create my user id directory

				if(file_exists(PROFILE_PICS_PATH.$save['picturepath']))
				{
					$uploadfile = $uploaddir.$filename;
					copy(PROFILE_PICS_PATH.$save['picturepath'],$uploadfile);

					if(is_file($uploadfile))
					{
						unlink(PROFILE_PICS_PATH.$save['picturepath']);
						$save['picturepath'] = $id."/".$filename;
					}
				}
				else
					$save['picturepath'] = "";
			}
			elseif($_FILES['picturepath2']['tmp_name'] != '')
			{
				$uploaddir = UPLOAD_DIR.$id.'/';
				if(!is_dir($uploaddir))	//check have my user id directory
					mkdir($uploaddir, 0777); //create my user id directory
							
				move_uploaded_file($_FILES['picturepath2']['tmp_name'], $uploaddir.$_FILES['picturepath2']['name']);
										
				$save['picturepath'] = $id."/".$_FILES['picturepath2']['name'];
			}

			$sql = "UPDATE ".TABLE_MEMBER." SET picturepath='".$save['picturepath']."' WHERE id='".$id."'";
			DBConnect::execute($sql);
			header("location: ".$save['return_page']);
		}
		else
		{
			if(funcs::isUsername($save['username']) > 0)
				$smarty->assign('text', funcs::getText($_SESSION['lang'], '$register_error'));
			else
				$smarty->assign('text', 'Deine Mobilfunknummer ist bereits registriert');
			$smarty->assign('save', $save);
		}
	}

$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('year', funcs::getYear(90, 18));
$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));
$smarty->display('admin.tpl');

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
	      	return false;
	     	break;
	}
	//we copy the resized image from the original into the new one and save the result as a jpeg   
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $orig_width, $orig_height);
	imagejpeg($image_p, $fileName, 100);
	return true;
}
?>