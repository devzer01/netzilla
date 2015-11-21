<?php
SmartyPaginate::connect();
SmartyPaginate::setLimit(MESSAGE_RECORD_LIMIT); //smarty paging set records per page
SmartyPaginate::setPageLimit(MESSAGE_PAGE_LIMIT); //smarty paging set limit pages show

if(isset($_GET['q_minage']) && $_GET['q_minage'] != '')
$_SESSION['right_search'] = $_GET;


$url  = "?action=".$_REQUEST['action'];
$url .= "&cond=".$_GET['cond'];
$url .= "&q_forsearch=".$_GET['q_forsearch'];
$url .= "&q_nickname=".$_GET['q_nickname'];
$url .= "&q_gender=".$_GET['q_gender'];
$url .= "&q_picture=".$_GET['q_picture'];
$url .= "&country=".$_GET['country'];
$url .= "&state=".$_GET['state'];
$url .= "&city=".$_GET['city'];
$url .= "&q_minage=".$_GET['q_minage'];
$url .= "&q_maxage=".$_GET['q_maxage'];
$url .= "&self_gender=".$_GET['self_gender'];
$memrec_total = 0;



/******************************* delete from favorite *********************************/
if($_GET['do']=='del')
{
	$delFaviteId = Search::searchDelMemberId($_GET['delname']);

	if(Search::deleteFavorite($_SESSION['sess_id'],$delFaviteId['id']))
	{
		$url = $_SERVER['REQUEST_URI'];
		$newUrl = preg_replace('/(.*)&do.*/i','\1',$url);

		header("Location: $newUrl");
	}
}
/******************************* end delete from favorite *********************************/

if($_GET['q_username'] != ''){
	$url .= "&q_username=".$_GET['q_username'];
}

SmartyPaginate::setUrl($url); //smarty paging set URL

if((!isset($_GET['next'])) || ($_GET['next']==1)){
	SmartyPaginate::setCurrentItem(1); //go to first record
	$next = 0;
	$pageLimit = 15;
	$start_match = 0;
}else{
	$next = $_GET['next'];
	$pageLimit = $next + 15;
	$start_match = $next - 1;
}

/******************************* Get Member Data *********************************/

$sql_member = "select * from member where (id='".$_SESSION['sess_id']."')";
$member_area_search = DBconnect::assoc_query_2D($sql_member);

/*********************************************************************************/
if($_GET['cond']==1)
{

	$search_type = $_GET['q_forsearch'];
	$username_full = $_GET['q_username'];
	$username = $_GET['q_nickname'];
	$gender = $_GET['q_gender'];
	$have_pic = $_GET['q_picture'];
	$country = $_GET['country'];
	$state = $_GET['state'];
	$city = $_GET['city'];
	$min_age = $_GET['q_minage'];
	$max_age = $_GET['q_maxage'];
}

/**
 * START NICKNAME SEARCH
 **/
