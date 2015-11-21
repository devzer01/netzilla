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
		$cookie_path = self::getCookiePath($username);
		$postData = array(	"user_name" => $username,
							"password" => $password,
							"rememberMe" => "on"
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
			// count try to login
			for($i=1; $i<=3; $i++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 					
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLINFO_HEADER_OUT, true);

				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				//print_r(curl_getinfo($ch));
				//file_put_contents("login.html",$result);
				curl_close($ch);

				if(self::isCookieValid($username,$loginRefererURL))
				{
					self::savelog("Logged in with profile: ".$username);
					return true;
				}
				else
				{
					self::savelog("Log in failed with profile: ".$username);
					self::savelog("Log in failed $i times.");
					if($i==3)
					{
						self::savelog("User ".$username." tried to login 3 times. This username would be deleted.");
						$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".SITE_ID." LIMIT 1";
						DBConnect::execute_q($sql);
						funcs::savelog("Couldn't login.");
						funcs::savelog("FINISHED");

						exit;
					}
				}
			}//end for count login
		}
		else
			return true;
	}

	static function isCookieValid($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL."/myhome.htm");
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		if(strpos($result,"logout.htm")>-1)
			return true;
		else
			return false;
	}

	static function getSearchGUID($username, $URL, $searchReferer)
	{
		$cookie_path = self::getCookiePath($username);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $URL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);

		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		$result=substr($result,strpos($result,"Search_Guid=")+12);
		$result=substr($result,0,strpos($result,"&"));
		return $result;
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page, $searchResultsPerPage)
	{
		$cookie_path = self::getCookiePath($username);

		if(!defined("SEARCH_GUID"))
		{
			$postData = array(
								"Search_Type" => "QuickSearch",
								"Search_Zip" => "HR1+2BD+",
								"Search_Country_ID" => $searchData['Search_Country_ID'],
								"Search_Age_Lower" => $searchData['Search_Age_Lower'],
								"Search_Age_Upper" => $searchData['Search_Age_Upper'],
								"Search_User_Gender" => $searchData['Search_User_Gender'],
								"Search_Seek_Gender" => $searchData['Search_Seek_Gender'],
								"Search_Distance" => "10000",
								"Pictures_Only" => "False"
							);

			$postData = http_build_query($postData);
			$searchGUID = self::getSearchGUID($username, "http://www.singlesbee.com/SearchRequest.aspx?".$postData, $searchReferer);
			define("SEARCH_GUID", $searchGUID);
		}
		else
		{
			$searchGUID = SEARCH_GUID;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"pageNumber":'.$page.',"shouldTrack":false}');
		curl_setopt($ch, CURLOPT_URL, $searchURL."?content=peek&Search_Guid=".$searchGUID."&action=QuickSearch");
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);

		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('AjaxPro-Method:MoveToPage'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		file_put_contents("search/".$username."-search-".$page.".html",$result);
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$content = substr($content,1,strlen($content)-1);
		$content = substr($content,0,strlen($content)-1);
		$content = str_replace('\r\n',"\n",$content);
		$content = str_replace('\t',"\t",$content);
		$content = str_replace('\"','"',$content);

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

		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		file_put_contents("xml/xml-".$username."-".$page.".xml",$xml);
		//$xml = file_get_contents("xml-".$username."-".$page.".txt");

		$parser = new XMLParser($xml);
		$parser->Parse();
		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $member)
			{
				if(($member->tagAttrs['class'] == "search-result-detail") || ($member->tagAttrs['class'] == "search-result-detail-last"))
				{
					$profile =  array(	"username" => $member->div[1]->div[0]->h2[0]->a[0]->tagData,
										"age" => str_replace("-year-old","",$member->div[1]->div[0]->p[0]->span[0]->tagData),
										"location" => str_replace("in ","",$member->div[1]->div[0]->p[0]->span[1]->tagData),
										"pic" => str_replace("_T","",$member->div[0]->div[0]->input[0]->tagAttrs['src'])
									);
					array_push($list,$profile);
				}
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
				DBConnect::execute_q("INSERT INTO singlesbee_member (username, gender, age, location, country, pic, created_datetime) VALUES ('".$member['username']."', '".$post['Search_Seek_Gender']."','".$member['age']."', '".$member["location"]."', '".$post["Search_Country_ID"]."', '".$member['pic']."', NOW())");
			}
			self::savelog("Saving done");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username, userid FROM singlesbee_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username FROM singlesbee_member WHERE username NOT IN (SELECT to_username FROM singlesbee_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) AND gender='".$post['Search_Seek_Gender']."' AND age>='".$post['Search_Age_Lower']."' AND age<='".$post['Search_Age_Upper']."' ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}

	static function getViewState($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		
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

	static function sendMessage($from, $toName, $subject, $message, $sendMessageURL, $sendMessageRefererURL)
	{
		$sendMessageRefererURL = $sendMessageRefererURL.$toName;
		$viewstate = self::getViewState($from,$sendMessageRefererURL);
		$sendMessagePostData = array(	
										"__EVENTTARGET" => "",
										"__EVENTARGUMENT" => "",
										"__VIEWSTATE" => $viewstate,
										"_ctl1:to_box" => $toName,
										"_ctl1:subject_box" => $subject,
										"_ctl1:copy_sent" => "on",
										"_ctl1:message_text" => $message,
										"_ctl1:sendAtBottom.x" => "24",
										"_ctl1:sendAtBottom.y" => "11"
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$cookie_path = self::getCookiePath($from);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL.$toName);
		curl_setopt($ch, CURLOPT_REFERER, $sendMessageRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sendMessagePostData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		file_put_contents("sending/".$from."-".$toName.".txt",$result);
		
		if(strpos($result, "Your message has been sent")>-1)
		{
			funcs::savelog("Sent message to ".$toName);
			
			DBConnect::execute_q("INSERT INTO singlesbee_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$toName."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$toName." failed");
			DBConnect::execute_q("DELETE FROM singlesbee_member WHERE username='".$toName."'");
			return false;
		}
	}

	static function saveMember($id, $name, $pic, $age)
	{
		$sql = "INSERT INTO singlesbee_member (`id`, `userid`, `username`, `gender`, `targettedGender`, `country`, `pic`, `age`, `created_datetime`, `member_status`) VALUES (NULL, '".$id."', '".$name."', '', '', '', '".$pic."', '".$age."', NOW(), 'true');";
		mysql_query($sql);
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
		$current_time = strtotime(date('Y-m-d H:i:s'));
		$before_start = (strtotime($start_time)-strtotime(date('Y-m-d H:i:s')));
		if($before_start>0)
		{
			funcs::savelog("Start time is : ".$start_time);
			funcs::sleep($before_start);
		}
		elseif(strtotime($end_time)<$current_time)
		{
			funcs::savelog("End time at : ".date('Y-m-d H:i:s',$current_time));
			funcs::savelog("FINISHED");
			exit;
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

	function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}
}
?>