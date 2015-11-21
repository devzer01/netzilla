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
		/*$need_login = true;

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
		}*/

		if(!(self::isLoggedIn($username)))
		{

			list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username,$loginRefererURL);
			$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7";
			$postData = array(	"ctl00_ScriptManagerMaster_HiddenField" => $hiddenField,
								"__EVENTTARGET" => "",
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"__EVENTVALIDATION" => $eventValidation,
								"txtloginUserName" => $username,
								"txtPassword" => $password,
								"imgbtnlogin.x" => "26",
								"imgbtnlogin.y" => "7"
								);

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
				file_put_contents("login.html",$result);
				curl_close($ch);

				if((strpos($result, "LOGOUT")!==false) && (strpos($result, $username." : Hay!!")!==false))//If logged in
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

	static function isLoggedIn($username)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.oceansofpeople.com/Default.aspx");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.oceansofpeople.com/");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.oceansofpeople.com', 'Origin: http://www.oceansofpeople.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$indexpage = curl_exec($ch);
		echo "<br/>-------------------<br/>".$indexpage;

		curl_close($ch);

		if((strpos($indexpage, "LOGOUT")!==false) && (strpos($indexpage, $username." : Hay!!")!==false))//If logged in
			$loggedin = true;
		else
			$loggedin = false;

		return $loggedin;
	}

	static function keepLogIn($username, $password,$loginURL,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);
		if(!(self::isLoggedIn($username)))
		{

			list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username,$loginRefererURL);
			$hiddenField = ";;AjaxControlToolkit, Version=3.0.30930.28736, Culture=neutral, PublicKeyToken=28f01b0e84b6d53e:en-US:b0eefc76-0092-471b-ab62-f3ddc8240d71:e2e86ef9:9ea3f0e2:9e8e87e9:1df13a87:d7738de7";
			$postData = array(	"ctl00_ScriptManagerMaster_HiddenField" => $hiddenField,
								"__EVENTTARGET" => "",
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"__EVENTVALIDATION" => $eventValidation,
								"txtloginUserName" => $username,
								"txtPassword" => $password,
								"imgbtnlogin.x" => "26",
								"imgbtnlogin.y" => "7"
								);

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

				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, "http://www.oceansofpeople.com/MyProfile.aspx");
				curl_setopt($ch, CURLOPT_REFERER, "http://www.oceansofpeople.com/MyProfile.aspx");
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT,30); 					
				//curl_setopt($ch, CURLOPT_HEADER, 1);
				//curl_setopt($ch, CURLINFO_HEADER_OUT, true);

				//curl_setopt($ch, CURLOPT_POST, 1);
				//curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
				curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
				//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				$indexpage = curl_exec($ch);


				//print_r(curl_getinfo($ch));
				echo "<br/>-------------------<br/>".$indexpage;
				file_put_contents("login.html",$indexpage);
				curl_close($ch);

				if((strpos($result, "LOGOUT")!==false) && (strpos($result, $username." : Hay!!")!==false))//If logged in
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
						return false;
					}
				}
			}//end for count login
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

	static function getViewStateAndEventValidationFromContent($username,$loginRefererURL, $result)
	{
		$viewstate=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$viewstate=str_replace("id=\"__VIEWSTATE\" value=\"","",$viewstate);
		$viewstate=trim(substr($viewstate,0,strpos($viewstate,"\" />")));

		$eventValidation=substr($result,strpos($result,"id=\"__EVENTVALIDATION\""));
		$eventValidation=str_replace("id=\"__EVENTVALIDATION\" value=\"","",$eventValidation);
		$eventValidation=trim(substr($eventValidation,0,strpos($eventValidation,"\" />")));

		return array($viewstate, $eventValidation);
	}

	static function isCookieValid($username,$loginRefererURL)
	{
		$cookie_path = self::getCookiePath($username);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $loginRefererURL."/MyProfile.aspx");
		curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		if(strpos($result,"LOGOUT")>-1)
			return true;
		else
			return false;
	}

	static function getSearchResult($username, $searchURL, $searchReferer, $searchData, $page, $searchResultsPerPage)
	{
		$cookie_path = self::getCookiePath($username);
		global $viewstate;
		global $eventValidation;
		
		if($page==1)
		{
			list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username,$searchReferer);
			$postData = array(
								"ScriptManager1" => "UpdatePanel1|imgsearch",
								"__EVENTTARGET" => "",
								"__EVENTARGUMENT" => "",
								"__LASTFOCUS" => "",
								"__VIEWSTATE" => $viewstate,
								"drpmysex" => $searchData['drpmysex'],
								"drplookingsex" => $searchData['drplookingsex'],
								"AgeFrom" => $searchData['AgeFrom'],
								"Ageto" => $searchData['Ageto'],
								"drprelationship" => "All",
								"Ethnicity" => "Do Not Matter",
								"ddl_country" => $searchData['ddl_country'],
								"ddl_province" => "0",
								"City" => "",
								"zip" => "",
								"drpmiles" => "200",
								"Photo" => "rdPhoto3",
								"Profile" => "rdp2",
								"txtMS" => "",
								"__ASYNCPOST" => "true",
								"imgsearch.x" => "41",
								"imgsearch.y" => "7"
							);
			$postData = http_build_query($postData);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_URL, $searchURL);
			curl_setopt($ch, CURLOPT_REFERER, $searchReferer);

			curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,30);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "http://www.oceansofpeople.com/Result.aspx");
			curl_setopt($ch, CURLOPT_REFERER, $searchReferer);

			curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,30);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		else
		{
			$postData = array(
								"__EVENTTARGET" => "Pagination1\$ImgNext",
								"__EVENTARGUMENT" => "",
								"__LASTFOCUS" => "",
								"__VIEWSTATE" => $viewstate,
								"__EVENTVALIDATION" => $eventValidation,
								"txtMS" => "",
								"txtFriends" => "",
								"txtname" => "",
								"txtEmail" => "",
								"txtScode" => "",
								"drpsortby" => " order by p.Lastlogin desc",
								"drpsortby1" => " order by p.Lastlogin desc"
							);
			$postData = http_build_query($postData);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
			curl_setopt($ch, CURLOPT_URL, "http://www.oceansofpeople.com/Result.aspx");
			curl_setopt($ch, CURLOPT_REFERER, "http://www.oceansofpeople.com/Result.aspx");

			curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT,30);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		}
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidationFromContent($username,$searchReferer, $result);

		file_put_contents("search/".$username."-search-".$page.".html",$result);
		return self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$content = substr($content,strpos($content,'<td width="796" align="left" valign="top">')+42);
		$content = substr($content,0,strpos($content,'<!--end left section-->'));
		$content = substr($content,0,strrpos($content,'</td>'));

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
		if(isset($parser->document->table[0]->tr[2]))
		{
			foreach($parser->document->table[0]->tr[2]->td[0]->table[0]->tr as $member)
			{
				if(isset($member->td[0]->table[0]))
				{
					$pic = isset($member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->div[0]->input[0])? str_replace("./UserImages/SmallImage","http://www.oceansofpeople.com/UserImages/BigImage",$member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->div[0]->input[0]->tagAttrs['src']):"";
					$profile =  array(	"username" => $member->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->a[0]->tagData,
										"userid" => str_replace("ViewProfile.aspx?profile=","",$member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[2]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[6]->a[0]->tagAttrs['href']),
										"age" => $member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[1]->table[0]->tr[2]->td[0]->span[0]->tagData,
										"location" => $member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[1]->table[0]->tr[1]->td[0]->span[0]->tagData,
										"pic" => $pic
									);
					array_push($list,$profile);
				}
			}
		}
		if(isset($parser->document->table[0]->tr[6]))
		{
			foreach($parser->document->table[0]->tr[6]->td[0]->table[0]->tr as $member)
			{
				if(isset($member->td[0]->table[0]))
				{
					$pic = isset($member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->div[0]->input[0])? str_replace("./UserImages/SmallImage","http://www.oceansofpeople.com/UserImages/BigImage",$member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->div[0]->input[0]->tagAttrs['src']):"";
					$profile =  array(	"username" => $member->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[0]->a[0]->tagData,
										"userid" => str_replace("ViewProfile.aspx?profile=","",$member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[2]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[6]->a[0]->tagAttrs['href']),
										"age" => $member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[1]->table[0]->tr[2]->td[0]->span[0]->tagData,
										"location" => $member->td[0]->table[0]->tr[2]->td[0]->table[0]->tr[0]->td[0]->table[0]->tr[0]->td[1]->table[0]->tr[1]->td[0]->span[0]->tagData,
										"pic" => $pic
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
				DBConnect::execute_q("INSERT INTO oceansofpeople_member (username, userid, gender, age, location, country, pic, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['drplookingsex']."','".$member['age']."', '".$member["location"]."', '".$post["ddl_country"]."', '".$member['pic']."', NOW())");
			}
			self::savelog("Saving done");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post,$amount)
	{
		$sql = "SELECT username, userid FROM oceansofpeople_member WHERE gender='".$post["ctl00\$cphContent\$dropCountry"]."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username, userid FROM oceansofpeople_member WHERE username NOT IN (SELECT to_username FROM oceansofpeople_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) AND gender='".$post['drplookingsex']."' AND age>='".$post['AgeFrom']."' AND age<='".$post['Ageto']."' ORDER BY id ASC LIMIT 1";
		return DBConnect::assoc_query_1D($sql);
	}

	static function sendMessage($from, $toName, $toId, $subject, $message, $sendMessageURL, $sendMessageRefererURL)
	{
		global $viewstate;
		global $eventValidation;
		$cookie_path = self::getCookiePath($from);

		$sendMessageRefererURL = $sendMessageRefererURL.$toId;
		$sendMessageURL = $sendMessageURL.$toId;
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($from,$sendMessageRefererURL);
		$sendMessagePostData = array(	
										"__EVENTTARGET" => "lnkmessage",
										"__EVENTARGUMENT" => "",
										"__VIEWSTATE" => $viewstate
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);


		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
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

		list($viewstate, $eventValidation) = self::getViewStateAndEventValidationFromContent($from,$sendMessageRefererURL, $result);
		$sendMessagePostData = array(	
										"__EVENTTARGET" => "lnkpost",
										"__EVENTARGUMENT" => "",
										"__VIEWSTATE" => $viewstate,
										"txt_subject" => $subject,
										"txt_message" => $message
									);
		$sendMessagePostData = http_build_query($sendMessagePostData);

		$cookie_path = self::getCookiePath($from);

		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $sendMessageURL);
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
		/*echo "<br/>---------------------------<br/>";
		echo $result;
		echo "<br/>---------------------------<br/>";*/

		file_put_contents("sending/".$from."-".$toName.".txt",$result);
		
		if(strpos($result, "FloatMessageWrapper")>-1)
		{
			funcs::savelog("Sent message to ".$toName);
			
			DBConnect::execute_q("INSERT INTO oceansofpeople_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$toName."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$toName." failed");
			DBConnect::execute_q("DELETE FROM oceansofpeople_member WHERE username='".$toName."'");
			return false;
		}
	}

	static function saveMember($id, $name, $pic, $age)
	{
		$sql = "INSERT INTO oceansofpeople_member (`id`, `userid`, `username`, `gender`, `targettedGender`, `country`, `pic`, `age`, `created_datetime`, `member_status`) VALUES (NULL, '".$id."', '".$name."', '', '', '', '".$pic."', '".$age."', NOW(), 'true');";
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