<?php

// 
require_once('bot.php');
require_once('simple_html_dom.php');

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

class botwork extends bot
{
	private $_table_prefix = 'freedating_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	private $target = 'Male';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.freedating.co.uk/';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	public $logged = FALSE;
		
	public function __construct($post)
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
			$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
				"profiles" => array(
								array(
								'username' => 'bbbamelie1987@hotmail.com',
								'password' => 'Whatthefox1'
								)
							),
				"messages" => array(
										array(
												"subject" => "Hallo",
												"message" => "Hallo"
											),
										array(
												"subject" => "Hallo",
												"message" => "Hallo"
											)
									),
				"start_h" => 8,
				"start_m" => 00,
				"end_h" => 23,
				"end_m" => 00,
				"messages_per_hour" => 30,
				"age_from" => 67,
				"age_to" => 68,
				"gender" => 'M',
				"status" => "all",
				"country" => 81,
				"postcode" => "91220",
				//"action" => "check"
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 10,
				'version' => 1,
				'disabled_tor' => 0,
				'online' => 1
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 111;
		}
		$this->usernameField = 'email';
		$this->loginURL = "http://www.freedating.co.uk/";
		$this->loginActionURL = 'http://www.freedating.co.uk/engine.php';
		$this->loginRefererURL = "http://www.freedating.co.uk/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.freedating.co.uk/engine.php?action=logout";
		$this->indexURL = "http://www.freedating.co.uk/";
		$this->indexURLLoggedInKeyword = 'engine.php?action=logout';
		$this->searchURL = "http://www.freedating.co.uk/engine.php?action=search";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "";
		$this->sendMessagePageURL = "h";
		$this->sendMessageURL = "";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:26.0) Gecko/20100101 Firefox/26.0";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		$this->botID = $botID;
		$this->siteID = $siteID;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->subject="";
		$this->message="";
		$this->newMessage=true;

		if($this->command['gender'] == '2'){
			$this->target = "Female";
		}
				
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
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
				'action' => 'Login',
				'email' => $user['username'],
				'fraw' => '',
				'password' => $user['password'],
				'remember_me' => 1,
				'timezone_offset' => '-0|-0'
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	private function sendUserMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']);
		if($this->reserveUser($item['username']))
		{
			// Go to profile page
			$this->savelog("Go to profile page: ".$item['username']." / ".$item['profile_url']);
			$content = $this->getHTTPContent($item['profile_url'], $this->searchURL, $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$text = botutil::getMessageText($this, $this->target, 'EN');	
            $subject = $text['subject'];
            $message = $text['message'];
            $this->savelog("Message is : ".$message);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
				if(!empty($content)) {
					$html = str_get_html($content);
					$message_arr = array(
						'action' =>'Send Message',
						'backlink' => '',	
						'backtext' => '',
						'message' => $message,
						'page' => ((!empty($html->find('input[name="page"]',0))) ? $html->find('input[name="page"]',0)->value : ''),
						'recipient_id' => ((!empty($html->find('input[name="recipient_id"]',0))) ? $html->find('input[name="recipient_id"]',0)->value : ''),
						'screen_name' => ((!empty($html->find('input[name="screen_name"]',0))) ? $html->find('input[name="screen_name"]',0)->value : ''),
						'sort_by' => ((!empty($html->find('input[name="sort_by"]',0))) ? $html->find('input[name="sort_by"]',0)->value : ''),
						'timezone_offset' => '-420|-420'
					);
					$content = $this->getHTTPContent('http://www.freedating.co.uk/engine.php', $item['profile_url'], $cookiePath, $message_arr);
					
					if(strpos($content, 'Your message has been sent to')) {
						DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
						$this->savelog("Sending message completed.");
						$this->lastSentTime = time();
						$this->sendmsg_total++;
					}
					else
					{
						$this->savelog("Sending message failed.");
					}
					
					if(!empty($this->command['test'])) {
						$this->savelog("Sent to Test Users is completed and Exit");
						$this->savelog('FINISHED');
						$this->cancelReservedUser($item['username']);
						exit();
					}
				} else {
					$this->savelog("No server response");
				}
			}
			else
			{
				$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
			}
			
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
	
	private function searchMember($online = 0) {
			
		$content = '';
		$this->savelog("Job criterias => Target age: ". $this->command['age_from']." to ".$this->command['age_to']);
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = $this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);
		
		
		for($age = $this->command['age_from']; $age <= $this->command['age_to']; $age++ )
		{
			
			$page=1;
			$list=array();
			$first_username = '';
			$cfid = '';
			do
			{
				
				/******************/
				/***** search *****/
				/******************/
				if($this->command['online'] == 1) {
					$this->savelog("Search for ONLINE Users / page ".$page);
				} else {
					$this->savelog("Search for Target age: ".$age." to ".$age." / page ".$page);
				}
				
				if($page == 1) {
					$content = $this->getHTTPContent(
						'http://www.freedating.co.uk/engine.php?'.http_build_query(
							array(
								'action' => 'Search',
								'postcode' => ((!empty($this->command['postcode'])) ? $this->command['postcode'] : 'M1 1AA'),
								'required_age_max' => $age,
								'required_age_min' => $age,
								'required_distance' => '9999',
								'required_photo' => ((!empty($this->command['required_photo'])) ? 1 : 0),
								'required_sex' => (($this->command['gender'] == 'female') ? 'female' : 'male'),
								'sex' => (($this->command['gender'] == 'female') ? 'male' : 'female')
							)
						), 
						$this->searchURL, 
						$cookiePath);
				} else { // Online Users
					$content = $this->getHTTPContent(
						'http://www.freedating.co.uk/engine.php?action=search&sort_by=last_active&page='.$page, 
						$this->rootDomain, 
						$cookiePath);
				}
				
				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = array();
				if(!empty($content)) {
					file_put_contents("search/".$username."-search-".$page.".html",$content);
					$list = $this->getMembersFromSearchResult($username, $page, $content, $age);
				}
				
				
				if(is_array($list))
				{
					
					if(count($list))
					{
						if($list[0]['username'] == $first_username && $first_username != '')
						{
							$list = array();
							$this->savelog("Skip this page because result duplicated previous page");
							break;
						}
						else
						{
							$first_username = $list[0]['username'];
						}
						
						$this->savelog("Found ".count($list)." member(s)");
						
						$this->sleep(5);
						$enableMoreThanOneMessage = FALSE;
						foreach($list as $item)
						{
													
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								if(!empty($this->command['test'])) {
									if($item['username'] == $this->command['test_username']) {
										$this->savelog("[Test] Found target profile : ".$item['username']);
										$this->sendUserMessage($item, $username, $cookiePath);	
									} else {
										$this->savelog("[Test] Skipped : ".$item['username']);
									}
								} else {
									// If not already sent
									if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
									{
										$this->sendUserMessage($item, $username, $cookiePath);	
									}
									else
									{
										$this->savelog("Already send message to profile: ".$item['username']);
									}
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

				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database
				
				$page++;
			}
			while(count($list)>=$this->searchResultsPerPage);
			
			$next_cid++;
		}

		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		$this->searchMember();
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$html = str_get_html($content);
		$list = array();
		$i = 0;
		foreach($html->find('a') as $anchor) {
			if($i < $this->searchResultsPerPage) {
				if(strpos($anchor->href, 'search.profile') && $anchor->plaintext != '') {
					$list[] = array(
						'profile_url' => 'http://www.freedating.co.uk/'.$anchor->href,
						'username' => $anchor->plaintext,
					);
					$i++;
				}
			}
		}
		var_dump($list);
		return $list;
	}

	public function getAction()
	{
		return $this->command['action'];
	}

	public function getSiteID()
	{
		return $this->siteID;
	}
	
	
	/**
	 * This function use for Check Profile !!
	 */
	public function checkLogin($username, $password)
	{
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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->_table_prefix."sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->_table_prefix."reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->_table_prefix."reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ".$this->_table_prefix."reservation WHERE username='".$username."' AND server=".$this->botID);
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
	
	public function testSuspended($username) {
		$cookiePath = $this->getCookiePath($username);
		$content = $this->getHTTPContent('http://www.freedating.co.uk/', $this->loginRefererURL, $cookiePath);
		if(strpos($content, 'Account Suspended')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
	
	public function testLogin($profile) {
			
		$default_proxy = $this->command['proxy_type'];
		$this->command['proxy_type'] = 2;
		$this->setProxy();
		$loginRetry = 2;
		$this->userAgent = botutil::getAgentString();
		$username = $profile['username'];
		$cookiePath = $this->getCookiePath($username);

		if(!($this->isLoggedIn($username)))
		{
			$this->loginArr = array();
			$this->addLoginData(array(
				array(
					'username' => $profile['username'],
					'password' => $profile['password']
				)
			));
			
			for($count_login=1; $count_login<=$loginRetry; $count_login++)
			{
				$content = $this->getHTTPContent($this->loginActionURL, $this->loginRefererURL, $cookiePath, $this->loginArr[0]);
				if(empty($content))
				{
					$loginRetry++;
				}
				elseif(!($this->isLoggedIn($username)))
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