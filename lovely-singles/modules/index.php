<?php
/*
//select template file//
$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year', funcs::getYear(90, 16));
	$smarty->assign('city', funcs::getChoice($_SESSION['lang'],'','$city'));
	//$smarty->assign('country', funcs::getChoice($_SESSION['lang'],'','$country'));
	$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));
*/

$smarty->assign('phone_code', funcs::getChoice($_SESSION['lang'],'','$phoneCode'));
$smarty->assign('date', funcs::getRangeAge(1,31));
$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
$smarty->assign('years', funcs::getYear());

//send data to template//
if(isset($_SESSION['sess_id']))
	$smarty->assign('username', funcs::findUserName($_SESSION['sess_id']));
$smarty->assign('account', funcs::member_account());
$smarty->assign('visit', funcs::getVisitor());	//get visitor

//echo $_SESSION[lang];
//$smarty->assign('newone_women', funcs::getNewest(2,2));	//get newest membership
/*/////
$newone = funcs::getNewest(0, 3, "");

$idTmp = "";
for($i=0;$i<count($newone);$i++){
  $idTmp .= ($i>0)? "," : "";   
  $idTmp .= ($newone[$i][id]!="" && $newone[$i]['id']>0)? $newone[$i]['id'] : "";
}
$newwomen = funcs::getNewest(2, 2, $idTmp);

$smarty->assign('newone', $newone);	//get newest membership
$smarty->assign('newwomen', $newwomen);	//get newest women membership
*/////
/*$smarty->assign('newone', $newone);	//get newest membership
$smarty->assign('newmen', $newmen);	//get newest men membership
$smarty->assign('newwomen', $newwomen);	//get newest women membership*/

$smarty->assign('manofday', funcs::getOfDay(1,1));	//get man of day
$smarty->assign('womanofday', funcs::getOfDay(2,1));	//get woman of day

// [Start] Manual select member for index page
//$newone = array(funcs::getAdvanceProfile(5597,1),funcs::getAdvanceProfile(5474,1),funcs::getAdvanceProfile(8467,1));
//$newwomen = array(funcs::getAdvanceProfile(4871,1),funcs::getAdvanceProfile(2219,1));  

//$smarty->assign('manofday', funcs::getAdvanceProfile(102179,2));
//$smarty->assign('womanofday', funcs::getAdvanceProfile(111514,2));
// [End] Manual select member for index page

	$smarty->assign('year',date('Y'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('status', funcs::getChoice($_SESSION['lang'],'$nocomment','$status'));
	$smarty->assign('appear', funcs::getChoice($_SESSION['lang'],'$nocomment','$appearance'));
	$smarty->assign('city', funcs::getChoice($_SESSION['lang'],'$nocomment','$city'));
	$smarty->assign('country', funcs::getChoiceCountry());

	//Get Lonely Heart Datas & assign to smarty
	$mData = Search::GetNewLonelyHeart("M",3);
	// [Phai 05/12/2011] for($n = 0; $mData[$n]; $n++)
	for($n = 0; $n<count($mData); $n++)
	{	
		//$mData[$n]['civilstatus'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $mData[$n]['civilstatus']);
		$mData[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $mData[$n][TABLE_MEMBER_CITY]);      
	} 
	/*echo "<pre>";
	print_r($mData);
	echo "</pre>";*/
	$smarty->assign("MLonelyHeart", $mData);

	$fData = Search::GetNewLonelyHeart("F",3);
	// [Phai 05/12/2011] for($n = 0; $fData[$n]; $n++)
	for($n = 0; $n<count($fData); $n++)
	{	
		//$fData[$n]['civilstatus'] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $fData[$n]['civilstatus']);
		$fData[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $fData[$n][TABLE_MEMBER_CITY]);
	}
	/*echo "<pre>";
	print_r($fData);
	echo "</pre>";*/
	$smarty->assign("FLonelyHeart", $fData);
//-----------------------------------------
//send choice to template//
  $smarty->assign('thumbpath', UPLOAD_DIR);

//Get membership payment history
if(isset($_SESSION['sess_id']))
{
	if($_SESSION['sess_id'] != '' && ($_GET['action'] == 'membership'))
	{
		if($_GET['type'] == 'cancel')
		{
			funcs::cancelPaymentHistory($_GET['id'], $_SESSION['sess_id']);
			header("location: ?action=membership");
		}
		elseif(($_GET['do'] == 'delete') && ($_POST['delete_button'] != ''))
		{
			sleep(5);
			funcs::deleteProfile($_SESSION['sess_id']);
			session_destroy();
			header("location: .");
			exit();
		}

		funcs::prepareMembershipPage($smarty);
	}
	if($_SESSION['sess_id'] != '' && ($_GET['action'] == 'membershipfront'))
	{
		if($_GET['type'] == 'cancel')
		{
			funcs::cancelPaymentHistory($_GET['id'], $_SESSION['sess_id']);
			header("location: ?action=membership");
		}
		elseif(($_GET['do'] == 'delete') && ($_POST['delete_button'] != ''))
		{
			sleep(5);
			funcs::deleteProfile($_SESSION['sess_id']);
			session_destroy();
			header("location: .");
			exit();
		}

		funcs::prepareMembershipPage($smarty);
	}
	elseif($_SESSION['sess_id'] != '' && ($_GET['action'] == 'testpay'))
	{
		if($_GET['type'] == 'cancel')
		{
			funcs::cancelPaymentHistory($_GET['id'], $_SESSION['sess_id']);
			header("location: ?action=membership");
		}
		funcs::prepareMembershipPage($smarty);
	}

	
	include('modules/checkprofile.php');
	$smarty->assign('total_score', number_format($total_score));
	$smarty->assign('progress_score', $progress_final);

	include('modules/checkbonus.php');

}

//check coin
/* if(isset($_SESSION['sess_username']))
{
	$coinVal = funcs::checkCoin($_SESSION['sess_username']);
	$smarty->assign('coin',$coinVal);
} */
$coin_conts = funcs::getCoinData();
$smarty->assign("coin_conts", $coin_conts);

if(isset($_SESSION['sess_username']))
{
	if(!funcs::checkmobile($_SESSION['sess_username']) && ($_SESSION['sess_mem'] == '1'))
		$smarty->assign("show_smsbanner" , "1");
}

if ($_SESSION['deviceType'] == 'phone' && !isset($_SESSION['sess_id'])) {
	$smarty->display('mobile/index.tpl');	
} else {
	$smarty->display('index.tpl');
}


 
if(isset($_SESSION['mobileverify_redirect']))
	unset($_SESSION['mobileverify_redirect']);
?>