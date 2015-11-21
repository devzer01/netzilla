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
	private $_table_prefix = 'berlinerliebe_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://berlinerliebe.de';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	
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
			if(empty($this->command['messages_per_hour'])){
				$this->command['messages_per_hour'] = 20;
			}
			$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
				"profiles" => array(
								array(
								'username' => 'Wroke199',
								'password' => 'Kappel412'
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
				"messages_per_hour" => 30,
				"messages_logout" => 1,
				"gender" => 1,
				"around" => 0,
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 'Y',
				'login_by' => 1,
				'version' => 1,
				'proxy_type' => 1,
				'online' => 0,
				'age_from' => 70,
				'age_to' => 70
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 98;
		}
		$this->usernameField = 'F_NICKNAME';
		$this->loginURL = "http://berlinerliebe.de/de_DE/community_login";
		$this->loginActionURL = 'http://berlinerliebe.de/de_DE/community_login';
		$this->loginRefererURL = "http://berlinerliebe.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "";
		$this->indexURL = "http://berlinerliebe.de/";
		$this->indexURLLoggedInKeyword = 'Logout';
		$this->searchURL = "";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 15;
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
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		
		$target = "Female";
		if($this->command['gender'] == "1"){
			$target = "Male";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
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
				'F_INKOGITO' => '0',
				'F_NICKNAME' => $user['username'],
				'F_PASSWD' => $user['password'],
				'F_SUBMIT.x' => rand(29,32),
				'F_SUBMIT.y' => rand(12,16),
				'login_form' => 'true'
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
			$this->savelog("Go to profile page: ".$item['username'].' / UID : '.$item['uid']);
			$content = $this->getHTTPContent(
				'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_showprofile?uID='.$item['uid'], 
				'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search', $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->message)-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
			{
				//RANDOM SUBJECT AND MESSAGE
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
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']))
			{
				$this->getHTTPContent('http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_msg_popup?uID='.$item['uid'], $item['profile_url'], $cookiePath);
				// http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_msg_popup?uID=249709
				$content = $this->getHTTPContent('http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_msg_popup?uID='.$item['uid'],
					'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_msg_popup?uID='.$item['uid'],
					$cookiePath, 
					array(
						'F_MAILRECEIPT' => 'on',
						'F_MAIL_CONTENT' => $message,
						'F_RECIPIENT' => $item['username'],
						'F_SUBJECT' => $subject,
						'F_SUBMIT' => 'E-Mail senden!',
						'email_newentry' => 'true',
						'email_newentry_form' => 'true',
						'ext' => 'email',
						'form' => 'email',
						'sp_a' => 'email',
						'uID' => $item['uid']	
					)
					,FALSE,
					array(
						'Content-Type: application/x-www-form-urlencoded'
					)
				);
				
				// Deine E-Mail-Nachricht wurde erfolgreich versendet!
				if(strpos($content, 'erfolgreich versendet')) {
					DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
					$this->savelog("Sending message completed.");
					$this->lastSentTime = time();
					$this->sendmsg_total++;
				}
				else
				{
					$this->lastSentTime = time();
					$this->savelog("Sending message failed.");
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
			
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = '';
		$this->savelog("Job criterias => Target age: ". $this->command['age_from'] ." to ". $this->command['age_to']);
		$this->savelog("Job started.");

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
				$content = $this->getHTTPContent(
					'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search',
					'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_start',
					$cookiePath
				);
				$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					'F_AGE_FROM' => $age,
					'F_AGE_TO' => $age,
					'F_ALCOHOLICS' => 0,
					'F_CHILDREN' => 0,
					'F_CHILDREN_WISH' => 0,
					'F_DO_SEARCH' => 'true',
					'F_GENDER' => ((empty($this->command['gender'])) ? 1 : $this->command['gender']),
					'F_HEIGHT_FROM' => '',
					'F_HEIGHT_TO' => '',
					'F_MDA_KIND' => 0,
					'F_MDA_STATE' => 0,
					'F_MDA_WHAT' => 0,
					'F_NICKNAME' => '',	
					'F_ONLINER' => ((empty($this->command['F_ONLINER'])) ? 2 : $this->command['F_ONLINER']),
					'F_POSTCODE_USER' => ((empty($this->command['F_POSTCODE_USER'])) ? 'PLZ eingeben' : $this->command['F_POSTCODE_USER']),
					'F_SMOKER' => 0,
					'F_SUBMIT_PARAM' => 'Suchen',
					'autocomp_usrNick_field' => -1,
					's_sort' => 'desc',
					's_type' => 'cm',
				);

				/**
					END PRE SEARCH
				**/
				if($page == 1){
					$content = $this->getHTTPContent(
						'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search', 
						'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search', 
						$cookiePath,
						$search_arr,
						FALSE,
						array(
							'Content-Type: application/x-www-form-urlencoded'
						));
				} else {
					$offset = (($page - 1)* 15);
					$content = $this->getHTTPContent(
						'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search?s_persist=1&s_type=cm&F_WITHPIC=0&s_offset='.$offset, 
						'http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search?s_persist=1&s_type=cm&F_WITHPIC=0&s_offset='.($offset-15), 
						$cookiePath
					);
				}
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
		$this->searchMember();
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if(!empty($html->find('a.userimage',0))){
				foreach($html->find('a.userimage') as $anchor) {
					if($anchor->href == '#suchbox_begin'){
						$text = str_replace(array('usr_showprofile',"(",")",';',"'") ,'', $anchor->onclick);
						$data = explode(',', $text);
						$list[] = array(
							'uid' => trim(str_replace('(', '',$data[0])),
							'username' => trim($data[1]),
							'profile_url' => $this->indexURL
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
				$content = $this->getHTTPContent($this->loginActionURL, $this->rootDomain, $cookiePath, $this->loginArr[$this->currentUser], FALSE, array(
					'Content-Type: application/x-www-form-urlencoded'
				));
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
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!!");
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
			$content = $this->getHTTPContent('http://berlinerliebe.de/de_DE/'.$this->user_name.'/usr_search', $this->rootDomain, $cookiePath , array(
				'F_AGE_FROM' => '',	
				'F_AGE_TO' => '',	
				'F_ALCOHOLICS' => 0,
				'F_CHILDREN' => 0,
				'F_CHILDREN_WISH' => 0,
				'F_DO_SEARCH' => 'true',
				'F_GENDER' => 1,
				'F_HEIGHT_FROM' => '',		
				'F_HEIGHT_TO' => '',		
				'F_MDA_KIND' => 0,
				'F_MDA_STATE' => 0,
				'F_MDA_WHAT' => 0,
				'F_NICKNAME' => $profile,
				'F_ONLINER' => 2,
				'F_POSTCODE_USER' => 'PLZ eingeben',
				'F_SMOKER' => 0,
				'F_SUBMIT_NICK' => 'Suchen',
				'autocomp_usrNick_field' => -1,
				's_sort' => 'desc',
				's_type' => 'cm'
			));
			// Keine Mitglieder zu den gegebenen Eingaben gefunden
			if(!strpos($content,'Keine Mitglieder')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}