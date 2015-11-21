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
	private $_table_prefix = 'bookofmatches_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.bookofmatches.com';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	public $logged = FALSE;
	public $name = array();
	public $target = 'Male';
	
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
						'username' => 'amelie1987',
						'password' => 'Whatthefox1'
					)
				),
				"messages" => array(
					array(
						"subject" => "Hello",
						"message" => " ilove24 net "
					)
				),
				"start_h" => 8,
				"start_m" => 00,
				"end_h" => 23,
				"end_m" => 00,
				"messages_per_hour" => 15,
				"messages_logout" => 4,
				"a1" => 60,
				"a2" => 60,
				"gender" => 'M',
				"postcode" => "34121",
				"online" => 1,
				"around" => 0,
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 10,
				'version' => 1,
				'proxy_type' => 1,
				'age_from' => 35,
				'age_to' => 60
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 144;
		}
		$this->usernameField = 'username';
		$this->loginURL = "";
		$this->loginActionURL = 'http://www.bookofmatches.com/logon.bm';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.bookofmatches.com/?logout=1";
		$this->indexURL = "http://www.bookofmatches.com/~Home.bm";
		$this->indexURLLoggedInKeyword = 'Logoff';
		$this->searchURL = "http://www.bookofmatches.com/~Search.bm";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "";
		$this->sendMessagePageURL = "";
		$this->sendMessageURL = "";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0";
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
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		if($this->command['gender'] == 'F') {
			$this->target = 'Female';
		}
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		parent::bot();
	}

	public function resetPLZ()
	{
		$this->command['postcode'] = "00000";
	}

	public function addLoginData($users)
	{
		$this->loginArr = array();
		foreach($users as $user)
		{
			$login_arr = array(
				'externalLogon' => 'true',
				'password' => $user['password'],
				'submit' => 'Login',
				'username' => $user['username']
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
			$this->savelog("Go to profile page: ".$item['username']);
			$content = $this->getHTTPContent($item['profile_url'], $this->searchURL, $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$text = botutil::getMessageText($this, $this->target, 'EN');
			$subject = $text['subject'];
			$message = $text['message'];
			$this->savelog("Message is : ".$message);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{			
				$content = $this->getHTTPContent($item['message_url'], $item['profile_url'], $cookiePath);
				if(!empty($content)) {
					$html = str_get_html($content);
					$content = $this->getHTTPContent('http://www.bookofmatches.com/~SendMessageConfirm.bm;jsessionid='.$this->jsessionid, 
						'http://www.bookofmatches.com/~SendMessage.bm;jsessionid='.$this->jsessionid, 
						$cookiePath, 
						array(
							'hiddenRandInt' => $html->find('input[name=hiddenRandInt]',0)->value,
							'message' => $message,
							'messageSubmit' => 'Please Wait',
							'randInt' => $html->find('input[name=hiddenRandInt]',0)->value,
							'recipient' => $item['username'],
							'respondedToID' => 'null',
							'subject' => 'Hello '.$item['username']
						)
					);
					
					// Your message was sent to: fishinandshootin
					if(strpos($content, 'message was successfully')) {
						DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
						$this->savelog("Sending message completed.");
						$this->lastSentTime = time();
					}
					else
					{
						$this->savelog("Sending message failed.");
					}
					
					
					$this->sendmsg_total++;
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
					$this->savelog('Logout after sent messages for '.$this->command['messages_logout'].' time(s)');
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
	
	private function searchOnlineMember() {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = '';
		$this->savelog("Job criterias => Online Users");
		$this->savelog("Job started.");
		
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);
		$page = 0;
		$first_username = '';
		do {
			$start = ($page * $this->searchResultsPerPage);
			$content = $this->getHTTPContent('http://www.bookofmatches.com/~WhosOn.bm;jsessionid='.$this->jsessionid.'?LastLineItem='.$start.'&OrderBy=Age', $this->rootDomain, $cookiePath);
			
			$list = $this->getOnlineMembersFromSearchResult($username, $page, $content);
			if(is_array($list))
			{
				
				if(count($list))
				{
					if($list[0]['username'] == $first_username && !empty($list[0]['username']))
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
							if($item['gender'] == $this->command['gender']) {
								if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage) {
									$this->sendUserMessage($item, $username, $cookiePath);	
								} else {
									$this->savelog("Already send message to profile: ".$item['username']);
								}
							} else {
								$this->savelog('[Skipped] '.$item['username'].' not macth selected gender');
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
			
			$page++;
		} while(count($list)>=$this->searchResultsPerPage);
		$this->savelog("Job completed.");
		return true;
	}
	
	private function searchMember() {
			
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = '';
		$this->savelog("Job criterias => Target age: ". $this->command['age_from'] ." to ". $this->command['age_to']);
		$this->savelog("Job started.");
		$this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					'Activities' => '',	
					'BomStatus' => 'Any',
					'DrinkPref'	=> 'Any',
					'EndAge' => $age,
					'Ethnicity' => 'All',
					'Gender' => $this->command['gender'],
					'LastLineItem' => 0,
					'Miles' => '',
					'Region' => ((!empty($this->command['region'])) ? $this->command['region'] : 'United Kingdom (UK)'),
					'SmokePref' => 'Any',
					'StartAge' => $age,
					'Zipcode' => '',
					'quickPics' => 'On',
					'submit' => 'Search Members'
				);

				/**
					END PRE SEARCH
				**/
				if($page != 1) {
					$search_arr['LastLineItem'] = (($page-1) * $this->searchResultsPerPage);
					unset($search['submit']);
					$content = $this->getHTTPContent('http://www.bookofmatches.com/~~Search2.bm;jsessionid='.$this->jsessionid.'?'.http_build_query($search_arr), $this->searchURL, $cookiePath);
				} else {
					$content = $this->getHTTPContent('http://www.bookofmatches.com/~~Search2.bm;jsessionid='.$this->jsessionid, $this->searchURL, $cookiePath, $search_arr);
				}
				
				$html = str_get_html($content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = array();
				if(!empty($content)) {
					file_put_contents("search/".$username."-search-".$page.".html",$content);
					$list = $this->getMembersFromSearchResult($username, $page, $content);
				}
				
				
				if(is_array($list))
				{
					
					if(count($list))
					{
						if($list[0]['username'] == $first_username && !empty($list[0]['username']))
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
								// If not already sent
								if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage) {
									$this->sendUserMessage($item, $username, $cookiePath);	
								} else {
									$this->savelog("Already send message to profile: ".$item['username']);
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
		}
		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		$online = ((empty($this->command['online'])) ? 0 : $this->command['online']);
		if( $online == 1 ){
			$this->searchOnlineMember();
		} 
		else {
			$this->searchMember();
		}
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if(!empty($html->find('a.linkcontent',0))) {
				foreach($html->find('a.linkcontent') as $anchor) {
					if(strpos($anchor->href, 'UserProfile.bm')) {
						$u = explode('handle=', $anchor->href);
						$list[] = array(
							'profile_url' => $this->rootDomain . $anchor->href,
							'message_url' => 'http://www.bookofmatches.com/~SendMessage.bm;jsessionid='. $this->jsessionid,
							'username' => trim($u[1])
						);	
					}
				}
			}
		}
		var_dump($list);
		return $list;
	}
	
	private function getOnlineMembersFromSearchResult($username, $page, $content) {
		$list = array();
		$i = 1;
		if(!empty($content)){
			$html = str_get_html($content);
			if(!empty($html->find('tr.rowColor1',0))) {
				foreach($html->find('tr.rowColor1') as $rowColor) {
					foreach($rowColor->find('td') as $box) {
						if(($i%4) == 0) {
							$anchor = $box->find('a',0);
							parse_str($anchor->href, $u);
							$list[] = array(
								'profile_url' => $this->rootDomain . $anchor->href,
								'message_url' => 'http://www.bookofmatches.com/~SendMessage.bm;jsessionid='. $this->jsessionid,
								'username' => trim($anchor->plaintext),
								'gender' => trim($box->find('font.content',0)->plaintext)
							);
						}
						$i++;
					}
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
			// die('SESS ID : '.$this->_session_id);
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

	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.connectingsingles.com/user/'.$profile, $this->rootDomain, $cookiePath);
			if(strpos($content, $profile)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}