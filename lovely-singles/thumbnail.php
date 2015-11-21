<?php
//flush();

	// Configuration thumbnail.php/user/.../picture/height/width
	$path = substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME'])+1);
		
	//echo $path;
	//echo "<br />";
	
	if(strstr($path,'/foto/'))  {
			list($user, $dir, $pic_name, $height, $width) = preg_split('~[/]~U', $path, -1, PREG_SPLIT_NO_EMPTY);
			$tmp_path	 = 'thumbs/thumbs/'.$user.'/'.$dir.'/'.$pic_name;
	}
	elseif (strstr($path,'default.'))  {
			list($pic_name, $height, $width) = preg_split('~[/]~U', $path, -1, PREG_SPLIT_NO_EMPTY);
			$tmp_path	 = 'thumbs/thumbs/'.$pic_name;
	}
	else  {
			list($user, $pic_name, $height, $width) = preg_split('~[/]~U', $path, -1, PREG_SPLIT_NO_EMPTY);
			$tmp_path	 = 'thumbs/thumbs/'.$user.'/'.$pic_name;
	}

	//print_r($tmp_path);
	//echo "<br />";
	
	list($r, $g, $b, $a)	 = array(255,255,255,0);

	// Check if image can be resized
	if(($height != '') && ($width != ''))
	{
		
			// get image info	
 			$size['old']  = getimagesize($tmp_path);
 
  			//Load original picture
  			switch($size['old']['mime'])  {
  				case "image/gif":
  		  			$src_img = imagecreatefromgif($tmp_path);
  		   			break;
   				case "image/jpeg":
     				$src_img = imagecreatefromjpeg($tmp_path);
     				break;
   				case "image/png":
     				$src_img = imagecreatefrompng($tmp_path);
     				break;
   				case "image/wbmp":
    	 			$src_img = imagecreatefromwbmp($tmp_path);
    	 			break;
   				default:
    				$src_img = '';
   					break;  }

 			if($src_img != '')
 			{
		
					imagealphablending($src_img, true);
		
					// Check if image width is smaller than desired widtht (and add bars left and right)
					if($width > imagesx($src_img))  {
				
							$dst_img = imagecreatetruecolor($width,imagesy($src_img));
							$dst_x	 = round(($width-imagesx($src_img))/2);
			
							imagealphablending($dst_img, false);
			
							$trans = imagecolorallocatealpha($dst_img, $r, $g, $b, $a);
							imagefill($dst_img, 0, 0, $trans);
			
							imagesavealpha($dst_img, true);
							imagecopy($dst_img, $src_img, $dst_x, 0, 0, 0, imagesx($src_img), imagesy($src_img));
			
							imagedestroy($src_img);
							$src_img	 = $dst_img;  }
		
					// Check if image height is smaller than desired height (and add bars in top and bottom)
					if($height > imagesy($src_img))  {
						
							$dst_img = imagecreatetruecolor(imagesx($src_img),$height);
							$dst_y	 = round(($height-imagesy($src_img))/2);
			
							imagealphablending($dst_img, false);
			
							$trans = imagecolorallocatealpha($dst_img, $r, $g, $b, $a);
			
							imagefill($dst_img, 0, 0, $trans);
							imagesavealpha($dst_img, true);
							imagecopy($dst_img, $src_img, 0, $dst_y, 0, 0, imagesx($src_img), imagesy($src_img));
			
							imagedestroy($src_img);
							$src_img	 = $dst_img;  }
		
					// cut image to desired size
					$diff = imagesx($src_img)/$width;
					$new_h = imagesy($src_img)/$diff;
					$find_left = 0;
					$find_top = 0;
					
					if($new_h > $height)  {
						
							$cut_height	 = $diff * $height;
							$cut_width	 = imagesx($src_img);
							$find_top	 = round(($new_h-$height)/3);  }
							
					elseif($new_h < $height) {
						
							$diff		 = imagesy($src_img)/$height;
							$cut_height	 = imagesy($src_img);
							$cut_width	 = $diff*$width;
							$find_left	 = round((imagesx($src_img)-$cut_width)/2);  }
							
					else  {
						
							$cut_height	 = imagesy($src_img);
							$cut_width	 = imagesx($src_img);  }
		
					$dst_img = imagecreatetruecolor($width,$height);
		
					imagealphablending($dst_img, false);
		
					$trans = imagecolorallocatealpha($dst_img, $r, $g, $b, $a);
		
					imagefill($dst_img, 0, 0, $trans);
					imagesavealpha($dst_img, true);
		
					imagecopyresampled($dst_img,$src_img,0,0,$find_left,$find_top,$width,$height,$cut_width,$cut_height);
		
					// Output image
					header("Content-type:image/PNG");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($tmp_path)) . " GMT");
					header('Content-Disposition: inline; filename="'.basename($tmp_path).'"');
					imagePNG($dst_img);
		
					imagedestroy($src_img);
					imagedestroy($dst_img);		
 			}
 			
			else  {
					//header("Content-type:image/jpeg");
					header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($tmp_path)) . " GMT");
					header('Content-Disposition: inline; filename="'.basename($tmp_path).'"');
					readfile($tmp_path);
			}
	
	}
	
	else  {
			header("Content-type:image/jpeg");
			readfile($tmp_path);
	}
	
?>