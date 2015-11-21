<?php

SmartyPaginate::connect();
SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE); //smarty paging set records per page
SmartyPaginate::setPageLimit(SEARCH_RESULTS_TOTAL_PAGES); //smarty paging set limit pages show

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

if(!isset($_GET['next'])){
	SmartyPaginate::setCurrentItem(1); //go to first record
	$next = 0;
	$pageLimit = 15;
}else{
	$next = $_GET['next'];
	$pageLimit = $next + 15;
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

if($username != '')
{
	$profileDetails = Search::searchName($username, $next, SEARCH_RESULTS_PER_PAGE); //limit 15
	$numProfile = count($profileDetails);
	
	$countAllProfile = Search::countSearchName($username);
	
	if ($numProfile != 0)
	{
		$memrec_total = $numProfile + $next;
	}
	else
	{
		$memrec_total = $numProfile;
	}
	
	if (is_array($profileDetails))
	{
		$memrec_data = array_slice($profileDetails, 0, SEARCH_RESULTS_PER_PAGE);
	}
	
	
	if (isset($_SESSION[sess_id]) && $_SESSION[sess_id]>0 && count($lonelyDetails) < SEARCH_RESULTS_PER_PAGE)
	{
		//login
		if($numProfile > 0 && count($profileDetails) > 0 && is_array($profileDetails))
		{
			$idTmp = "";
			for($i=0; $i < $numProfile; $i++)
			{
				$idTmp .= ($i>0)? "," : "";
				$idTmp .= $profileDetails[$i]['id'];
			}
		}
		
		
		$temp = Search::geProfileSameArea($_SESSION[sess_id], $gender, $idTmp, $next, SEARCH_RESULTS_PER_PAGE, $city);
		$profileDetailsSamArea = $temp[0];
		
		$numProfileSameArea = $temp[1];
		
		if($numProfile > 0 && $numProfileSameArea > 0)
		{
			$profileDetailsSamArea[0]['advanced_result'] = "yes";
			$arrData = array_merge($profileDetails, $profileDetailsSamArea);
			$memrec_total = $numProfile + $numProfileSameArea;
			$memrec_data = array_slice($arrData, 0, SEARCH_RESULTS_PER_PAGE);
		
		}
		elseif($numProfile <= 0 && $numProfileSameArea > 0)
		{
			$noResult = 1;
			$profileDetailsSamArea[0]['advanced_result'] = "yes";
			$memrec_total = $numProfileSameArea;
			$memrec_data = array_slice($profileDetailsSamArea, 0, SEARCH_RESULTS_PER_PAGE);
		
		}
		elseif($numProfile > 0 && $numProfileSameArea <= 0)
		{
			$memrec_total = $numProfile;
			$memrec_data = array_slice($profileDetails, 0, SEARCH_RESULTS_PER_PAGE);
		
		}
		
	}
	else 
	{
	//not login
		
				if(count($profileDetails) < 1 )
				{//noresult
					$noResult = 1;
					
					$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
					$add = Search::geProfileByName('', $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), SEARCH_RESULTS_PER_PAGE, $_SESSION[sess_id]);
					
					if(is_array($add))
					{
						$add[0]['advanced_result'] = "yes";
						$profileDetails = array_merge($profileDetails, $add);
					
					}
					
				}
				else if($numProfile < PROFILE_SEARCH_MIN)
				{
					$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
					$add = Search::geProfileByName($username, $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), SEARCH_RESULTS_PER_PAGE, $_SESSION[sess_id]);
					if(is_array($add))
					{
						$add[0]['advanced_result'] = "yes";
						$profileDetails = array_merge($profileDetails, $add);
		
					}
				}
	
				
				$memrec_total = count($profileDetails);
	
				if($memrec_total > 0)
				{
					$memrec_data = array_slice($profileDetails, $next, SEARCH_RESULTS_PER_PAGE);
				}
	}
	
	$_SESSION['result_memrec'] = $memrec_total;
	$_SESSION['resultHeader'] = " Profiles Search";
	$_SESSION['resulttype'] = 1;
