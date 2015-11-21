<?php
//check permission type//
$permission_lv = array(1, 8);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
define("SEARCH_RESULTS_PER_PAGE", 10);

$servers = array(
					//3 => "http://www.yourbuddy24.com/",
					//4 => "http://www.lovely-singles.com/",
					13 => array(	"url" => "http://www.flirt48.net/",
									"thumbs_url" => "http://www.flirt48.net/sites/flirt48.net/"
								),
				);
if(isset($_GET['search']) && ($_GET['search']=="1"))
{
	if(isset($_GET['server']) && isset($servers[$_GET['server']]) && ($servers[$_GET['server']]!=""))
	{
		$url = $_GET;
		unset($url['action']);
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

		if(!is_numeric($_GET['age_from']))
		{
			$_GET['age_from'] = 18;
		}
		if(!is_numeric($_GET['age_to']))
		{
			$_GET['age_to'] = 99;
		}

		if($_GET['age_from'] > $_GET['age_to'])
		{
			$temp = $_GET['age_from'];
			$_GET['age_from'] = $_GET['age_to'];
			$_GET['age_to'] = $temp;
		}

		list($country, $state, $city) = explode("_", $_GET['location']);
		$arr = array(
						"felder" => "id, gender, username, country, state, city, picturepath, description, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(birthday)), '%Y')+0 as age",
						"min_age" => $_GET['age_from'],
						"max_age" => $_GET['age_to'],
						"gender" => $_GET['gender'],
						"fake" => 1,
						"picturepath" => 1,
						"country" => $country,
						"state" => $state,
						"city" => $city,
						"order" => "id DESC",
						"assoc" => 1,
						"start" => $next,
						"offset" => SEARCH_RESULTS_PER_PAGE
					);
		if(is_numeric($city) && ($city>0))
		{
			unset($arr["state"]);
			unset($arr["country"]);
		}
		elseif(is_numeric($state) && ($state>0))
		{
			unset($arr["city"]);
			unset($arr["country"]);
		}

		//print_r($arr);exit;
		
		$client = new SoapClient(null, array('location' => $servers[$_GET['server']]["url"]."soapserver.php", 'uri' => "urn://kontaktmarkt"));
		$result = $client->simpleSearch($arr);
		//print_r($result);exit;
		$smarty->assign("server_url", $servers[$_GET['server']]["url"]);

		if(isset($result['content']))
		{
			if(is_array($result['content']) && count($result['content']))
			{
				foreach($result['content'] as $profile)
				{
					$profile["state"] = DBConnect::retrieve_value("SELECT parent FROM xml_cities WHERE id=".$profile["city"]);
					$profile["gender"] = funcs::getAnswerChoice("eng",'', '$gender', $profile["gender"]);
					$profile["country"] = funcs::getAnswerCountry("eng", $profile["country"]);
					$profile["state"] = funcs::getAnswerState("eng", $profile["state"]);
					$profile["city"] = funcs::getAnswerCity("eng", $profile["city"]);
					$profile["already"] = DBConnect::retrieve_value("SELECT 1 FROM member_temp WHERE id=".$profile['id']);
					$smarty->assign("server", $_GET['server']);
					$smarty->assign("profile", $profile);
					echo $smarty->fetch("admin_copyfakeprofiles_item.tpl");
				}

				SmartyPaginate::setTotal($result['total']);
				SmartyPaginate::assign($smarty);
				$smarty->assign("paginate", true);
				echo $smarty->fetch("admin_copyfakeprofiles_pager.tpl");
			}
		}
	}
}
elseif(isset($_GET['username']) && ($_GET['username']!=""))
{
	if(isset($_GET['server']) && isset($servers[$_GET['server']]["url"]) && ($servers[$_GET['server']]["url"]!=""))
	{
		$return_arr = array();

		$profile_arr = array(
						"felder" => "*",
						"username" => $_GET['username'],
						"username_exact" => 1,
						"assoc" => 1
					);
		$client = new SoapClient(null, array('location' => $servers[$_GET['server']]["url"]."soapserver.php", 'uri' => "urn://kontaktmarkt"));
		$result = $client->simpleSearch($profile_arr);

		if(isset($result['content']))
		{
			if(is_array($result['content']) && count($result['content']))
			{
				if(isset($_GET['getOnly']) && ($_GET['getOnly']=="1"))
				{
					list($result['content'][0]['year'], $result['content'][0]['month'], $result['content'][0]['date']) = explode("-", $result['content'][0]['birthday']);
					$smarty->assign("server", $_GET['server']);
					$smarty->assign('username', $_GET['username']);
					$smarty->assign('date', funcs::getRangeAge(1,31));
					$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
					$smarty->assign('year', funcs::getYear());
					$smarty->assign("profile", $result['content'][0]);
					echo $smarty->fetch("admin_copyfakeprofiles_edit.tpl");
					exit;
				}
				else
				{
					if(isset($_POST) && count($_POST))
					{
						$_POST['birthday'] = $_POST['year']."-".$_POST['month']."-".$_POST['date'];
						unset($_POST['year']);
						unset($_POST['month']);
						unset($_POST['date']);
						foreach($_POST as $key=>$val)
						{
							$result['content'][0][$key] = $val;
						}
					}

					/*if(DBConnect::retrieve_value("SELECT username FROM member WHERE username='".$result['content'][0]['username']."'"))
					{
							$return_arr = array(
											"result" => "0",
											"action" => "alert('Duplicate username in member table.')"
										);
					}
					else*/if(DBConnect::retrieve_value("SELECT username FROM member_temp WHERE username='".$result['content'][0]['username']."'"))
					{
							$return_arr = array(
											"result" => "0",
											"action" => "alert('Duplicate username in member_temp table.')"
										);
					}
					else
					{
						$username_ok = false;
						if($result['content'][0]['username'] != $_GET['username'])
						{
							$profile_arr = array(
											"felder" => "*",
											"username" => $result['content'][0]['username'],
											"username_exact" => 1,
											"assoc" => 1
										);
							$client = new SoapClient(null, array('location' => $servers[$_GET['server']]["url"]."soapserver.php", 'uri' => "urn://kontaktmarkt"));
							$result2 = $client->simpleSearch($profile_arr);

							if(isset($result2['content']) && is_array($result2['content']) && count($result2['content']))
							{
								$return_arr = array(
												"result" => "0",
												"action" => "alert('Duplicate username in ".$servers[$_GET['server']]["url"]."')"
											);
								$username_ok = false;
							}
							else
							{
								$username_ok = true;
							}
						}
						else
						{
							$username_ok = true;
						}

						if($username_ok)
						{
							if(isset($_POST['description']) && ($_POST['description']!=""))
							{
								$result['content'][0]['description'] = $_POST['description'];
							}
							$result['content'][0]["state"] = DBConnect::retrieve_value("SELECT parent FROM xml_cities WHERE id=".$result['content'][0]["city"]);
							$colnames = array_flip(DBconnect::get_col_names("member_temp"));
							$member_post = array_intersect_key($result['content'][0], $colnames);
							if(DBconnect::assoc_insert_1D($member_post, "member_temp"))
							{
								$url = $servers[$_GET['server']]["thumbs_url"]."thumbs/".$member_post['picturepath'];
								if(url_exists($url))
								{
									$uploaddir = "thumbs_temp/".$member_post['id'].'/';
									if(!is_dir($uploaddir))	//check have my user id directory
										mkdir($uploaddir, 0777); //create my user id directory
									
									copy($url, "thumbs_temp/".$member_post['picturepath']);
								}

								$age = DBConnect::retrieve_value("SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS('".$member_post['birthday']."')),  '%Y' ) +0");
								$city_count = DBConnect::retrieve_value("SELECT count(id) FROM member_temp WHERE city=".$member_post['city']." AND DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW()) - TO_DAYS(birthday)) ,  '%Y' )+0 =".$age);

								$action = "jQuery('#copy_".$_GET['username']."').remove(); jQuery('#edit_".$_GET['username']."').remove();";
								/*if($city_count >= 50)
								{
									$action = "alert('Already ".$city_count." profiles with age=".$age." in this city!'); nextLocation();";
								}*/

								$return_arr = array(
												"result" => "1",
												"action" => $action
											);
							}
							else
							{
								$return_arr = array(
												"result" => "0",
												"action" => "alert('Saving to database failed.');"
											);
							}
						}
					}
				}
			}
			else
			{
				$return_arr = array(
								"result" => "0",
								"action" => "alert('No profile found.');"
							);
			}
		}
		else
		{
			$return_arr = array(
							"result" => "0",
							"action" => "alert('Getting profile failed.');"
						);
		}
		echo json_encode($return_arr);
	}
}
else
{
	$countries = array(1);
	$arr = array();
	foreach($countries as $country)
	{
		$country_name = DBConnect::retrieve_value("SELECT name FROM xml_countries WHERE id=".$country);
		$states = DBConnect::assoc_query_2D("SELECT id, name FROM xml_states WHERE parent=".$country." ORDER BY name ASC");
		array_push($arr, array(
								"prefix" => $country."_0_",
								"cities" => array(array("id" => 0, "name" => $country_name." - No city selected"))
								));
		foreach($states as $state)
		{
			array_push($arr, array(
									"name" => $country_name." - ".$state['name'],
									"prefix" => $country."_".$state['id']."_",
									"cities" =>  DBConnect::assoc_query_2D("SELECT id, name FROM xml_cities WHERE parent=".$state['id']." AND status=1 ORDER BY name ASC")
									));
		}
	}

	$countries = array(2,22);
	foreach($countries as $country)
	{
		$country_name = DBConnect::retrieve_value("SELECT name FROM xml_countries WHERE id=".$country);
		array_push($arr, array(
								"prefix" => $country."_0_",
								"cities" => array(array("id" => 0, "name" => $country_name." - No city selected"))
								));
		array_push($arr, array(
									"name" => $country_name,
									"prefix" => $country."_",
									"states" =>  DBConnect::assoc_query_2D("SELECT id, name FROM xml_states WHERE parent=".$country." AND status=1  ORDER BY name ASC")
									));
	}

	$smarty->assign("states", $arr);
	$smarty->assign("servers", $servers);
	$smarty->display('admin.tpl');
}

function url_exists($url) {
    $h = get_headers($url);
    $status = array();
    preg_match('/HTTP\/.* ([0-9]+) .*/', $h[0] , $status);
    return ($status[1] == 200);
}
?>