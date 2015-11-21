<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in flirtfever()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class kwick extends bot
{
	public $sendmsg_total = 0;
	
	public function kwick($post)
	{
		if(is_array($post) && count($post))
		{
			ignore_user_abort(true);
			$commandID = $post['id'];
			$runCount = $post['run_count'];
			$botID = $post['server'];
			$siteID = $post['site'];
			$this->command = $this->mb_unserialize($post['command']);
			$post['command'] = $this->command;
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			ignore_user_abort(false);
			$this->command = array(
									"profiles" => array(
															array(
																	"username" => "Angenehm1",
																	"password" => "Delikat19"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "XXX"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 5,
									"logout_after_sent" => "Y",
									"messages_logout" => 1,
									"wait_for_login" => 1,
									"login_by" => 1,
									"age_from" => 20,
									"age_to" => 20,
									"gender" => 2,
									"msg_type" => "pm",
									"send_test" => 0,
									"distance" => 0,
									"start_city" => "Berlin",
									"start_page" => 1,
									"version" => 1,
									"inboxLimit" => 1,
									"debug" => 1,
									"options" => array(
														//"withImage" => 1,
														//"single" => 1,
														"online" => 1
														),
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 54;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 100;

		$this->token = "";
		$this->databaseName = "kwick";
		$this->profileLanguage = "DE";
		$this->usernameField = "kwick_username";
		$this->indexURL = "http://www.kwick.de/";
		$this->indexURLLoggedInKeyword['EN'] = "Enter search term";
		$this->indexURLLoggedInKeyword['DE'] = "/startseite/";
		$this->loginURL = "https://www.kwick.de/login/";
		$this->loginRefererURL = "http://www.kwick.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.kwick.de/members/logout/?_token_=lAW4COBh5.RyI";
		$this->searchPageURL = "http://www.kwick.de/community/members/";
		$this->searchURL = "http://www.kwick.de/community/updateFilter";
		$this->searchURL2 = "http://www.kwick.de/members/userlist/CommunityMembers";
		$this->searchRefererURL = "http://www.ktosexy.de/user.php";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "http://www.kwick.de/";
		$this->sendMessagePageURL = "http://www.kwick.de/messages/message/get/";
		$this->sendMessageURL = "http://www.kwick.de/messages/message/send/";
		$this->sendGuestbookPageURL = "http://www.kwick.de/";
		$this->sendGuestbookURL = "http://www.kwick.de/";
		$this->inboxURL = "http://www.kwick.de/messages/recv/";
		$this->deleteInboxURL = "http://www.kwick.de/messages/delete/recv/0/?__env=ajaxform";
		$this->outboxURL = "http://www.kwick.de/messages/sent/";
		$this->deleteOutboxURL = "http://www.kwick.de/messages/delete/sent/0/?__env=ajaxform";
		$this->photoCommentURL = "http://www.kwick.de/socialobject/Profile_Photos_Photo/";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
		$this->commandID = $commandID;
		$this->workCount = 1;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->runCount = $runCount;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->cities = array(
								"Berlin", "Hamnburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Halle (Saale)", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Müllheim an der Ruhr", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Offenbach am Main", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		if($this->command['gender'] == 2){
			$target = "Male";
		}elseif($this->command['gender'] == 1){
			$target = "Female";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}

		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	"kwick_username" => $user['username'],
								"kwick_password" => $user['password'],
								"flashInfo" => "Shockwave Flash 11.9 r900",
								"jsInfo" => "true",
								"cookieInfo" => "true",
								"browserInfo" => "Chrome 24.0.1312.56",
								"osInfo" => "Windows XP"
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	private function getMessagePart($part)
	{
		if(count($this->messagesPartTemp[$part])==0)
		{
			shuffle($this->messagesPart[$part]);
		}
		elseif(count($this->messagesPart[$part])==0)
		{
			$this->messagesPart[$part] = $this->messagesPartTemp[$part];
			shuffle($this->messagesPart[$part]);
			$this->messagesPartTemp[$part] = array();
		}

		$msg = array_pop($this->messagesPart[$part]);
		array_push($this->messagesPartTemp[$part], $msg);
		return $msg;
	}

	public function getNewProfile($forceNew = FALSE) {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
	
		$this->savelog("Site ID : ". $this->siteID);
		$fetch = array();
	
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
				
			$row = botutil::getNewProfile($this->siteID, $username, $this->command, $this);
			$fetch[0] = $row;
			
		}else{
			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (username='".$username."') LIMIT 1";
			$fetch = DBConnect::assoc_query_2D($sql);
		}
		
		if (isset($this->command['debug']) && $this->command['debug'] == 1) {
			$this->savelog(serialize($fetch));
		}
	
		
		if(empty($fetch[0]['username'])) {
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!! / Debug : " . $sql);
			$this->savelog('FINISHED');
			die();
		} else {
			$this->addLoginData(array(
					array(
							'username' => $fetch[0]['username'],
							'password' => $fetch[0]['password']
					)
			));
			$this->savelog('New profile account is '. $fetch[0]['username']);
			$this->currentUser=0;
		}
	}
	
	private function getMessage($new=true)
	{
		if($new)
		{
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->command['messages'])-1);

			$subject = $this->command['messages'][$this->currentSubject]['subject'];
			$message = $this->command['messages'][$this->currentMessage]['message'];

			$this->message = "";
			for($i=1; $i<=$this->totalPart; $i++)
			{
				$this->message .= $this->getMessagePart($i)." ";
				if($i == ($this->totalPart/2))
				{
					$this->message .= $message." ";
				}
			}
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
	}

	function checkInbox()
	{
		$this->savelog("Checking message inbox");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$mailpage = $this->getHTTPContent("http://www.kwick.de/messages", "http://www.kwick.de/", $cookiePath);

		$html = str_get_html($mailpage);
		
		$this->sleep(30);
		
		foreach ($html->find(".msgRow") as $tr) {
			$dellink = $tr->find("td.action", 0)->find("a", 0);
			if ($dellink != null) {
				$this->savelog("Deleting Message" . $dellink->href);
				$mailcontent = $this->getHTTPContent("http://www.kwick.de" . $dellink->href, "http://www.kwick.de/", $cookiePath);
				$this->sleep(5);
			}
		}
		
	}
	
	function checkEmail()
	{
		$this->savelog("Checking Email");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$mailpage = $this->getHTTPContent("http://www.kwick.de/mail", "http://www.kwick.de/", $cookiePath);
		
		$html = str_get_html($mailpage);
		
		$inboxid = $html->find("select[name=folderlist]", 0)->children(0)->value;
		
		$i = 0;
		foreach ($html->find("#mailTable tbody tr") as $tr) {
			
			if (++$i == 1) continue;
			
			$maillink = $tr->find("td", 3); 
			if ($maillink != null) {
				$maillink = $maillink->find("a", 0);
				$this->savelog("Reading Email " . $maillink->href);
				$mailcontent = $this->getHTTPContent("http://www.kwick.de" . $maillink->href, "http://www.kwick.de/", $cookiePath);
				$this->sleep(5);
			}
			$deletebox = $tr->find("td", 0)->find("input", 0);
			if ($deletebox != null) {
				$mailid = $deletebox->value;
				
				$this->savelog("Deleting Email " . $mailid);
				
				$postvars = array('delete' => 'Delete', 'folderlist' => $inboxid, 'show' => '', 'page' => '', 'folder' => $inboxid, 'tagmsg[]' => $mailid);
				$mailcontent = $this->getHTTPContent("http://www.kwick.de/mail/folder/" . $inboxid ."/?__env=ajaxform", "http://www.kwick.de/", $cookiePath, $postvars);
				
				$this->sleep(2);
			}
		}
		
	}
	
	public function checkUser($username_check)
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$content = $this->getHTTPContent("http://www.kwick.de/" . $username_check . "/", $this->loginRefererURL, $cookiePath);
		
		$html = str_get_html($content);
		$error = $html->find(".msgErrorText", 0);
		
		if ($error == null) return true;
		
		return false;
	}
	
	public function work()
	{
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);
		
		$this->checkEmail();
		
		$this->checkInbox();

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchPageURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			if(isset($this->command['options']['online']))
			{
				//$this->command['distance'] = "0";
				$cities = array("-");
			}
			else
			{
				$cities = $this->cities;
				if(($this->workCount == 1) && ($age==$this->command['age_from']))
				{
					if($key = array_search($this->command['start_city'],$cities))
					{
						$cities = array_slice($cities, $key);
					}
				}
			}

			foreach($cities as $city)
			{
				$list=array();

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($city==$this->command['start_city']))
					$page=$this->command['start_page'];
				else
					$page=1;

				$log_distance = "";
				if(!isset($this->command['options']['online']))
					$log_distance = ", city: ".$city; //.", distance: ".$this->command['distance'];

				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$search_arr = array(
											"gender" => $this->command['gender'],
											"ageFrom" => $age,
											"ageTo" => $age,
											"distance" => 0, //$this->command['distance'],
											"smoker" => 0,
											"children" => 0,
											"city" => ($city=="-")?"":$city,
											"__env" => "json",
											"_token_" => $this->token,
											"_" => $this->utime()
										);

						$details = "";
						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr[$key]=$value;
								$details .= ", $key = $value";
							}
						}

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.$log_distance.$details.", page: ".$page);
						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);

						$search_arr = array(
											"type" => "CommunityMembers",
											"refId" => 1,
											"limit" => $this->searchResultsPerPage,
											"containerId" => "userlist_2beda217",
											"scrollable" => "true",
											"filter" => 1,
											"offset" => $this->searchResultsPerPage * ($page-1),
											"hideHeader" => 1,
											"reverseOffset" => 0,
											"sortBy" => "",
											"search" => "",
											"_" => $this->utime()
										);

						$content = $this->getHTTPContent($this->searchURL2."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",$content);

						/***********************************************/
						/***** Extract profiles from search result *****/
						/***********************************************/
						$list = $this->getMembersFromSearchResult($username, $page, $content);

						if(is_array($list))
						{
							$this->savelog("Found ".count($list)." member(s)");
							if(count($list))
							{
								$this->sleep(5);
								foreach($list as $key => $item)
								{									
									$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
									//If in runnig time period
									if($sleep_time==0)
									{
										if($this->command['version']==1)
										{
											$this->work_sendMessage($username, $item, $cookiePath);
										}
										elseif($this->command['version']==2)
										{
											$this->work_visitProfile($username, $item, $cookiePath);
										}
										else
										{
											$this->savelog("Wrong version selected.");
										}
									}
									else
									{
										$this->savelog("Not in running time period.");
										$this->sleep($sleep_time);
										return true;
									}
									
									if($this->command['logout_after_sent'] == "Y"){
										if($this->sendmsg_total >= $this->command['messages_logout']){
											$this->sendmsg_total = 0;
											$this->logout();
											$this->savelog('Logout after sent '.$this->command['messages_logout'].' messages(s) completed');
											$this->savelog("Get a new Profile for Send Message");
											$this->getNewProfile();
											$this->sleep(($this->command['wait_for_login']*60));
											$this->login();
											$this->work();
										}
									}
								}
							}
						}

						if($this->command['version']==1)
						{
							$this->deleteAllOutboxMessages($username, $cookiePath);
						}
						elseif($this->command['version']==2)
						{
							$inbox = $this->getInboxMessages($username, $cookiePath);
							if(is_array($inbox))
							{
								$this->savelog("Found ".count($inbox)." inbox message(s)");
								$this->sleep(5);
								if(count($inbox)>=$this->inboxLimit)
								{
									foreach($inbox as $key => $item)
									{
										$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
										//If in runnig time period
										if($sleep_time==0)
										{
											if(!$this->work_sendMessage($username, $item, $cookiePath))
												return false;
										}
										else
										{
											$this->savelog("Not in running time period.");
											$this->sleep($sleep_time);
											return true;
										}
									}
									$this->deleteAllOutboxMessages($username, $cookiePath);
								}
							}
						}
						
						$page++;
					}
					else
					{
						$this->savelog("This profile: ".$username." does not log in.");
						return false;
					}
				}
				while(count($list)>=$this->searchResultsPerPage);
			}

			if($age=="-")
				$age=$this->command['age_to'];
		}

		$this->savelog("Job completed.");
		$this->workCount++;
		return true;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function getMembersFromSearchResult($username, $page, $content){
		$list = array();
		$content = json_decode($content);

		if(isset($content->html) && ($content->html != ""))
		{
			// Make it to XML object
			$parser = $this->convertToXML($username, $page, $content->html);

			// Check if it's correct result
			if($parser->document->div[0]->tagAttrs['class']=="userList userListThumbs")
			{
				foreach($parser->document->div[0]->div as $item)
				{
					if(strpos($item->tagAttrs['class'], "userListInfo")!==false)
					{
						$profile = array(
											"username" => $item->div[1]->div[0]->span[0]->a[0]->tagData,
											"userid" => $item->div[1]->div[1]->a[0]->tagAttrs['data-miniprofile']
										);
						array_push($list,$profile);
					}
				}
			}
		}
		return $list;
	}

	private function getProfilePhoto($username, $name, $content){
		$photo = "";
		if(strpos($content, '<div class="colOne top profileHeader"')!==false)
		{
			$content = substr($content, strpos($content, '<div class="colOne top profileHeader"'));
			$content = substr($content, 0, strpos($content, '<div class="tabnav'));
			$parser = $this->convertToXML($username, "photo-".$name, $content);

			// Check if it's correct result
			if(strpos($parser->document->div[0]->tagAttrs['class'], "profileHeader")!==false)
			{
				$photo = $parser->document->div[0]->a[0]->tagAttrs['href'];
			}
		}
		return $photo;
	}

	public function getAction(){
		return $this->command['action'];
	}

	public function getSiteID(){
		return $this->siteID;
	}

	public function checkLogin($username, $password){
		$this->loginArr = array();
		$this->addLoginData(array(array("username"=>$username, "password"=>$password)));
		$this->currentUser=0;
		$cookiePath = $this->getCookiePath($username);

		if(!($this->isLoggedIn($username)))
		{
			$this->savelog("This profile: ".$username." does not log in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				if($this->tor_new_identity($this->proxy_ip,$this->proxy_control_port,'bot'))
					$this->savelog("New Tor Identity request completed.");
				else
					$this->savelog("New Tor Identity request failed.");

				$this->savelog("Logging in.");
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, $this->loginArr[$this->currentUser]);
				file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

				if(!($this->isLoggedIn($username)))
				{
					$this->savelog("Log in failed with profile: ".$username);
					$this->savelog("Log in failed $count_login times.");

					if($count_login>($this->loginRetry-1))
					{
						return false;
					}
					else
					{
						$sleep_time = 120; // 2 mins

						$this->savelog("Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
				}
				else
				{
					$this->savelog("Logged in with profile: ".$username);
					return true;
				}
			}
		}
		else
		{
			$this->savelog("This profile: ".$username." has been logged in.");
			return true;
		}
	}

	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item['username']);
		$content = $this->getHTTPContent($this->profileURL.$item['username']."/portrait/", $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
		{
			if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
			{
				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->command['messages'])-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
				$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);
			}
			else
			{
				list($subject, $message)=$this->getMessage($this->newMessage);
			}
			$this->savelog("Message is : ".$message);

			// Go to profile page
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			if(($this->command['msg_type']=="pm") || ($this->command['msg_type']=="gb"))
			{
				if((strpos($content , "Send message")!==false) || (strpos($content , "Message schreiben")!==false) || ($this->command['version']==2))
				{
					///reserve this user, so no other bot can send msg to
					$this->savelog("Reserving profile to send message: ".$item['username']);
					if($this->reserveUser($item['username'], $item['userid']))
					{
						if($this->command['msg_type']=="pm")
						{
							////////////////////////////////////////
							/////// Go to send message page ////////
							////////////////////////////////////////
							$this->savelog("Go to send message page: ".$item['username']);
							$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid']."/new/?__env=partial&_token_=".$this->token."&_=".$this->utime(), $this->profileURL.$item['username']."/?ref=search", $cookiePath);
							$this->sleep(5);
							$message_arr = array(
													"text_shadowed" => $message,
													"text" => "
													".$message,
													"sender" => $item['userid'],
													"isAnswer" => ($this->command['version']==2)?1:0
													);
							if(time() < ($this->lastSentTime + $this->messageSendingInterval))
								$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
							$this->savelog("Sending message to ".$item['username']);
							if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
							{
								$url = $this->sendMessageURL.$item['userid']."/0/recv/?_token_=".$this->token."&_rlc_=&__env=ajaxformpartial";
								$url_referer = $this->profileURL.$item['userid']."/?ref=search";
								$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
								$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
								file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$url_log.$content);

								if((strpos($content, 'SC.messages.conversation.remove()')!==false) || (strpos($content, 'SC.messages.conversation.build')!==false))
								{
									$this->newMessage=true;
									$this->savelog("Sending message completed.");
									DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->lastSentTime = time();
									if(($this->command['version']==1) && isset($item['message']))
										$this->deleteInboxMessage($username, $item['message'], $cookiePath);
									$this->checkEmail();
									$this->checkInbox();
									$this->sendmsg_total++;
									$return = true;
								}
								else
								{
									$this->newMessage=true;
									$this->savelog("Sending message failed.");
									$this->lastSentTime = time();
									$this->sleep(120);
									$return = true;
								}
							}
							else
							{
								//$this->newMessage=false;
								$this->newMessage=true;
								$this->cancelReservedUser($item['userid']);
								$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
								if(isset($item['message']))
									$this->deleteInboxMessage($username, $item['message'], $cookiePath);
								$return = true;
							}
						}
						elseif($this->command['msg_type']=="gb")
						{
							//////////////////////////////////////////
							/////// Go to sign guestbook page ////////
							//////////////////////////////////////////
							$this->savelog("Go to sign guestbook page: ".$item['username']);
							$content = $this->getHTTPContent($this->sendGuestbookPageURL.$item['username']."/gb/", $this->profileURL.$item['username']."/?ref=search", $cookiePath);
							$this->sleep(5);
							$message_arr = array(
													"newgb" => "<p>".$message."</p>",
													"editorType" => 1
													);
							if(time() < ($this->lastSentTime + $this->messageSendingInterval))
								$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
							$this->savelog("Signing guestbook to ".$item['username']);
							if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
							{
								$content = $this->getHTTPContent($this->sendGuestbookURL.$item['username']."/gb/save/?_token_=".$this->token."&_rlc_=&__env=ajaxform", $this->profileURL.$item['username'], $cookiePath, $message_arr);
								file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['userid'].".html",$content);

								if(strpos($content, 'Your guestbook entry has been saved')!==false)
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook completed.");
									DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->lastSentTime = time();
									if(isset($item['message']))
										$this->deleteInboxMessage($username, $item['message'], $cookiePath);
									$return = true;
								}
								elseif(strpos($content, 'Der Eintrag wurde gespeichert')!==false)
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook completed.");
									DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->lastSentTime = time();
									if(isset($item['message']))
										$this->deleteInboxMessage($username, $item['message'], $cookiePath);
									$return = true;
								}
								elseif(strpos($content, "Unfortunately as a new member you can't write so many guestbook entries at a time")!==false)
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook failed, unfortunately as a new member this profile can't write so many guestbook entries at a time.");
									$this->lastSentTime = time();
									$return = false;
								}
								else
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook failed.");
									$this->lastSentTime = time();
									$this->sleep(120);
									$return = true;
								}
							}
							else
							{
								//$this->newMessage=false;
								$this->newMessage=true;
								$this->cancelReservedUser($item['userid']);
								$this->savelog("Signing guestbook failed. This profile has been sent message to.");
								if(isset($item['message']))
									$this->deleteInboxMessage($username, $item['message'], $cookiePath);
								$return = true;
							}
						}
						$this->cancelReservedUser($item['userid']);
						$this->sleep(2);
					}
				}
				else
				{
					//$this->newMessage=false;
					$this->newMessage=true;
					$this->savelog("It's not allowed to send message to ".$item['username']);
					if(isset($item['message']))
						$this->deleteInboxMessage($username, $item['message'], $cookiePath);
					$this->sleep(120);
					$return = true;
				}
			}
			elseif($this->command['msg_type']=="pt")
			{
				$photo = $this->getProfilePhoto($username, $item['username'], $content);
				$photo = substr($photo, 1);
				$photo_id = substr($photo, 0, -1);
				$photo_id = substr($photo_id, strrpos($photo_id,"/")+1);
				///reserve this user, so no other bot can send msg to
				$this->savelog("Reserving profile to send message: ".$item['username']);
				if($this->reserveUser($item['username'], $item['userid']))
				{
					//////////////////////////////////////////
					/////// Go to profile photo page ////////
					//////////////////////////////////////////
					$this->savelog("Go to profile photo page: ".$item['username']);
					$content = $this->getHTTPContent($this->indexURL.$photo, $this->profileURL.$item['username']."/?ref=search", $cookiePath);
					$this->sleep(5);

					$message_arr = array(
											"text" => "
".$message,
											"_token_" => $this->token
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing comment on profile photo: ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$url = $this->photoCommentURL.$item['userid']."___".$photo_id."/comment/add?__env=json";
						$content = $this->getHTTPContent($url, $this->indexURL.$photo, $cookiePath, $message_arr);
						file_put_contents("sending/pt-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						if(strpos($content, '"error":false')!==false)
						{
							$this->newMessage=true;
							$this->savelog("Signing comment completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
						}
						elseif(strpos($content, "Unfortunately as a new member you can't write so many guestbook entries at a time")!==false)
						{
							$this->newMessage=true;
							$this->savelog("Signing comment failed, unfortunately as a new member this profile can't write so many guestbook entries at a time.");
							$this->lastSentTime = time();
							$return = false;
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Signing comment failed.");
							$this->lastSentTime = time();
							$this->sleep(120);
							$return = true;
						}
					}
					else
					{
						//$this->newMessage=false;
						$this->newMessage=true;
						$this->savelog("Signing comment failed. This profile has been sent message to.");
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
					$this->cancelReservedUser($item['userid']);
				}
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
			if(isset($item['message']))
				$this->deleteInboxMessage($username, $item['message'], $cookiePath);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $cookiePath){
		$this->savelog("Sending test message.");
		$profiles = DBConnect::assoc_query_1D("SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID);
		if($this->command['gender']=="maennlich")
		{
			$receiverId			= $profiles['male_id'];
			$receiverUsername	= $profiles['male_user'];
		}
		else
		{
			$receiverId			= $profiles['female_id'];
			$receiverUsername	= $profiles['female_user'];
		}

		if(($receiverId!='') && ($receiverUsername!=''))
		{
			$item = array(	"username" => $receiverUsername,
							"userid" => $receiverId
							);

			$this->work_sendMessage($username, $item, $cookiePath, true);
		}
		else
		{
			$this->savelog("Get test profile failed.");
		}
	}

	private function isAlreadySent($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
			DBConnect::execute_q($sql);
			return true;
		}
		elseif($server==$this->botID)
		{
			$this->savelog("Already Reserved: ".$username);
			return true;
		}
		else
		{
			$this->savelog("Already Reserved by other bot: ".$username);
			return false;
		}
	}

	private function cancelReservedUser($userid)
	{
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE userid=".$userid." AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		/*while($list = $this->getOutboxMessages($username, $cookiePath))
		{
			$this->savelog("Found ".count($list)." outbox messages.");
			foreach($list as $message)
			{
				$this->deleteOutboxMessage($username, $message, $cookiePath);
			}
		}*/
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);

		if(strpos($content, '<table id="msgTable')!==false)
		{
			$content = substr($content, strpos($content, '<table id="msgTable"'));
			$content = substr($content, 0, strpos($content, '</form>'));
			$parser = $this->convertToXML($username, "outbox", $content);

			// Check if it's correct result
			if($parser->document->table[0]->tagAttrs['id']=="msgTable")
			{
				foreach($parser->document->table[0]->tr as $item)
				{
					if(isset($item->tagAttrs['class']) && (strpos($item->tagAttrs['class'], "msgRow")!==false))
					{
						$message = $item->td[0]->input[0]->tagAttrs['value'];
						array_push($list,$message);
					}
				}
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message);
		$ch = curl_init();
		$delete_arr = array(
								"msg_id[]" => $message
							);

		$content = $this->getHTTPContent($this->deleteOutboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$page = 0;
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL.$page."/", $this->indexURL, $cookiePath);

		if((strpos($content, "<span>Received</span>")!==false) || (strpos($content, "<span>Empfangen</span>")!==false))
		{
			if($this->profileLanguage == "EN")
				$total = substr($content, strpos($content, '<span>Received</span>')+21);
			else
				$total = substr($content, strpos($content, '<span>Empfangen</span>')+22);
			$total = substr($total, 0, strpos($total, '</li>'));
			$total = str_replace(array("(",")"),"",$total);

			if($total >= $this->inboxLimit)
			{
				$totalPages = 1;
				if(strpos($content, '<div class="Paginator">')!==false)
				{
					$content = substr($content, strpos($content, '<div class="Paginator">'));
					$content = substr($content, 0, strpos($content, '</div>'));
					$parser = $this->convertToXML($username, "inbox-paginate", $content);

					if($parser->document->div[0]->tagAttrs['class']=="Paginator")
					{
						foreach($parser->document->div[0]->a as $item)
						{
							if(is_numeric($item->tagData))
							{
								if($item->tagData > $totalPages)
									$totalPages = $item->tagData;
							}
						}
					}
				}

				for($page=0; $page<$totalPages; $page++)
				{
					$content = $this->getHTTPContent($this->inboxURL.$page."/", $this->indexURL, $cookiePath);
					if(strpos($content, '<table id="msgTable')!==false)
					{
						$content = substr($content, strpos($content, '<table id="msgTable"'));
						$content = substr($content, 0, strpos($content, '</form>'));
						$parser = $this->convertToXML($username, "inbox", $content);

						// Check if it's correct result
						if($parser->document->table[0]->tagAttrs['id']=="msgTable")
						{
							foreach($parser->document->table[0]->tr as $item)
							{
								if(isset($item->tagAttrs['class']) && (strpos($item->tagAttrs['class'], "msgRow")!==false))
								{
									$message = array(
														"message" => $item->td[0]->input[0]->tagAttrs['value'],
														"username" => $item->td[2]->div[0]->a[0]->strong[0]->tagData,
														"userid" => $item->td[2]->div[0]->a[0]->tagAttrs['data-miniprofile'],
													);
									array_push($list,$message);
								}
							}
						}
					}
				}
			}
			else
			{
				$list = range(1, $total);
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message);
		$ch = curl_init();
		$delete_arr = array(
								"msg_id[]" => $message
							);

		$content = $this->getHTTPContent($this->deleteInboxURL, $this->inboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}
}
?>
