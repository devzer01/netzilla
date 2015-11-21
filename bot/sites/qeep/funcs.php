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
		self::savelog("Random new profile");
		if(count($comman_profiles)>0)
		{
			if($current_profile<count($comman_profiles)-1)
				$current_profile++;
			else
				$current_profile=0;
			self::savelog("Profile index: ".$current_profile);
			return $current_profile;
		}
		else
		{
			self::savelog("All profiles are unable to log in");
			self::savelog("FINISHED");
			exit;
		}
	}

	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $currentIndex, $total)
	{
		//$viewstate = self::getViewState($username, $loginRefererURL);
		$cookie_path = self::getCookiePath($username);
		$postData = array(
							'username' => $username,
							'password' => $password,
							'submit' => 'Login'
						);
		
		$postData = http_build_query($postData); //urlencode($postData);
		if(!(self::isLoggedIn($username, $preLoginURL, $preLoginRefererURL, $headerOpt)))//if($need_login)
		{
			for($count_login = 1; $count_login <= 6; $count_login++)
			{
				self::savelog("Trying to get new Tor Identity.");
				if(self::tor_new_identity(PROXY_IP,PROXY_CONTROL_PORT,'bot'))
					self::savelog("New Tor Identity request completed.");
				else
					self::savelog("New Tor Identity request failed.");

				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
				curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);


				//echo "<br/>2<br/><div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');
				file_put_contents("logging/".ID."_".$username.".log", $result);
					
				curl_close($ch);

				if(strpos($result, "Hello ".$username)!==false)//if($cookie['sid']!="")//
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
		//echo "<br/>1<br/><div style='border:solid 1px #F00'>".$result."</div>";
		curl_close($ch);

		if(strpos($result, "Hello ".$username)!==false) //if($cookie['sid']!="")//
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

	static function memberLogOut($username)
	{
		$cookie_path = self::getCookiePath($username);
		self::savelog("Logging out with profile: ".$username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.qeep.mobi/logout?logout=1");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.qeep.mobi/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		//echo "<div style='border:solid 1px #F00'>".$result."</div>"; die('<br/>Log in result');
		curl_close($ch);

		//START GET INDEX PAGE AFTER LOGGED IN
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.qeep.mobi/profilesearch/search_simple/");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.qeep.mobi/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$index = curl_exec($ch);
		//echo "<div style='border:solid 1px #F00'>".$index."</div>"; die('<br/>After logged in');
		curl_close($ch);
		if(strpos($index, "Login")!==false) //if($cookie['sid']!="")//
		{
			self::savelog("Logged out complete with profile: ".$username);
			return true;
		}
		else
		{
			self::savelog("Can not logging out with profile: ".$username);
			return false;
		}

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
		self::savelog($username.":".$password." can be reuse");
	}

	static function getViewState($username, $loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
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

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page)
	{
		$viewstate = self::getViewState($username, $searchReferer);
		$cookie_path = self::getCookiePath($username);
		$postData = array(	
							"criteria.firstname" => "",
							"criteria.lastname" => "",
							"criteria.username" => "",
							"criteria.gender" => $searchData['gender'],
							"_criteria.profilePhoto" => "on",
							"criteria.ageFrom" => $searchData['age'],
							"criteria.ageTo" => $searchData['age'],
							"criteria.interests" => "",
							"criteria.city" => "",
							"criteria.countryCode" => "-1",
							"_finish" => "Find a qeeper"
							);

		/*echo "<pre>";
		print_r($postData);
		echo "</pre>"; die();*/

		$postData = http_build_query($postData);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		//echo "<div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Search result');
		curl_close($ch);/**/

		//$result=file_get_contents("sample-search-content.html");
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//Find NEXT url
		$content = substr($content, strpos($content, '<table style="width:100%; margin-left:4px; margin-top:5px; padding:0;">'));
		$content = substr($content, 0, strpos($content, '<table style="border:0; border-collapse:collapse; border-spacing:0; width:100%;padding:0;">'));
		//$content = substr($content, 0, strpos($content, '<div class="clear_both">'));
		$content = str_replace("&", "&amp;", $content);
		$content = str_replace("image.do?userId=", "http://qeep.mobi/xmps/image.do?userId=", $content);
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
		//echo $parser->GenerateXML(); //die('<br/>XML');

		$list = array();
		//tagData tagParents tagChildren tagAttrs tagName tagAttrs['class']

		if(isset($parser->document->table))
		{
			foreach($parser->document->table as $table)
			{
				for($i=0; $i<60; $i=$i+2)
				{
					$user_id = str_replace('viewprofile.do?user=','',$table->tr[$i]->td[0]->a[0]->tagAttrs['href']);
					$user_name = trim($table->tr[$i]->td[1]->a[0]->tagData);
					$user_img = "image.do?userId=".$user_id;
					if(($user_id!="") && ($user_name!=""))
					{
						array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img));
					}
					//echo "-".$user_id." : ".$user_name." : ".$user_img."<br/>";
				}
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
		foreach($list as $member)
		{
			//echo "INSERT INTO qeep_member (username, userid, gender, age, country, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['gender']."', '".$post['age']."', '', '".$member['pic']."', NOW())"."<br/>";
			DBConnect::execute_q("INSERT INTO qeep_member (username, userid, gender, age, country, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['gender']."', '".$post['age']."', '', '".$member['pic']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post, $amount)
	{
		$sql = "SELECT username FROM qeep_member WHERE gender='".$post['gender']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM qeep_member WHERE gender='".$post['gender']."' AND username NOT IN (SELECT to_username FROM qeep_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY DATE(created_datetime) DESC, id ASC LIMIT 1";

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function sendMessage($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer, $headerOpt, $sendRequest = true)
	{
		//$userid = "18110621";
		$sendMessageReferer .= "?recipient=".$userid."&continuePath=viewprofile.do";
		//$sendMessageReferer = "http://qeep.mobi/xmps/qmswrite.do?recipient=18110621&continuePath=viewprofile.do";
		//print_r(array($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer, $headerOpt));
		$cookie_path = self::getCookiePath($from);
		
		//$paramsURL = $sendMessageReferer;
		//$paramsRefererURL = "http://qeep.mobi/xmps/viewprofile.do?user=".$userid;
		//self::getSpecialParams($sendMessageURL, $sendMessageReferer, $headerOpt);
		
		//echo $sendMessageURL; die();
		//$viewstate = self::getViewState($from, $sendMessageReferer);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$qmsToken = curl_exec($ch);
		//echo $qmsToken."<br/>--------<br/>"; /**/
		//$qmsToken=file_get_contents("sample-send-content.html");
		$qmsToken=substr($qmsToken,strpos($qmsToken,'name="qmsToken" value="'));
		$qmsToken=str_replace('name="qmsToken" value="','', $qmsToken);
		$qmsToken=trim(substr($qmsToken,0,strpos($qmsToken,'"/>')));
		self::savelog("QMS Token: ".$qmsToken);
		//die("<br/>Special Params");

		$sendMessagePostData = array(
										'text' => $message, //'Hi honey',//
										'_finish' => 'Send',
										//'offset' => '0',
										//'_target1' => 'Send',
										'qmsToken' => $qmsToken
									);

		//echo $sendMessageURL."</br>".$sendMessageReferer."</br>";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendMessagePostData);

		//curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		//echo "<br/>--------<br/>"; 

		//file_put_contents("sending/".ID."_".$from."_send_to_".$username.".log", $result);
		//echo $result; //die("<br/>Sent Message");
		curl_close($ch);

		if(strpos($result, "Your QMS has been sent to ".$username)>-1)
		{
			self::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO qeep_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			self::savelog("Sending message to ".$username." failed");
			DBConnect::execute_q("DELETE FROM qeep_member WHERE username='".$username."'");

			$arr_sec = range(300,600,30);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			self::savelog("Sleep before log in new profile for ". self::secondToTextTime($sleep_time));
			self::sleep($sleep_time);
			return false;
		}
	}

	static function getSpecialParams($paramsURL, $paramsRefererURL, $headerOpt)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://qeep.mobi/xmps/qmswrite.do?recipient=13702358&continuePath=viewprofile.do");//$paramsURL
		curl_setopt($ch, CURLOPT_REFERER, "http://qeep.mobi/xmps/viewprofile.do?user=13702358");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$result = curl_exec($ch);
		echo $result."<br/>--------<br/>"; 

		$result=substr($result,strpos($result,"name=\"qmsToken\""));
		$result=str_replace("name=\"qmsToken\" value=\"","", $result);
		$result=trim(substr($result,0,strpos($result,"\" />")));
		
		echo $result."<br/>--------<br/>"; 
		die("<br/>Special Params");
		return $result;

		curl_close($ch);

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
				$txt_time = self::secondToTextTime($sleep_time);
				self::savelog("Still sleeping [".$txt_time." left]");
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
					self::savelog("Start time is : ".date('Y-m-d H:i:s',$start_time));
					self::sleep($sleep_time);
				}
				elseif($unx_end_time<$unx_current_time)
				{
					self::savelog("End time at : ".date('Y-m-d H:i:s',$unx_end_time));
					$sleep_time = ($unx_end_day_time-$unx_end_time)+($unx_start_time-$unx_start_day_time);
					self::sleep($sleep_time);
					//self::savelog("FINISHED");
					//exit;
				}
			}
		}
	}

	static function checkInboxPage($username, $inboxURL, $from)
	{
		self::savelog("Receiving inbox page");
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
		self::savelog("Receiving of inbox page done");
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
		$sql = "SELECT COUNT(id) AS total FROM qeep_member";
		return DBConnect::assoc_query_1D($sql);
	}

	static function tor_new_identity($tor_ip='127.0.0.1', $control_port='9051', $auth_code='bot')
	{
		$fp = fsockopen($tor_ip, $control_port, $errno, $errstr, 30);
		if (!$fp) return false; //can't connect to the control port
		 
		fputs($fp, "AUTHENTICATE \"$auth_code\"\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; //authentication failed

		//send the request to for new identity
		fputs($fp, "signal NEWNYM\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; //signal failed
		 
		fclose($fp);
		return true;
	}
}
?>