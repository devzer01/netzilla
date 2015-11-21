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
		$cookie_path = self::getCookiePath($username);
		$postData = "uname=".$username."&pass=".$password."&op=login";

		if(!(self::isLoggedIn($username)))
		{
			// count try to login
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
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch,CURLOPT_POST, 1);
				curl_setopt($ch,CURLOPT_POSTFIELDS, $postData);
				
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				
				curl_close($ch);

				$cookie = self::parse_curl_cookie($cookie_path);
				
				if(strpos($result, "LOGOUT")!==false)//If logged in
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $count_login times.");
					//echo "<div style='border:solid 1px #F00'>".$result."</div>";
					file_put_contents("logging/".ID."_".$username.".log", $result);
					
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

			}//end for count login
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
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, "http://www.flirtbox.co.uk/");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.flirtbox.co.uk/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		curl_close($ch);

		if(strpos($indexpage, "LOGOUT")!==false)//If logged in
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

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		$diff = (int)$cookie['user']['expired']-time();
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $post, $page, $searchResultsPerPage)
	{
		$cookie_path = self::getCookiePath($username);
		$post['resultpage'] = $page;
		$searchData = http_build_query($post);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL."?".$searchData);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 
		
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);
		file_put_contents("search/".$username."-".$page.".html",$result);
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$content = substr($content,strpos($content,'<div class="holder with-popup">'));
		$content = substr($content,0,strrpos($content,'<span class="bottom-bg">'));
		$content = substr($content,0,strrpos($content,'</div>'));
		$content = substr($content,0,strrpos($content,'</div>'));
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);
		$content = str_replace("&amp;","&",$content);
		$content = str_replace("&amp","&",$content);
		$content = str_replace("&","&amp;",$content);
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		file_put_contents("xml/".$username."-".$page.".xml",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $member)
			{
				if(isset($member->div[0]->div[0]->a[0]->tagData))
				{
					array_push($list, array(	"username"=>$member->div[0]->div[0]->a[0]->tagData,
												"pic"=>isset($member->div[0]->a[0])?str_replace("userpics/small/","userpics/profile/",$member->div[0]->a[0]->img[0]->tagAttrs['src']):"",
												"age"=>$member->div[1]->div[0]->span[0]->tagData
											)
								);
				}
			}
		}
		return $list;
	}

	static function saveMembers($list,$post)
	{
		foreach($list as $member)
		{
			DBConnect::execute_q("INSERT INTO flirtbox_member (username, gender, targettedGender, country, pic, age, created_datetime) VALUES ('".$member['username']."','".$post['gender']."','".$post['targettedGender']."','".$post['country']."','".$member['pic']."','".$member['age']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM flirtbox_member WHERE gender='".$post['gender']."' AND targettedGender='".$post['targettedGender']."' AND country='".$post['country']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		if(MSG_INTERVAL==1)
		{
			$sql = "SELECT username FROM flirtbox_member WHERE gender='".$post['gender']."' AND targettedGender='".$post['targettedGender']."' AND username NOT IN (SELECT to_username FROM flirtbox_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY DATE(created_datetime) DESC, id ASC LIMIT 1"; //AND country='".$post['country']."' 
		}
		elseif(MSG_INTERVAL==2)
		{
			$sql = "SELECT username FROM flirtbox_member WHERE gender='".$post['gender']."' AND targettedGender='".$post['targettedGender']."' AND username IN (SELECT to_username FROM flirtbox_sent_messages WHERE msg_interval='1') AND username NOT IN (SELECT to_username FROM flirtbox_sent_messages WHERE msg_interval='2') AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY DATE(created_datetime) DESC, id ASC LIMIT 1"; // AND country='".$post['country']."'
		}

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function updateMember($username)
	{
		$sql = "update user_profiles set status='false' where username='".$username."' and site_id = 10";
		DBConnect::execute_q($sql);
	}

	static function sendMessage($from, $to, $subject, $message,$sendMessageURL)
	{
		$cookie_path = self::getCookiePath($from);

		$sendMessagePostData = array(	
										"to_user" => $to,
										"subject" => $subject,
										"message" => $message,
										"image" => "icon2.gif",
										"smile" => "on",
										"msg_id" => "",
										"submit" => "submit"
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$sendMessageReferer = "http://www.flirtbox.co.uk/reply.php?send=1&uname=".$to;

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		file_put_contents("sending/".$from."-".$to.".html",$result);
		if(strpos($result, "Message has been sent!")>-1)
		{
			funcs::savelog("Sent message to ".$to);
			DBConnect::execute_q("INSERT INTO flirtbox_sent_messages (to_username,from_username,subject,message,sent_datetime, msg_interval) VALUES ('".$to."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW(),'".MSG_INTERVAL."')");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$to." failed");
			DBConnect::execute_q("DELETE FROM flirtbox_member WHERE username='".$to."'");
			//DBConnect::execute_q("UPDATE flirtbox_member set member_status ='false' where username='".$to."'");
			return false;
		}
	}

	static function deleteMessageFromInbox($username, $inboxURL, $inboxRefererURL)
	{
		$messagesData = self::checkInboxPage($username, $inboxURL, $inboxRefererURL);

		$msgIds = array();

		if(count($messagesData)>0)
		{
			$deleteData = array("delete_messages" => "delete_messages", "total_messages" => "1000");
			$i = 1;
			foreach($messagesData as $key => $val)
			{
				if($key>5)
				{
					$deleteData['msg_id'][$i] = $val['msgid'];
					$i++;
				}
			}

			/*echo "<pre>";
			print_r($deleteData);
			echo "</pre>";
			die();*/

			$ch = curl_init();
		
			curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
			curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
			curl_setopt($ch, CURLOPT_URL, $inboxURL);
			curl_setopt($ch, CURLOPT_REFERER, $inboxRefererURL);
			curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $deleteData);

			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$result = curl_exec($ch);
			//echo "<div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');
			funcs::savelog("Deleted message from '".$username."' inbox");
			curl_close($ch);
		}
		else
		{
			funcs::savelog("There is no message in '".$username."' inbox");
		}
		//exit;
	}
	
	static function checkInboxMessage($username, $inboxURL, $from)
	{
		$content = self::checkInboxPage($username, $inboxURL, $from);
		return self::getInboxMessagesFromInboxPage($content);
	}

	static function checkInboxPage($username, $inboxURL, $inboxRefererURL)
	{
		funcs::savelog("Receiving inbox page");
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $inboxRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.flirtbox.co.uk', 'Origin: http://www.flirtbox.co.uk'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		echo "<div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Log in result');
		funcs::savelog("Receiving of inbox page done");
		curl_close($ch);
		/*$result=file_get_contents("sample-inbox-content.html");*/
		return self::getInboxMessagesFromInboxPage($result);
	}

	static function getInboxMessagesFromInboxPage($content)
	{
		$list = array();
		$content = substr($content,strpos($content,'<div class="text">')); //<form name="prvmsg" method="post" action="/reply.php">
		$content = trim(substr($content,0,strrpos($content,'<div class="message-navigation">'))); //</form>
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

		$xml="<?xml version='1.0' standalone='yes' ?>".$content;
		//file_put_contents("xml-".$username."-".$page.".txt",$xml);
		//tagData tagParents tagChildren tagAttrs tagName tagAttrs['class']
		//echo $xml;

		$parser = new XMLParser($xml);
		$parser->Parse();

		//echo $parser->document->input->tagChildren; 

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $message)
			{
				if(isset($message->ul[0]->li[0]))
				{
					array_push($list, array(	"msgid"=>$message->ul[0]->li[0]->span[0]->input[0]->tagAttrs['value']/*,
												"username"=>$message->ul[0]->li[3]->span[0]->a[0]->tagData,
												"subject"=>$message->ul[0]->li[2]->span[0]->a[0]->tagData,
												"url"=>$message->ul[0]->li[2]->span[0]->a[0]->tagAttrs['href']*/
											)
								);
				}
			}
		}
		return $list;
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
				if(strpos($line,"flirtbox.co.uk")>-1)
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