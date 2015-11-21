<?php
// $random_contacts = DBconnect::assoc_query_2D("SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . RANDOM_CONTACTS);
// $smarty->assign("random_contacts", $random_contacts);

$sql = "SELECT username, id, picturepath FROM member WHERE gender=2 AND isactive=1 AND fake=1 AND id>3 ORDER BY RAND() LIMIT 1";
$rs = mysql_query($sql);
$smarty->assign('random_profile', mysql_fetch_assoc($rs));

if(isset($_GET['type']) && ($_GET['type']!=""))
{
	switch($_GET['type'])
	{
		case 'searchUsername':
		case 'searchMembers':
		case 'searchGender':
			$url = $_GET;
			if(isset($url['next']))
				unset($url['next']);
			$url = "?".http_build_query($url);
			SmartyPaginate::connect();
			SmartyPaginate::setLimit(SEARCH_RESULTS_PER_PAGE);
			SmartyPaginate::setPageLimit(10);
			SmartyPaginate::setUrl($url);
			if((!isset($_GET['next'])) || ($_GET['next']==1)){
				$next = 0;
			}else{
				$next = $_GET['next']-1;
			}
			SmartyPaginate::setCurrentItem($next+1);
			
			if($_GET['type'] == "searchMembers")
			{
				$arr = array("q_gender", 'q_picture', 'q_minage', 'q_maxage', 'country', 'state', 'city');
				$last_search_hash = array();
				do
				{
					$search_hash = serialize($_GET);
					if($search_hash != $last_search_hash)
					{
						$profileDetails = search::geProfile('', $_GET['q_gender'], '', $_GET['q_picture'], $_GET['country'], $_GET['state'], $_GET['city'], $_GET['q_minage'], $_GET['q_maxage'], $next, SEARCH_RESULTS_PER_PAGE, $id_arr);

						if(isset($profileDetails['datas']))
							$result = $profileDetails['datas'];
						if(isset($profileDetails['total']))
							$memrec_total = $profileDetails['total'];
					}
					$last_search_hash = $search_hash;

					if(count($arr))
					{
						$field = array_pop($arr);
						switch($field)
						{
							case "q_picture";
								$_GET[$field] = 0;
								break;
							case "q_minage";
								$_GET[$field] = 18;
								break;
							case "q_maxage";
								$_GET[$field] = 99;
								break;
							default:
								$_GET[$field] = "";
								break;
						}
					}
					else
					{
						break;
					}

					if(($memrec_total<(SEARCH_RESULTS_PER_PAGE/2)) && (ENABLE_ADDITIONAL_SEARCH_RESULT))
						$loop = true;
					else
						$loop = false;
				}
				while($loop);
			}
			elseif($_GET['type'] == "searchUsername")
			{
				$profileDetails = search::geProfile($_GET['username'], '', '', '', '', '', '', '', '', $next, SEARCH_RESULTS_PER_PAGE);
				if(isset($profileDetails['datas']))
					$result = $profileDetails['datas'];
				if(isset($profileDetails['total']))
					$memrec_total = $profileDetails['total'];
			}
			elseif($_GET['type'] == "searchGender")
			{
				if(isset($_GET['wsex']) && isset($_GET['sex']))
				{
					$gender = $_GET['wsex']=="m"?1:2;
					$look_gender = $_GET['sex']=="m"?1:2;
					$profileDetails = search::geProfile('', $gender, $look_gender, '', '', '', '', '', '', $next, SEARCH_RESULTS_PER_PAGE);
					if(isset($profileDetails['datas']))
						$result = $profileDetails['datas'];
					if(isset($profileDetails['total']))
						$memrec_total = $profileDetails['total'];
				}
			}
			
			//Additional search result
			if((count($result)<SEARCH_RESULTS_PER_PAGE) && ($memrec_total<(2*SEARCH_RESULTS_PER_PAGE)) && ($_GET['type'] == "searchMembers"))
			{
				$profileDetails = search::geProfile('', $_GET['q_gender'], '', $_GET['q_picture'], $_GET['country'], $_GET['state'], $_GET['city'], $_GET['q_minage'], $_GET['q_maxage'], $next, SEARCH_RESULTS_PER_PAGE);
			}
			if(is_array($result) && count($result))
			{
				SmartyPaginate::setTotal($memrec_total);
				SmartyPaginate::assign($smarty);
				$smarty->assign("paginate", true);
				$smarty->assign("result", $result);
				$smarty->display("profile_list.tpl");
			}
			break;
		case 'searchNewestMembers':
			if(isset($_SESSION['sess_id']))
				$profile = DBConnect::assoc_query_1D("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
			else
				$profile = array("gender" => 1);
			if(isset($_GET['total']) && is_numeric($_GET['total']))
				$maxResult = $_GET['total'];
			else
				$maxResult = SEARCH_RESULTS_PER_PAGE;
			if($profile['gender']==2)
			{
				$male_amount = 0.6;
				$female_amount = 0.4;
			}
			else
			{
				$male_amount = 0.4;
				$female_amount = 0.6;
			}
			
			$NewestMembersMale = DBConnect::assoc_query_2D("SELECT username, picturepath FROM member WHERE gender='1' AND picturepath!='' AND isactive=1 AND id>3 ORDER BY id DESC LIMIT ".($maxResult*2));

			$NewestMembersFemale = DBConnect::assoc_query_2D("SELECT username, picturepath FROM member WHERE gender='2' AND picturepath!='' AND isactive=1 AND id>3 ORDER BY id DESC LIMIT ".($maxResult*2));

			
			
			shuffle($NewestMembersMale);
			shuffle($NewestMembersFemale);

			$NewestMembersMale = array_slice($NewestMembersMale,0, round($maxResult*$male_amount));
			$NewestMembersFemale = array_slice($NewestMembersFemale,0, round($maxResult*$female_amount));
			$NewestMembers =  array_merge($NewestMembersMale, $NewestMembersFemale);
			shuffle($NewestMembers);
			$smarty->assign("result", $NewestMembers);
			/*if($_SESSION['sess_id'])
			{
				$gender = DBConnect::retrieve_value("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
				$sql_gender = "AND gender='".($gender=="1"?"2":"1")."'";
			}

			$NewestMembers = DBConnect::assoc_query_2D("SELECT username, picturepath FROM member WHERE 1 $sql_gender AND picturepath!='' AND isactive=1 AND id>3 ORDER BY id DESC LIMIT 100");

			shuffle($NewestMembers);
			$smarty->assign("result", array_slice($NewestMembers,0,SEARCH_RESULTS_PER_PAGE));*/
			
			if (!isset($_GET['template'])) $smarty->display("profile_list.tpl");
			else {
				if ($_GET['template'] == 'flirten') {
					$smarty->display('newest_users.tpl');
				}
			}
			break;
		case 'searchOnline':
			if(isset($_GET['total']) && is_numeric($_GET['total']))
				$total = $_GET['total'];
			else
				$total = SEARCH_RESULTS_PER_PAGE;
			$OnlineRealMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member m LEFT JOIN member_session s ON m.id=s.member_id WHERE 1 AND m.isactive=1 AND m.id>3 AND picturepath!='' AND s.last_action_datetime>NOW()-INTERVAL 5 MINUTE ORDER BY RAND() LIMIT ".MAX_REAL_MEMBERS_ONLINE);

			$time = time();
			if(($time-(5*60))>ONLINE_MEMBERS_DATETIME)
			{
				$num_profiles_to_removed = round(($time - ONLINE_MEMBERS_DATETIME)/(5*60));

				$OnlineFakeMaleMembersOld = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_MALE'"));

				$OnlineFakeFemaleMembersOld = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_FEMALE'"));

				$count = $total;
				$male_id_arr = array();
				if(is_array($OnlineFakeMaleMembersOld))
				{
					//shuffle($OnlineFakeMaleMembersOld);
					for($i=0; $i<$num_profiles_to_removed; $i++)
					{
						if(is_array($OnlineFakeMaleMembersOld))
							array_shift($OnlineFakeMaleMembersOld);
						else
							break;
					}
					$count = $total - count($OnlineFakeMaleMembersOld);
					foreach($OnlineFakeMaleMembersOld as $item)
					{
						array_push($male_id_arr, $item['id']);
					}
				}

				if($count>0)
				{
					if(count($male_id_arr))
						$sql_male_id = " AND id NOT IN (".implode(',', $male_id_arr).") ";
					$OnlineFakeMaleMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member WHERE 1 $sql_male_id AND gender=1 AND isactive=1 AND fake=1 AND picturepath!='' AND id>3 ORDER BY RAND() LIMIT ".$count);

					if(is_array($OnlineFakeMaleMembersOld))
						$OnlineFakeMaleMembers = array_merge($OnlineFakeMaleMembers, $OnlineFakeMaleMembersOld);
				}
				else
				{
					$OnlineFakeMaleMembers = $OnlineFakeMaleMembersOld;
				}

				$count = $total;
				$female_id_arr = array();
				if(is_array($OnlineFakeFemaleMembersOld))
				{
					//shuffle($OnlineFakeFemaleMembersOld);
					for($i=0; $i<$num_profiles_to_removed; $i++)
					{
						if(is_array($OnlineFakeMaleMembersOld))
							array_shift($OnlineFakeFemaleMembersOld);
						else
							break;
					}
					$count = $total - count($OnlineFakeFemaleMembersOld);
					foreach($OnlineFakeFemaleMembersOld as $item)
					{
						array_push($female_id_arr, $item['id']);
					}
				}

				if($count>0)
				{
					if(count($female_id_arr))
						$sql_female_id = " AND id NOT IN (".implode(',', $female_id_arr).") ";

					$OnlineFakeFemaleMembers = DBConnect::assoc_query_2D("SELECT id, username, picturepath FROM member WHERE 1 $sql_female_id AND gender=2 AND isactive=1 AND fake=1 AND picturepath!='' AND id>3 ORDER BY RAND() LIMIT ".$count);
					if(is_array($OnlineFakeFemaleMembersOld))
						$OnlineFakeFemaleMembers = array_merge($OnlineFakeFemaleMembers, $OnlineFakeFemaleMembersOld);
				}
				else
				{
					$OnlineFakeFemaleMembers = $OnlineFakeFemaleMembersOld;
				}

				DBConnect::execute_q("UPDATE config SET value='".time()."' WHERE name='ONLINE_MEMBERS_DATETIME'");
				DBConnect::execute_q("UPDATE config SET long_value='".serialize($OnlineFakeMaleMembers)."' WHERE name='ONLINE_MEMBERS_MALE'");
				DBConnect::execute_q("UPDATE config SET long_value='".serialize($OnlineFakeFemaleMembers)."' WHERE name='ONLINE_MEMBERS_FEMALE'");
			}
			else
			{
				$OnlineFakeMaleMembers = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_MALE'"));

				$OnlineFakeFemaleMembers = unserialize(DBConnect::retrieve_value("SELECT long_value FROM config WHERE name='ONLINE_MEMBERS_FEMALE'"));
			}

			if(isset($_SESSION['sess_id']) && ($_SESSION['sess_id']))
			{
				$gender = DBConnect::retrieve_value("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
				if($gender=="1")
					$OnlineMembers = $OnlineFakeFemaleMembers;
				else
					$OnlineMembers = $OnlineFakeMaleMembers;
			}
			else
			{
				$OnlineMembers = array_merge($OnlineFakeMaleMembers, $OnlineFakeFemaleMembers);
			}

			if(is_array($OnlineRealMembers) && count($OnlineRealMembers))
				$OnlineMembers = array_merge($OnlineMembers, $OnlineRealMembers);
			shuffle($OnlineMembers);
			$smarty->assign("result", array_slice($OnlineMembers,0,$total));
			if (!isset($_GET['template'])) $smarty->display("profile_list.tpl");
			else {
				if ($_GET['template'] == 'bigicon_online') {
					$smarty->display('bigicon_online.tpl');
				}
			}
			break;
	}
}
else
	$smarty->display('index.tpl');
?>