<?php 
	require_once('classes/top.class.php'); 
	$list = funcs2::chklastlogin();
	if($list){
		foreach($list as $key => $val){ 
			$datas =  funcs2::datamail($val); 
			$mail_to_id = $datas[0]['to_id'];
			$mail_from_id  = $datas[0]['from_id'];
			$mail_to_address = funcs::getEmail($mail_to_id);
			$mail_form_info = funcs::getProfile($mail_from_id); 
			$smarty->assign("from_info",$mail_form_info);
			$smarty->assign('base_url',URL_WEB);
			$msg = $smarty->fetch("viewmail24hr.tpl");  
			if(funcs::sendMail($mail_to_address, "You got a post", $msg, $mail_to_address)){ 
				$sql2 = "INSERT INTO ".TABLE_MESSAGE_ALERT." 
							SET ".TABLE_MESSAGE_ALERT_MASSAGE_ID."=".$val.",  
							".TABLE_MESSAGE_ALERT_DATETIME."='".funcs::getDateTime()."'";
				DBconnect::execute_q($sql2);
			}
		}// foreach
	}// if 
?>