// print_r($memrec_data);	
	
}else {
	if($search_type == 1)
	{
		//search ads
	
		/******************************************* lonely_heart_ads *****************************************************/
		echo "<!-- search type 1 resulttype 2 -->";
		$self_gender = $_GET['self_gender'];
	
		$lonelyDetails = Search::getLonelyHeartAds($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age, $next, SEARCH_RESULTS_PER_PAGE, $self_gender);
		//$numLonelyDetails = Search::countLonelyHeartAds($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age);
		$numLonelyDetails = count($lonelyDetails);
	
		//login
		if(isset($_SESSION[sess_id]) && $_SESSION[sess_id]>0 && count($lonelyDetails) < SEARCH_RESULTS_PER_PAGE){
	
			if($numLonelyDetails>0){
	
				$idTmp = "";
				for($i=0;$i<$numLonelyDetails;$i++){
					$idTmp .= ($i>0)? "," : "";
					$idTmp .= $lonelyDetails[$i][userid];
				}
			}
	
			$temp = Search::getLonelyHeartAdsSameArea($_SESSION[sess_id], $gender, $idTmp, $next, SEARCH_RESULTS_PER_PAGE, $city, $self_gender);
			$lonelyDetailsSameArea = $temp[0];
	
	
			$countLonelySameArea = $temp[1];
	
			if($numLonelyDetails>0 && $countLonelySameArea>0){
				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
				$arrData = array_merge($lonelyDetails, $lonelyDetailsSameArea);
				$memrec_total = $numLonelyDetails + $countLonelySameArea;
				$memrec_data = array_slice($arrData, 0, SEARCH_RESULTS_PER_PAGE);
	
			}elseif($numLonelyDetails<=0 && $countLonelySameArea>0){
					
				$noResult = 1;
				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
				$memrec_total = $countLonelySameArea;
				$memrec_data = array_slice($lonelyDetailsSameArea, 0, SEARCH_RESULTS_PER_PAGE);
	
			}elseif($numLonelyDetails>0 && $countLonelySameArea<=0){
	
				$memrec_total = $numLonelyDetails;
				$memrec_data = array_slice($lonelyDetails, 0, SEARCH_RESULTS_PER_PAGE);
	
			}
		}else{//not login
	
			/* $memrec_total = $numLonelyDetails;
	
			if($memrec_total>0){
			$memrec_data = array_slice($lonelyDetails, 0, SEARCH_RESULTS_PER_PAGE);
			} */
			$temp = Search::getLonelyHeartAdsSameArea('', $gender, $idTmp, $next, SEARCH_RESULTS_PER_PAGE, $city, $self_gender);
			$lonelyDetailsSameArea = $temp[0];
	
	
			$countLonelySameArea = $temp[1];
	
			if($numLonelyDetails>0 && $countLonelySameArea>0){
				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
				$arrData = array_merge($lonelyDetails, $lonelyDetailsSameArea);
				$memrec_total = $numLonelyDetails + $countLonelySameArea;
				$memrec_data = array_slice($arrData, 0, SEARCH_RESULTS_PER_PAGE);
	
			}elseif($numLonelyDetails<=0 && $countLonelySameArea>0){
	
				$noResult = 1;
				$lonelyDetailsSameArea[0]['advanced_result'] = "yes";
				$memrec_total = $countLonelySameArea;
				$memrec_data = array_slice($lonelyDetailsSameArea, 0, SEARCH_RESULTS_PER_PAGE);
	
			}elseif($numLonelyDetails>0 && $countLonelySameArea<=0){
	
				$memrec_total = $numLonelyDetails;
				$memrec_data = array_slice($lonelyDetails, 0, SEARCH_RESULTS_PER_PAGE);
	
			}
	
		}
	
		$_SESSION['result_memrec'] = $memrec_total;
		$_SESSION['resultHeader'] = " Lonely Heart Search ";
		$_SESSION['resulttype'] = 2;
	
		/******************************************************************************************************************/
	
	}
	elseif($search_type == 2)//search profile
	{
		echo "<!-- search type 2 resulttype 1 -->";
		/************************************************ Profile *********************************************************/
		$extended = false;
	
		//$profileDetails = Search::geProfile($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age, $next, PROFILE_SEARCH_MAX-$next, $_SESSION[sess_id]); // SEARCH_RESULTS_PER_PAGE
		//$numProfile = Search::countProfile($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age);
	
		$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, $min_age, $max_age);
		$profileDetails = Search::geProfile($username, $gender, $have_pic, $country, $state, $city, $min_age, $max_age, $next, SEARCH_RESULTS_PER_PAGE, $_SESSION[sess_id]);
		$numProfile = count($profileDetails);
	
		// 	echo "numProfile: ".$numProfile."<br>";
		if ($numProfile != 0)
		{
			$memrec_total = $numProfile + $next;
		}
		else
		{
			$memrec_total = $numProfile;
		}
	
		if (is_array($profileDetails))
		{
			$memrec_data = array_slice($profileDetails, 0, SEARCH_RESULTS_PER_PAGE);
		}
		/**
		 * Erste Abfrage zuviele Ergebnisse
		 */
	
		/*  if ($memrec_total > PROFILE_SEARCH_MAX) {
		 $memrec_total = PROFILE_SEARCH_MAX;
		} */
	
	
	
		echo "<!--numProfile: ".$numProfile.", good: ".$firstTooBig.", memrec_total: ".$memrec_total.", next: ".$next."-->";
	
		if ($memrec_total < PROFILE_SEARCH_MIN) //PROFILE_SEARCH_MIN = 15
		{
			$extended = true;
	
			
			if(isset($_SESSION['sess_id']) && $_SESSION['sess_id'] > 0 && count($profileDetails) < SEARCH_RESULTS_PER_PAGE)
			{
				//login
				if($numProfile > 0 && count($profileDetails) > 0 && is_array($profileDetails))
				{
					$idTmp = "";
					for($i=0; $i < $numProfile; $i++)
					{
						$idTmp .= ($i>0)? "," : "";
						$idTmp .= $profileDetails[$i]['id'];
					}
				}
	
				$temp = Search::geProfileSameArea($_SESSION[sess_id], $gender, $idTmp, $next, SEARCH_RESULTS_PER_PAGE, $city);
				
				$profileDetailsSamArea = $temp[0];
	
				$numProfileSameArea = $temp[1];
					
				echo "<!-- 1 -->";
				if($numProfile > 0 && $numProfileSameArea > 0)
				{
					$profileDetailsSamArea[0]['advanced_result'] = "yes";
					$arrData = array_merge($profileDetails, $profileDetailsSamArea);
					$memrec_total = $numProfile + $numProfileSameArea;
					$memrec_data = array_slice($arrData, 0, SEARCH_RESULTS_PER_PAGE);
	
				}
				elseif($numProfile <= 0 && $numProfileSameArea > 0)
				{
					//no result
					$noResult = 1;
					$profileDetailsSamArea[0]['advanced_result'] = "yes";
					$memrec_total = $numProfileSameArea;
					$memrec_data = array_slice($profileDetailsSamArea, 0, SEARCH_RESULTS_PER_PAGE);
	
				}
				elseif($numProfile > 0 && $numProfileSameArea <= 0)
				{
					$memrec_total = $numProfile;
					$memrec_data = array_slice($profileDetails, 0, SEARCH_RESULTS_PER_PAGE);
	
				}
	
				/**
				 * Maximale Anzahl der Datensätze: $maxProfile
				 */
	
				/* if ($memrec_total > PROFILE_SEARCH_MAX) { //PROFILE_SEARCH_MAX
				 $memrec_total = PROFILE_SEARCH_MAX;
				} */
	
	
				echo "<!-- sess id, num: ".$numProfile." count: ".count($profileDetails)."-->";
				echo "<!-- sess id, memrec_total: ".$memrec_total." next: ".$next."-->";
				//print_r($memrec_data);
				//echo count($memrec_data);
			}
			else
			{
				//not login
				if(count($profileDetails) < SEARCH_RESULTS_PER_PAGE)
				{
					//if no result
					$noResult = 1;
	
					echo "<!-- Profiles:".count($profileDetails)."-->";
					//$profileDetails = Search::geProfile('', $gender, $have_pic, $country, $state, $city, 18, 99, 0, PROFILE_SEARCH_MAX, $_SESSION[sess_id]); //PROFILE_SEARCH_MAX
					$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
					$profileDetails = Search::geProfile('', $gender, $have_pic, $country, $state, $city, 18, 99, 0, $numSameArea, $_SESSION[sess_id]);
	
	
				}
				else if($numProfile < PROFILE_SEARCH_MIN)
				{
					echo "<!-- 2 -->";
					//$add = Search::geProfile($username, $gender, $have_pic, $country, $state, $city, 18, 99, $numProfile, PROFILE_SEARCH_MAX-$numProfile, $_SESSION[sess_id]); //PROFILE_SEARCH_MAX
					$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
	
					$add = Search::geProfile('', $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), $numSameArea-count($profileDetails), $_SESSION[sess_id]);
					// 				$profileDetails = Search::geProfile('', $gender, $have_pic, $country, $state, $city, 18, 99, 0, $numSameArea, $_SESSION[sess_id]);
	
					// 				print_r($add);
	
					if(is_array($add))
					{
						echo "<!-- 2-1 ; Profiles:".count($profileDetails).", next: ".$next."-->";
						$add[0]['advanced_result'] = "yes";
						$profileDetails = array_merge($profileDetails, $add);
							
					}
				}
	
				if ($_SESSION['username'] =='') //if member not login
				{
					echo "<!-- sess -->";
					if(count($profileDetails) < PROFILE_SEARCH_MIN)
					{
						echo "<!-- count -->";
						//$add = Search::geProfile('', $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), PROFILE_SEARCH_MAX-count($profileDetails), $_SESSION[sess_id]); //PROFILE_SEARCH_MAX
						//print_r($add);
						$numSameArea = search::numAllProfileSameArea($gender, $have_pic, $country, $state, $city, 18, 99);
						$add = Search::geProfile('', $gender, $have_pic, $country, $state, '', 18, 99, count($profileDetails), $numSameArea-count($profileDetails), $_SESSION[sess_id]);
	
						if(is_array($add))
						{
							echo "<!-- count-1 -->";
							$add[0]['advanced_result'] = "yes";
							$profileDetails = array_merge($profileDetails, $add);
						}
					}
				}
				//echo "<!--".print_r($profileDetails,true)."-->";
	
				$memrec_total = count($profileDetails);
	
				/**
				 * Maximale Anzahl der Datensätze: $maxProfile
				 */
	
				/* if ($memrec_total > PROFILE_SEARCH_MAX) { //PROFILE_SEARCH_MAX
				 $memrec_total = PROFILE_SEARCH_MAX;
				} */
	
				if($memrec_total > 0)
				{
					$memrec_data = array_slice($profileDetails, $next, SEARCH_RESULTS_PER_PAGE);
				}
	
			}
	
		}
	
	
		/**
		 * Maximale Anzahl der Datensätze: $maxProfile
		 */
	
		/* if ($memrec_total > PROFILE_SEARCH_MAX) { //PROFILE_SEARCH_MAX
		 $memrec_total = PROFILE_SEARCH_MAX;
		} */
	
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
	$memrec_data[$n][TABLE_MEMBER_CITY] = funcs::getAnswerCity($_SESSION['lang'], $memrec_data[$n][TABLE_MEMBER_CITY]);
	$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
	$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
}

SmartyPaginate::setTotal($memrec_total);
SmartyPaginate::assign($smarty);

//check search result
$smarty->assign('noresult',$noResult);
//get member by search//
$smarty->assign('extended', $extended);
$smarty->assign('minProfiles', PROFILE_SEARCH_MIN);
$smarty->assign('maxProfiles', PROFILE_SEARCH_MAX);
$smarty->assign('data_total', $memrec_total);
$smarty->assign('datas',$memrec_data);
$smarty->assign('year',date('Y'));
$smarty->display('index.tpl');
?>