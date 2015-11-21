<?php
//send data to template//
$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('years', funcs::getYear());
$smarty->assign('year',date('Y'));
$smarty->assign('year_range', funcs::getYear());
/*if(isset($_SESSION['sess_id']))
	$smarty->assign('username', funcs::findUserName($_SESSION['sess_id']));*/

//Get Lonely Heart Datas & assign to smarty
$mData = Search::GetNewLonelyHeart("M",LONELY_HEARTS_MALE);
for($n = 0; $n<count($mData); $n++)
{	
	//$mData[$n]['civilstatus'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $mData[$n]['civilstatus']);
	$mData[$n]['city'] = funcs::getAnswerCity($_SESSION['lang'], $mData[$n]['city']);      
} 
$smarty->assign("MLonelyHeart", $mData);

$fData = Search::GetNewLonelyHeart("F",LONELY_HEARTS_FEMALE);
for($n = 0; $n<count($fData); $n++)
{	
	//$fData[$n]['civilstatus'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $fData[$n]['civilstatus']);
	$fData[$n]['city'] = funcs::getAnswerCity($_SESSION['lang'], $fData[$n]['city']);
}
$smarty->assign("FLonelyHeart", $fData);

//Get my picture
$profile = DBConnect::assoc_query_1D("SELECT * FROM member WHERE id=".$_SESSION['sess_id']);
$smarty->assign("MyPicture",$profile['picturepath']);

//Get new messages's contact
$recent_contacts = DBconnect::assoc_query_2D("SELECT m.username, m.id, m.picturepath, i.datetime, i.status FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.to_id=".$_SESSION['sess_id']." AND m.username IS NOT NULL AND m.id>3 GROUP BY m.username ORDER BY i.datetime DESC LIMIT " . RECENT_CONTACTS );

$smarty->assign("recent_contacts", $recent_contacts);

$random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=".($profile['gender']=="1"?"2":"1")." AND isactive=1 AND fake=1 AND id>3 ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
$smarty->assign("random_contacts", $random_contacts);
//-----------------------------------------

if(isset($_SESSION['sess_id']))
{
	include('modules/checkbonus.php');
	$coin_conts = funcs::getCoinData();
	$smarty->assign("coin_conts", $coin_conts);
}

if(!$_SESSION['MOBILE_VERIFIED'] && ($_SESSION['sess_mem'] == '1'))
	$smarty->assign("show_smsbanner" , "1");

$smarty->display('index.tpl');

if(isset($_SESSION['mobileverify_redirect']))
	unset($_SESSION['mobileverify_redirect']);
?>