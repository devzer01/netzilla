<?php 
   /** 
   * This page is tranfering Request to be Session;
   * @filesource  
   * @author Pakin R.
   * @version   Apr 18, 2007
  */  
  session_start();
  		//print_r($_POST);
  		//exit;
	    $arrPostRequst = array(); 
		$from = $_GET['from'] ;
		$src = explode('?',$from);
		if($src[1] == 'action=search')
		{
			$newsrc = $src[0]."?cond=1&".$src[1].
			"&q_forsearch=".$_POST['q_forsearch'].
			"&q_nickname=".$_POST['q_nickname'].
			"&q_gender=".$_POST['q_gender'].
			"&q_picture=".$_POST['q_picture'].
			"&country=".$_POST['country'].
			"&state=".$_POST['state'].
			"&city=".$_POST['city'].
			"&q_minage=".$_POST['q_minage'].
			"&q_maxage=".$_POST['q_maxage'].
			"&self_gender=".$_POST['self_gender'];
			if($_POST['q_username'] != '')
				$newsrc .= "&q_username=".$_POST['q_username'];
		}
		else
			$newsrc = $src[0]."?cond=1&".$src[1];
		$from = $src[0];  
  /**
  * This is tranfering a Request to be an Array  ;
  */
		$arrPostRequst = $_REQUEST;
		
  /**
  * This is putting FILES properties into an array.
  */
		if(count($_FILES)){    
			$arrPostRequst['FILES'] =  $_FILES;   
			$i=0;
			while (current($arrPostRequst['FILES'])) { 
				$i ++;
				$FilePic = key($arrPostRequst['FILES']) ;
				$pic_upload=$arrPostRequst['FILES']["$FilePic"]['name']; 
				$path = "imagesTest/temp_".$i.time().$pic_upload; 
				copy($arrPostRequst['FILES']["$FilePic"]['tmp_name'],$path);		
				$arrPostRequst['FILES']["$FilePic"]['tmp_name'] = $path;  
				next($arrPostRequst['FILES']); 
			}    
		}  
		
  /**
  * This is tranfering an Array to be Session;
  */
		session_register("arrPost") ;
		$_SESSION["arrPost"] = $arrPostRequst;
		header("Location: $newsrc"); 
?>