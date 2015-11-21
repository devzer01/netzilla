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
	private $_table_prefix = 'deinsexdate_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.deinsexdate.com/';
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
			$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
									"profiles" => array(
													array(
													'username' => 'BrideOfChucky',
													'password' => 'Klene1105'
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
									"age_from" => 22,
									"age_to" => 25,
									"gender" => 2,
									"status" => "all",
									"country" => 81,
									"postcode" => "91220",
									//"action" => "check"
									"action" => "send",
									"wait_for_login" => 1,
									'logout_after_sent' => 10,
									'version' => 1,
									'disabled_tor' => 0,
									'country_code' => 'DE',
									'online' => 1
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 112;
		}
		$this->usernameField = 'username';
		$this->loginURL = "http://www.deinsexdate.com/index.php?action-userauthentication=doLoginAction&main=userauthentication&page=main";
		$this->loginActionURL = 'http://www.deinsexdate.com/index.php?action-userauthentication=doLoginAction&main=userauthentication&page=main';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = 'http://www.deinsexdate.com/index.php?action-userauthentication=logoutUser&main=userauthentication&page=main';
		$this->indexURL = 'http://www.deinsexdate.com';
		$this->indexURLLoggedInKeyword = 'Ausloggen';
		$this->searchURL = 'http://www.deinsexdate.com/index.php?main=finder&page=overview&subpage=quick_search';
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 12;
		$this->profileURL = "";
		$this->sendMessagePageURL = "";
		$this->sendMessageURL = "";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/27.0";
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

		$target = "Male";
		if($this->command['gender'] == '1'){
			$target = "Female";
		}
		
		$this->postMessageTemp = array();
		$this->preMessageTemp = array();
		
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
				'action' => 'login',
				'border' => 1,
				'login-uuid' => '',
				'main' => 'layout',
				'page' => 'main',
				'password' => $user['password'],
				'submit_image.x' => rand(30, 40),
				'submit_image.y' => rand(21, 31),
				'username' => $user['username']
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	private function getPreMessage()
	{
		if(count($this->preMessageTemp)==0)
		{
			shuffle($this->preMessage);
		}
		elseif(count($this->preMessage)==0)
		{
			$this->preMessage = $this->preMessageTemp;
			shuffle($this->preMessage);
			$this->preMessageTemp = array();
		}

		$msg = array_pop($this->preMessage);
		array_push($this->preMessageTemp, $msg);
		return $msg;
	}

	private function getPostMessage()
	{
		if(count($this->postMessageTemp)==0)
		{
			shuffle($this->postMessage);
		}
		elseif(count($this->postMessage)==0)
		{
			$this->postMessage = $this->postMessageTemp;
			shuffle($this->postMessage);
			$this->postMessageTemp = array();
		}

		$msg = array_pop($this->postMessage);
		array_push($this->postMessageTemp, $msg);
		return $msg;
	}
	
	private function keepSession($url = '') {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		if(empty($url)) {
			$url = $this->rootDomain;
		}
		$this->nullPost = 1;
		$this->getHTTPContent('http://www.deinsexdate.com/index.php?action-page=session_keepalive&main=page&view=ajax_result&r='.microtime(true), $url, $cookiePath);
	}
	
	private function sendUserMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']);
		if($this->reserveUser($item['username']))
		{
			// Go to profile page
			$this->savelog("Go to profile page: ".$item['username']);
			$content = $this->getHTTPContent($item['profile_url'], $this->rootDomain, $cookiePath);
			$this->keepSession($item['profile_url']);
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
				$html = str_get_html($content);
				
				// Crawl
				$this->nullPost = 1;
				$content = $this->getHTTPContent(
					'http://www.deinsexdate.com/index.php?main=comcentre&view=ajax_result&fetchview=bubble_message&user_id='.$item['uid'].'&target=bubbleMessageContainer', 
					$this->rootDomain, 
					$cookiePath,
					NULL,
					array(
						'X-Requested-With: XMLHttpRequest'
					)
				);

				$message_arr = array(
					'message' => $message,
					'subject' => $subject,
					'type' => 0,
					'user_id' => $item['uid']
				);
				
				// POST
				$this->keepSession();
				$content = $this->getHTTPContent(
					'http://www.deinsexdate.com/index.php?action-comcentre=sendMessage&main=comcentre&page=main&view=ajax_result&fetchview=noView&subaction=writeFromProfile', 
					$this->rootDomain, 
					$cookiePath, 
					$message_arr
				);
				
				$json = json_decode($content);
				
				// Ihre Nachricht wurde erfolgreich an antonR74 gesendet
				if($json->status != 'error') {
					DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
					$this->savelog("Sending message completed.");
					$this->lastSentTime = time();
					$this->sendmsg_total++;
				}
				else
				{
					$this->savelog("Sending message failed / REASON : ".$json->view);
				}
				
				if(!empty($this->command['test'])) {
					$this->savelog("Sent to Test Users is completed and Exit");
					$this->savelog('FINISHED');
					$this->cancelReservedUser($item['username']);
					exit();
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

	private function searchOnlineMember() {
		$content = '';
		$this->savelog("Job criterias => Search Online Users");
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$this->savelog("Go to SEARCH Online User page.");
		$this->sleep(5);
		
		$this->keepSession('http://www.deinsexdate.com/index.php?main=finder&page=wrap_online_users');
		$content = $this->getHTTPContent(
			'http://www.deinsexdate.com/index.php?main=finder&page=wrap_online_users',
			$this->rootDomain,
			$cookiePath);
			
		$page=1;
		$list=array();
		$first_username = '';
		
		
		/**
		 * PRE VALUE
		 */
		if($this->command['gender'] == 1) { // Woman
			$search_array = array(
				'limit' => 25,
				'pageindex' => ($page-1),
				'sexual_orientation_flags' => array(
					'couple' => -1,
					'man' => -1,
					'tvts' => -1,
					'woman' => -1,
					'woman' => 1
				),
				'sort_fields' => 'creation_date DESC',
				'x' => rand(21,36),
				'y' => rand(10,30)
			);
		} else {
			$search_array = array(
				'limit' => 25,
				'pageindex' => ($page-1),
				'sexual_orientation_flags' => array(
					'couple' => -1,
					'man' => -1,
					'man' => 2,
					'tvts' => -1,
					'woman' => -1,
				),
				'sort_fields' => 'creation_date DESC',
				'x' => rand(21,36),
				'y' => rand(10,30)
			);
		}
			
		do
		{
			$this->savelog("Search for Online Users page ".$page);
			
			if($page != 1) {
				$search_array['pageindex'] = ($page-1);
				unset($search_array['x']);
				unset($search_array['y']);
			}
			
			
			$content = $this->getHTTPContent(
				'http://www.deinsexdate.com/index.php?action-finder=filterLastFind&main=finder&page=wrap_online_users&pageindex='.($page-1), 
				$this->rootDomain, 
				$cookiePath,
				$search_array
			);
			$this->keepSession('http://www.deinsexdate.com/index.php?action-finder=filterLastFind&main=finder&page=wrap_online_users');
			
			$list = array();
			if(!empty($content)) {
				file_put_contents("search/".$username."-search-".$page.".html",$content);
				$list = $this->getMembersFromSearchResult($username, $page, $content);
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
		} while(count($list)>=$this->searchResultsPerPage);
		
		$this->savelog("Job completed.");
		return true;
	}
	
	private function searchMember() {
			
		$content = '';
		$this->savelog("Job criterias => Target age: ". $this->command['age_from']." to ".$this->command['age_to']);
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);
		
		$this->keepSession();
		$content = $this->getHTTPContent(
			$this->searchURL,
			$this->rootDomain,
			$cookiePath);
		$html = str_get_html($content);
		
		for($age = $this->command['age_from']; $age <= $this->command['age_to']; $age++ )
		{
			
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				
				/******************/
				/***** search *****/
				/******************/
				$this->savelog("Search for Target age: ".$age." to ".$age." / page ".$page);
				
				if($page == 1) {
						$search_arr = array(
							'age_range' => $age.'-'.$age,
							'border' => 1,
							'country_code' => $this->command['country_code'],
							'gender_flags' => (($this->command['gender'] == 2) ? 1 : 2),
							'limit' => 24,
							'main' => 'finder',
							'page' => 'find_by_quicksearch',
							'prefer_hot' => 'true',
							'sexual_orientation_flags' => array(
								$this->command['gender']
							),
							'sort_fields' => 'creation_date desc',
							'subpage' => 'quick_search',
							'user_id' => '',
							'view_style' => 'big',
							'with_image_only' => 0,
							'with_image_only' => 1,
							'x' => rand(119,129),
							'y' => rand(10,20),
							'zipcode' => '',
						);
						
						$this->keepSession();
						$content = $this->getHTTPContent(
							$html->find('form[id="right_column_search"]',0)->action, 
							$this->searchURL, 
							$cookiePath,
							$search_arr);
						
				} else {
					
					$this->keepSession();
					$content = $this->getHTTPContent(
						'http://www.deinsexdate.com/index.php?action-finder=filterLastFind&main=finder&page=find_by_quicksearch&pageindex='.($page-1), 
						$this->rootDomain, 
						$cookiePath,
						array(
							'haarfarbe[]}' => '',	
							'limit' => 24,
							'pageindex' => ($page-1),
							'sort_fields' => 'creation_date desc',
							'stored_search_id' => '',
							'subpage' => 'quick_search',
							'view_style' => 'big',
							'with_image_only' => 0,
						));
				}
				

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
		}

		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		$online = 0;
		if(!empty($this->command['online'])) {
			$online = 1;
		}
		
		if($online == 1) {
			$this->searchResultsPerPage = 25;
			$this->searchOnlineMember();
		} else {
			$this->searchResultsPerPage = 24;
			$this->searchMember();
		}
		
	}
	
	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		if(!empty($content)) {
			$html = str_get_html($content);
			if(!empty($html->find('ul.profileList',0))) {
				$ul = $html->find('ul.profileList',0);
				if(!empty($ul->find('li',0))){
					
					foreach($ul->find('li') as $li) {
						$uid = $li->{'data-user-id'};
						$list[] = array(
							'profile_url' => 'http://www.deinsexdate.com/index.php?main=userprofile&page=page_profile&user_id='.$uid,
							'uid' => $uid,
							'username' => $li->find('img',0)->alt
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
	
	private function generateRandomString($length = 10) {
	    return substr(str_shuffle("123456789"), 0, $length);
	}
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$url = 'http://www.deinsexdate.com/index.php?main=finder&page=overview&subpage=quick_search';
			$findname = '';
			$content = $this->getHTTPContent($url, $this->indexURL, $cookiePath);
			$html = str_get_html($content);
			
			foreach($html->find('form') as $form) {
				if(strpos($form->action,'find_by_name')) {
					$findname = $form->action;
					break;
				}
			}
			
			$content = $this->getHTTPContent('http://www.deinsexdate.com/index.php?main=finder&page=find_by_name', $url, $cookiePath, array(
				'user_name' => $profile,
				'x' => rand(36,46),
				'y' => rand(16,26)
			));
			
			$this->sleep(3);
			
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