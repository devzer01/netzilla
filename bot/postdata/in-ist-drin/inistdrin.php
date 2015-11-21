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

class inistdrin extends bot
{
	private $_table_prefix = 'inistdrin_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.in-ist-drin.de/';
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
			if(empty($this->command['messages_per_hour'])){
				$this->command['messages_per_hour'] = rand(20,30);
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
													'username' => 'annabellsss',
													'password' => '741236987'
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
									"age_from" => 20,
									"age_to" => 65,
									"gender" => 'm',
									"status" => "all",
									"country" => 81,
									//"action" => "check"
									"action" => "send",
									"wait_for_login" => 1,
									'logout_after_sent' => 10,
									'version' => 1
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 86;
		}
		$this->usernameField = 'username';
		$this->loginURL = "";
		$this->loginActionURL = '';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = "";
		$this->indexURL = "http://www.in-ist-drin.de/";
		$this->indexURLLoggedInKeyword = 'Ausloggen';
		$this->searchURL = "";
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
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();

		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		$target = "Male";
		if($this->command['gender'] == 'w'){
			$target = "Female";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}
		
		parent::bot();
	}

	public function pageURL($page = 'vaqrk') {
		$data = base64_encode('&hfvq='.$this->_session_id.'&frvgr='.$page);
		return $this->rootDomain.'bereich_mitglieder/index.php?p='.str_replace('=', '-3D', $data);
	}
	
	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				"token" => '',
				"username" => $user['username'],
				"password" => $user['password'],
				"Abschicken2" => "Login"
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
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
					
				$content = $this->getHTTPContent($item['message_url'], $this->searchURL, $cookiePath);
				if(!empty($content)){
					$html = str_get_html($content);
					if(!empty($html->find('form[id=nachrichtformular]',0))){
						$html->find('form[id=nachrichtformular]',0)->action;
						$message_arr = array(
							$html->find('input[id=empfaenger]',0)->name => $html->find('input[id=empfaenger]',0)->value,
							$html->find('textarea[id=nachricht]',0)->name => $message,
							'token' => $html->find('input[id=token]',0)->value,
							'entwurf' => $html->find('input[id=entwurf]',0)->value,
							'nachrichtEntwurfID' => $html->find('input[name=nachrichtEntwurfID]',0)->value,
							'' => 'Nachricht senden'
						);
						$content = $this->getHTTPContent($this->rootDomain.'bereich_mitglieder/'.$html->find('form[id=nachrichtformular]',0)->action,$item['message_url'], $cookiePath, $message_arr);
						if(strpos($content,'Nachricht wurde erfolgreich versendet')) {
							DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->savelog("Sending message completed.");
							$this->lastSentTime = time();
							$this->sendmsg_total++;
						}
						else
						{
							$this->savelog("Sending message failed.");
						}
					}
					else{
						$this->savelog("Sending message failed.");
					}
				}
				else {
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
			if($this->command['version']==1){
				if($this->command['logout_after_sent'] == "Y"){
					if($this->sendmsg_total >= $this->command['messages_logout']){
						$this->sendmsg_total = 0;
						$this->logout();
						$this->savelog('Logout after sent '.$this->command['messages_logout'].' messages(s) completed');
						$this->savelog("Get a new Profile for Send Message");
						$this->getNewProfile();
						$this->sleep(($this->command['wait_for_login']*60));
						$this->login();
						break 2;
					}
				}
			}
			/* End of logout */
		}
	}
	
	private function searchMember() {
			
		$content = '';
		$this->savelog("Job criterias => Target age: ".((empty($this->command['altervon'])) ? $this->command['age_from'] : $this->command['altervon'])." to ".((empty($this->command['alterbis'])) ? $this->command['age_to'] : $this->command['alterbis']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);

		if(!empty($this->command['altervon'])) {
			$this->command['age_from'] = $this->command['altervon'];
		}
		if(!empty($this->command['alterbis'])) {
			$this->command['age_to'] = $this->command['alterbis'];
		}
		
		$plz_list = array('01067',
			'02625',
			'04315',
			'08525',
			'12621',
			'18069',
			'18437',
			'20253',
			'23566',
			'24837',
			'28213',
			'30179',
			'50937',
			'52066',
			'60528',
			'69126',
			'81829',
			'85051',
			'88212',
			'99089');
		$plz = '';
		$index = (empty($this->command['start_plz'])) ? 0 : $this->command['start_plz'];
		$count = count($plz_list);
		for($a = 0; $a <= $count; $a++) {
			
			if(!empty($plz_list[$index])){
				$plz = $plz_list[$index];
			} else {
				$index = 0;
				$plz = $plz_list[$index];
			}
			
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
						'geschlecht' => (empty($this->command['gender']) ? 'm' : $this->command['gender']), // (Looking For) => * = All , w = Woman , m = man
						'rubrik' => '*',
						'altervon' => $age,
						'alterbis' => $age,
						'ISO' => 'DE',
						'plz' => (empty($this->command['plz']) ? $plz : $this->command['plz']),
						'plzumkreis' => (empty($this->command['plzumkreis']) ? 25 : $this->command['plzumkreis']),
						'bild' => '*',
						'stichwort' => '',
						'raucher' => '*',
						'schulbildung' => 0,
						'haarfarbe' => 0,
						'groesse' => '*',
						'augenfarbe' => 0,
						'kinder' => '*',
						'sternzeichen' => '*'
					);
	
					/**
					 	END PRE SEARCH
					**/
					$content = '';
					if($page == 1) {
						$this->nextSearchPage = '';
						$formAction = '';
						do {
							$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);
							if(!empty($content)) {
								$html = str_get_html($content);
								if(!empty($html->find('form[id=Formular]',0))){
									$formAction = $this->rootDomain.'bereich_mitglieder/'.$html->find('form[id=Formular]',0)->action;
								}
								if(!empty($formAction)){
									$content = $content = $this->getHTTPContent($formAction, $this->searchURL, $cookiePath, $search_arr);
									$html = str_get_html($content);					
									if(!empty($html->find('div.pagination a',0))) {
										$this->nextSearchPage = $this->rootDomain.'bereich_mitglieder/'.str_replace('&pageNo=2','',$html->find('div.pagination a',0)->href);
									}
								}
							}
						} while(!$content);
					} else {
						if(!empty($this->nextSearchPage)) {
							$content = $this->getHTTPContent($this->nextSearchPage . '&pageNo='.$page, $this->searchURL, $cookiePath);
						}
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
						
						$this->savelog("Search for Target age: ".$age." to ".$age.", / PLZ : ".$plz." / page ".$page);
						if(count($list))
						{
							if($list[0]['username'] == $first_username)
							{
								$list = array();
								$this->savelog("Skip this page because result duplicated previous page");
								break;
							}
							if($page == 1)
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
			}
	
			$index++;
		}
		
		$this->savelog("Job completed.");
		return true;
	}
	
	private function searchOnlineMember() {
		$content = '';
		$this->savelog("Job criterias => Online Target");
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to Online SEARCH page.");
		$this->sleep(5);

		$page=1;
		$list=array();
		$first_username = '';
		do {

			/******************/
			/***** search *****/
			/******************/
			$search_arr = array(
				'auswahlgeschlecht' => (empty($this->command['gender']) ? 'm' : $this->command['gender']), // (Looking For) => * = All , w = Woman , m = man
				'auswahlalter' => (empty($this->command['auswahlalter']) ? '0025' : $this->command['auswahlalter']),
				'auswahlregion' => ''
			);

			/**
			 	END PRE SEARCH
			**/
			$this->savelog("Search Online Users, page ".$page);
			if($page == 1) {
				$formAction = '';
				do {
					$content = $this->getHTTPContent($this->onlineSearchURL, $this->searchURL, $cookiePath);
					if(!empty($content)){
						$html = str_get_html($content);
						if(!empty($html->find('form[id=filterListe]',0))){
							$formAction = $this->rootDomain.'bereich_mitglieder/'.$html->find('form[id=filterListe]',0)->action;
						}
						if(!empty($formAction)){
							$content = $content = $this->getHTTPContent($formAction, $this->searchURL, $cookiePath, $search_arr);					
							if(!empty($html->find('div.pagination a',0))) {
								$this->nextSearchPage = $this->rootDomain.'bereich_mitglieder/'.str_replace('&pageNo=2','',$html->find('div.pagination a',0)->href);
							}
						}
					}
				} while (!$content);
			} else {
				if(!empty($this->nextSearchPage)) {
					$content = $this->getHTTPContent($this->nextSearchPage . '&pageNo='.$page, $this->searchURL, $cookiePath);
				}
			}
			
			/***********************************************/
			/***** Extract profiles from search result *****/
			/***********************************************/
			$list = array();
			if(!empty($content)) {
				file_put_contents("search/".$username."-search-".$page.".html",$content);
				$list = $this->getMembersFromSearchResult($username, $page, $content, 0);
			}
			
			if(is_array($list))
			{
				
				if(count($list))
				{
					if($list[0]['username'] == $first_username)
					{
						$list = array();
						$this->savelog("Skip this page because result duplicated previous page");
						break;
					}
					if($page == 1)
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
		

		$this->savelog("Job completed.");
		return true;
	}
	

	public function work()
	{
		$online = (empty($this->command['online'])) ? 0 : $this->command['online'];
		if($online == 1) {
			$this->searchOnlineMember();
		} else {
			$this->searchMember();
		}
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if($html->find('div.profilinfos-g',0)) {
				foreach($html->find('div.profilinfos-g') as $profile){
					if($profile->find('b a',0)) {
						$userage = 0;
						$user = $profile->find('b a',0);
						$msg = $profile->find('span.icons a',0);
						$a = $profile->find('div.ortundalter',0);
						
						// Filter Age
						if(!empty($a) && $age != 0) {
							$b = explode('Jahre', $a->plaintext);
							$userage = trim($b[0]);
						}
						
						if(!empty($msg) && !empty($user) && ( $age == 0 || $age == $userage)) {
												
							$list[] = array(
								'username' => $user->plaintext,
								'profile_url' => $this->rootDomain . 'bereich_mitglieder/' . $user->href,
								'message_url' => $this->rootDomain . 'bereich_mitglieder/' . $msg->href
							);
						}
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
				if(!empty($this->proxy_control_port )){
					if($this->tor_new_identity($this->proxy_ip,$this->proxy_control_port,'bot'))
						$this->savelog("New Tor Identity request completed.");
					else
						$this->savelog("New Tor Identity request failed.");
				}
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

	public function testLogin($profile) {
			
		$default_proxy = $this->command['proxy_type'];
		$this->command['proxy_type'] = 2;
		$this->setProxy();
		$loginRetry = 1;
		$this->userAgent = botutil::getAgentString();
		$username = $profile['username'];
		$cookiePath = $this->getCookiePath($username);
		
		$this->loginRetry = 1;
		if(!($this->isLoggedIn($username)))
		{
			// $this->savelog("This profile: ".$username." does not log in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				
				$loginArr = array();
				$this->getHTTPContent($this->rootDomain, $this->indexURL, $cookiePath);
				$this->getHTTPContent('http://www.in-ist-drin.de/requests/heuteOnline.php', $this->indexURL, $cookiePath);
				$content = $this->getHTTPContent($this->rootDomain, $this->indexURL, $cookiePath);
				$formURL = '';
				if(!empty($content)){
					/**
						PRE HACK BEFORE LOGIN FOR in-ist-drin.de
					**/
					$html = str_get_html($content);
					if($html->find('form',0)) {
						
						$formURL = $html->find('form',0)->action;
						$this->_session_id = str_replace('login.php?SID=', '', $formURL);
						$nickname = $html->find('input[id=nickname]',0)->name;
						$passwort = $html->find('input[id=passwort]',0)->name;
						
						// Fill form
						$loginArr[$nickname] = $profile['username'];
						$loginArr[$passwort] = $profile['password'];
						$loginArr['token'] = $html->find('input[name=token]',0)->value;
					}
					/**
						END OF HACK
					*/

					$content = $this->getHTTPContent($this->rootDomain . $formURL, $this->indexURL, $cookiePath, $loginArr);
					if(!empty($content)) {
						$html = str_get_html($content);
						if((!strpos($content,"Ausloggen")) && (!empty($html->find('h1',0))) ) {
							$error = trim($html->find('h1',0)->plaintext);
						} else if((strpos($content,"Ausloggen")) && (!empty($html->find('a[title=Videos]',0))) ) {
							$encode = str_replace('index.php?p=','',$html->find('a[title=Videos]',0)->href);
							$c = explode('&frvgr=',base64_decode($encode));
							$session_id = str_replace('&hfvq=','', $c[0]);
							if(!empty($session_id) && $this->_session_id != $session_id) {
								$this->_session_id = $session_id;
								// $this->savelog("Set session id to : ".$this->_session_id);
							}											
						}
					}
					
					// Log
					if(!empty($error)) {
						// $this->savelog('Log in failed message is '.$error);
					} else {
						file_put_contents("login/".$username."-".date("YmdHis").".html",$content);
					}
				}
				if(empty($content))
				{
					// $this->savelog("= = = = = failed : No response from server. = = = = =");
					$this->setProxy();
				}
				else if(!($this->isLoggedIn($username)))
				{
					
					// $this->savelog("Log in failed with profile: ".$username);
					// $this->savelog("Log in failed $count_login times.");

					if($count_login>($this->loginRetry-1))
					{
						return FALSE;
					}
					else
					{
						$sleep_time = 1; // 2 mins
						$this->_session_id = NULL;
						// $this->savelog("Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
					
				} else {
					return TRUE;
				}
			}
		}
		else
		{
			// $this->savelog("This profile: ".$username." has been logged in.");
			return TRUE;
		}
	}
}