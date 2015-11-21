<?php
require_once("XMLParser.php");
class funcs
{
	static function getCookiePath($username)
	{
		return dirname($_SERVER['SCRIPT_FILENAME'])."/cookies/".$username.".txt";
	}

	static function checkCurrentProfile($comman_profiles, $current_profile)
	{
		funcs::savelog("Random new profile");
		if(count($comman_profiles)>0)
		{
			if($current_profile<count($comman_profiles)-1)
				$current_profile++;
			else
				$current_profile=0;
			funcs::savelog("Profile index: ".$current_profile);
			return $current_profile;
		}
		else
		{
			funcs::savelog("All profiles are unable to log in");
			funcs::savelog("FINISHED");
			exit;
		}
	}

	static function memberlogin($username, $password, $loginURL, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);
		$requireData = array(	"txtLoginUsername" => $username,
							"txtLoginPassword" => $password,
							"cbAutoLogin" => "on",
							"cmdSignIn" => "Sign In"
							);
							
		$requireData = http_build_query($requireData);

		if(!(self::isLoggedIn($username)))
		{
			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
				
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);

				//echo "<div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');//

				////file_put_contents("logging/".ID."_".$username.".log", $result);
					
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];
				
				if((strpos($result, "Hi, ".$username."!")!==false) && (strpos($result, "sign out")!==false)) //if($cookie['sid']!="")// Hi, rosiebell!  sign out
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					if($count_login==3)
					{
						self::savelog("User ".$username." tried to login 3 times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".SITE_ID." LIMIT 1";						
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						funcs::savelog("FINISHED");

						exit;
					}
				}
			}
		}
		else
			return true;
	}

	static function isLoggedIn($username)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.matchdoctor.com");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.matchdoctor.com/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		curl_close($ch);

		if((strpos($indexpage, "Hi, ".$username."!")!==false) && (strpos($indexpage, "sign out")!==false))//If logged in
		{
			self::savelog("This profile: ".$username." has been logged in.");
			$loggedin = true;
		}
		else
		{
			self::savelog("This profile: ".$username." does not log in.");
			$loggedin = false;
		}

		return $loggedin;
	}

	static function keepLogIn($username, $password, $loginURL, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);
		$requireData = array(	"login_lg" => $username,
							"pass_lg" => $password,
							"x" => "13",
							"y" => "3"
							);
		$requireData = http_build_query($requireData);

		if(!(self::isLoggedIn($username)))
		{
			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);

				curl_setopt($ch, CURLOPT_URL, "http://www.matchdoctor.com/homepage.php");
				curl_setopt($ch, CURLOPT_REFERER, "http://www.matchdoctor.com/authorization.php");
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
				//curl_setopt($ch, CURLOPT_POST, 1);
				//curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$homepage = curl_exec($ch);
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];
				
				if(strpos($homepage, "Log off")!==false) //if($cookie['sid']!="")//
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					if($count_login==3)
					{
						self::savelog("User ".$username." tried to login 3 times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".SITE_ID." LIMIT 1";						
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						return false;
					}
				}
			}
		}
		else
			return true;
	}

	static function getUnLogInUser()
	{
		$sql = "SELECT `username` , `password` FROM `user_profiles` WHERE `status` != 'banded' AND site_id =".SITE_ID;
		return DBConnect::assoc_query_2D($sql);
	}

	static function setUserToReuse($username, $password)
	{
		$sql = "UPDATE `user_profiles` SET `status` = 'true' WHERE `username` = '".$username."' AND `password` = '".$password."' AND site_id =".SITE_ID;
		DBConnect::execute_q($sql);
		funcs::savelog($username.":".$password." can be reuse");
	}

	static function getViewState($username, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL);
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		$result=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$result=str_replace("id=\"__VIEWSTATE\" value=\"","", $result);
		$result=trim(substr($result,0,strpos($result,"\" />")));
		return $result;
	}

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		if(isset($cookie['Username']))
			return true;
		else
			return false;
		/*$diff = (int)$cookie['Username']['expired']-time();
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}*/
	}

	static function getViewStateAndEventValidation($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL);
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		$viewstate=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$viewstate=str_replace("id=\"__VIEWSTATE\" value=\"","",$viewstate);
		$viewstate=trim(substr($viewstate,0,strpos($viewstate,"\" />")));

		$eventValidation=substr($result,strpos($result,"id=\"__EVENTVALIDATION\""));
		$eventValidation=str_replace("id=\"__EVENTVALIDATION\" value=\"","",$eventValidation);
		$eventValidation=trim(substr($eventValidation,0,strpos($eventValidation,"\" />")));

		return array($viewstate, $eventValidation);
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page)
	{
		//$viewstate = self::getViewState($username, $searchReferer);
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $searchURL);
		$cookie_path = self::getCookiePath($username);

		$requireData = array(	
			'__EVENTTARGET' => '',
			'__LASTFOCUS' => '',
			'__VIEWSTATE' => $viewstate,
			'__EVENTVALIDATION' => $eventValidation,
			'ddGender' => $searchData['ddGender'],
			'ddSeeking' => $searchData['ddSeeking'],
			'ddAgeMin' => $searchData['ddAgeMin'],
			'ddAgeMax' => $searchData['ddAgeMax'],
			'rdPhoto' => '-1',
			'ctrlGeo_search:txtDistance' => '',
			'ctrlGeo_search:ddDistanceType' => '1',
			'ctrlGeo_search:txtZip' => '',
			'ctrlGeo_search:SearchType' => 'rdLocation',
			'ctrlGeo_search:ctrlGeo_ddCountry:ddCountry' => $searchData['ddCountry'],
			'div_relationshiptype_ck' => '0',
			'div_maritalstatus_ck' => '0',
			'div_haircolor_ck' => '0',
			'div_bodytype_ck' => '0',
			'ctrlSearch:rp1:_ctl2:ctrlProfileQuestionList:repQuestionList:_ctl3:ctrlProfileQuestion:ctrlMyProfile_height:ddHeightMin' => '0',
			'ctrlSearch:rp1:_ctl2:ctrlProfileQuestionList:repQuestionList:_ctl3:ctrlProfileQuestion:ctrlMyProfile_height:ddHeightMax' => '0',
			'div_education_ck' => '0',
			'div_ethnicity_ck' => '0',
			'div_languages_ck' => '0',
			'div_religion_ck' => '0',
			'div_smokes_ck' => '0',
			'div_alcohole_ck' => '0',
			'div_liveAlone_ck' => '0',
			'div_pets_ck' => '0',
			'div_children_ck' => '0',
			'div_childrenPlan_ck' => '0',
			'div_job_ck' => '0',
			'div_income_ck' => '0',
			'div_activities_ck' => '0',
			'div_characteristics_ck' => '0',
			'cmdSearch.x' => '26',
			'cmdSearch.y' => '12',
			'ctrlSearchUserName_userNameSearch' => ''
							);

		/*echo "<pre>";
		print_r($searchData);
		echo "</pre>";
		exit;
		*/
	

		if($page==2)
		{
			$searchURL		= 'http://www.matchdoctor.com/searchResult.aspx?summaryView=2&currentView=1&page=2';
			$searchReferer	= 'http://www.matchdoctor.com/searchResult.aspx';
		}
		elseif($page>2)
		{
			$pre_page		= $page-1;
			$searchURL		= 'http://www.matchdoctor.com/searchResult.aspx?summaryView=2&currentView=1&page='.$page;
			$searchReferer	= 'http://www.matchdoctor.com/searchResult.aspx?summaryView=2&currentView=1&page='.$pre_page;
		}

		$searchData	= http_build_query($requireData);


		//echo $searchURL."<br/>";
		//echo $searchReferer."<br/>";
		//exit;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		
		if($page==1)
		{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $searchData);
		}

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		curl_close($ch);

		/*echo "<div style='border:solid 1px #F00'>".$result."</div>"; 
		echo "<br/>-----------------------------<br/>";
		die('<br/>Search result');
		$result=file_get_contents("sample-search-content.html");*/
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//echo $content;
		//die('<br/>Search result');
		//Find NEXT url
		$content = substr($content, strpos($content, '<table id="ctrlSearchResult_ctrlSearchResult3_summary_dlGallery" cellspacing="2" cellpadding="0" border="0" style="background-color:#3381D6;width:100%;">'));
		$content = substr($content, 0, strpos($content, '<div style="margin-top:2px;">'));
		//echo "<div style='border:solid 1px #F00'>".$content."</div>";
		//die('<br/>--------------------------');
		$content = str_replace("&", "&amp;", $content);
		$content = str_replace("<br>", "<br/>", $content);
		//$content = str_replace("/uploades/icons/", "http://www.matchdoctor.com/uploades/icons/", $content); 
		$content = str_replace("&gt;", "", $content); 

		$tidy_config = array(	'clean' => true,
								'output-xhtml' => true,
								'show-body-only' => true,
								'wrap' => 0,
								'indent' => true,
								'indent-spaces' => 4
                     );
		$content = tidy_parse_string($content, $tidy_config, 'UTF8');
		$content->cleanRepair( );
		$content = str_replace("&nbsp;"," ",$content);
		
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		
		//echo $xml; die;
		$parser = new XMLParser($xml);//--
		$parser->Parse();//--
		//echo $parser->GenerateXML(); 
		//die();

		$list = array();
		//tagData tagParents tagChildren tagAttrs tagName tagAttrs['class']
		//echo '<br/>--------------------------';

		if(isset($parser->document->table))
		{
			foreach($parser->document->table as $table)
			{
				//echo $table->tagName."<br/>";
				for($i=0; $i<6; $i++)
				{
					for($j=0; $j<4; $j++)
					{
							$user_text = $table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->a[0]->tagAttrs['href'];
							$user_text = substr($user_text, 0, strpos($user_text, '&indexID='));

							$user_id = str_replace('&userID=','',substr($user_text, strpos($user_text, '&userID=')));
							$user_name =  $table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->a[0]->tagData;
							$user_img = $table->tr[$i]->td[$j]->table[0]->tr[1]->td[0]->div[0]->a[0]->img[0]->tagAttrs['src'];
							$user_loc = str_replace(array('<br/>','&nbsp;'),array(' ',' '),$table->tr[$i]->td[$j]->table[0]->tr[1]->td[1]->tagData);
							//echo $table->tr[$i]->td[$j]->table[0]->tr[1]->td[1]->tagData."<br/>";

							if($user_id!="" && $user_name!="" && $user_img!="")
								array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img, 'loc' => $user_loc));
					}
				}
			}
		}

		/*echo "<pre>";
		print_r($list);
		echo "</pre>";
		die();*/

		$searchData = array();
		return array($list, $searchData);
	}

	static function saveMembers($list, $post)
	{
		foreach($list as $member)
		{
			//$sql = "INSERT INTO matchdoctor_member (username, userid, gender, pic, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['ddSeeking']."', '".$member['pic']."', '".$post['ddCountry']."', '".$member['loc']."', NOW())";
			//funcs::savelog($sql);
			DBConnect::execute_q("INSERT INTO matchdoctor_member (username, userid, gender, pic, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['ddSeeking']."', '".$member['pic']."', '".$post['ddCountry']."', '".$member['loc']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post, $amount)
	{
		$sql = "SELECT username FROM matchdoctor_member WHERE gender='".$post['ddSeeking']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM matchdoctor_member WHERE gender='".$post['ddSeeking']."' AND username NOT IN (SELECT to_username FROM matchdoctor_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		//echo $sql;
		/*self::savelog($sql);
		self::savelog("FINISHED");
		self::savelog("2-".$post[data][User][Gender]."-<br/>");
		self::savelog("3-".$post['data[User][Gender]']."-<br/>");*/

		//$member = DBConnect::assoc_query_1D($sql);
		//file_put_contents("logs/".ID."_post.log",print_r($member,true));

		return DBConnect::assoc_query_1D($sql);
	}

	static function sendMessage($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageReferer .= "?userID=".$userid."&redirect=/searchUserName.aspx?summaryView=2&currentView=3&userName=".$username."&userID=".$userid."&indexID=1&page=1&profileView=0";

		//echo $sendMessageURL; die();
		//$viewstate = self::getViewState($from, $sendMessageReferer);

/*cmdSend:send
ddPredefined:
txtSubject:re: re: Nice to know you
txtMsg:Hi Robby
tagcount:0
helpbox:Insert Italic Text (alt + i)
toUserID:11598791
msgID:39742438
draftID:0
redirect:myMailBox.aspx?mailBox=1&page=1
*/
		$requireData = array(	
							'txtSubject' => $subject,
							'txtMsg' => $message,
							'tagcount' => '0',
							'helpbox' => 'Insert Bold Text (alt + b)',
							'toUserID' => $userid,
							'msgID' => '0',
							'draftID' => '0',
							'redirect' => '/searchUserName.aspx?summaryView=2&currentView=3&userName='.$username.'&userID='.$userid.'&indexID=1&page=1&profileView=0',
							'cmdSend' => 'send',
							'ddPredefined' => ''
							);
		$sendMessagePostData = http_build_query($requireData);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.matchdoctor.com', 'Origin: http://www.matchdoctor.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		file_put_contents("sending/".ID."_".$from."_send_to_".$username.".log", $result);
		//echo $result; die("<br/>Sent Message");//
		curl_close($ch);

		if(strpos($result, "Mail sent to ".$username.".")>-1)
		{
			funcs::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO matchdoctor_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$username." failed");
			//DBConnect::execute_q("DELETE FROM matchdoctor_member WHERE username='".$username."'");
			return false;
		}
	}
	
	static function checkInboxMessage($username, $inboxURL, $from)
	{
		$content = self::checkInboxPage($username, $inboxURL, $from);
		return self::getInboxMessagesFromInboxPage($content);
	}

	static function sleep($time)
	{
		$sleep_time = $time;
		while($sleep_time>=60)
		{
			if($sleep_time!=$time)
			{
				$txt_time = funcs::secondToTextTime($sleep_time);
				funcs::savelog("Still sleeping [".$txt_time." left]");
			}
			sleep(60);
			$sleep_time-=60;

		}
		sleep($sleep_time);
	}

	static function secondToTextTime($seconds)
	{
		$h = (int)($seconds / 3600);
		$m = (int)(($seconds - $h*3600) / 60);
		$s = (int)($seconds - $h*3600 - $m*60);
		return (($h)?(($h<10)?("0".$h):$h):"00").":".(($m)?(($m<10)?("0".$m):$m):"00").":".(($s)?(($s<10)?("0".$s):$s):"00");
	}

	static function checkRunningTime($start_time, $end_time)
	{
		$unx_current_time = strtotime(date('Y-m-d H:i:s'));
		$unx_start_day_time = strtotime(date('Y-m-d').'00:00:00');
		$unx_end_day_time = strtotime(date('Y-m-d').'24:00:00');
		$unx_start_time = $start_time; // strtotime();
		$unx_end_time = $end_time; //strtotime();
		
		
		if($unx_end_time>=$unx_start_time)
		{	//Check if current time is not in start and end time then do sleep time below otherwise follow while loop structure in send-message.php
			//Ex. Sending Time 10:00:00 - 17:00:00 AND Sleeping Time 17:00:01 - 09:59:59
			//Ex. Sending Time 22:00:00 - 04:00:00 AND Sleeping Time 04:00:01 - 21:59:59
			if(!(($unx_start_time<=$unx_current_time) && ($unx_end_time>=$unx_current_time)))
			{
				$sleep_time = ($unx_start_time-$unx_current_time);
				if($sleep_time>0)
				{
					funcs::savelog("Start time is : ".date('Y-m-d H:i:s',$start_time));
					funcs::sleep($sleep_time);
				}
				elseif($unx_end_time<$unx_current_time)
				{
					funcs::savelog("End time at : ".date('Y-m-d H:i:s',$unx_end_time));
					$sleep_time = ($unx_end_day_time-$unx_end_time)+($unx_start_time-$unx_start_day_time);
					funcs::sleep($sleep_time);
					//funcs::savelog("FINISHED");
					//exit;
				}
			}
		}
	}

	static function checkInboxPage($username, $inboxURL, $from)
	{
		funcs::savelog("Receiving inbox page");
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $inboxURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		funcs::savelog("Receiving of inbox page done");
		curl_close($ch);
		return $result;
	}

	static function getInboxMessagesFromInboxPage($content)
	{
		$list = array();
		$content = substr($content, strpos($content, '<div class="text">'));
		$content = trim(substr($content, 0, strrpos($content, '<div class="message-navigation">')));
		$content = str_replace("’", "'", $content);
		$content = str_replace("“", "\"", $content);
		$content = str_replace("´", "\"", $content);

		//Hack for some invalid XML
		$content = str_replace("\"></span>", "\"/></span>", $content);
		$content = str_replace('<span class="bottom-bg"><span>&nbsp;</span></span>', "", $content);
		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt", $xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $message)
			{
				if(isset($message->ul[0]->li[3]))
				{
					array_push($list, array(	"username"=>$message->ul[0]->li[3]->span[0]->a[0]->tagData,
												"subject"=>$message->ul[0]->li[2]->span[0]->a[0]->tagData,
												"url"=>$message->ul[0]->li[2]->span[0]->a[0]->tagAttrs['href']
											)
								);
				}
			}
		}
		return $list;
	}

	static function randomText($message)
	{
		$list = array(
						" your "	=> array(" ur "),
						"I'm "		=> array("Im ","im ", "I am ","i'm "),
						" for "		=> array(" 4 "),
						" to "		=> array(" 2 "),
						"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						" "			=> array("  ","   "),
						" you're "	=> array(" u're "),
						"!"			=> array(".","..","..."),
						" be "		=> array(" b ")
					);
		if(rand(0,1))
		{
			foreach($list as $key => $words)
			{
				if(rand(0,1))
				{
					$message=str_replace($key, $words[rand(0,count($words)-1)], $message);
				}
			}
		}
		return $message;
	}

	static function savelog($msg)
	{
		$time=date("Y-m-d H:i:s");
		$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

		echo "[$time] $msg<br/>\r\n".$scrollScript;
		ob_end_flush();
		ob_flush();
		flush();
		ob_start();

		file_put_contents("logs/".ID."_latest.log","[$time] $msg");
		file_put_contents("logs/".ID.".log","[$time] $msg\r\n",FILE_APPEND);

		if(file_exists("logs/".ID."_command.log"))
		{
			$txt_command = file_get_contents("logs/".ID."_command.log");
			if($txt_command == "STOP")
			{
				file_put_contents("logs/".ID."_latest.log","[$time] Force stop");
				file_put_contents("logs/".ID.".log","[$time] Force stop\r\n",FILE_APPEND);
				unlink("logs/".ID."_command.log");
				exit;
			}
		}

		if(file_exists("logs/".ID."_run_count.log"))
		{
			$txt_count = file_get_contents("logs/".ID."_run_count.log");
			if($txt_count != RUN_COUNT)
			{
				exit;
			}
		}

	}

	static function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n", "\n", $cookie);
			$cookie = str_replace("\r", "\n", $cookie);
			$lines = explode("\n", $cookie);
			$result = array();
			foreach($lines as $line)
			{
				if(strpos($line,"www.matchdoctor.com")>-1)
				{
					$contents = explode("\t", $line);
					$result[$contents[5]]=array("value"=>$contents[6],"expired"=>$contents[4]);
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
    }

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	function getCountExistingUser()
	{
		$sql = "SELECT COUNT(id) AS total FROM matchdoctor_member";
		return DBConnect::assoc_query_1D($sql);
	}

}
?>