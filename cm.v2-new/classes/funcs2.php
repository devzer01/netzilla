<?php 
class funcs2
{
	/**
	* This function is used for retrieves the number of Card 
	* @return array $rows the array with image properties
	*/		
	static function numDataCard()
	{
		$sql = "SELECT * FROM ".TABLE_CARD;
		$rows = DBconnect::num_rows($sql) ;
		return $rows ;
	}
	
	static function DeleteCard($cardid)
	{  
		$picpath = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDPATH,TABLE_CARD_ID,$cardid);
		$pictmp = DBconnect::retrieve_value_param(TABLE_CARD, TABLE_CARD_CARDTMP,TABLE_CARD_ID,$cardid);
		@unlink($picpath);
		@unlink($pictmp);
		$cond = "WHERE ".TABLE_CARD_ID."= '$cardid'"; 
		DBconnect::delete_data (TABLE_CARD,$cond); 
	}
	
	/**
	* This function is used for get the image properties . 
	* Select the image properties with php function getimagesize. 
	* Put the properties in array.   
	* @param $img this is a path of image
	* @return array $prop the array with image properties
	*/		
		static function getImgProperty($img)
		{
			$ImgSize = getimagesize($img);    
			$ImgMime = $ImgSize[mime];
			$mime = explode('/',$ImgMime);
			$ImgType = $mime[1];
			$prop['width'] = $ImgSize[0];
			$prop['height'] = $ImgSize[1]; 
			$prop['type'] = $ImgType; 
			return($prop);
		} 
		
	/**
	* This function is used for find a suitable size. 
	* Select the image properties with php function getimagesize. 
	* Put the new suitable size in array.   
	* @param $w this is a width image
	* @param $h this is a height of image
	* @param $limitw this is a limit width of image
	* @param $limith this is a limit height of image
	* @return array $newSize the array with new suitable size
	*/		
		static function ImageCalSize($w,$h,$limitw,$limith)
		{
			if($w>$limitw)
			{
				$per = $limitw/$w;
				$w = $w * $per ; 
				$h = $h * $per ;
			} 
			if($h >$limith)
			{
				$per = $limith/$h;
				$w = $w * $per ;
				$h = $h * $per ;
			} 
			$newSize = array($w,$h);
			return $newSize;
		}

	/**
	* This function is used for resize the image.  
	* Select the image properties with getImgProperty. 
	* Find the suitable image size with ImageCalSize. 
	* Resize images by  each type of images 
	* @param $FileTmp this is a width image
	* @param array $LimitSize this is an array size of image
	* @param $picPath this is a  path of image  
	*/		
		static function ImageResize($FileTmp,$LimitSize,$picPath)
		{ 
			$ImgProp = funcs2::getImgProperty($FileTmp); 
			$ImgWidth = $ImgProp['width']  ;
			$ImgHeight = $ImgProp['height'] ; 
			$ImgType = $ImgProp['type'] ;
			$NewSize = funcs2::ImageCalSize($ImgWidth,$ImgHeight,$LimitSize[0],$LimitSize[1]);
			$image_p = imagecreatetruecolor($NewSize[0], $NewSize[1]);
			switch($ImgType)
			{
				case 'gif': $image = imagecreatefromgif($FileTmp);
				break;
				case 'jpeg': $image = imagecreatefromjpeg($FileTmp);
				break;
				case 'png': $image = imagecreatefrompng($FileTmp);
				break;
				case 'bmp': $image = imagecreatefromwbmp ($FileTmp);
				break; 
			}
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $NewSize[0], $NewSize[1], $ImgWidth, $ImgHeight); 
			switch($ImgType)
			{
				case 'gif': $image = imagegif($image_p,$picPath);
				break;
				case 'jpeg': $image = imagejpeg($image_p,$picPath);
				break;
				case 'png': $image = imagepng($image_p,$picPath);
				break;
				case 'bmp': $image = imagewbmp($image_p,$picPath);
				break;
			}
		}

		static function ImageResizeThumbs($FileTrue,$LimitSize,$picPath)
		{
			$ImgProp = funcs2::getImgProperty($FileTrue); 
			$ImgWidth = $ImgProp['width']  ;
			$ImgHeight = $ImgProp['height'] ; 
			$ImgType = $ImgProp['type'] ;
			$NewSize = funcs2::ImageCalSize($ImgWidth,$ImgHeight,$LimitSize[0],$LimitSize[1]);
			$image_p = imagecreatetruecolor($NewSize[0], $NewSize[1]);
			switch($ImgType)
			{
				case 'gif': $image = imagecreatefromgif($FileTmp);
				break;
				case 'jpeg': $image = imagecreatefromjpeg($FileTmp);
				break;
				case 'png': $image = imagecreatefrompng($FileTmp);
				break;
				case 'bmp': $image = imagecreatefromwbmp ($FileTmp);
				break; 
			}
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $NewSize[0], $NewSize[1], $ImgWidth, $ImgHeight); 
			 switch($ImgType){
				case 'gif': $image = imagegif($image_p,$picPath);
				break;
				case 'jpeg': $image = imagejpeg($image_p,$picPath);
				break;
				case 'png': $image = imagepng($image_p,$picPath);
				break;
				case 'bmp': $image = imagewbmp($image_p,$picPath);
				break;
			}
		}

		static function chklastlogin()
		{ 
			$sql = "SELECT `".TABLE_MESSAGE_INBOX."`.* FROM `".TABLE_MEMBER."` 
							INNER JOIN  `".TABLE_MESSAGE_INBOX."` 
								ON `".TABLE_MESSAGE_INBOX."`. `".TABLE_MESSAGE_INBOX_TO."`  =  `".TABLE_MEMBER."`. `".TABLE_MEMBER_ID."` 
						  WHERE `".TABLE_MEMBER_SIGNIN_DATETIME."` < '".funcs::getlast24DateTime()."'"; 
			$rec = DBconnect::assoc_query_2D($sql); 
			if($rec)
			{
				foreach($rec as $key => $val)
				{
					$msgid = $val[TABLE_MESSAGE_INBOX_ID]; 
					$sql = "SELECT * FROM `".TABLE_MESSAGE_ALERT."` 
								 WHERE `".TABLE_MESSAGE_ALERT_MASSAGE_ID."` = '".$msgid."'"; 
					$rec2 = DBconnect::assoc_query_2D($sql); 
					if(!$rec2){ $alert[] = $msgid; }
				} // Foreach
			} //IF
			return $alert;
		}

		static function datamail($m)
		{
			$sql = "SELECT * FROM `".TABLE_MESSAGE_INBOX."` 
						WHERE `".TABLE_MESSAGE_INBOX_ID."` = '".$m."'"; 
			return $rec = DBconnect::assoc_query_2D($sql); 
		}

		function getUserid_email($email)
		{
			$sql = "SELECT ".TABLE_MEMBER_ID." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_EMAIL."='".$email."'";
			
			return DBconnect::retrieve_value($sql);
		}

		static function sendBirthCard(&$smarty,$from, $to, $card, $subject, $message)
		{
				//$userid = funcs2::getUserid_email($to);
				$time = funcs::getDateTime();
				$sql = "INSERT INTO ".TABLE_CARDLOG." 
							SET ".TABLE_CARDLOG_CHILD."=0, 
							".TABLE_CARDLOG_PARENT."=".$from.",  
							".TABLE_CARDLOG_CARD."=".$card.",  
							".TABLE_CARDLOG_MESSAGE."='".$message."', 
							".TABLE_CARDLOG_DATETIME."='".$time."'";
				DBconnect::execute($sql);

				$sql = "INSERT INTO `card_mail` SET 
						`parent_id`=".$_SESSION['sess_id'].", 
						`email`='".$to."', 
						`card_id`=".$card.", 
						`subject`='".$subject."', 
						`message`='".$message."', 
						`datetime`='".funcs::getDateTime()."'";

				if(DBconnect::execute_q($sql))
				{
					$id = mysql_insert_id();
					$mail_subject = funcs::getText($_SESSION['lang'], '$ecard_send_subject');
					$mail_message = funcs::getText($_SESSION['lang'], '$ecard_send_message');
					$mail_from = funcs::getEmail($_SESSION['sess_id']);
					$smarty->assign('mail_message',$mail_message);
					$smarty->assign('url_web', URL_WEB);
					$message = $smarty->fetch('ecard.tpl');
					
					if(funcs::sendMail($mail_from, $mail_subject, $message, MAIL_FROM))
					{
						$mail_subject = funcs::getText($_SESSION['lang'], '$ecard_subject');
						$mail_message = funcs::getText($_SESSION['lang'], '$ecard_message')."<br><br><a href='".URL_WEB."?action=viewcard_mail&id=".$id."&from=".$from."&email=".$to."' style=\"color:#FFFFFF;text-decoration:underline; font-size:14px;\">".funcs::getText($_SESSION['lang'], '$view_ecard')."</a>";

						$mail_message = str_replace("\$username","<a href='".URL_WEB."?action=register&type=membership&cate=profile&username=".$_SESSION['sess_username']."' style=\"color:#FFFFFF;text-decoration:underline; font-size:14px;\">".$_SESSION['sess_username']."</a>", $mail_message);
						$smarty->assign('mail_message',$mail_message);
						$smarty->assign('url_web', URL_WEB);
						
						$message = $smarty->fetch('ecard.tpl');

						return funcs::sendMail($to, $mail_subject, $message, MAIL_FROM);
					}
					
					/* $sql = "SELECT * FROM ".TABLE_CARDLOG." 
								 WHERE ".TABLE_CARDLOG_CHILD."=".$userid." 
									AND ".TABLE_CARDLOG_DATETIME."='".$time."'";
					$rec =DBconnect::assoc_query_1D($sql);
					$cardid = $rec[TABLE_CARDLOG_ID];
					$message = "#HPB#".$cardid ;
					funcs::sendMessage($from, $to, $subject, $message);*/
					
					}

				return false;
		}

		static function detailCardLog($id)
		{
			$sql = "SELECT * FROM ".TABLE_CARDLOG." WHERE ".TABLE_CARDLOG_ID." = '$id'";
			return $rec = DBconnect::assoc_query_1D($sql); 
		}

	static function chklast3day()
	{ 
		$sql = "SELECT * FROM `".TABLE_MEMBER."` WHERE `".TABLE_MEMBER_SIGNIN_DATETIME."` < '".funcs::getlast3Day()."' order by id DESC "; 
		//$query = mysql_query($sql);
		//echo mysql_num_rows($query)."<br>";
		$rec = DBconnect::assoc_query_2D($sql); 
		if($rec)
		{
			foreach($rec as $key => $val)
			{
				if(($val[username]) == "sanook")
				{
					echo $val[username];
					 $message = funcs::getMessageEmail_missing($val[username],$val[gender]);
					echo $val[TABLE_MEMBER_EMAIL];
					echo funcs::getText($_SESSION['lang'], '$email_missing');
					echo MAIL_FROM;
					if(funcs::sendMail($val[TABLE_MEMBER_EMAIL],funcs::getText($_SESSION['lang'], '$email_missing'), $message, MAIL_FROM)) 
						return true;
					else
						return false;		
				}
			
			}
		}		
	}
		
	static function visit_account()
	{ 
		$visit = mt_rand(35,65);
		$sql = "SELECT * FROM `".TABLE_CONFIG."` WHERE name = '".COUNT_VISITOR."'"; 
		$rec = DBconnect::assoc_query_2D($sql); 
		foreach($rec as $key => $value)
		{
			echo $account = $value['value']+$visit;
			return $account;
			$sql = "UPDATE ".TABLE_CONFIG." SET value='$account' WHERE name = '".COUNT_VISITOR."'";
			$query= mysql_query($sql);
		}
	}

	static function removePic_profile($userid)
	{
		//Delete from Temp
		$sql = "SELECT picturepath FROM phototemp WHERE userid=".$userid;
		$pic = DBconnect::retrieve_value($sql);
		$path = UPLOAD_DIR;

		if(is_file($path.$pic))
			unlink($path.$pic);

		$sql = "DELETE FROM phototemp WHERE ".TABLE_FOTOALBUM_USERID."=".$userid." and status='1'";
		DBconnect::execute_q($sql);

		$sql = "SELECT ".TABLE_MEMBER_PICTURE." FROM ".TABLE_MEMBER." WHERE ".TABLE_MEMBER_ID."=".$userid;
		$pic = DBconnect::retrieve_value($sql);

		if(is_file($path.$pic))
			unlink($path.$pic);

		$sql = "UPDATE ".TABLE_MEMBER." SET ".TABLE_MEMBER_PICTURE."='' 
				WHERE ".TABLE_MEMBER_ID."=".$userid;;
		return DBconnect::execute_q($sql);
	}

	static function updateEdit_datetime($username)
	{
		$userid = funcs::getUserid($username);
		$sql = "SELECT COUNT(*) FROM `edit_session` WHERE `userid`=".$userid;
		if(DBconnect::get_nbr($sql) > 0)		
			$sql = "UPDATE `edit_session` SET `datetime`='".funcs::getDateTime()."', `session`='".session_id()."' WHERE `userid`=".$userid;
		else
			$sql = "INSERT INTO `edit_session` SET `userid`=".$userid.", 
					`session`='".session_id()."', 
					`datetime`='".funcs::getDateTime()."'";
		
		return DBconnect::execute_q($sql);
	}

	static function checkEdit_user($userid)
	{
		$sql = "SELECT * FROM `edit_session` WHERE `userid`=".$userid;
		return DBconnect::assoc_query_1D($sql);
	}

	static function convertTo_timestamp($datetime)
	{
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
} // End Class
?>