if($username != '')
{
	/**
	 * MATCH AND SIMILAR TO KEY WORD PROFILE
	 **/
	$profileDetails = array();
	$match_num = Search::countSearchName($username);
	$matchingProfile = array();
	$addtionalProfile = array();
	
	//If can find profile which matching with keyword then collect matching profile
	if($match_num > $next)
	{
		$arr_matching = Search::searchName($username, $start_match, MESSAGE_RECORD_LIMIT, $_SESSION[sess_id]); //limit 15
		$matchingProfile = $arr_matching['datas'];
		$matchingIds = $arr_matching['ids'];
		//$memrec_total = count($matchingProfile);
		/*echo "<pre>";
		print_r($matchingProfile);
		echo "</pre>";*/

		//echo join($matchingProfile['id']);
	}
	
	/*if (is_array($matchingProfile))
	{
		$memrec_data = array_slice($matchingProfile, 0, MESSAGE_RECORD_LIMIT);
	}*/
	
	//If matching profile on current page less than perpage limit then Additional profile should be appear
	if(count($matchingProfile)<MESSAGE_RECORD_LIMIT)
	{
		$fraction = ($match_num % MESSAGE_RECORD_LIMIT);
		//echo $fraction;
		//echo "<br/>";
		
		if(($start_match + $fraction) == $match_num)
		{
			$start_ads = 0;
			$end_ads = MESSAGE_RECORD_LIMIT-$fraction;
		}
		else
		{
			$start_ads = MESSAGE_RECORD_LIMIT-$fraction;
			$end_ads = $fraction;//MESSAGE_RECORD_LIMIT;
		}
		/*if($match_num <= MESSAGE_RECORD_LIMIT)
		{
			if($next < (MESSAGE_RECORD_LIMIT+1))
			{
				$start_ads = 0;
				$end_ads = intval(MESSAGE_RECORD_LIMIT-count($matchingProfile));
			}
			else 
			{
				$start_ads = intval(MESSAGE_RECORD_LIMIT-count($matchingProfile));
				$end_ads = MESSAGE_RECORD_LIMIT;
			}
		}
		else
		{
			if($next < 16)
			{
				$start_ads = 0;
				$end_ads = MESSAGE_RECORD_LIMIT;
			}
		}*/
		/*echo "start ads: ".$start_ads;
		echo "<br/>";
		echo "end_ads ads: ".$end_ads;
		echo "<br/>";
		echo "Total Match + Similar: ".$match_num;
		echo "<br/>";*/
		

	// 	if (isset($_SESSION[sess_id]) && $_SESSION[sess_id]>0 && count($lonelyDetails) < MESSAGE_RECORD_LIMIT)
	// 	{
		/**
		 * ADDITIONAL PROFILE
		 **/
		//echo $_SESSION[sess_id];
	// 	if(isset($_SESSION[sess_id]) && $_SESSION[sess_id]>0)
	// 	{ 
			/**
			 * START NICKNAME SEARCH WITH LOGGED IN
			 **/
	// 		echo "Logged";
	// 		//login
	// 		if($numProfile > 0 && count($profileDetails) > 0 && is_array($profileDetails))
	// 		{
	// 			$idTmp = "";
	// 			for($i=0; $i < $numProfile; $i++)
	// 			{
	// 				$idTmp .= ($i>0)? "," : "";
	// 				$idTmp .= $profileDetails[$i]['id'];
	// 			}
	// 		}
	
	
	// 		$temp = Search::geProfileSameArea($_SESSION[sess_id], $gender, $idTmp, $next, MESSAGE_RECORD_LIMIT, $city);
	// 		$profileDetailsSamArea = $temp[0];
	
	// 		$numProfileSameArea = $temp[1];
	
	// 		if($numProfile > 0 && $numProfileSameArea > 0)
	// 		{
	// 			$profileDetailsSamArea[0]['advanced_result'] = "yes";
	// 			$arrData = array_merge($profileDetails, $profileDetailsSamArea);
	// 			$memrec_total = $numProfile + $numProfileSameArea;
	// 			$memrec_data = array_slice($arrData, 0, MESSAGE_RECORD_LIMIT);
	
	// 		}
	// 		elseif($numProfile <= 0 && $numProfileSameArea > 0)
	// 		{
	// 			$noResult = 1;
	// 			$profileDetailsSamArea[0]['advanced_result'] = "yes";
	// 			$memrec_total = $numProfileSameArea;
	// 			$memrec_data = array_slice($profileDetailsSamArea, 0, MESSAGE_RECORD_LIMIT);
	
	// 		}
	// 		elseif($numProfile > 0 && $numProfileSameArea <= 0)
	// 		{
	// 			$memrec_total = $numProfile;
	// 			$memrec_data = array_slice($profileDetails, 0, MESSAGE_RECORD_LIMIT);
	
	// 		}
			/**
			 * END NICKNAME SEARCH WITH LOGGED IN
			 **/
	
	// 	}
	// 	else
	// 	{
			/**
			 * START NICKNAME SEARCH WITHOUT LOGIN
			 **/
	// 		echo "Not login<br/>";
			//not login
			/*$limit_ads = intval(MESSAGE_RECORD_LIMIT-$memrec_total);
			echo "Ads Start: " . $limit_ads;
			echo "<br/>";*/
			
			//$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
			//echo "Total Ads: ".$numSameArea;
			//echo "<br/>";
			$addtionalProfile = Search::geProfileByName('', $gender, $have_pic, $country, $state, '', 18, 99, $start_ads, $end_ads, $matchingIds);
			//echo "ADS:". count($addtionalProfile);
	// 		echo "<br/>";
			
	
	// 		if(count($profileDetails) < 1 )
	// 		{
	// 			//noresult
	// 			$noResult = 1;
					
	// 			$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
	// 			$add = Search::geProfileByName('', $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), MESSAGE_RECORD_LIMIT, $_SESSION[sess_id]);
					
	// 			if(is_array($add))
	// 			{
	// 				$add[0]['advanced_result'] = "yes";
	// 				$profileDetails = array_merge($profileDetails, $add);
						
	// 			}
					
	// 		}
	// 		else if($numProfile < PROFILE_SEARCH_MIN)
	// 		{
	// 			$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
	// 			$add = Search::geProfileByName($username, $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), MESSAGE_RECORD_LIMIT, $_SESSION[sess_id]);
	// 			if(is_array($add))
	// 			{
	// 				$add[0]['advanced_result'] = "yes";
	// 				$profileDetails = array_merge($profileDetails, $add);
	
	// 			}
	// 		}
	
	
			
	
			/*if($memrec_total > 0)
			{
				$memrec_data = array_slice($profileDetails, $next, MESSAGE_RECORD_LIMIT);
			}*/
			/**
			 * END NICKNAME SEARCH WITHOUT LOGIN
			 **/
		//}
	}
	
	if((is_array($addtionalProfile)) && (count($matchingProfile)>0))
	{
		$profileDetails = array_merge($profileDetails, $matchingProfile);
	}
	
	if((is_array($addtionalProfile)) && (count($addtionalProfile)>0))
	{
		$addtionalProfile[0]['advanced_result'] = "yes";
		$profileDetails = array_merge($profileDetails, $addtionalProfile);
	
	}
	
	/*echo "<pre>";
	print_r($profileDetails);
	echo "</pre>";*/
	
	//Total record should be total matching profile + addtional profile (equal to MESSAGE_RECORD_LIMIT) 
	$memrec_total = $match_num + MESSAGE_RECORD_LIMIT;//count($addtionalProfile);
	$memrec_data = $profileDetails;
	
	/*if ($numProfile != 0)
	{
		$memrec_total = $numProfile + $next;
	}
	else
	{
		$memrec_total = $numProfile;
	}*/

// 	$_SESSION['result_memrec'] = $memrec_total;
// 	$_SESSION['resultHeader'] = " Profiles Search";
// 	$_SESSION['resulttype'] = 1;
// 	// print_r($memrec_data);
/**
 * END NICKNAME SEARCH
 **/
}
else 
{
/**
 * START 
 **/
	if($search_type == 1)
	{
		//search ads

		/******************************************* lonely_heart_ads *****************************************************/
		// 		echo "<!-- search type 1 resulttype 2 -->";
		/*echo "<pre>";
		print_r($_GET);
		echo "</pre>";*/
		
		$profileDetails = search::numAllPorfileSameArea2('lonelyheart', $_GET, $start_match, MESSAGE_RECORD_LIMIT);
		/*echo "<pre>";
		 print_r($profileDetails);
		echo "</pre>";*/
		
		$memrec_data = $profileDetails['datas'];
		$memrec_total = $profileDetails['total'];
		
// 		$self_gender = $_GET['self_gender'];

// 		$lonelyDetails = Search::getLonelyHeartAds($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age, $next, MESSAGE_RECORD_LIMIT, $self_gender);
// 		//$numLonelyDetails = Search::countLonelyHeartAds($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age);
// 		$numLonelyDetails = count($lonelyDetails);

// 		//login
// 		if(isset($_SESSION[sess_id]) && $_SESSION[sess_id]>0 && count($lonelyDetails) < MESSAGE_RECORD_LIMIT){

// 			if($numLonelyDetails>0){

// 				$idTmp = "";
// 				for($i=0;$i<$numLonelyDetails;$i++){
// 					$idTmp .= ($i>0)? "," : "";
// 					$idTmp .= $lonelyDetails[$i][userid];
// 				}
// 			}

// 			$temp = Search::getLonelyHeartAdsSameArea($_SESSION[sess_id], $gender, $idTmp, $next, MESSAGE_RECORD_LIMIT, $city, $self_gender);
// 			$lonelyDetailsSameArea = $temp[0];


// 			$countLonelySameArea = $temp[1];

// 			if($numLonelyDetails>0 && $countLonelySameArea>0){
// 				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
// 				$arrData = array_merge($lonelyDetails, $lonelyDetailsSameArea);
// 				$memrec_total = $numLonelyDetails + $countLonelySameArea;
// 				$memrec_data = array_slice($arrData, 0, MESSAGE_RECORD_LIMIT);

// 			}elseif($numLonelyDetails<=0 && $countLonelySameArea>0){
					
// 				$noResult = 1;
// 				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
// 				$memrec_total = $countLonelySameArea;
// 				$memrec_data = array_slice($lonelyDetailsSameArea, 0, MESSAGE_RECORD_LIMIT);

// 			}elseif($numLonelyDetails>0 && $countLonelySameArea<=0){

// 				$memrec_total = $numLonelyDetails;
// 				$memrec_data = array_slice($lonelyDetails, 0, MESSAGE_RECORD_LIMIT);

// 			}
// 		}else{//not login

// 			/* $memrec_total = $numLonelyDetails;

// 			if($memrec_total>0){
// 			$memrec_data = array_slice($lonelyDetails, 0, MESSAGE_RECORD_LIMIT);
// 			} */
// 			$temp = Search::getLonelyHeartAdsSameArea('', $gender, $idTmp, $next, MESSAGE_RECORD_LIMIT, $city, $self_gender);
// 			$lonelyDetailsSameArea = $temp[0];


// 			$countLonelySameArea = $temp[1];

// 			if($numLonelyDetails>0 && $countLonelySameArea>0){
// 				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
// 				$arrData = array_merge($lonelyDetails, $lonelyDetailsSameArea);
// 				$memrec_total = $numLonelyDetails + $countLonelySameArea;
// 				$memrec_data = array_slice($arrData, 0, MESSAGE_RECORD_LIMIT);

// 			}elseif($numLonelyDetails<=0 && $countLonelySameArea>0){

// 				$noResult = 1;
// 				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
// 				$memrec_total = $countLonelySameArea;
// 				$memrec_data = array_slice($lonelyDetailsSameArea, 0, MESSAGE_RECORD_LIMIT);

// 			}elseif($numLonelyDetails>0 && $countLonelySameArea<=0){

// 				$memrec_total = $numLonelyDetails;
// 				$memrec_data = array_slice($lonelyDetails, 0, MESSAGE_RECORD_LIMIT);

// 			}

// 		}

		$_SESSION['result_memrec'] = $memrec_total;
		$_SESSION['resultHeader'] = " Lonely Heart Search ";
		$_SESSION['resulttype'] = 2;

		/******************************************************************************************************************/

	}
	elseif($search_type == 2)//search profile
	{
		// 		echo "<!-- search type 2 resulttype 1 -->";
		/************************************************ Profile *********************************************************/
		$extended = false;

		/*echo "<pre>";
		print_r($_GET);
		echo "</pre>";*/
		
		$profileDetails = search::numAllPorfileSameArea2('profile', $_GET, $start_match, MESSAGE_RECORD_LIMIT);
		/*echo "<pre>";
		print_r($profileDetails);
		echo "</pre>";*/
		
		$memrec_data = $profileDetails['datas'];
		$memrec_total = $profileDetails['total'];

		$_SESSION['result_memrec'] = $memrec_total;
		$_SESSION['resultHeader'] = " Profiles Search";
		$_SESSION['resulttype'] = 1;

		/******************************************************************************************************************/

	}//end q_forsearch = 2

}


