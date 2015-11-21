<?php
//check permission type//
// $permission_lv = array(1, 2, 8);	//define type permission can open this page.
$permission_lv = array(1, 2, 3, 4, 8);//jeab edit
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

//get user birthday is today//
$today = funcs::getDate(); 
$username = $_GET['uname'];
$card = $_GET['card'];
if($_GET['cond']==1){
	$arrPost = $_SESSION["arrPost"] ;
	$card = $arrPost['card'];
	$to = $arrPost['to'];
	$subject = $arrPost['subject'];
	$message = $arrPost['message']; 
	$_SESSION["arrPost"] = array();
	$sendresult = funcs2::sendBirthCard(&$smarty,$_SESSION['sess_id'], $to, $card, $subject , $message);
	//if($sendresult == 'true')
	//{
	//header("Location: ?action=sendcard_to&card=$card&type=sending");
	//}
	//header("Location: ?action=thankyou&user=$username&type=sending");
	if($sendresult == 'true')
	{
		$_SESSION['card']['id'] = $card;
		$_SESSION['card']['subject'] = $arrPost['subject'];
		$_SESSION['card']['message'] = $arrPost['message'];
		$_SESSION['card']['to'] = $arrPost['to'];
		header("Location: ?action=thankyou&type=sending");
		exit;
	}
}

if($username!=''){
	$_SESSION['uname'] = $username;
	header("Location: ?action=sendcard_to&card=$card&type=sending");
	exit;
}
$uid = $_SESSION['uname'];  
$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
			.TABLE_CARD_CARDTMP." AS cardtmp ,"
			.TABLE_CARD_CARDPATH." AS cardpath ,"
			.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
			." WHERE ".TABLE_CARD_ID." = '$card'"
			." ORDER BY " .TABLE_CARD_ID." ASC";
$rec = DBconnect::assoc_query_2D($sql); 
$image = $rec[0]['cardtmp'];
$uinfo = funcs::getProfile($uid); 
//select template file//  
$smarty->assign('card', $card);  
$smarty->assign('cardrec', $cardrec);  
$smarty->assign('image', $image);  
$smarty->assign('uinfo',$uinfo); 
$smarty->display('index.tpl'); 
?>