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
	private $_table_prefix = 'ratemybody_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	private $target = 'Male';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.ratemybody.com';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	public $logged = FALSE;
	public $name = array();
	
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
					'username' => 'annabellsss',
					'password' => '1qazxsw2'
					)
				),
				"messages" => array(
					array(
						"subject" => "Hallo",
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
				"gender" => '2',
				"postcode" => "34121",
				"online" => 0,
				"around" => 0,
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 10,
				'version' => 1,
				'proxy_type' => 1,
				'online' => 0,
				'age_from' => 35,
				'age_to' => 60
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 60;
		}
		$this->usernameField = 'nameOnline';
		$this->loginURL = "http://www.ratemybody.com/login.aspx?loggedin=no";
		$this->loginActionURL = 'http://www.ratemybody.com/login.aspx?loggedin=no';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.ratemybody.com/logout.aspx";
		$this->indexURL = "http://www.ratemybody.com/";
		$this->indexURLLoggedInKeyword = 'LOGOUT';
		$this->searchURL = "http://www.ratemybody.com/search.aspx";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "";
		$this->sendMessagePageURL = "h";
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
		
		if($this->command['gender'] == '2') {
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
		foreach($users as $user)
		{
			$login_arr = array(
				'nameOnline' => $user['username'],
				'password' => $user['password']
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	private function sendUserMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']. ' / UID : '. $item['uid']);
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
			$text = botutil::getMessageText($this, $this->target, 'EN');
            $subject = $text['subject'];
            $message = $text['message'];
			$this->savelog("Message is : ".$message);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
				// $content = $this->getHTTPContent('http://www.ratemybody.com/loggedin/instant_message.aspx?messageTo='.$item['username'].'&messageToID='.$item['uid'], $item['profile_url'], $cookiePath);
				
				$content = $this->getHTTPContent('http://www.ratemybody.com/mailbox/message_write.aspx?messageTo='.$item['username'].'&messageToID='.$item['uid'].'&redir=1', $item['profile_url'], $cookiePath);
				if(!empty($content)) {
					$html = str_get_html($content);
					/* $content = $this->getHTTPContent('http://www.ratemybody.com/loggedin/instant_message.aspx?messageTo='.$item['username'].'&messageToID='.$item['uid'], $item['profile_url'], $cookiePath, array(
						'__EVENTVALIDATION' => $html->find("input[id='__EVENTVALIDATION']",0)->value,
						'__VIEWSTATE' => $html->find("input[id='__VIEWSTATE']",0)->value,
						'btnSubmit' => 'Send Message',
						'message' => $message,
						'messageTo' => $item['username'],
						'remLen' => (int) (990-strlen($message))
					)); */
					
					$content = $this->getHTTPContent('http://www.ratemybody.com/mailbox/message_write.aspx?messageTo='.$item['username'].'&messageToID='.$item['uid'].'&redir=1', $item['profile_url'], $cookiePath, array(
						'Subject' => 'Hello '.$item['username'],
						'__EVENTVALIDATION' => $html->find("input[id='__EVENTVALIDATION']",0)->value,
						'__VIEWSTATE' => $html->find("input[id='__VIEWSTATE']",0)->value,
						'btnSendMail' => 'Send Mail',
						'message' => $message,
						'prevMsg' => '',
						'redir' => 1,
						'remLen' => (int) (990-strlen($message))
					));
					
					$content = $this->getHTTPContent('http://www.ratemybody.com/mailbox/messages_sent.aspx','http://www.ratemybody.com/profile.aspx?userID='.$item['uid'].'&mailSent=yes&overRide=true', $cookiePath);
					

					// if(strpos($content, 'self.close()')) {
					if(strpos($content, $item['username'])) {
						DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
						$this->savelog("Sending message completed.");
						$this->lastSentTime = time();
					}
					else
					{
						$this->savelog("Sending message failed.");
					}
					
					
					$this->sendmsg_total++;
					
				} else {
					$this->savelog('No response');
					
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
	
	private function searchMember($online = 0) {
			
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
					'Submit' => 'Start Search Now',
					'alcohol' => '',
					'bodyType'	 => '',
					'city' => '',	
					'country' => '',	
					'education' => '',	
					'employment' => '',	
					'eyeColor' => '',	
					'gender' => ((empty($this->command['gender'])) ? 1 : $this->command['gender']),
					'hairColor' => '',
					'heightFeet' => '',	
					'heightInches' => '',	
					'maxAge' => $age,
					'maxRating' => '',
					'memberSince' => '',	
					'minAge' => $age,
					'minRating' => '',	
					'nameOnline' => '',	
					'orderBy' => 1,
					'postalCode' => '',	
					'searchName' => '',	
					'sexuality' => '',	
					'smoke' => '',	
					'specifyCity' => 'like',
					'specifyName' => 'like',
					'specifyPostal' => 'like',
					'stateProv' => '',
					'status' => ''
				);

				/**
					END PRE SEARCH
				**/
				if($online == 1) {
					$search_arr['online'] = 'on';
				}
				
				if($page != 1) {
					$search_arr['page'] = $page;
				}
				$content = $this->getHTTPContent('http://www.ratemybody.com/search_results.aspx?'.http_build_query($search_arr), $this->searchURL, $cookiePath);
				$html = str_get_html($content);

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
								if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
								{
									$this->sendUserMessage($item, $username, $cookiePath);	
								}
								else
								{
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
		$this->searchMember($this->command['online']);
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			
			if(!empty($html->find('div.comments_tablecontent1'))) {
				foreach($html->find('div.comments_tablecontent1') as $div) {
					if(!empty($div->find('a',1))) {
						$e = explode('_',$div->find('a',1)->href);
						$list[] = array(
							'uid' => $e[1],
							'profile_url' => $this->rootDomain . '/' . $div->find('a',1)->href,
							'username' => $div->find('a',1)->plaintext
						);
					}
				}
			}
			
			if(!empty($html->find('div.comments_tablecontent2'))) {
				foreach($html->find('div.comments_tablecontent2') as $div) {
					if(!empty($div->find('a',1))) {
						$e = explode('_',$div->find('a',1)->href);
						$list[] = array(
							'uid' => $e[1],
							'profile_url' => $this->rootDomain . '/' . $div->find('a',1)->href,
							'username' => $div->find('a',1)->plaintext
						);
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
}