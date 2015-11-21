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
	private $_table_prefix = 'germany_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.germany.ru/';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	public $logged = FALSE;
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
						'username' => 'PollyPeachum',
						'password' => 'LuckyOne1'
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
				"age_from" => 59,
				"age_to" => 60,
				"gender" => 'all',
				"status" => "all",
				"country" => 81,
				"postcode" => "91220",
				// "action" => "check"
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 10,
				'version' => 1,
				'disabled_tor' => 0,
				'sort' => 1
				// 'online' => 1
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 149;
		}
		$this->usernameField = 'Username';
		$this->loginURL = "http://www.germany.ru/cgi-bin/portal/login.cgi";
		$this->loginActionURL = 'http://www.germany.ru/cgi-bin/portal/login.cgi';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = 'http://www.germany.ru/cgi-bin/portal/logout.cgi';
		$this->indexURL = 'http://www.germany.ru/deutsch/';
		$this->indexURLLoggedInKeyword = 'logout.cgi';
		$this->searchURL = 'http://gaycomy.de/index.php?dll=search&sub=advanced';
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 12;
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
		if($this->command['gender'] == 'f'){
			$this->target = "Female";
		}
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		if(!empty($_GET['test'])) {
			$this->command['test'] = 1;
			$this->command['test_username'] = $_GET['test_username'];
		}
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				'Login' => 'Login',
				'Password' => $user['password'],
				'Username' => $user['username'],
				'remember_me'=> 'on'
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
			$content = $this->getHTTPContent($item['profile_url'], $this->rootDomain, $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$text = botutil::getMessageText($this, $this->target);
			$subject = $text['subject'];
			$message = $text['message'];

			$this->savelog("Message is : ".$message);
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
				$html = str_get_html($content);
				if(!empty($html->find('div.list_cell1',0)->find('a',0)->href)) {
					$message_url = $html->find('div.list_cell1',0)->find('a',0)->href;
					if(strpos($message_url,'dialog')) {
						$content = $this->getHTTPContent($message_url, $item['profile_url'], $cookiePath);
						$html = str_get_html($content);
						$message_arr = array(
							'BackEditing' => '',	
							'Cat' => '',	
							'Message' => $message,
							'Sender' => $html->find('input[name=Sender]',0)->value,
							'Username' => $html->find('input[name=Username]',0)->value,
							'senderid' => $html->find('input[name=senderid]',0)->value,
						);
						
						// POST
						$content = $this->getHTTPContent('http://my.germany.ru/cgi-bin/my/dialogsendmessage.cgi', $message_url, $cookiePath, $message_arr);
						$html = str_get_html($content);
						
						
						// Nachricht gesendet 
						if(strpos($html->find('div[id=msgbox]',0)->innertext, $username)) {
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
						$this->savelog('Skipped : '.$item['username'].' enable private message for some peoples / '.$message_url);
					}
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
		$this->savelog("Job criterias => Target gender ".$this->target);
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH Online Users page.");
		$this->sleep(5);
		
		$page=1;
		$list=array();
		$first_username = '';
		$age = '';
		do {
			
			$content = $this->getHTTPContent('http://www.germany.ru/cgi-bin/portal/online.cgi?page='.$page, $this->indexURL, $cookiePath);
			
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
								if($item['gender'] != $this->command['gender']) {
									$this->savelog("Skipped : ".$item['username'].' ('.$item['gender'].')');
								} else {
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
		$list = array();
		if(!empty($content)) {
			$html = str_get_html(str_replace('<a></td>', '</a></td>', $content));
			if($html->find('table.list_table',0)){
				$table = $html->find('table.list_table',0);
				foreach($table->find('tr') as $tr) {
					if($tr->class != 'leftnav_header') {
						$anchor = $tr->find('td',3)->find('a',0);
						$gender = (strpos($tr->find('td',2)->outertext,'status_male')) ? 'male' : 'female';
						$list[] = array(
							'username' => $anchor->plaintext,
							'profile_url' => $anchor->href,
							'gender' => $gender
						);
					}
				}
			}
		}
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
		// $fetch = DBConnect::assoc_query_2D("SELECT * FROM user_profiles WHERE status != 'false' AND site_id=".$this->siteID." AND in_use = 'false' ORDER BY rand() LIMIT 1");
		
		
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
			
			$row = botutil::getNewProfile($this->siteID, $username, $this->command);
			$fetch[0] = $row;
		}else{

			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (username='".$username."') LIMIT 1";
			$fetch = DBConnect::assoc_query_2D($sql);
		}
		
		//$this->savelog('Debug SQL : '.$sql);
		
		
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
	
	private function generateRandomString($length = 10) {
	    return substr(str_shuffle("123456789"), 0, $length);
	}
	
	public function checkTargetProfile($profile = '') {
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.germany.ru/cgi-bin/portal/showusers.cgi?Cat=&Menu1=username&Sort_key=&search_val='.$profile, $this->rootDomain, NULL);
			if(!strpos($content, $profile.'</a>')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}