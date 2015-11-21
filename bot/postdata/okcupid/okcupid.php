<?php
require_once 'bot.php';
require_once 'simple_html_dom.php';
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

class okcupid extends bot
{
	private $target = 'Male';
	public $databaseName = 'okcupid';
	public $sendmsg_total = 0;
	public $name = '';
	
	public function okcupid($post)
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
			$this->command = array(
				"profiles" => array(
					array(
						"username" => "Lomism0087",
						"password" => "N0meenga32"
					)
				),
				"messages" => array(
					array(
						"subject" => "Hallo",
						"message" => "hallo"
					)
				),
				"start_h" => 00,
				"start_m" => 00,
				"end_h" => 00,
				"end_m" => 00,
				"messages_per_hour" => 5,
				"age_from" => 18,
				"age_to" => 20,
				"gender" => "17", //34
				"msg_type" => "im",
				"send_test" => 0,
				"distance" => 25,
				"start_city" => "Cardiff",
				"start_page" => 1,
				"version" => 1,
				//"full_msg" => 1,
				//"online" => 3600,				//now
				//"online" => 86400,			//last day
				//"online" => 604800,			//last week
				//"online" => 2678400,		//last month
				"online" => 315360000,		//last year
				//"online" => 315360000,		//last decade
				"proxy_type" => 1,
				"withImage" => 0,
				"action" => "send",
				'logout_after_sent' => 'Y',
				'messages_logout' => 1,
				'wait_for_login' => 1
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 39;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->usernameField = "username";
		$this->indexURL = "http://www.okcupid.com";
		$this->indexURLLoggedInKeyword = "Sign out";
		$this->loginURL = "https://www.okcupid.com/login";
		$this->loginRefererURL = "http://www.okcupid.com";
		$this->loginRetry = 3;
		$this->logoutURL = "https://www.okcupid.com/signout";
		$this->searchPageURL = "http://www.okcupid.com/match";
		$this->searchURL = "http://www.okcupid.com/match";
		$this->searchRefererURL = "http://www.okcupid.com/match";
		$this->locationQueryURL = "http://www.okcupid.com/locquery?func=query&query=";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "http://www.okcupid.com/profile/";
		$this->sendMessagePageURL = "http://www.okcupid.com/profile";
		$this->sendMessageURL = "http://www.okcupid.com/mailbox";
		$this->sendQuestionURL = "http://feed.meetme.com/askMe/json/submit";
		$this->sendIMURL = "http://www.okcupid.com/instantevents";
		$this->sendGuestbookURL = "http://single.de/Rest/postfach-message";
		$this->inboxURL = "http://www.okcupid.com/messages";
		$this->deleteInboxURL = "http://www.okcupid.com/mailbox";
		$this->deleteInboxRefererURL = "http://www.okcupid.com/messages";
		$this->outboxURL = "http://www.okcupid.com/messages?folder=2";
		$this->deleteOutboxURL = "http://www.okcupid.com/mailbox";
		$this->deleteOutboxRefererURL = "http://www.okcupid.com/messages?folder=2";
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
		$this->message="";
		$this->newMessage=true;
		$this->cities = array(
			"Belfast", "Cardiff", "Castlereagh", "Aberdeen", "Dundee", "Edinburgh", "Glasgow", "Craigavon", "Derry", "Bangor", "Beeston and Stapleford", "Carlton", "Chesterfield", "Corby", "Derby", "Kettering", "Leicester", "Lincoln", "Loughborough", "Mansfield", "Northampton", "Nottingham", "Wellingborough", "West Bridgford", "Basildon", "Bedford", "Cambridge/Milton", "Chelmsford", "Cheshunt", "Clacton-on-Sea", "Colchester", "Dunstable", "Grays", "Great Yarmouth", "Harlow/Sawbridgeworth", "Hemel Hempstead", "Ipswich", "Lowestoft", "Luton", "Norwich", "Peterborough", "Saint Albans", "Southend-on-Sea", "Stevenage", "Watford", "London", "Lisburn", "Newport", "Newtownabbey", "Darlington", "Gateshead", "Hartlepool", "Middlesbrough", "Newcastle upon Tyne", "South Shields", "Stockton-on-Tees", "Sunderland", "Washington", "Bebington", "Birkenhead", "Blackburn", "Blackpool", "Bolton", "Bootle", "Burnley", "Bury", "Carlisle", "Cheadle and Gatley", "Chester", "Crewe", "Crosby", "Ellesmere Port", "Greasby/Moreton", "Huyton-with-Roby", "Liverpool", "Macclesfield", "Manchester", "Morecambe", "Oldham", "Preston", "Rochdale", "Runcorn", "Saint Helens", "Sale", "Salford", "Southport", "Stockport", "Wallasey", "Warrington", "Widnes", "Wigan", "Paisley", "Rhondda", "Aldershot", "Ashford", "Aylesbury", "Basingstoke", "Bletchley", "Bognor Regis", "Bracknell", "Brighton", "Chatham", "Crawley", "Dartford", "Eastbourne", "Eastleigh", "Epsom and Ewell", "Esher/Molesey", "Fareham/Portchester", "Farnborough", "Gillingham", "Gosport", "Gravesend", "Guildford", "Hastings", "High Wycombe", "Horsham", "Hove", "Littlehampton", "Maidenhead", "Maidstone", "Margate", "Oxford", "Portsmouth", "Reading", "Reigate/Redhill", "Royal Tunbridge Wells", "Slough", "Southampton", "Staines", "Walton and Weybridge", "Waterlooville", "Woking/Byfleet", "Wolverton/Stony Stratford", "Worthing", "East Kilbride", "Bath", "Bournemouth", "Bristol", "Cheltenham", "Exeter", "Gloucester", "Kingswood", "Paignton", "Plymouth", "Poole", "Swindon", "Taunton", "Torquay", "Weston-super-Mare", "Weymouth", "City", "Swansea", "Barry", "Livingston", "Birmingham", "Cannock", "Coventry", "Dudley", "Halesowen", "Hereford", "Kidderminster", "Newcastle-under-Lyme", "Nuneaton", "Oldbury/Smethwick", "Redditch", "Royal Leamington Spa", "Rugby", "Shrewsbury", "Solihull", "Stafford", "Stoke-on-Trent", "Stourbridge", "Sutton Coldfield", "Tamworth", "Walsall", "West Bromwich", "Wolverhampton", "Worcester", "Barnsley", "Batley", "Bradford", "Dewsbury", "Doncaster", "Grimsby", "Halifax", "Harrogate/Knaresborough", "Huddersfield", "Keighley", "Kingston upon Hull", "Leeds", "Morley", "Rotherham", "Scunthorpe", "Sheffield", "Wakefield", "York"
		);
		
		if($this->command['gender'] == '34') {
			$this->target = 'Female';
		}
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])){
		    $this->command['proxy_type'] = 2;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				"username" => $user['username'],
				"password" => $user['password'],
				"okc_api" => 1
			);

			array_push($this->loginArr, $login_arr);
		}
	}

	private function getLocationID($location, $cookiePath)
	{
		$content = $this->getHTTPContent($this->locationQueryURL.$location, $this->searchPageURL, $cookiePath);
		$content = json_decode($content);
		if(is_object($content))
			return $content->locid;
		else
			return false;
	}

	private function getUserID($content)
	{
		$userid = substr($content, strpos($content, "var user_info =")+16);
		$userid = substr($userid, 0, strpos($userid, "}")+1);
		$userid = json_decode($userid);

		if(is_object($userid))
			return $userid->userid;
		else
			return false;
	}

	private function getAuthCode($content)
	{
		// $content=substr($content,strpos($content,"authid=")+7);
		// $content=substr($content,0,strpos($content,"&"));
		$a = explode('authid=', $content, 2);
		$b = explode('&amp;', $a[1], 2);
		return $b[0];
	}

	public function work()
	{
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);
		$this->newMessage=false;

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchPageURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$cities = $this->cities;
			if(($this->workCount == 1) && ($age==$this->command['age_from']))
			{
				if($key = array_search($this->command['start_city'],$cities))
				{
					$cities = array_slice($cities, $key);
				}
			}

			foreach($cities as $city)
			{
				$list=array();

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($city==$this->command['start_city']))
					$page=$this->command['start_page'];
				else
					$page=1;

				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$details = "";
						$search_arr = array(
							"filter1" => "0,".$this->command['gender'],
							"filter2" => "2,".$age.",".$age,
							"filter3" => "3,".$this->command['distance'],
							"filter4" => "5,".$this->command['online'],
							"filter5" => "1,".$this->command['withImage'],
							"filter6" => "35,0",
							"locid" => $this->getLocationID($city, $cookiePath),
							"lquery" => $city,
							"timekey" => 1,
							"matchOrderBy" => "LOGIN",
							"custom_search" => 0,
							"fromWhoOnline" => 0,
							"mygender" => ($this->command['gender']==17)?"f":"m",
							"update_prefs" => 1,
							"sort_type" => 0,
							"sa" => 1,
							"using_saved_search" => "",
							"low" => (($page-1)*$this->searchResultsPerPage)+1,
							"count" => $this->searchResultsPerPage,
							"ajax_load" => 1
						);

						$this->savelog("Search for gender: ".(($this->command['gender']==17)?"Male":"Female").", age: ".$age.", last online: ".$this->command['online'].", city: ".$city.", with image: ".$this->command['withImage'].", page: ".$page);

						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);				
						
						$vowels = array("\\r\n", "\\n", "\\r","\\t","\\",'{"html" : "','", "end" : false}');
						$content = trim(str_replace($vowels,'',$content));
						
						//$content = json_decode($content);

						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",print_r($content,true));

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
						if($this->login())
						{
							$list = range(1,$this->searchResultsPerPage);
						}
						else
						{
							return false;
						}
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

	private function getMembersFromSearchResult($username, $page, $content){
		$list = array();
		
		if($html = str_get_html($content)){
			$nodes = $html->find("div.username a");
			foreach ($nodes as $node) {	
				array_push($list,array("username" => trim($node->innertext), "link" => $node->href));
			}
		}		
		
		// var_dump($list);
		return $list;
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
		$content = $this->getHTTPContent($this->profileURL.$item['username']."?cf=regular", $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
		{
			$text = botutil::getMessageText($this, $this->target, 'EN');
            $subject = $text['subject'];
            $message = $text['message'];
			$this->savelog("Message is : ".$message);

			// Go to profile page
			$utime = $this->utime();
			$content = $this->work_visitProfile($username, $item, $cookiePath);
			$item['userid'] = $this->getUserID($content);
			$item['authcode'] = $this->getAuthCode($content);
			//if(strpos($content,"InstantEvents.openAnImWindow")!==false)
				//$this->command['msg_type']=="im";
			/*if($this->command['online']==3600)
				$this->command['msg_type']="im";
			else*/
				$this->command['msg_type']="pm";

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				if($this->command['msg_type']=="pm")
				{
					////////////////////////////////////////
					/////// Go to send message page ////////
					////////////////////////////////////////
					$message_arr = array(
						"cb" => "",
						"showsuccess" => "",
						"winktype" => "",
						"ajaxEdit" => 1,
						"shadowbox" => "send_message",
						"tuid" => $item['userid']
					);

					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL, $this->profileURL.$item['username']."?cf=regular", $cookiePath, $message_arr);
					$this->sleep(5);
					$authcode = urldecode($this->getAuthCode($content));
					$message_arr = array(	"ajax" => 1,
											"sendmsg" => 1,
											"r1" => $item['username'],
											"subject" => "",
											"body" => $message,
											"threadid" => 0,
											"authcode" => $item['authcode'],
											"reply" => 0,
											"from_profile" => 1
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					
					$this->savelog("Sending message to ".$item['username']);
					
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendMessageURL;
						$url_referer = $this->profileURL.$item['username']."?cf=regular";
						$content = $this->getHTTPContent('http://www.okcupid.com/mailbox?'.http_build_query($message_arr), $url_referer, $cookiePath);
						file_put_contents("sending/".time()."-pm-".$username."-".$item['username'].".html",$content);

						$content = json_decode($content);

						if($content->status==3)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							// if(isset($item['message']))
								// $this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$error_arr = array(
								2 => "Oops, something went wrong!", 
								4 => "Your mailbox is full! You can't send messages.", 
								5 => "Receiver's mailbox is full! They can't receive messages.", 
								11 => "You are trying to send the same message twice."
							);
							if(is_numeric($content->status))
							{
								$error_message = $error_arr[$content->status];
							}
							elseif($content == "")
							{
								$error_message = "Empty result returned.";
							}
							else
							{
								$error_message = "";
							}
							$this->newMessage=true;
							$this->savelog("Sending message failed. ".$error_message);
							$this->lastSentTime = time();
							$this->sleep(120);
							$return = true;
						}
						
						
						// Count for all sending
						$this->sendmsg_total++;
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				/* elseif($this->command['msg_type']=="im")
				{
					$message_arr = array(
											"from_profile" => 1,
											"send" => 1,
											"attempt" => 1,
											"rid" => $item['userid'],
											"recipient" => $item['username'],
											"topic" => "false",
											"body" => $message,
											"rand" => (float)rand()/(float)getrandmax()
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending instant message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendIMURL;
						$url_referer = $this->profileURL.$item['username']."?cf=regular";
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						$content = json_decode($content);

						if($content->message_sent==1)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
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
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				} */
				$this->cancelReservedUser($item['username']);
				$this->sleep(2);
				
				/* Logout after send x message completed */
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
				/* End of logout */
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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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

	private function cancelReservedUser($username)
	{
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE username='".$username."' AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		while($list = $this->getOutboxMessages($username, $cookiePath))
		{
			$this->savelog("Found ".count($list)." outbox messages.");
			foreach($list as $message)
			{
				$this->deleteOutboxMessage($username, $message, $cookiePath);
			}
		}
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "outbox", $content);

		if(isset($parser->document->ul[0]) && isset($parser->document->ul[0]->li))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$message = array(
									"message" => str_replace("checkbox_","",$item->input[0]->tagAttrs['id'])
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL."?".http_build_query($delete_arr), $this->deleteOutboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "inbox", $content);

		if(isset($parser->document->ul[0]) && isset($parser->document->ul[0]->li))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$message = array(
									"message" => str_replace("checkbox_","",$item->input[0]->tagAttrs['id'])
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteInboxURL."?".http_build_query($delete_arr), $this->deleteInboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}
	
	public function getNewProfile($forceNew = FALSE) {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$this->loginArr = array();
	
		$this->savelog("Site ID : ". $this->siteID);		
		
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
			
			$row = botutil::getNewProfile($this->siteID, $username, $this->command);
			$fetch[0] = $row;
			
		}else{

			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (username='".$username."') LIMIT 1";
			$fetch = DBConnect::assoc_query_2D($sql);
			
		}
		
		if(empty($fetch[0]['username'])) {
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!! / Debug : " . $sql);
			$this->savelog('FINISHED');
			die();
		} else {
			$this->loginArr = array();
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

	public function getUKProxy() {
		$n = rand(1,1000);
		if(($n%2) == 0) {
			$content = $this->getHTTPContent('http://free-proxy-list.net/uk-proxy.html', 'http://free-proxy-list.net');
		} else {
			$content = $this->getHTTPContent('http://www.us-proxy.org/', 'http://www.us-proxy.org/');
		}
		if(!empty($content)) {
			$html = str_get_html($content);
			$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
			$pdo = new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot", $options);
			$i = 0;
			$sql = 'SELECT MIN(count) as max_count FROM proxy WHERE country = :country LIMIT 1';
			$sth = $pdo->prepare($sql);
			$sth->execute(array(':country' => 'uk'));
			$row = $sth->fetch(PDO::FETCH_ASSOC);
			$max_count = $row['max_count'];
			$data = array();
			foreach($html->find('tbody',0)->find('tr') as $tr) {
				
				$host = trim($tr->find('td',0)->plaintext);
				$port = trim($tr->find('td',1)->plaintext);
				$type = 'http';
				
			
				// Checking ?
				$sql = 'SELECT COUNT(*) as total_rows FROM proxy WHERE host = :host AND port = :port AND country = :country';
				$sth = $pdo->prepare($sql);
				$sth->execute(array(':host' => $host, ':port' => $port, ':country' => 'uk'));
				$row = $sth->fetch(PDO::FETCH_ASSOC);
				
				if($row['total_rows'] == 0) {
					$sql = 'INSERT INTO `proxy` (`host`, `port`, `type`, `count`, `latency`, `country`) VALUES (:host, :port, :type, :count, :latency, :country)';
					$sth = $pdo->prepare($sql);
					$sth->execute(array(
						':host' => $host,
						':port' => $port,
						':count' => $max_count,
						':type' => 'http',
						':latency' => 1,
						':country' => 'uk'
					));
					$i++;
				}
			}
			$this->savelog('Added '.$i.' UK Proxy into Databases');
		}
	}

	public function testLogin($profile) {
			
		$default_proxy = $this->command['proxy_type'];
		
		$this->command['proxy_type'] = 2;
		$this->setProxy();
		$loginRetry = 2;
		
		$this->userAgent = botutil::getAgentString();
		$username = time().'-'.$profile['username'];
		$cookiePath = $this->getCookiePath($username);
		
		if(!($this->isLoggedIn($username)))
		{
			$loginArr = array(
					'username' => $profile['username'],
					'password' => $profile['password'],
					'okc_api' => 1
			);
			for($count_login=1; $count_login<=$loginRetry; $count_login++)
			{
				$content = $this->getHTTPContent($this->loginURL, $this->indexURL, $cookiePath, $loginArr, array(
					'X-Requested-With: XMLHttpRequest'
				));
				$json = json_decode($content);

				if(empty($content)) {
					$loginRetry++;
				}
				elseif($json->status != 0)
				{
					if($count_login>($loginRetry-1))
					{
						return FALSE;
					}
					else
					{
						sleep(3);
					}
				}
				else
				{
					return TRUE;
				}
			}
		}
		else
		{
			return TRUE;
		}
	}
}
?>
