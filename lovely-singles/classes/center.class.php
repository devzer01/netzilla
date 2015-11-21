<?php
class Center
{
	static function login($username, $password)
	{
		if($id = DBConnect::retrieve_value("SELECT id FROM member WHERE username='{$username}' AND password='{$password}' AND photoapprove='1'"))
		{
			$_SESSION['emailchat_profile_user_id'] = $id;
			return $id;
		}
		else
		{
			return false;
		}
	}

	static function array_remove_key($arr, $key_remove)
	{
		$temp = array();
		foreach($arr as $key => $val)
		{
			if($key != $key_remove)
				$temp[$key] = $val;
		}

		return $temp;
	}

	static function getNoActivePhoto($site){			

		$site = Center::getFlirtpubSites($site);

		$post = array(
					"action"			=> "getPhotoTemp",
					"access_password"	=> "this_is_only_for_administration_purpose"					
				);

		$list = HttpRequest::sendReq($site['url'], $post);

		return $list;
	}

	static function getFlirtpubSites($id)
	{
		if($id == 0)
			$sites = DBConnect::assoc_query_2D("SELECT * FROM sites WHERE name <> 'Global DB' ORDER BY id");
		else
			$sites = DBConnect::assoc_query_1D("SELECT * FROM sites WHERE id = ".$id);
		return $sites;
	}

	static function getProfileList($site, $name, $id, $start, $num)
	{
		$site = Center::getFlirtpubSites($site);

		$post = array(
					"action"			=> "get_profile",
					"access_password"	=> "this_is_only_for_administration_purpose",
					"option"			=> "(fake=0)",
					"start"				=> $start,
					"num"				=> $num
				);
		if($id != '')
			$post['id'] = $id;
		elseif($name != '')
			$post['username'] = $name;

		$list = HttpRequest::sendReq($site['url'], $post);

		return $list;
	}

	static function getNextProfile($site, $name, $id)
	{
		$site = Center::getFlirtpubSites($site);

		$post = array(
					"action"			=> "get_next_profile",
					"access_password"	=> "this_is_only_for_administration_purpose",
					"username"			=> $name,
					"id"				=> $id
				);

		$list = HttpRequest::sendReq($site['url'], $post);

		return $list;
	}

	static function saveProfile($site, $save, $id)
	{
		$site = Center::getFlirtpubSites($site);

		$post = array(
					"action"			=> "save_profile",
					"access_password"	=> "this_is_only_for_administration_purpose",
					"save"				=> $save,
					"id"				=> $id
				);

		return HttpRequest::sendReq($site['url'], $post);
	}

	static function uploadImage($site, $id, $temp_filename)
	{
		if(!is_dir("thumbs"))
			mkdir("thumbs", 0777);
		if(!is_dir("thumbs/thumbs"))
			mkdir("thumbs/thumbs", 0777);

		$uploaddir = "thumbs/$id/";
		$thumbdir = "thumbs/thumbs/$id/";

		if(!is_dir($uploaddir))
			mkdir($uploaddir, 0777);
		if(!is_dir($thumbdir))
			mkdir($thumbdir, 0777);

		list($orig_width, $orig_height, $type) = getimagesize($temp_filename);
		switch($type){
			 case 1: //GIF
				$ext = ".gif";
				break;
			 case 2: //JPEG
				$ext = ".jpg";
				break;
			 case 3: //PNG
				$ext = ".png";
				break;
			 default;
				$ext = ".jpg";
				break;
		}

		$filename = uniqid().$ext;
		copy($temp_filename, $uploaddir.$filename);
		copy($temp_filename, $thumbdir.$filename);

		Center::resizeImageWhiteBkg($uploaddir.$filename, 105, 'width', 105, 120);
		Center::resizeImageWhiteBkg($thumbdir.$filename, 90, 'width', 90, 103);

		$site = Center::getFlirtpubSites($site);
		$src_path = pathinfo($_SERVER["HTTP_REFERER"]);
		$src_path = $src_path['dirname'];

		$post = array(
					"action"			=> "upload_image",
					"access_password"	=> "this_is_only_for_administration_purpose",
					"id"				=> $id,
					"src_path"			=> $src_path."/",
					"pic1"				=> $uploaddir.$filename,
					"pic2"				=> $thumbdir.$filename
				);
		HttpRequest::sendReq($site['url'], $post);

		unlink($uploaddir.$filename);
		rmdir($uploaddir);
		unlink($thumbdir.$filename);
		rmdir($thumbdir);

		return $id."/".$filename;
	}

