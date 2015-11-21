<?php
require_once("XMLParser.php");
class funcs
{
	static function getCookiePath($username)
	{
		return dirname($_SERVER['SCRIPT_FILENAME'])."/cookies/".$username.".txt";
	}

	static function memberlogin($username, $password,$loginURL,$loginRefererURL)
	{
		/*
		name:Angelica8
		pass:thtl6a
		op:Log in
		form_build_id:form-71220c9c067bced25f3dfb9c39a30d5d
		form_id:user_login_block
		*/

		$cookie_path = self::getCookiePath($username);
		$need_login = true;

		// Check cookie file
		if(!file_exists($cookie_path))
		{
			self::savelog("No cookie file for profile '$username'");
			$need_login = true;
		}
		else
		{
			if(self::isCookieValid($username,$loginRefererURL))
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
			$form_build_id = self::getHiddenInput("form_build_id",$username,$loginRefererURL,2);
			$postData = array(	"name" => $username,
								"pass" => $password,
								"op" => "Log in",
								"form_build_id" => $form_build_id,
								"form_id" => "user_login_block"
								);
			$postData = http_build_query($postData);

			for($count_login = 1; $count_login <= 3; $count_login++)
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

				curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				file_put_contents("login.html",$result);
				curl_close($ch);

				if(strpos($result, "Log out")===false)
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

	static function getHiddenInput($name,$username,$loginRefererURL,$index)
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

		for($index; $index>1; $index--)
		{
			$result=substr($result,strpos($result,"name=\"".$name."\"")+50);
		}
		$result=substr($result,strpos($result,"name=\"".$name."\""));
		$result=substr($result,strpos($result,"value=\"")+7);
		$result=trim(substr($result,0,strpos($result,"\"")));
		return $result;
	}

	static function isCookieValid($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
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

		if(strpos($result,"Log out")>-1)
			return true;
		else
			return false;
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
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
		$searchData['page'] = $page-1;
		$searchData = http_build_query($searchData);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $searchURL."?".$searchData);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
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
		$content = substr($content,strpos($content,'<div class="view-content">'));
		$content = substr($content,0,strrpos($content,'<div class="art-spost">'))."</div>";
		$content = substr($content,0,strrpos($content,'<br>'));
		$content = str_replace("\r\n","\n",$content);
		$content = str_replace("\r","\n",$content);
		$content = str_replace("</div>\n</div>\n</div>","</div></div></div></div>",$content);

		$search = '/\<!--(.*?)--\>/is';
		$replace = '';
		$content = preg_replace( $search, $replace, $content );
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
		file_put_contents("xml/".$username."-".$page.".xml",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $member)
			{
				$profile =  array(	"username" => trim($member->div[0]->div[0]->span[0]->a[0]->tagData),
									"pic" => isset($member->div[1]->div[0]->div[0]->a)?str_replace(".thumbnail","",$member->div[1]->div[0]->div[0]->a[0]->img[0]->tagAttrs['src']):"",
									"gender" => "",
									"seeking" => "",
									"birthday" => "",
									"country" => "",
									"state" => "",
									"city" => "",
									"lastLogin" => ""
								);

				for($i = 2; $i<10; $i++)
				{
					if(isset($member->div[$i]))
					{
						switch(trim($member->div[$i]->label[0]->tagData))
						{
							case "Gender:":
								$profile['gender'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "Seeking:":
								if(isset($member->div[$i]->span))
									$profile['seeking'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "Date of Birth:":
								$profile['birthday'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "Country:":
								$profile['country'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "State:":
								$profile['state'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "City:":
								$profile['city'] = trim($member->div[$i]->span[0]->tagData);
								break;
							case "Last login:":
								$profile['lastLogin'] = trim($member->div[$i]->span[0]->tagData);
								break;
						}
					}
				}
				array_push($list,$profile);
			}
		}
		return $list;
	}

	static function saveMembers($list,$post)
	{
		if(count($list)>0)
		{
			self::savelog("Saving to database");
			foreach($list as $member)
			{
				$birthday = explode("/",$member['birthday']);
				$birthday = $birthday[2]."-".$birthday[0]."-".$birthday[1];

				list($lastLogin_date, $lastLogin_time) = explode(" - ",$member['lastLogin']);
				$lastLogin_date = explode("/",$lastLogin_date);
				$lastLogin = $lastLogin_date[2]."-".$lastLogin_date[0]."-".$lastLogin_date[1]." ".$lastLogin_time;

				$sql = "INSERT INTO sexycupid_member (username, gender,seeking, pic, birthday, country, state, city, last_login, created_datetime) VALUES ('".$member['username']."', '".$member["gender"]."', '".$member["seeking"]."','".$member['pic']."', '".$birthday."', '".$member["country"]."', '".$member["state"]."', '".$member["city"]."','".$lastLogin."' , NOW())";
				DBConnect::execute_q($sql);
			}
			self::savelog("Saving done");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM sexycupid_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		if($post['Country']!="All")
			$sql_country = "and country='".$post["Country"]."'";

		$sql = "SELECT username FROM sexycupid_member WHERE gender='".$post["Seeking"]."' ".$sql_country." AND username NOT IN (SELECT to_username FROM sexycupid_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}

	static function sendMessage($from, $to, $subject, $message,$sendMessageURL,$sendMessageReferer)
	{
		$cookie_path = self::getCookiePath($from);
		$form_build_id = self::getHiddenInput("form_build_id",$from,$sendMessageReferer,2);
		$form_token = self::getHiddenInput("form_token",$from,$sendMessageReferer,2);
		$sendMessagePostData = array(	"recipients" => $to,
										"title" => $subject,
										"body" => $message,
										"form_build_id" => $form_build_id,
										"form_token" => $form_token,
										"changed" => "",
										"form_id" => "pm_node_form",
										"op" => "Send private message"
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_PROXY, PROXY_IP);
		curl_setopt($ch, CURLOPT_PROXYPORT, PROXY_PORT);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);

		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		file_put_contents("sending/".$from."-".$to.".html",$result);
		$info = curl_getinfo($ch);
		curl_close($ch);

		//if(strpos($result, $subject)>-1)
		if($info['url']!=$sendMessageURL)
		{
			funcs::savelog("Sent message to ".$to);
			DBConnect::execute_q("INSERT INTO sexycupid_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$to."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$to." failed");
			DBConnect::execute_q("DELETE FROM sexycupid_member WHERE username='".$to."'");
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
				if(strpos($line,".sexycupid.org")>-1)
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