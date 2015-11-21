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
	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $currentIndex, $total)
	{
		$viewstate = self::getViewState($username,$loginRefererURL);
		$cookie_path = self::getCookiePath($username);
		$postData = array(	"__EVENTTARGET" => "",
							"__EVENTARGUMENT" => "",
							"__VIEWSTATE" => $viewstate,
							"ctl00\$Main\$txt_Username_Or_Email" => $username,
							"ctl00\$Main\$txt_Password" => $password,
							"ctl00\$Main\$chk_Remember_Me" => "on",
							"ctl00\$Main\$btn_Login_Submit" => "Submit"
							);
		$postData = http_build_query($postData);
		$need_login = true;

		// Check cookie file
		if(!file_exists($cookie_path))
		{
			self::savelog("No cookie file for profile '$username'");
			$need_login = true;
		}
		else
		{
			if(self::isCookieValid($username, $loginRefererURL))
			{
				self::savelog("Cookie for profile '$username' is not expired, no login needed.");
				$need_login = false;
			}
			else
			{
				self::savelog("Cookie for profile '$username' is expired, perform login.");
				$need_login = true;
			}
		}

		if($need_login)
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
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				file_put_contents("login.html",$result);
				
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				if(self::isCookieValid($username,$loginRefererURL))
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

	static function getViewState($username,$loginRefererURL)
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
		$result=str_replace("id=\"__VIEWSTATE\" value=\"","",$result);
		$result=trim(substr($result,0,strpos($result,"\" />")));
		return $result;
	}

	static function isCookieValid($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.casualkiss.com/Default.aspx");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.casualkiss.com/Default.aspx");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		if(strpos($result,"/misc/SignOut.aspx")>-1)
			return true;
		else
			return false;
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

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page)
	{
		$cookie_path = self::getCookiePath($username);

		$postData = array(
							"Search" => 1,
							"Gender_Seeking" => $searchData['gender'],
							"Order" => 4,
							"Age_Max"=>$searchData['age_to'],
							"Age_Min"=>$searchData['age_from'],
							"FromSearch" => 1,
							);
		$postData = http_build_query($postData);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL."?".$searchData);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		
		//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		//curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		file_put_contents("search/".$username."-".$page.".html",$result);
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//Find NEXT url
		$nav = substr($content,strpos($content,'<a id="ctl00_Main_Pager_link_Next"'));
		$nav = substr($nav,0,strpos($nav,'<br />'));
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$nav."</members>";
		$parser = new XMLParser($xml);
		$parser->Parse();
		//$searchData = str_replace("/Search.aspx?","",$parser->document->a[0]->tagAttrs['href']);

		$list = array();
		$content = substr($content,strpos($content,'<div class="RepeatPersonals">'));
		$content = substr($content,0,strpos($content,'<a id="ctl00_Main_Pager_link_Next"'));
		$content = substr($content,0,strrpos($content,'<br />'));
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);
		$content = str_replace("src=\"","src=\"http://www.casualkiss.com",$content);
		$content = str_replace("&","&amp;",$content);
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		file_put_contents("xml/".$username."-".$page.".xml",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div[0]->table[0]->tr as $row)
			{
				foreach($row->td as $box)
				{
					if(isset($box->table[0]))
					{
						$href=str_replace("/members/","",$box->table[0]->tr[0]->td[1]->div[0]->a[0]->tagAttrs['href']);
						array_push($list, array(	"username"=>$box->table[0]->tr[0]->td[1]->div[0]->a[0]->tagData,
													"userid"=>substr($href,0,strpos($href,"?")),
													"pic"=>$box->table[0]->tr[0]->td[0]->a[0]->img[0]->tagAttrs['src']
												)
									);
					}
				}
			}
		}
		return $list;
	}

	static function saveMembers($list,$post)
	{
		foreach($list as $member)
		{
			DBConnect::execute_q("INSERT INTO casualkiss_member (username, userid, gender, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['gender']."', '".$member['pic']."', NOW())");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM casualkiss_member WHERE gender='".$post['gender']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM casualkiss_member WHERE gender='".$post['gender']."' AND username NOT IN (SELECT to_username FROM casualkiss_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY DATE(created_datetime) DESC, id ASC LIMIT 1";

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function sendMessage($from, $toName, $toId, $subject, $message, $sendMessageURL, $sendMessageRefererURL)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageRefererURL = $sendMessageRefererURL.$toId;
		$viewstate = self::getViewState($from,$sendMessageRefererURL);
		$sendMessagePostData = array(	"__EVENTTARGET" => "",
										"__EVENTARGUMENT" => "",
										"__VIEWSTATE" => $viewstate,
										"ctl00\$Main\$txt_Content\$txt_Content" => $message,
										"ctl00\$Main\$btn_submit" => "Submit"
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL."?Member_ID=".$toId);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageRefererURL);
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

		file_put_contents("sending/".$from."-".$toName.".html",$result);

		if(strpos($result, "Your Message has been sent.")>-1)
		{
			funcs::savelog("Sent message to ".$toName);
			DBConnect::execute_q("INSERT INTO casualkiss_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$toName."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$toName." failed");
			DBConnect::execute_q("DELETE FROM casualkiss_member WHERE username='".$toName."'");
			return false;
		}
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

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
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
				funcs::savelog("Still sleeping");
			}
			sleep(60);
			$sleep_time-=60;

		}
		sleep($sleep_time);
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
			$cookie = str_replace("\r\n","\n",$cookie);
			$cookie = str_replace("\r","\n",$cookie);
			$lines = explode("\n",$cookie);
			$result = array();
			foreach($lines as $line)
			{
				if(strpos($line,".casualkiss.com")>-1)
				{
					$contents = explode("\t",$line);
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