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
		$need_login = true;

		// Check cookie file
		/*if(!file_exists($cookie_path))
		{
			self::savelog("No cookie file for profile '$username'");
			$need_login = true;
		}
		else
		{
			if(self::isCookieValid($username))
			{
				self::savelog("Cookie for profile '$username' is not expired, no login needed.");
				$need_login = false;
			}
			else
			{
				self::savelog("Cookie for profile '$username' is expired, perform login.");
				$need_login = true;
			}
		}*/
		if(!(self::isLoggedIn($username)))
		{
			$viewstate = self::getViewState($username,$loginRefererURL);
			$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7";
			$postData = array(	"ctl00_ScriptManagerMaster_HiddenField" => $hiddenField,
								"__EVENTTARGET" => "",
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"ctl00\$ScriptManagerMaster" => "",
								"ctl00\$cphContent\$txtUsername" => $username,
								"ctl00\$cphContent\$txtPassword" => $password,
								"ctl00\$cphContent\$fbLogin\$imgbutton.x" => "30",
								"ctl00\$cphContent\$fbLogin\$imgbutton.y" => "13"
								);
			$postData = http_build_query($postData);

			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				//echo $result;
				/*die('<br/>---------------Result Log in page');*/
				
				curl_close($ch);

				//$cookie = self::parse_curl_cookie($cookie_path);
				if((strpos($result, $username)!==false) && (strpos($result, "Logout")!==false))//If logged in
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
		
		curl_setopt($ch, CURLOPT_URL, "http://www.singlesaroundme.com");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.singlesaroundme.com/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.singlesaroundme.com', 'Origin: http://www.singlesaroundme.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		curl_close($ch);

		if((strpos($indexpage, $username)!==false) && (strpos($indexpage, "Logout")!==false))//If logged in
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

	static function keepLogIn($username, $password,$loginURL,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);
		$need_login = true;

		if(!(self::isLoggedIn($username)))
		{
			$viewstate = self::getViewState($username,$loginRefererURL);
			$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7";
			$postData = array(	"ctl00_ScriptManagerMaster_HiddenField" => $hiddenField,
								"__EVENTTARGET" => "",
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"ctl00\$ScriptManagerMaster" => "",
								"ctl00\$cphContent\$txtUsername" => $username,
								"ctl00\$cphContent\$txtPassword" => $password,
								"ctl00\$cphContent\$fbLogin\$imgbutton.x" => "30",
								"ctl00\$cphContent\$fbLogin\$imgbutton.y" => "13"
								);
			$postData = http_build_query($postData);

			for($count_login = 1; $count_login <= 3; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 
			
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				
				curl_setopt($ch, CURLOPT_HEADER, 1);
				curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$result = curl_exec($ch);
				//echo $result;
				/*die('<br/>---------------Result Log in page');*/
				
				curl_close($ch);

				//$cookie = self::parse_curl_cookie($cookie_path);
				if((strpos($result, $username)!==false) && (strpos($result, "Logout")!==false))//If logged in
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

	static function getViewStateFromContent($page,$content)
	{
		if($page==1)
		{
			$content=substr($content,strpos($content,"id=\"__VIEWSTATE\""));
			$content=str_replace("id=\"__VIEWSTATE\" value=\"","",$content);
			$content=trim(substr($content,0,strpos($content,"\" />")));
		}
		else
		{
			$content=substr($content,strpos($content,"__VIEWSTATE|")+12);
			$content=substr($content,0,strpos($content,"|"));
		}
		return $content;
	}

	static function isCookieValid($username)
	{
		$cookie_path = self::getCookiePath($username);
		$cookie = self::parse_curl_cookie($cookie_path);
		$diff = (int)$cookie['LanguageId']['expired']-time();
		if($diff > (1*60*60))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page, $viewstate)
	{
		$cookie_path = self::getCookiePath($username);
		$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7:af22e781";
		$postData = array(	"ctl00_ScriptManagerMaster_HiddenField" => $hiddenField,
							"__EVENTTARGET" => "",
							"__EVENTARGUMENT" => "",
							"__VIEWSTATE" => $viewstate,
							"ctl00\$ScriptManagerMaster" => "",
							"ctl00\$cphContent\$dropGender" => $searchData["ctl00\$cphContent\$dropGender"],
							"ctl00\$cphContent\$dropInterestedIn" => $searchData["ctl00\$cphContent\$dropInterestedIn"],
							"ctl00\$cphContent\$txtAgeFrom" => $searchData["ctl00\$cphContent\$txtAgeFrom"],
							"ctl00\$cphContent\$txtAgeTo" => $searchData["ctl00\$cphContent\$txtAgeTo"],
							"ctl00\$cphContent\$CascadingDropDownCountry_ClientState" => $searchData["ctl00\$cphContent\$dropCountry"].":::".$searchData["ctl00\$cphContent\$dropCountry"],
							"ctl00\$cphContent\$CascadingDropDownState_ClientState" => ":::",
							"ctl00\$cphContent\$CascadingDropDownCity_ClientState" => ":::",
							"ctl00\$cphContent\$dropCountry" => $searchData["ctl00\$cphContent\$dropCountry"],
							"ctl00\$cphContent\$dropRegion" => "",
							"ctl00\$cphContent\$txtZip" => "",
							"ctl00\$cphContent\$1\$ddFrom" => "1",
							"ctl00\$cphContent\$1\$ddTo" => "37",
							"ctl00\$cphContent\$1\$hidQuestionId" => "1",
							"ctl00\$cphContent\$3\$hidQuestionId" => "3",
							"ctl00\$cphContent\$21\$hidQuestionId" => "21",
							"ctl00\$cphContent\$30\$hidQuestionId" => "30",
							"ctl00\$cphContent\$25\$hidQuestionId" => "25",
							"ctl00\$cphContent\$31\$hidQuestionId" => "31",
							"ctl00\$cphContent\$26\$hidQuestionId" => "26",
							"ctl00\$cphContent\$28\$hidQuestionId" => "28",
							"ctl00\$cphContent\$27\$hidQuestionId" => "27",
							"ctl00\$cphContent\$29\$hidQuestionId" => "29",
							"ctl00\$cphContent\$38\$hidQuestionId" => "38",
							"ctl00\$cphContent\$37\$dropValues" => "",
							"ctl00\$cphContent\$37\$hidQuestionId" => "37",
							"ctl00\$cphContent\$36\$hidQuestionId" => "36",
							"ctl00\$cphContent\$32\$hidQuestionId" => "32",
							"ctl00\$cphContent\$34\$hidQuestionId" => "34",
							"ctl00\$cphContent\$35\$hidQuestionId" => "35",
							"ctl00\$cphContent\$40\$hidQuestionId" => "40",
							"ctl00\$cphContent\$41\$hidQuestionId" => "41",
							"ctl00\$cphContent\$42\$hidQuestionId" => "42",
							"ctl00\$cphContent\$43\$hidQuestionId" => "43",
							"ctl00\$cphContent\$txtSavedSearchName" => "",
							"ctl00\$cphContent\$ddEmailFrequency" => "7",
							"ctl00\$cphContent\$ddEmailFrequency" => "7",
							"ctl00\$cphContent\$btnSearch" => "Search",
							"hiddenInputToUpdateATBuffer_CommonToolkitScripts" => "1",
							);

		if($page > 1)
		{
			$postData['ctl00$ScriptManagerMaster'] = "ctl00\$cphContent\$SearchResults\$UpdatePanelPaginator|ctl00\$cphContent\$SearchResults\$lnkNext";
			$postData['__EVENTTARGET']= "ctl00\$cphContent\$SearchResults\$lnkNext";
			$postData['ctl00$cphContent$CascadingDropDownState_ClientState'] = " :::All";
			$postData['ctl00$cphContent$dropCity']="";
			$postData['__ASYNCPOST'] = "true";
			unset($postData['ctl00$cphContent$btnSearch']);
		}
		$postData = http_build_query($postData);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $searchURL);
		curl_setopt($ch, CURLOPT_REFERER, $searchReferer);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		//file_put_contents($username."-search-".$page.".html",$result);
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//Find NEXT __VIEWSTATE
		$viewstate = self::getViewStateFromContent($page, $content);

		$list = array();
		$content = substr($content,strpos($content,'<table id="ctl00_cphContent_SearchResults_dlUsersGrid"'));
		if($page==1)
		{
			$content = substr($content,0,strpos($content,'<div id="ctl00_cphContent_SearchResults_pnlPaginator">'));
			$content = substr($content,0,strrpos($content,'</div>'));
		}
		else
		{
			$content = substr($content,0,strpos($content,'|'));
		}
		$content = str_replace("’","'",$content);
		$content = str_replace("“","\"",$content);
		$content = str_replace("´","\"",$content);
		$content = str_replace("&","&amp;",$content);
		$content = str_replace("<br>","<br/>",$content);
		$content = str_replace("<td></td>","",$content);
		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		//file_put_contents("xml-".$username."-".$page.".txt",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();

		if(isset($parser->document->table))
		{
			foreach($parser->document->table[0]->tr as $row)
			{
				foreach($row->td as $box)
				{
					//Find profile url
					$pic = $box->div[0]->tagAttrs['style'];
					$pic = str_replace(array("background:url(",") no-repeat"),"",$pic);
					array_push($list, array(	"username" => $box->div[0]->span[0]->a[0]->tagData,
												"pic" => $pic,
												"age" => str_replace("Male","",$box->div[0]->span[0]->span[0]->tagData)
											));
				}
			}
		}
		file_put_contents("viewstates/".$username."-".$page.".txt",$viewstate);
		return array($list,$viewstate);
	}

	static function saveMembers($list,$post)
	{
		if(count($list)>0)
		{
			self::savelog("Saving to database");
			foreach($list as $member)
			{
				DBConnect::execute_q("INSERT INTO singlesaroundme_member (username, gender, age, pic, country, created_datetime) VALUES ('".$member['username']."', '".$post["ctl00\$cphContent\$dropGender"]."','".$member['age']."', '".$member['pic']."', '".$member["ctl00\$cphContent\$dropCountry"]."', NOW())");
			}
			self::savelog("Saving done");
		}
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username FROM singlesaroundme_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username FROM singlesaroundme_member WHERE gender='".$post["ctl00\$cphContent\$dropGender"]."' and country='".$post["ctl00\$cphContent\$dropCountry"]."' AND username NOT IN (SELECT to_username FROM singlesaroundme_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}

	static function checkStatus($sender, $recipients)
	{
		funcs::savelog("Checking status before send message for: ".$sender);

		$cookie_path = self::getCookiePath($sender);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.singlesaroundme.com/SendMessage.aspx?to_user=".$recipients."&src=profile");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.singlesaroundme.com/ShowUser.aspx?uid=".$recipients);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_TIMEOUT,30); 

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		/*echo "<br/>Checking Status Result";
		echo "<br/>-------<br/>";
		echo $result;
		echo "<br/>-------<br/>";*/
		//echo $result;
		//die('<br/>-------<br/>Sending Result');
		curl_close($ch);

		if(strpos($result, "You've exceeded the number of users you can contact per day!")>-1)
		{
			$sql = "UPDATE user_profiles set lastsent = NOW() WHERE username='".$sender."' AND site_id=".SITE_ID." LIMIT 1";
			DBConnect::execute_q($sql);
			funcs::savelog("Error for profile ".$sender.": You've exceeded the number of users you can contact per day!");
			return false;
		}
		else
		{
			funcs::savelog("Profile '".$sender."' can be send message.");
			return true;
		}
	}

	static function sendMessage($sender, $recipients, $subject, $message, $sendMessageURL)
	{
			$cookie_path = self::getCookiePath($sender);
			$sendMessageReferer = $sendMessageURL."?to_user=".$recipients."&src=profile";
			$viewstate = self::getViewState($sender,$sendMessageReferer);
			$sendMessagePostData = array(	"ctl00_ScriptManagerMaster_HiddenField" => ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7",
											"__EVENTTARGET" => "",
											"__EVENTARGUMENT" => "",
											"__VIEWSTATE" => $viewstate,
											"ctl00\$ScriptManagerMaster" => "",
											"ctl00\$cphContent\$txtMessageBody" => $message,
											"ctl00\$cphContent\$btnSend" => "Send Message"
										);
			$sendMessagePostData = http_build_query($sendMessagePostData);

			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $sendMessageURL."?to_user=".$recipients."&src=profile");
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
			/*echo "<br/>Sending Result";
			echo "<br/>-------<br/>";
			echo $result;
			echo "<br/>-------<br/>";*/
			//die('<br/>-------<br/>Sending Result');
			curl_close($ch);
			file_put_contents("sending/".$sender."-".$recipients.".txt",$result);

			if(strpos($result, "Your message has been sent successfully!")>-1)
			{
				funcs::savelog("Sent message to ".$recipients);
				DBConnect::execute_q("INSERT INTO singlesaroundme_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$recipients."','".$sender."','".addslashes($subject)."','".addslashes($message)."',NOW())");
				return true;
			}
			else
			{
				funcs::savelog("Sending message to ".$recipients." failed");
				DBConnect::execute_q("DELETE FROM singlesaroundme_member WHERE username='".$recipients."'");
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
				if(strpos($line,"www.singlesaroundme.com")>-1)
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

}
?>