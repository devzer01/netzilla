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

	static function memberlogin($username, $password, $loginURL, $loginRefererURL, $headerOpt, $currentIndex, $total)
	{
		//if(!(self::isLoggedIn($username)))
		//{
			list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $loginRefererURL);
			$cookie_path = self::getCookiePath($username);
			$requireData = array(	
									"__EVENTTARGET" => '',
									"__EVENTARGUMENT" => '',
									"__VIEWSTATE" => $viewstate,
									"ctl00\$Header1\$LoginLine1\$txtUsername" => $username,
									"ctl00\$Header1\$LoginLine1\$txtPassword" => $password,
									"ctl00\$Header1\$LoginLine1\$wm3_ClientState" => '',
									"ctl00\$Header1\$LoginLine1\$wm1_ClientState" => '',
									"ctl00\$Header1\$LoginLine1\$imgbLogin.x" => '25',
									"ctl00\$Header1\$LoginLine1\$imgbLogin.y" => '4',
									"ctl00\$SearchStrip\$CascadingDropDownCountry_ClientState" => 'United States:::United States',
									"ctl00\$SearchStrip\$dropGender" => '1',
									"ctl00\$SearchStrip\$txtAgeFrom" => '18',
									"ctl00\$SearchStrip\$txtAgeTo" => '80',
									"ctl00\$SearchStrip\$ddCountry" => 'United States',
									"ctl00\$SearchStrip\$txtZip" => '',
									"ctl00\$cphHeader\$SearchBox1\$CascadingDropDownCountry_ClientState" => 'United States:::United States',
									"ctl00\$cphHeader\$SearchBox1\$dropGender" => '1',
									"ctl00\$cphHeader\$SearchBox1\$txtAgeFrom" => '18',
									"ctl00\$cphHeader\$SearchBox1\$txtAgeTo" => '80',
									"ctl00\$cphHeader\$SearchBox1\$ddCountry" => 'United States',
									"ctl00\$cphHeader\$SearchBox1\$txtZip" => '',
									"hiddenInputToUpdateATBuffer_CommonToolkitScripts" => '1'
								);

			//$requireData = http_build_query($requireData);

			for($count_login = 1; $count_login <= 6; $count_login++)
			{
				self::savelog("Logging in with profile: ".$username);
				$ch = curl_init();
				
				curl_setopt($ch, CURLOPT_URL, $loginURL);
				curl_setopt($ch, CURLOPT_REFERER, $loginRefererURL);
				curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.todaysdating.com', 'Origin: http://www.todaysdating.com'));
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
				
				if((strpos($result, $username)!==false) && (strpos($result, "Welcome")!==false))//If logged in
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
		//}
		//else
			//return true;
	}

	static function isLoggedIn($username)
	{
		self::savelog("Checking login status for profile: ".$username);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://www.todaysdating.com/Home.aspx");
		curl_setopt($ch, CURLOPT_REFERER, "http://www.todaysdating.com/default.aspx");
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.todaysdating.com', 'Origin: http://www.todaysdating.com'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
	
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);

		//echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>";
		curl_close($ch);

		if((strpos($result, $username)!==false) && (strpos($result, "Welcome")!==false))//If logged in
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
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $logoutRefererURL);
		$cookie_path = self::getCookiePath($username);
		$requireData = array(	
								"__EVENTTARGET" => '',
								"__EVENTARGUMENT" => '',
								"__VIEWSTATE" => $viewstate,
								"ctl00\$Header1\$LoginLine1\$imgbLogout.x" => '37',
								"ctl00\$Header1\$LoginLine1\$imgbLogout.y" => '9',
								"ctl00\$SearchStrip\$CascadingDropDownCountry_ClientState" => '',
								"ctl00\$SearchStrip\$dropGender" => '1',
								"ctl00\$SearchStrip\$txtAgeFrom" => '18',
								"ctl00\$SearchStrip\$txtAgeTo" => '80',
								"ctl00\$SearchStrip\$ddCountry" => '',
								"ctl00\$SearchStrip\$txtZip" => ''
							);
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $logoutURL);
		curl_setopt($ch, CURLOPT_REFERER, $logoutRefererURL);
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

	static function getLogInUser()
	{
		$sql = "SELECT `username` , `password` FROM `user_profiles` WHERE `status` = 'true' AND site_id =".SITE_ID." ORDER BY id ASC LIMIT 0,3";
		return DBConnect::assoc_query_2D($sql);
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

	static function getSearchResult($username, $searchURL, $searchReferer, $headerOpt, $searchData, $page, $viewstate)
	{
		//list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($username, $searchURL);
		//echo "<br/>-----------------------------<br/>";
		//echo $viewstate;
		//echo "<br/>-----------------------------<br/>";/**/
		$cookie_path = self::getCookiePath($username);

		if($page>1)
			$eventtarget = "ctl00\$cphcontent\$SearchResults\$lnkNext";
		else
			$eventtarget = "";

		$requireData = array(	
								"__EVENTTARGET" => $eventtarget,
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"ctl00\$SearchStrip\$CascadingDropDownCountry_ClientState" => $searchData['Seeking_Country'].":::".$searchData['Seeking_Country'],
								"ctl00\$SearchStrip\$dropGender" => $searchData['Seeking_Gender'],
								"ctl00\$SearchStrip\$txtAgeFrom" => $searchData['Seeking_Age_From'],
								"ctl00\$SearchStrip\$txtAgeTo" => $searchData['Seeking_Age_To'],
								"ctl00\$SearchStrip\$ddCountry" => $searchData['Seeking_Country'],
								"ctl00\$SearchStrip\$txtZip" => "",
								"ctl00\$cphcontent\$CascadingDropDownCountry_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDownState_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDownCity_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDownCountry2_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDownState2_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDownCity2_ClientState" => "",
								"ctl00\$cphcontent\$CascadingDropDown1_ClientState" => "",
								"ctl00\$cphcontent\$dropGender3" => $searchData['Seeking_Gender'],
								"ctl00\$cphcontent\$txtAgeFrom3" => $searchData['Seeking_Age_From'],
								"ctl00\$cphcontent\$txtAgeTo3" => $searchData['Seeking_Age_To'],
								"ctl00\$cphcontent\$ddCountry" => $searchData['Seeking_Country'],
								"ctl00\$cphcontent\$txtZipCode" => ""
							);
		if($page>1)
		{
			unset($requireData['ctl00\$cphcontent\$btnDistanceSearchGo']);
			$requireData['hiddenInputToUpdateATBuffer_CommonToolkitScripts'] = "0";
		}
		else
			$requireData["ctl00\$cphcontent\$btnDistanceSearchGo"] = "Search";

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
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $requireData);

		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_path);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch); 
		curl_close($ch);

		//echo "<div style='border:solid 1px #F00'>".$result."</div>"; 
		//echo "<br/>-----------------------------<br/>";
		$viewstate=substr($result,strpos($result,"id=\"__VIEWSTATE\""));
		$viewstate=str_replace("id=\"__VIEWSTATE\" value=\"","",$viewstate);
		$viewstate=trim(substr($viewstate,0,strpos($viewstate,"\" />")));

		/*echo "<br/>-----------------------------<br/>";
		echo $viewstate;
		echo "<br/>-----------------------------<br/>";*/

		/*die('<br/>Search result');
		$result=file_get_contents("sample-search-content.html");*/
		list($list, $searchData) = self::getMembersFromSearchResult($username, $page, utf8_encode($result));
		return array($list, $searchData, $viewstate);//self::getMembersFromSearchResult($username, $page, utf8_encode($result));
	}

	static function getMembersFromSearchResult($username, $page, $content)
	{
		//echo $content;
		//die('<br/>Search result');
		//Find NEXT url
		$content = substr($content, strpos($content, '<table id="ctl00_cphcontent_SearchResults_dlUsers" cellspacing="0" border="0" style="width:100%;border-collapse:collapse;">'));
		$content = substr($content, 0, strpos($content, '<div id="ctl00_cphcontent_SearchResults_pnlPaginator">'));
		//echo "<div style='border:solid 1px #F00'>".$content."</div>";
		//die('<br/>--------------------------');
		$content = str_replace("&", "&amp;", $content);
		$content = str_replace("<br>", "<br/>", $content);
		//$content = str_replace("/uploades/icons/", "http://www.todaysdating.com/uploades/icons/", $content); 
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
				for($i=0; $i<20; $i=$i+2)
				{
					//$user_text = $table->tr[$i]->td[$j]->table[0]->tr[0]->td[0]->a[0]->tagAttrs['href'];
					//$user_text = substr($user_text, 0, strpos($user_text, '&indexID='));

					$user_id = "";
					$user_name =  $table->tr[$i]->td[0]->div[0]->div[0]->h4[1]->strong[0]->tagData;
					$user_img = $table->tr[$i]->td[0]->div[0]->a[0]->img[0]->tagAttrs['src'];
					$user_url = $table->tr[$i]->td[0]->div[0]->a[0]->tagAttrs['href'];
					$user_loc = $table->tr[$i]->td[0]->div[0]->div[0]->h4[4]->strong[0]->tagData;

					if($user_name!="" && $user_img!="")
						array_push($list,array('userid' => $user_id, 'username' => $user_name, 'pic' => $user_img, 'url' => $user_url, 'loc' => $user_loc));
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
			//$sql = "INSERT INTO todaysdating_member (username, userid, gender, pic, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['Seeking_Gender']."', '".$member['pic']."', '".$post['Seeking_Country']."', '".$member['loc']."', NOW())";
			//funcs::savelog($sql);
			DBConnect::execute_q("INSERT INTO todaysdating_member (username, userid, gender, pic, url, country, location, created_datetime) VALUES ('".$member['username']."', '".$member['userid']."', '".$post['Seeking_Gender']."', '".$member['pic']."', '".$member['url']."', '".$post['Seeking_Country']."', '".$member['loc']."', NOW())");
		}
	}

	static function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".SITE_ID;
		return DBConnect::assoc_query_1D($sql);
	}

	static function getMembers($post, $amount)
	{
		$sql = "SELECT username FROM todaysdating_member WHERE gender='".$post['Seeking_Gender']."' LIMIT ".$amount;
		return DBConnect::assoc_query_2D($sql);
	}

	static function getNextMember($post)
	{
		$sql = "SELECT username,userid FROM todaysdating_member WHERE gender='".$post['Seeking_Gender']."' AND username NOT IN (SELECT to_username FROM todaysdating_sent_messages) AND ((id-1)%6)=(".BOT_ID."-1) ORDER BY id ASC LIMIT 1";

		$member = DBConnect::assoc_query_1D($sql);
		if(!(is_array($member)))
			funcs::savelog($sql);

		return $member;
	}

	static function checkSendingRestric($username, $sendMessageURL, $sendMessageReferer, $headerOpt)
	{
		echo "<pre>";
		print_r(array($username, $sendMessageURL, $sendMessageReferer, $headerOpt));
		echo "</pre>";
		$ch = curl_init();

		//echo "http://www.todaysdating.com/showuser.aspx?uid=".$username."<br/>";
		
		curl_setopt($ch, CURLOPT_URL, "http://www.todaysdating.com/SendMessage.aspx?to_user=brianguy&src=profile");//"http://www.todaysdating.com/showuser.aspx?uid=".$username
		//curl_setopt($ch, CURLOPT_REFERER, $sendMessageReferer);//"http://www.todaysdating.com/showuser.aspx?uid=".$username
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpt);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		$result = curl_exec($ch);
		curl_close($ch);

		echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>"; //die('<br/>Sendiing Restric result');//
	}

	//ctl00$cphcontent$txtMessageBody

	static function sendMessage($from, $username, $userid, $subject, $message, $sendMessageURL, $sendMessageReferer, $headerOpt)
	{
		$cookie_path = self::getCookiePath($from);
		$sendMessageURL		.= "?to_user=".$username."&src=profile";
		$sendMessageReferer  = $sendMessageURL; 
		//self::checkSendingRestric($username, $sendMessageURL, $sendMessageReferer, $headerOpt);
		list($viewstate, $eventValidation) = self::getViewStateAndEventValidation($from, $sendMessageReferer);
		$requireData = array(	
								"__EVENTARGUMENT" => "",
								"__VIEWSTATE" => $viewstate,
								"ctl00\$SearchStrip\$CascadingDropDownCountry_ClientState" => "",
								"ctl00\$SearchStrip\$dropGender" => "1",
								"ctl00\$SearchStrip\$txtAgeFrom" => "18",
								"ctl00\$SearchStrip\$txtAgeTo" => "80",
								"ctl00\$SearchStrip\$ddCountry" => "",
								"ctl00\$SearchStrip\$txtZip" => "",
								"ctl00\$cphcontent\$txtMessageBody" => $subject." ".$message,
								"ctl00\$cphcontent\$btnSend" => "Send Message"
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
		//echo "<br/>----------------------<br/><div style='border:solid 1px #F00'>".$result."</div>"; //die("<br/>Sending Message");//
		curl_close($ch);

		if(strpos($result, "Your message has been sent successfully!")>-1)
		{
			funcs::savelog("Sent message to ".$username);
			DBConnect::execute_q("INSERT INTO todaysdating_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".$username."','".$from."','".addslashes($subject)."','".addslashes($message)."',NOW())");
			return true;
		}
		else
		{
			funcs::savelog("Sending message to ".$username." failed");
			DBConnect::execute_q("DELETE FROM todaysdating_member WHERE username='".$username."'");
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
		$sql = "SELECT COUNT(id) AS total FROM todaysdating_member";
		return DBConnect::assoc_query_1D($sql);
	}

}
?>