	static function uploadAlbumImage($site, $id, $file)
	{
		$temp_filename = $file['tmp_name'];
		list($orig_width, $orig_height, $type) = getimagesize($temp_filename);
		switch($type){
			 case 1: //GIF
				$ext = ".gif";
				break;
			 case 2: //JPEG
				$ext = ".jpg";
				break;
			 case 3: //PNG
				$ext = ".png";
				break;
			 default;
				$ext = ".jpg";
				break;
		}

		$filename = uniqid().$ext;
		copy($temp_filename, "temp/".$filename);

		$site = Center::getFlirtpubSites($site);
		$src_path = pathinfo($_SERVER["HTTP_REFERER"]);
		$src_path = $src_path['dirname'];

		$post = array(
					"action"			=> "upload_album_image",
					"access_password"	=> "this_is_only_for_administration_purpose",
					"id"				=> $id,
					"src_path"			=> $src_path."/",
					"pic"				=> "temp/".$filename,
					"filename"			=> $file['name']
				);
		HttpRequest::sendReq($site['url'], $post);
		unlink("temp/".$filename);
	}

	static function getChoices($site)
	{
		$site = Center::getFlirtpubSites($site);

		$post = array(
					"action"			=> "getChoices",
					"access_password"	=> "this_is_only_for_administration_purpose",
				);
		$choice = HttpRequest::sendReq($site['url'], $post);

		return $choice;
	}

	static function sendAdminCancelEmail($booking_number, &$smarty)
	{
		list($site, $member_id, $id) = split("-", $booking_number, 3);
		$sites = Center::getFlirtpubSites($site);

		$entry = Center::getBookingList($booking_number, 0, 0, 1);

		$entry['list'][0]['site'] = $sites[0]['name'];
		$smarty->assign('entry', $entry['list'][0]);
		$message =  $smarty->fetch('admin_cancel_email.tpl');

		return Center::send_email(MAIL_ADMIN_EMAIL, MAIL_ADMIN_NAME, "New cancel payment member.", $message);
	}

	/**
	* Send email
	* @param
	*/
	static function send_email($email,$name,$subject,$message)
	{
		require_once('Mail.php');
		$params["host"] = MAIL_HOST;
		$params["port"] = MAIL_PORT;
		$params["auth"] = MAIL_AUTH;
		$params["username"] = MAIL_USERNAME;
		$params["password"] = MAIL_PASSWORD;
		$params['persist'] = true;

		$headers['From'] = MAIL_FROM;
		$headers['MIME-Version'] = '1.0';
		$headers['Subject'] = $subject;
		$headers['Content-Type'] = "text/html; charset=utf-8";
		$message = stripslashes($message);

		$mail = Mail::factory("smtp", $params);

		$completed = false;
		$email = str_replace(" ", "", $email);
		$email = str_replace(";",",",$email);
		$email = explode(",", $email);

		$headers['To'] = $name." <".$email.">";
		$recipient = $email;
		if(!empty($recipient))
		{				
			$result = $mail->send($recipient, $headers, $message);
			if (PEAR::isError($result))
				$completed = true;
		}

		return $completed;
	}

	static function mySort(&$array,$key, $type)
	{
		if(!in_array($type, array("ASC", "DESC")))
		{
			$type = "ASC";
		}
		if (!is_array($array) || count($array) == 0) return true;
		$assocSortCompare  = '$a = $a["'.$key.'"]; $b = $b["'.$key.'"];';

		if (is_numeric($array[0][$key]))
		{
			if($type == "ASC")
				$assocSortCompare.= ' return ($a == $b) ? 0 : (($a < $b) ? -1 : 1);';
			elseif($type == "DESC")
				$assocSortCompare.= ' return ($a == $b) ? 0 : (($a > $b) ? -1 : 1);';
		}
		else
		{
			if($type == "ASC")
				$assocSortCompare.= ' return strcmp($a,$b);';
			elseif($type == "DESC")
				$assocSortCompare.= ' return strcmp($b,$a);';
		}

		$assocSortCompare = create_function('$a,$b',$assocSortCompare);
		return usort($array,$assocSortCompare);
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
	static function resizeImageWhiteBkg($fileName, $constraint, $flag, $tot_width, $tot_height){
		$background = imagecreatetruecolor($tot_width, $tot_height);
		$white = imagecolorallocate($background, 255, 255, 255);
		imagefill($background, 0, 0, $white);
		if(!Center::resizeImage($fileName, $constraint, $flag))
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
	static function resizeImage($fileName, $constraint, $flag){
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

}

if (!function_exists('array_intersect_key'))
{
	function array_intersect_key($isec, $keys)
	{
		$argc = func_num_args();
		if ($argc > 2)
		{
			for ($i = 1; !empty($isec) && $i < $argc; $i++)
			{
				$arr = func_get_arg($i);
				foreach (array_keys($isec) as $key)
				{
					if (!isset($arr[$key]))
					{
						unset($isec[$key]);
					}
				}
			}
			return $isec;
		}
		else
		{
			$res = array();
			foreach (array_keys($isec) as $key)
			{
				if (isset($keys[$key]))
				{
					$res[$key] = $isec[$key];
				}
			}
			return $res;
		}
	}
}
?>