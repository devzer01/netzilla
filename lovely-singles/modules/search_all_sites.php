<?php
	require_once("classes/HttpRequest.php");
	$sites = array(
					array(
							"name" => "HERZOASE",
							"url" => URL_WEB,
							"script" => "/extRequests.php",
							"is_external"	=> 0,
							"total" => 0),
					array(
							"name" => "SONAFLIRT",
							"url" => "http://www.sonaflirt.com",
							"script" => "/extRequests.php",
							"is_external"	=> 1,
							"total" => 0));
	SmartyPaginate::connect();
	if(!isset($_GET['next']))
		  SmartyPaginate::reset();
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

	if($_GET['q_username'] != ''){
		$url .= "&q_username=".$_GET['q_username'];
	}

	SmartyPaginate::setUrl($url); //smarty paging set URL


//******************************* Get Member Data *********************************

	if($_GET['cond']==1)
	{
		$criteria = array();
		$criteria['search_type'] = $_GET['q_forsearch'];
		$criteria['search_username'] = $_GET['q_nickname'];
		$criteria['gender'] = $_GET['q_gender'];
		$criteria['have_pic'] = $_GET['q_picture'];
		$criteria['country'] = $_GET['country'];
		$criteria['state'] = $_GET['state'];
		$criteria['city'] = $_GET['city'];
		$criteria['fake'] = 0;
		$criteria['minage'] = $_GET['q_minage'];
		$criteria['maxage'] = $_GET['q_maxage'];
		$criteria['start'] = 0;
		$criteria['limit'] = 1;
		$criteria['self_gender'] = $_GET['self_gender'];
	}

	$total = 0;
	$post = array(	"action" => "search",
					"access_password" => "this_is_only_for_administration_purpose",
					"criteria" => $criteria
				);
	foreach($sites as &$site)
	{
		$profileDetails = HttpRequest::sendReq($site['url'].$site['script'], $post);
		$site['total'] = $profileDetails['count'];
		$total += $profileDetails['count'];
		//break;
	}

	// Extent search results
	if($_SESSION['sess_username'] != '')
	{
		$selfinfo = DBConnect::assoc_query_1D("SELECT * FROM member WHERE id='{$_SESSION['sess_id']}'");
		$location = array(	'id'		=> $selfinfo['id'],
							'country'	=> $selfinfo['country'],
							'state'		=> $selfinfo['state'],
							'city'		=> $selfinfo['city'],
						);
	}
	$profileDetails = Search::getUsersListSameArea($criteria,$location);
	$sites[] = array('total' => $profileDetails['count'], 'is_external' => 0);
	$total += $profileDetails['count'];

	$result = array();
	$index = 0;
	$start = SmartyPaginate::getCurrentIndex();
	foreach($sites as &$site)
	{
		if(count($result) < SmartyPaginate::getLimit())
		{
			if($start <= ($index + $site['total']))
			{
				if(count($result) == 0)
					$post['criteria']['start'] = $start - $index;
				else
					$post['criteria']['start'] = 0;

				if($site['url'] != '')
				{
					$post['criteria']['limit'] = SmartyPaginate::getLimit()-count($result);
					$profileDetails = HttpRequest::sendReq($site['url'].$site['script'], $post);
				}
				else
				{
					$criteria['limit'] = SmartyPaginate::getLimit()-count($result);
					$profileDetails = Search::getUsersListSameArea($criteria,$location);
					//print_r($profileDetails);
				}
				foreach($profileDetails['data'] as &$entry)
				{
					$entry['site_url'] = $site['url'];
					$entry['is_external'] = $site['is_external'];
					array_push($result, $entry);
				}
			}
		}
		else
			break;

		$index += $site['total'];
	}

	$memrec_total = $total;
	$memrec_data = $result;

  	if($criteria['search_type'] == 1)
  	{

//******************************************* lonely_heart_ads *****************************************************
		$_SESSION['result_memrec'] = $memrec_total;
		$_SESSION['resultHeader'] = " Lonely Heart Search ";
		$_SESSION['resulttype'] = 2;
//******************************************************************************************************************
  	}
	elseif($criteria['search_type'] == 2)
	{
//************************************************ Profile *********************************************************		
		$_SESSION['result_memrec'] = $memrec_total;
		$_SESSION['resultHeader'] = " Profiles Search";
		$_SESSION['resulttype'] = 1;
//******************************************************************************************************************
	}

	for($n = 0; $memrec_data[$n]; $n++)
	{
		$memrec_data[$n][TABLE_MEMBER_APPEARANCE] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$appearance', $memrec_data[$n][TABLE_MEMBER_APPEARANCE]);
		$memrec_data[$n][TABLE_MEMBER_CIVIL] = funcs::getAnswerChoice($_SESSION['lang'],'$nocomment', '$status', $memrec_data[$n][TABLE_MEMBER_CIVIL]);
	}

	SmartyPaginate::setTotal($memrec_total);
	SmartyPaginate::assign($smarty);

	//get member by search//
	$smarty->assign('data_total', $memrec_total);
	$smarty->assign('datas',$memrec_data);
	$smarty->assign('year',date('Y'));
	$smarty->display('index.tpl');
?>