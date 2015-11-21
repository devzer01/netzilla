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
if(isset($_SESSION['sess_id']) && ($_SESSION['sess_id']!=""))
{
	//Get new messages's contact
	$recent_contacts = DBconnect::assoc_query_2D("SELECT m.username, m.id, m.picturepath, i.datetime, i.status FROM message_inbox i LEFT JOIN member m ON i.from_id=m.id WHERE i.to_id=".$_SESSION['sess_id']." AND m.username IS NOT NULL AND m.id > 3 AND m.picturepath != '' GROUP BY m.username ORDER BY i.datetime DESC LIMIT " . RECENT_CONTACTS );
	$smarty->assign("recent_contacts", $recent_contacts);
}

// if (!isset($profile['gender'])) $profile['gender'] = 2;
// $random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=".($profile['gender']=="1"?"2":"1")." AND isactive=1 AND fake=1 AND id > 3 AND picturepath != '' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
// $smarty->assign("random_contacts", $random_contacts);

$sql = "SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 AND picturepath != '' ORDER BY RAND() LIMIT 1";
$rs = mysql_query($sql);
$smarty->assign('random_profile', mysql_fetch_assoc($rs));

//-----------------------------------------

if(isset($_SESSION['sess_id']))
{
	include('modules/checkbonus.php');
	$coin_conts = funcs::getCoinData();
	$smarty->assign("coin_conts", $coin_conts);
}

if((!isset($_SESSION['MOBILE_VERIFIED']) || !$_SESSION['MOBILE_VERIFIED']) && (isset($_SESSION['sess_mem']) && ($_SESSION['sess_mem'] == '1')))
	$smarty->assign("show_smsbanner" , "1");

$enableDoor = false;

if (isset($_GET['ref']) || isset($_SESSION['ref']) || isset($_COOKIE['ref'])) {
	if ($_COOKIE['domain'] == 'flirt48.net') $enableDoor = true;
}

$landing = 'landing1';
$landings = array('landing1', 'landing2');

if ($enableDoor) {
	if (isset($_COOKIE['landing']) && in_array($_COOKIE['landing'], $landings)) {
		$landing = $_COOKIE['landing'];
	} else {
		if (in_array($_GET['ref'], $landings)) {
			$landing = $_GET['ref'];
		} else {
			$landing = $landings[array_rand($landings)];
		}
		setcookie('landing', $landing, time()+60*60*24*365);
		$sql = "INSERT INTO landing (landing_time) VALUES (NOW()) ";
		mysql_query($sql);
	}
}

if ($enableDoor) {
	$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('country', funcs::getChoiceCountry());
	$smarty->assign('age', funcs::getRangeAge());//Singh
}

if ($_SESSION['deviceType'] == 'phone' && !isset($_SESSION['sess_id'])) {
	$smarty->display('mobile/index.tpl');	
} else if ($_SESSION['deviceType'] != 'phone' && !isset($_SESSION['sess_id']) && $enableDoor) {
	if ($landing == 'landing1') {
		$smarty->display('landing1/index.tpl');
	} elseif ($landing == 'landing2') {
		$smarty->display('landing2/index.tpl');
	} else {
		$smarty->display('index.tpl'); 
	}
} else {
	$smarty->display('index.tpl');
}

if(isset($_SESSION['mobileverify_redirect']))
	unset($_SESSION['mobileverify_redirect']);