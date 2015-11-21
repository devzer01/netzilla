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

	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $isExit = true)
	{
		$cookie_path = self::getCookiePath($username);
		$postData = "username=".$username."&password=".$password."&login=Login&islogin=1";

		if(!(self::isLoggedIn($username, $preLoginURL, $preLoginRefererURL, $headerOpt)))
		{
			// count try to login
			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch,CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch,CURLOPT_POST, 1);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
				
				curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				
				curl_close($ch);

				if(strpos($result, "Logout")!==false)//If logged in
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
						funcs::savelog("Couldn't login with profile: ".$username);
						if($isExit)
						{
							funcs::savelog("FINISHED");
							exit;
						}
						else
							return false;
					}
				}

			}//end for count login
		}
		else
			return true;
	}

	static function isLoggedIn($username, $preLoginURL, $preLoginRefererURL, $headerOpt)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
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

		if(strpos($result, "Logout")!==false)//If logged in
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

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		$rp_cookie = str_replace('#HttpOnly_.single123.com	TRUE	/	FALSE	','',$cookie);
		
		$expire = substr($rp_cookie, 0, 10);		
		$diff = (int)$expire-time();
		/*
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}
		*/
		//return false for site single123 only
		return false;
	}
	
	static function getSearchResult($username, $searchURL, $searchReferer, $headerOpt, $searchData, $page, $extraParams)
	{
		//list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $searchURL);
		//echo "<br/>-----------------------------<br/>";
		//echo $viewstate;
		//echo "<br/>-----------------------------<br/>";/**/
		$cookie_path = self::getCookiePath($username);

		$prev = $page-1;

		if($page>1)
		{
			/*http://www.single123.com/search/bs3anbos6569bb8znkfyzjrdp6y7nauq/3/
			http://www.single123.com/search/bs3anbos6569bb8znkfyzjrdp6y7nauq/4/ */
			$searchReferer  = "http://www.single123.com/search/";
			$searchReferer .= $extraParams."/";
			$searchReferer .= $prev."/";

			$searchURL  = "http://www.single123.com/search/";
			$searchURL .= $extraParams."/";
			$searchURL .= $page."/";
		}
		else
		{	
			$requireData = array(
						'type_id' => 'members',
						'gender2' => '1',
						'gender1' => $searchData['targettedGender'],
						'age_from' => '18',
						'age_to' => '30',
						'country' => '',
						'state' => '',
						'city' => '',
						'uszip' => '',
						'dist' => '',
						'online_only' => '0',
						'pictures_only' => '0',
						'display_type' => '0',
						'search_save' => '',
						'submit' => 'Submit',
						'issearch' => '1'
					);
		}

		/*echo "<pre>";
		print_r($requireData);
		echo "</pre>";
		exit;
		*/

		//$searchData	= http_build_query($requireData);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		
		if($page==1)
		{
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);
		}

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
		$content = substr($result, strpos($result, '<a class="active"'));
		$content = substr($content, 0, strpos($content, '<a href'));
		$content = str_replace('<a class="active" href="http://www.single123.com/search/', '', $content);
		$returnParams = str_replace('/'.$page.'/">'.$page.'</a> ', '', $content);

		$list = self::getMembersFromSearchResult($username, $page, utf8_encode($result));
		return array($list, $returnParams);//self::getMembersFromSearchResult($username, $page, utf8_encode($result));//
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//echo $content;
		//die('<br/>Search result');
		//Find NEXT url
		$content = substr($content, strpos($content, '<div class="outter page_search_results">'));
		$content = substr($content, 0, strpos($content, '<div class="footer_wrap">'));
		//echo "<div style='border:solid 1px #F00'>".$content."</div>";//
		//die('<br/>--------------------------');//
		//$content = str_replace("../../../../shared/", "http://www.2busy2date.com/shared/", $content); 
		//$content = str_replace('<span class="Navigatorgray">&nbsp;&#8226;&nbsp;</span>', ' ', $content);
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

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $div)
			{
				for($i=2;$i<=11;$i++)
				{
					//echo $table->tagName."<br/>";
					$user_name = str_replace('http://www.single123.com/','', $div->div[0]->div[$i]->table[0]->tr[0]->td[0]->div[0]->a[0]->tagAttrs['href']);
					$user_id = self::getMemberDetailsFromSite($user_name);
					$user_img = $div->div[0]->div[$i]->table[0]->tr[0]->td[0]->div[0]->a[0]->img[0]->tagAttrs['src'];
					$user_age = str_replace('&nbsp;','',$div->div[0]->div[$i]->table[0]->tr[0]->td[1]->div[0]->dl[0]->dd[0]->tagData);
					$user_loc = $div->div[0]->div[$i]->table[0]->tr[0]->td[1]->div[0]->dl[0]->dd[3]->a[1]->tagData.", ".$div->div[0]->div[$i]->table[0]->tr[0]->td[1]->div[0]->dl[0]->dd[3]->a[0]->tagData;

					if($user_name!="" && $user_id!="")
						array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img, 'age' => $user_age, 'loc' => $user_loc));

					//echo $user_id." : ".$user_name." : ".$user_img."<br/>";
				}
			}
		}

		/*echo "<pre>";
		print_r($list);
		echo "</pre>";
		die();*/

		return $list;
	}

	static function getMemberDetailsFromSite($username)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/".$username);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.single123.com/".$username);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.single123.com', 'Origin: http://www.single123.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch); 
		curl_close($ch);

		$content = substr($result, strpos($result, 'http://www.single123.com/account/friends/add/'));
		$content = substr($content, 0, strpos($content, '/"'));
		$content = str_replace('http://www.single123.com/account/friends/add/','',$content);
		return $content;
	}
	
	/*static function getMembersFromSearchResult($content,$count)
	{ 
		
		//if($count==2)
		//{
			//echo $content;exit;
		//}
		
		$header = substr($content, strpos($content, 'HTTP/'), strpos($content, 'charset=utf-8') - strpos($content, 'HTTP/'));
		$key = substr($header, strpos($header,'location: http://www.single123.com/search/'), strpos($header,'Content-Length: 0')- strpos($header,'location: http://www.single123.com/search/'));
		
		$key = str_replace(array('location: http://www.single123.com/search/','/',' '),'',$key);
		
		$a = strpos($content,'<div class="outter page_search_results">');
		$b = strpos($content, '<div id="footer_wrap">');
		$c = $b - $a;

		$content = substr($content,strpos($content,'<div class="dataitem odd ">'),$b - strpos($content, '<div class="dataitem odd ">'));		
		$content = substr($content,0,strpos($content,'<div class="clear"></div>'));
	
		$content = str_replace(array('&nbsp;', '<table class="plain">', '</table>','<tbody>','</tbody>','<td class="data">','<td>','</td>','<tr>','</tr>'),'',$content);
		$content = str_replace('<br>','<br />',$content);
		$content = str_replace('"><br />', '" /><br />', $content);
		$content = substr($content,0,-9);
	
		
			$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		
		$parser = new XMLParser($xml);
		$parser->Parse();
		
		//$arr_memberInfo = array();

		if(isset($parser->document->div))
		{
			
				
			
			foreach($parser->document->div as $member)
			{
				$member_id = explode('/', $member->div[0]->a[0]->img[0]->tagAttrs['src']);
				if(isset($member_id[9]))
				{
					$user_id = $member_id[9];
				}
				else
				{
					$user_id = "";
				}
				

				self::saveMember($user_id, $member->div[1]->tagChildren[0]->tagChildren[0]->tagData, $member->div[0]->a[0]->img[0]->tagAttrs['src'], $member->div[1]->dl[0]->dd[0]->tagData);
				
			}
			if($count==1)
			{
				return $key;
			}
		}
		else
		{	
			
			//echo "no search result";
			//echo $content;
			
		}

	}*/

	
	static function getIdUpdate($content)
	{
		$content = substr($content, strpos($content,'action="http://www.single123.com/member/'), strpos($content, '/guestbook/">') - strpos($content,'action="http://www.single123.com/member/'));
		$content = str_replace('action="http://www.single123.com/member/', '', $content);
		
		$userid = trim($content);
		
		return $userid;
	}


	static function saveMembers($list,$post)
	{
		if(count($list)>0)
		{
			self::savelog("Saving to database");
			foreach($list as $member)
			{
				//echo "INSERT INTO single123_member (username, gender, age, pic, country, created_datetime) VALUES ('".$member['username']."', '".$post['targettedGender']."','".$member['age']."', '".$member['pic']."', '".$member['loc']."', NOW())"."<br/>";
				DBConnect::execute_q("INSERT INTO single123_member (userid, username, gender, age, pic, country, created_datetime) VALUES ('".$member['userid']."', '".$member['username']."', '".$post['targettedGender']."','".$member['age']."', '".$member['pic']."', '".$member['loc']."', NOW())");
			}
			self::savelog("Saving done");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM single123_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT userid,username FROM single123_member WHERE gender='".$post["targettedGender"]."' and username NOT IN (SELECT to_username FROM single123_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}


	static function sendMessage($from, $toId, $toName, $subject, $message)
	{
		//$toId = "52110";
		//$toName = "jackharris11";
		$cookie_path = self::getCookiePath($from);
		$sendMessagePostData = "subject=".$subject."&body=".$message."&submit=Send&ismessage=1";
		$sendMessageURL = "http://www.single123.com/account/messages/compose/".$toId."/";
		$sendMessageReferer = $sendMessageURL;
		//self::savelog('Send URL '.$sendMessageURL);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		
		file_put_contents("sending/".$from."-".$toName.".txt",$result);
		
		if(strpos($result, "Message has been successfully sent")>-1)
		{
			funcs::savelog("Sent message to ".$toName);
			
			DBConnect::execute_q("INSERT INTO single123_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$toName."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			
			funcs::savelog("Sending message to ".$toName." failed");
			DBConnect::execute_q("DELETE FROM single123_member WHERE username='".$toName."'");
			//DBConnect::execute_q("UPDATE single123_member set member_status ='false' where username='".$to."'");
			return false;
		}
	}


	static function saveMember($id, $name, $pic, $age)
	{
		$sql = "INSERT INTO `bot`.`single123_member` (`id`, `userid`, `username`, `gender`, `targettedGender`, `country`, `pic`, `age`, `created_datetime`, `member_status`) VALUES (NULL, '".$id."', '".$name."', '', '', '', '".$pic."', '".$age."', NOW(), 'true');";
		//echo $sql;echo "<br>";
		mysql_query($sql);
	}

	static function dbGetNoIdMember()
	{
		$sql = "SELECT id, username FROM `bot`.`single123_member` WHERE userid=0 ";
		$query = mysql_query($sql);

		//$arr_result = array();
		while($result=mysql_fetch_array($query))
		{
			$arr_result[] = $result;
		}

		return $arr_result;
	}

	static function updateMember($id, $userid, $name)
	{
		$sql = "UPDATE `bot`.`single123_member` SET userid='".$userid."' WHERE id = '".$id."' AND username = '".$name."'";
		if(mysql_query($sql))
		{
			return true;
		}else
		{
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
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
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
		$content = substr($content,strpos($content,'<div class="text">'));
		$content = trim(substr($content,0,strrpos($content,'<div class="message-navigation">')));
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);

		//Hack for some invalid XML
		$content = str_replace("\"></span>","\"/></span>",$content);
		$content = str_replace('<span class="bottom-bg"><span>&nbsp;</span></span>',"",$content);
		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt",$xml);

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
						//"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						//" "			=> array("  ","   "),
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
					$message=str_replace($key,$words[rand(0,count($words)-1)],$message);
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
				exit;
			}
		}
	}


	static function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n","\n",$cookie);
			$cookie = str_replace("\r","\n",$cookie);
			$lines = explode("\n",$cookie);
			
			if($lines[6]!='')
			{
				return $lines[6];
			}
			else
			{
				return false;
			}
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


	static function countMember()
	{
		$sql = "SELECT COUNT(*) FROM single123_member WHERE userid=0";
		$query = mysql_query($sql);
		$num = mysql_num_rows($query);

		return $num;
	}


	static function getSentUser($username)
	{
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/sent/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/sent/1/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		//curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);
		curl_close($ch);
		$arr_data = array();
		$arr_content = explode(' ',$result);
		foreach($arr_content as $datas)
		{
			if(strstr($datas, 'name="message_id'))
			{
				$messageid = str_replace(array('name="','"'),'',$datas);
				array_push($arr_data, $messageid);
			}
		}

		return $arr_data;
	}

	static function messageRecipient($username)
	{
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/inbox/1/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		//curl_setopt($ch,CURLOPT_POST, 1);
		//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);
		curl_close($ch);
		
		$arr_data = array();
		$arr_content = explode(' ',$result);
		foreach($arr_content as $datas)
		{
			if(strstr($datas, 'name="message_id'))
			{
				$messageid = str_replace(array('name="','"'),'',$datas);
				array_push($arr_data, $messageid);
			}
		}

		return $arr_data;

	}


	static function saveMessageRecipient($username)
	{
		$arr_message = self::messageRecipient($username);
		$countInbox = count($arr_message);

		if($countInbox>=6)
		{
			foreach($arr_message as $message_id)
			{
				$message_id = str_replace(array('message_id[', ']'),'',$message_id);
				$cookie_path = self::getCookiePath($username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/inbox/read/".$message_id."/");
				curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/');
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch,CURLOPT_TIMEOUT,30); 
				//curl_setopt($ch,CURLOPT_POST, 1);
				//curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

				$result = curl_exec($ch);
				curl_close($ch);

				$sender1 = substr($result, strpos($result, '<li>Sender:'), 130);
				$sender = substr($sender1, strpos($sender1, 'Sender: '), strpos($sender1, '</a></li>') - strpos($sender1, 'Sender: '));
				$sender = substr($sender, strpos($sender,'">'));
				$sender = str_replace('">', '', $sender);

				$receive_date = substr($sender1, strpos($sender1, '<li>Sent on'), strpos($sender1, '</li>') - strpos($sender1, '<li>Sent on'));
				$receive_date = str_replace(array('<','li','/','>'),'',$receive_date);
				

				$a = strpos($result, '<h2 class="inner"><a href="http://www.single123.com/account/messages/inbox/read/');
				$subject = substr($result, $a, strpos($result, '</a></h2>') - $a);
				$subject = substr($subject, strpos($subject, '/">'));
				$subject = str_replace(array('"','/','>'),'',$subject);
				$subject = mysql_real_escape_string($subject);

				$b = strpos($result, '<div class="entry">');
				$message = substr($result, $b, strpos($result, '<form method="post" name="message" id="privatemessageform"') - $b);
				$message = str_replace('<div class="entry">','', $message);
				$message = mysql_real_escape_string($message);

				if($subject != '' and $message != '')
				{
					$sql = "INSERT INTO `bot`.`single123_receive_message` (`id`, `sender`, `recipient`, `subject`, `message`, `receivedate`) VALUES (NULL, '".$sender."', '".$username."', '".$subject."', '".$message."', '".$receive_date."')";
					
					if(mysql_query($sql))
					{
						
						self::savelog("INSERT INBOX DATAS TO DATABASE ID : $message_id");
						self::deleteRecipientMsg($username, $message_id);
					}

				}				
				
			}//foreach

		}//if countInbox		
		
	}

	static function deleteRecipientMsg($username, $message_id)
	{
		
		self::savelog("DELETE MESSAGE FROM INBOX MESSAGE ID: $messsage_id");
		$cookie_path = self::getCookiePath($username);
		$sendMessagePostData = "message_id[".$message_id."]=1";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/inbox/delete/1/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/inbox/read/'.$message_id.'/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
	}
	
	static function deleteSentMessage($username, $arrMessage)
	{
		$cookie_path = self::getCookiePath($username);
		$sendMessagePostData = "check_all_messages=on";
		foreach($arrMessage as $message)
		{
			$sendMessagePostData .= "&".$message."=on";
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.single123.com/account/messages/sent/delete/1/");
		curl_setopt($ch, CURLOPT_REFERER, 'http://www.single123.com/account/messages/sent/');
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
	}
}
?>