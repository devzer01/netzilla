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

	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $currentIndex, $total)
	{
		if(!(self::isLoggedIn($username, $preLoginURL, $preLoginRefererURL, $headerOpt)))
		{
			//list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $loginRefererURL);
			$cookie_path = self::getCookiePath($username);
			$requireData = array(	
									"inputname" => $username,
									"inputpassword" => $password,
									"remember" => "3",
									"action" => "Log in",
									"lost_pass" => ""
								);

			$requireData = http_build_query($requireData);

			for($count_login = 1; $count_login <= 6; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
				
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);

				//echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');

				////file_put_contents("logging/".ID."_".$username.".log", $result);
					
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				//echo $_SERVER["REQUEST_URI"];
				
				if(strpos($result, "Log out")!==false)//If logged in
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					
					if($count_login==6)
					{
						self::savelog("User ".$username." tried to login ".$count_login." times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".SITE_ID." LIMIT 1";	
						DBConnect::execute_q($sql);
						if($currentIndex==$total)
						{
							self::savelog("FINISHED");
							exit;
						}
						else
							return false;
					}
					else
					{
						if($count_login==3)
							$sleep_time = 600; // 10 mins
						else
							$sleep_time = 120; // 2 mins

						self::savelog("Sleep after log in failed for ". self::secondToTextTime($sleep_time));
						self::sleep($sleep_time);
					}
				}
			}
		}
		else
			return true;
	}

	static function isLoggedIn($username, $preLoginURL, $preLoginRefererURL, $headerOpt)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $preLoginURL);
		curl_setopt($ch, CURLOPT_REFERER, $preLoginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);

		//echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>";
		curl_close($ch);

		if(strpos($result, "Log out")!==false)//If logged in
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

	static function memberLogOut($username, $logoutURL, $logoutRefererURL, $headerOpt)
	{
		self::savelog("Signed out with profile: ".$username);
		//list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $logoutRefererURL);
		$cookie_path = self::getCookiePath($username);
		//$requireData = array();
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $logoutURL);
		curl_setopt($ch, CURLOPT_REFERER, $logoutRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
		
		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		//echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Signed out result');//
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

	static function getViewStateAndEventValidation($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		
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

	static function getSearchResult($username, $searchURL, $searchReferer, $headerOpt, $searchData, $page, $extraParams)
	{
		//list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $searchURL);
		//echo "<br/>-----------------------------<br/>";
		//echo $viewstate;
		//echo "<br/>-----------------------------<br/>";/**/
		$cookie_path = self::getCookiePath($username);
		echo "<br/>page ".$page."<br/>";

		$prev = ($page - 2) * 24;
		$curr = ($page - 1) * 24;

		if($page>1)
		{
			/*http://www.2busy2date.com/pages/search/search_advanced_test.php?count=35&row_found=45099&start=24&sex=12&rpp=8&sort=age&action=SEARCH
			http://www.2busy2date.com/pages/search/search_advanced_test.php?count=35&row_found=45099&start=48&sex=12&rpp=8&sort=age&action=SEARCH*/
			$searchReferer  = "http://www.2busy2date.com/pages/search/search_advanced_test.php";
			$searchReferer .= "?".$extraParams;
			$searchReferer .= "&start=".$prev;
			$searchReferer .= "&sex=".$searchData['Seeking_Gender'];
			$searchReferer .= "&rpp=8";
			$searchReferer .= "&sort=age";
			$searchReferer .= "&action=SEARCH";

			$searchURL  = "http://www.2busy2date.com/pages/search/search_advanced_test.php";
			$searchURL .= "?".$extraParams;
			$searchURL .= "&start=".$curr;
			$searchURL .= "&sex=".$searchData['Seeking_Gender'];
			$searchURL .= "&rpp=8";
			$searchURL .= "&sort=age";
			$searchURL .= "&action=SEARCH";
		}
		elseif($page=="2")
		{
			/*http://www.2busy2date.com/pages/search/search_advanced_test.php?sex=12&rpp=8&sort=age&action=SEARCH
			http://www.2busy2date.com/pages/search/search_advanced_test.php?count=35&row_found=45099&start=24&sex=12&rpp=8&sort=age&action=SEARCH*/
			$searchReferer  = "http://www.2busy2date.com/pages/search/search_advanced_test.php";
			$searchReferer .= "?sex=".$searchData['Seeking_Gender'];
			$searchReferer .= "&rpp=8";
			$searchReferer .= "&sort=age";
			$searchReferer .= "&action=SEARCH";

			$searchURL  = "http://www.2busy2date.com/pages/search/search_advanced_test.php";
			$searchURL .= "?".$extraParams;
			$searchURL .= "&start=".$curr;
			$searchURL .= "&sex=12";
			$searchURL .= "&rpp=8";
			$searchURL .= "&sort=age";
			$searchURL .= "&action=SEARCH";
		}
		else
		{	/*http://www.2busy2date.com/pages/search/search.php
			http://www.2busy2date.com/pages/search/search.php?old_search_name=&search_name=&sex=12&age_1=18&age_2=99&all_looking%5B%5D=&height=124&height_2=208&fromweight=40&toweight=250&all_eye%5B%5D=0&all_hair%5B%5D=0&all_body%5B%5D=0&all_ethnicity%5B%5D=0&all_religion%5B%5D=0&all_language%5B%5D=0&all_status%5B%5D=0&all_education%5B%5D=0&all_horoscope%5B%5D=0&rpp=8&sort=age&action=SEARCH*/

			$searchReferer = "http://www.2busy2date.com/pages/search/search.php";

			$searchURL  = "http://www.2busy2date.com/pages/search/search.php";
			$searchURL .= "?old_search_name=";
			$searchURL .= "&search_name=";
			$searchURL .= "&sex=12";
			$searchURL .= "&age_1=18";
			$searchURL .= "&age_2=99";
			$searchURL .= "&all_looking[]=";
			$searchURL .= "&height=124";
			$searchURL .= "&height_2=208";
			$searchURL .= "&fromweight=40";
			$searchURL .= "&toweight=250";
			$searchURL .= "&all_eye[]=0";
			$searchURL .= "&all_hair[]=0";
			$searchURL .= "&all_body[]=0";
			$searchURL .= "&all_ethnicity[]=0";
			$searchURL .= "&all_religion[]=0";
			$searchURL .= "&all_language[]=0";
			$searchURL .= "&all_status[]=0";
			$searchURL .= "&all_education[]=0";
			$searchURL .= "&all_horoscope[]=0";
			$searchURL .= "&rpp=8";
			$searchURL .= "&sort=age";
			$searchURL .= "&action=SEARCH";
		}

		/*echo "<pre>";
		print_r($requireData);
		echo "</pre>";
		exit;
		*/

		//$searchData	= http_build_query($requireData);


		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		
		//curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		curl_close($ch);

		/*echo "<div style='border:solid 1px #F00'>".$result."</div>"; //
		echo "<br/>-----------------------------<br/>";//
		$viewstate=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$viewstate=str_replace("id=\"__VIEWSTATE\" value=\"","",$viewstate);
		$viewstate=trim(substr($viewstate,0,strpos($viewstate,"\" />")));

		echo "<br/>-----------------------------<br/>";
		echo $viewstate;
		echo "<br/>-----------------------------<br/>";*/

		/*die('<br/>Search result');
		$result = file_get_contents("sample-search-content.html");*/
		$content = substr($result, strpos($result, '?count='));
		$content = substr($content, 0, strpos($content, '&amp;start='));
		$content = str_replace('?', '', $content);
		$returnParams = str_replace('&amp;', '&', $content);

		$list = self::getMembersFromSearchResult($username, $page, utf8_encode($result));
		return array($list, $returnParams);//self::getMembersFromSearchResult($username, $page, utf8_encode($result));//
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//echo $content;
		//die('<br/>Search result');
		//Find NEXT url
		$content = substr($content, strpos($content, '<table width="450"'));
		$content = substr($content, 0, strpos($content, '<table cellpadding="0"'));
		//echo "<div style='border:solid 1px #F00'>".$content."</div>";
		//die('<br/>--------------------------');
		$content = str_replace("../../../../shared/", "http://www.2busy2date.com/shared/", $content); 
		$content = str_replace('<span class="Navigatorgray">&nbsp;&#8226;&nbsp;</span>', ' ', $content);
		$content = str_replace("&", "&amp;", $content);
		$content = str_replace("<br>", "<br/>", $content);
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
				$user_text = $table->tr[0]->td[0]->a[0]->tagAttrs['onclick'];
				$user_id = substr($user_text, strpos($user_text, 'MID='));
				$user_id = substr($user_id, 0, strpos($user_id, '&amp;headline'));
				$user_id = str_replace('MID=', '', $user_id);
				$user_name = substr($user_text, strpos($user_text, 'name='));
				$user_name = substr($user_name, 0, strpos($user_name, '&amp;age='));
				$user_name = str_replace('name=', '', $user_name);
				
				$user_img = $table->tr[0]->td[0]->a[0]->img[0]->tagAttrs['src'];
				if($user_img=="../../images/no_pic.jpg")
					$user_img = "";

				$user_loc = str_replace('&nbsp;&nbsp;&nbsp;&nbsp;','',$table->tr[2]->td[0]->tagData);
				//echo $user_loc."<br/>";

				if($user_name!="" && $user_id!="")
					array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img, 'loc' => $user_loc));

				//echo $user_id." : ".$user_name." : ".$user_img."<br/>";
			}
		}

		/*echo "<pre>";
		print_r($list);
		echo "</pre>";
		die();*/

		return $list;
	}

	static function saveMembers($list, $post)
	{
		if($post['Seeking_Gender']=="12")
			$Seeking_Gender = "Male";
		elseif($post['Seeking_Gender']=="21")
			$Seeking_Gender = "Female";

		foreach($list as $member)
		{
			//$sql = "INSERT INTO 2busy2date_member (username, userid, gender, pic, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$Seeking_Gender."', '".$member['pic']."', '', '".$member['loc']."', NOW())";
			//funcs::savelog($sql);
			DBConnect::execute_q("INSERT INTO 2busy2date_member (username, userid, gender, pic, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$Seeking_Gender."', '".$member['pic']."', '', '".$member['loc']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post, $amount)
	{
		$sql = "SELECT username FROM 2busy2date_member WHERE gender='".$post['Seeking_Gender']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM 2busy2date_member WHERE gender='".$post['Seeking_Gender']."' AND username NOT IN (SELECT to_username FROM 2busy2date_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function sendMessage($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer, $headerOpt)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageURL		.= "?MID=".$userid."&name=".$username."&mail_id=";
		$sendMessageReferer .= "?MID=".$userid."&pic=1&name=".$username;
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($from, $sendMessageReferer);
		$requireData = array(	
								"subject" => $subject,
								"message" => $message,
								"action" => "OK Send Email"
							);
		//$requireData = http_build_query($requireData);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30);
		
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $requireData);

		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);
		file_put_contents("sending/".ID."_".$from."_send_to_".$username.".log", $result);
		//echo $result; die("<br/>Sending Message");//
		curl_close($ch);

		if(strpos($result, "Email has been sent")>-1)
		{
			funcs::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO 2busy2date_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$username." failed");
			DBConnect::execute_q("DELETE FROM 2busy2date_member WHERE username='".$username."'");
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
				if(strpos($line,"www.4ppl.com")>-1)
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
		$sql = "SELECT COUNT(id) AS total FROM 2busy2date_member";
		return DBConnect::assoc_query_1D($sql);
	}

}
?>