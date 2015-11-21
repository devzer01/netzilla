<?php
require_once('classes/top.class.php');
if(isset($_GET['file']) && ($_GET['file']!=''))
{
	$file = "thumbs/".$_GET['file'];
}
elseif(isset($_GET['username']) && ($_GET['username']!=""))
{
	if($picturepath = DBConnect::retrieve_value("SELECT picturepath FROM member WHERE username='".$_GET['username']."'"))
	{
		$file = "thumbs/".$picturepath;
	}
}

if(file_exists($file))
{
	if($_GET['w'] || $_GET['h'])
	{
		list($orig_width, $orig_height, $type) = getimagesize($file);
		$height = $_GET['h']?$_GET['h']:$_GET['w']*$orig_height/$orig_width;
		$width = $_GET['w']?$_GET['w']:$_GET['h']*$orig_width/$orig_height;
		switch($type)
		{
			 case 1: //GIF
				$src_img = imagecreatefromgif($file);
				break;
			 case 2: //JPEG
				$src_img = imagecreatefromjpeg($file);
				break;
			 case 3: //PNG
				$src_img = imagecreatefrompng($file);
				break;
			 default;
				$src_img = imagecreatefromjpeg($file);
				break;
		}
		$diff = imagesx($src_img)/$width;
		$new_h = imagesy($src_img)/$diff;
		$find_left = 0;
		$find_top = 0;
		if ($new_h > $height)
		{
			$cut_height = $diff * $height;
			$cut_width =  imagesx($src_img);
			//$find_top = round ((imagesy($src_img)-$cut_height)/2);
			$find_top = round($new_h * 5/100);
		}
		else if ($new_h < $height)
		{
			$diff = imagesy($src_img)/$height;
			$cut_height = imagesy($src_img);
			$cut_width = $diff*$width;
			$find_left = round ((imagesx($src_img)-$cut_width)/2);
		}
		else
		{
			$cut_height = imagesy($src_img);
			$cut_width = imagesx($src_img);
		}
		$dst_img = imagecreatetruecolor($width,$height);
		imagecopyresampled($dst_img,$src_img,0,0,$find_left,$find_top,$width,$height,$cut_width,$cut_height);      

		header("Content-type:image/jpeg");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($file)) . " GMT");
		header('Content-Disposition: inline; filename="'.basename($file).'"');
		imagejpeg($dst_img);

		imagedestroy($src_img);
		imagedestroy($dst_img);	
	}
	else
	{
		header("Content-type:image/jpeg");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($file)) . " GMT");
		header('Content-Disposition: inline; filename="'.basename($file).'"');
		readfile($file);
	}
}
else
{
	$file = "thumbs/default.jpg";
	header("Content-type:image/jpeg");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s", filemtime($file)) . " GMT");
	header('Content-Disposition: inline; filename="'.basename($file).'"');
	readfile($file);
}
?>