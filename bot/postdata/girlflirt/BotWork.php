<?php
 
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
	private $_table_prefix = 'girlflirt_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.girlflirt.de/';
	public $searchActionURL = 'http://www.girlflirt.de/in.new_search.jsp';
	public $sendMessageActionURL = 'http://www.girlflirt.de/in.write_msg.jsp';
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
						'username' => 'littlemeexD',
						'password' => '3touchme7'
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
				"gender" => 1,
				"postcode" => "34121",
				"online" => 1,
				"around" => 0,
				"action" => "send",
				"wait_for_login" => 1,
				'logout_after_sent' => 10,
				'version' => 1,
				'proxy_type' => 1,
				'online' => 1,
				'age_from' => 23,
				'age_to' => 65
			);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 60;
		}
		$this->usernameField = 'username';
		$this->loginURL = "http://www.girlflirt.de/login.jsp";
		$this->loginActionURL = 'http://www.girlflirt.de/login.jsp';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = "";
		$this->indexURL = "http://www.girlflirt.de/";
		$this->indexURLLoggedInKeyword = 'logout.jsp';
		$this->searchURL = "http://www.girlflirt.de/in.new_search.jsp";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 10;
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
		
		$target = "Female";
		if($this->command['gender'] == "1"){
			$target = "Male";
		}

		// Force to Male
		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='Male'");
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

	public function resetPLZ()
	{
		$this->command['postcode'] = "00000";
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				'password' => $user['password'],
				'username' => $user['username']
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	private function sendUserMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']. ' / UID : '. $item['uid']);
		if($this->reserveUser($item['username']))
		// if($item['username'] == 'Johann')
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


			$message = iconv('utf-8', 'iso-8859-1', $message);
			$this->savelog("Message is : ".$message);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
				$content = $this->getHTTPContent($this->sendMessageActionURL, $item['profile_url'], $cookiePath, array(
					'subject' => $subject,
					'body' => $message,
					'outbox' => 'true',
					'zid' => $item['uid'],
					'Senden' => 'Senden',
				));
				
				$content = $this->getHTTPContent('http://www.girlflirt.de/in.outbox.jsp', $item['profile_url'], $cookiePath);
				if(strpos($content, $item['username'])) {
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
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = '';
		$this->savelog("Job criterias => Search Online Users");
		$this->savelog("Job started.");
		$this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);
		$page = 1;
		$first_username = '';
		do
		{
				
			$this->savelog("Search Online Users, page ".$page);	
			
			if($page == 1) {
				$url = 'http://www.girlflirt.de/in.useronline_uebersicht.jsp?&saus=u_age&sdesc=DESC&output=1&searchtype=basic&seeklocator=1';
			} else { 
				$url = 'http://www.girlflirt.de/in.useronline_uebersicht.jsp?&saus=u_age&sdesc=DESC&pos='.$search_arr['pos'].'&output=1&searchtype=basic';
			}
			
			$content = $this->getHTTPContent($url, $this->searchURL, $cookiePath);
			$html = str_get_html($content);

			/***********************************************/
			/***** Extract profiles from search result *****/
			/***********************************************/
			$list = array();
			if(!empty($content)) {
				file_put_contents("search/".$username."-search-".$page.".html",$content);
				$list = $this->getMembersFromSearchResult($username, 0, $content, 0);
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
					'alter_bis' => $age,
					'alter_von' => $age,
					'gewicht_bis' => '',
					'gewicht_von' => '',
					'image2.x' => rand(24,34),
					'image2.y' => rand(6,16),
					'output' => 1,
					'picture' => 1,
					'plz_bis' => '',
					'plz_von' => '',
					'saus' => 'u_joindate',
					'sdesc' => 'DESC',
					'searchtype' => 'basic',
					'size_bis' => '',
					'size_von' => '',
					'suche' => ((empty($this->command['gender'])) ? 3 : $this->command['gender'])
				);

				/**
					END PRE SEARCH
				**/
				
				if($page != 1) {
					// $content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath, $search_arr);
				// } else {
					
					unset($search_arr['image2.x']);
					unset($search_arr['image2.y']);
					$search_arr['pos'] = (( $page - 1 ) * 10 );
				}

				if(!empty($this->command['online'])) {
					
					$this->savelog("Search Online Users, page ".$page);	
					
					if($page == 1) {
						$url = 'http://www.girlflirt.de/in.useronline_uebersicht.jsp?&saus=u_age&sdesc=DESC&output=1&searchtype=basic&seeklocator=1';
					} else { 
						$url = 'http://www.girlflirt.de/in.useronline_uebersicht.jsp?&saus=u_age&sdesc=DESC&pos='.$search_arr['pos'].'&output=1&searchtype=basic';
					}
					$content = $this->getHTTPContent($url, $this->searchURL, $cookiePath, $search_arr);
					
				} else {
					
					$this->savelog("= Search for Target age: ".$age." to ".$age.", page ".$page);
					$content = $this->getHTTPContent('http://www.girlflirt.de/in.new_search.jsp?'.http_build_query($search_arr), $this->searchURL, $cookiePath, $search_arr);
					
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
		if(!empty($this->command['online'])){
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
		$mem = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if(!empty($html->find("td.head1",0))) {
				$table = $html->find("td.head1",0);
				foreach($table->find('a') as $anchor){
					$username = trim($anchor->plaintext);
					$href = $anchor->href;
					if(!in_array($username, $mem)){
						echo '<p>', $href,'</p>';
						$str = str_replace('in.look.jsp?', '', $href);
						
						parse_str($str, $output);
						// var_dump($output);
						$mem[] = $username;
						if(!empty($output['zid'])) {
							$list[] = array(
								'profile_url' => $this->rootDomain . $href,
								'username' => $username,
								'uid' => $output['zid']
							);
						}
					}
				}
			}
		}
		// var_dump($List);
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
		$this->loginArr = array();
	
		$this->savelog("Site ID : ". $this->siteID);
		// $fetch = DBConnect::assoc_query_2D("SELECT * FROM user_profiles WHERE status != 'false' AND site_id=".$this->siteID." AND in_use = 'false' ORDER BY rand() LIMIT 1");
		
		
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
			$row = botutil::getNewProfile($this->siteID, $username, $this->command, $this);
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
}