/**
 * Abfrage erweitert?
 */
/*if ($extended) {
 $firstgood = false;
}*/

/*$recordperpage = 20;
 $page = $_GET['page'];
$arrTmp = $_SESSION['result_memrec'];
$count = count($arrTmp);
$datas = array_slice($arrTmp, $page*$recordperpage, $recordperpage);*/

for($n = 0; $memrec_data[$n]; $n++)
{
	//$memrec_data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $memrec_data[$n][TABLE_MEMBER_CITY]);
	$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
	$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
	$memrec_data[$n][TABLE_MEMBER_HEIGHT] = ($memrec_data[$n][TABLE_MEMBER_HEIGHT]>0) ? funcs::getAnswerChoice($_SESSION['lang'],'', '$height',$memrec_data[$n][TABLE_MEMBER_HEIGHT]) : "";
}

SmartyPaginate::setTotal($memrec_total);
SmartyPaginate::assign($smarty);

//check search result
$smarty->assign('noresult',$noResult);
//get member by search//
$smarty->assign('count', 1);
$smarty->assign('extended', $extended);
$smarty->assign('minProfiles', PROFILE_SEARCH_MIN);
$smarty->assign('maxProfiles', PROFILE_SEARCH_MAX);
$smarty->assign('data_total', $memrec_total);
$smarty->assign('datas',$memrec_data);
$smarty->assign('year',date('Y'));
$smarty->display('index.tpl');
?>