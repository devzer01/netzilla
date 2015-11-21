<?php
//check permission type//
$permission_lv = array(1, 2, 3, 4, 8);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

$today = funcs::getDate(); 	//get date today
$username = $_GET['uname'];
$card = $_GET['card'];

if($_GET['cond']==1)
{
	$arrPost = $_SESSION["arrPost"] ;
	$card = $arrPost['card'];
	$to = $arrPost['to'];
	$subject = $arrPost['subject'];
	$message = $arrPost['message']; 
	$_SESSION["arrPost"] = array();
	funcs::sendBirthCard($_SESSION['sess_id'], $to,$card, $subject , $message);
	header("Location: ?action=writecard&card=$card");
} 

if($username!='')
{
	$_SESSION['uname'] = $username;
	header("Location: ?action=writecard&card=$card");
	exit;
}

$uid = $_SESSION['uname'];  
//get card//
$sql = "SELECT ".TABLE_CARD_ID." AS cardid ,"
		.TABLE_CARD_CARDTMP." AS cardtmp ,"
		.TABLE_CARD_CARDPATH." AS cardpath ,"
		.TABLE_CARD_CARDTMP." AS cardtmp  ,"
		.TABLE_CARD_CARDSHOW." AS cardshow   FROM ".TABLE_CARD
		." WHERE ".TABLE_CARD_ID." = '$card'"
		." ORDER BY " .TABLE_CARD_ID." ASC";
$rec = DBconnect::assoc_query_2D($sql); 
$image = $rec[0]['cardtmp'];
$uinfo = funcs::getProfile($uid); 	//get data profile
//send data to template//
$smarty->assign('card', $card);  
$smarty->assign('cardrec', $cardrec);  
$smarty->assign('image', $image);  
$smarty->assign('uinfo',$uinfo); 
//select template file//  
$smarty->display('index.tpl'); 
?>