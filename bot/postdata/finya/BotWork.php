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
	private $_table_prefix = 'finya_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.finya.de/';
	public $searchActionURL = 'http://www.finya.de/Search/';
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
													'username' => 'ClaudXX23',
													'password' => 'lambsel34'
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
									'version' => 2,
									'proxy_type' => 1
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 89;
		}
		$this->usernameField = 'aba';
		$this->loginURL = "http://www.finya.de/?view=login";
		$this->loginActionURL = 'http://www.finya.de/Index/trylogin';
		$this->loginRefererURL = "";
		$this->loginRetry = 3;
		$this->logoutURL = "";
		$this->indexURL = "http://www.finya.de/";
		$this->indexURLLoggedInKeyword = 'logout();';
		$this->searchURL = "http://www.finya.de/Browse";
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
		if(!empty($this->command['profiles'])){
			$this->addLoginData($this->command['profiles']);
		}
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		$this->plz = array(
			"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
		);

		if(!empty($this->command['gender'])) {
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
				'aab' => '',
				"aba" => $user['username'],
				"password" => $user['password'],
				"abb" => 1,
				'aaa' => ''
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
				
				$content = $this->getHTTPContent($this->rootDomain.'User/composeMessage/'.$item['user_url'], $item['profile_url'], $cookiePath);
				$html = str_get_html($content);
				$fy_tn = $html->find('#fy_tn',0)->value;				
				$hf_tn = hash('sha256',$fy_tn.';return r[e]}];e=function(){re');
				
				$message_arr = array(
					'fy_tn' => $fy_tn,
					'hf_tn' => $hf_tn,
					'mb' => $message
				);
								
				$content = $this->getHTTPContent($this->rootDomain.'Messages/send/'.$item['user_url'], $item['profile_url'], $cookiePath, $message_arr, FALSE, array(
					'X-Requested-With: XMLHttpRequest'
				));
				$json = json_decode($content);
				if($json->rsp > 0) {
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
		$this->savelog("Job criterias => Target age: ".((empty($this->command['a1'])) ? $this->command['age_from'] : $this->command['a1'])." to ".((empty($this->command['a2'])) ? $this->command['age_to'] : $this->command['a2']));
		$this->savelog("Job started.");
		$this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);

		if(!empty($this->command['a1'])) {
			$this->command['age_from'] = $this->command['a1'];
		}
		if(!empty($this->command['a2'])) {
			$this->command['age_to'] = $this->command['a2'];
		}

		$plz = $this->plz;
		if($key = array_search($this->command['postcode'],$plz))
		{
			$plz = array_slice($plz, $key);
		}

		foreach($plz as $zipcode)
		{
			for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
			{
				$page=1;
				$list=array();
				$first_username = '';
				do
				{
					$json = $this->getHTTPContent($this->rootDomain.'Misc/getCities', $this->searchURL, $cookiePath, array(
						'c' => 'de',
						'z' => $zipcode
					), FALSE, array(
						'X-Requested-With: XMLHttpRequest'
					));
					$data = json_decode($json);
					$k = 0;
					foreach ($data->cities as $key => $value) { $k = $key;}
					if($this->command['around'] == '1')
						$n = 'areaExt,'.$data->cities->{$k}->area.','.$zipcode;
					else
						$n = 'area,'.$data->cities->{$k}->area.','.$zipcode;
					/******************/
					/***** search *****/
					/******************/
					$search_arr = array(
						'a1' => $age,
						'a2' => $age,
						'g' =>  (empty($this->command['gender'])) ? 2 : $this->command['gender'],
						'im' => '', // Has avatar
						'n' => $n,
						'ref' => 'extsearch',
						'se' => '',
						'si' => (empty($this->command['si'])) ? '' : $this->command['si'], // nur Singles 
						'p' => ($page == 1) ? '1': $page,
						'o' => ($online == 1) ? '1' : ''
					);

					/**
						END PRE SEARCH
					**/
					$this->savelog("Search for Target age: ".$age." to ".$age.", gender: ".$this->command['gender'].", plz: ".$zipcode.", page ".$page);
					$content = $this->getHTTPContent($this->searchActionURL.'?'.http_build_query($search_arr), $this->searchURL, $cookiePath);
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
		}
		$this->resetPLZ();
		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		// GET INBOX
		if($this->command['version'] == 2) {
			$this->checkInbox();
		}
		$this->searchMember($this->command['online']);
	}
	
	public function checkInbox() {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$list = $this->getMessageInboxUsers();	
		
		$this->savelog("Check Inbox");
		if(!empty($list)) {
			$this->savelog("Found ".count($list)." Message(s) inbox and send message back");
			foreach($list as $item) {
				// Send Message back
				$this->savelog("Send response inbox message to : ".$item['username']);
				$this->sendUserMessage($item, $username, $cookiePath);	
				
				// Delete Message inbox
				$this->savelog("Deleting inbox message from user : ".$item['username']);
				$this->getHTTPContent('http://www.finya.de/Messages/deleteThread/?t='.$item['user_id'], 'http://www.finya.de/Messages/mailbox', 'forcePost');
			}
		} else {
			$this->savelog("No inbox message");
		}
	}
	
	public function getMessageInboxUsers() {
		$list = array();
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = $this->getHTTPContent('http://www.finya.de/Messages/mailbox', $this->rootDomain, $cookiePath);
		if(!empty($content)) {
			$html = str_get_html($content);
			if(!empty($html->find("tr.thread",0))) {
				foreach($html->find("tr.thread") as $thread) {
					if($thread->{'data-dp'} != 0 ){
						$href = $thread->find('a.userpic-link',0)->href;
						$list[] = array(
							'user_url' => str_replace('/User/profile/', '', $href),
							'profile_url' => 'http://www.finya.de'.$href,
							'username' => $thread->{'data-nn'},
							'user_id'=> $thread->{'data-dp'}
						);
					}
				}
			}
		}
		return $list;
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if(!strpos($content, 'Es wurden keine Treffer erzielt')) {
				if(!empty($html->find('li.thumb2-wrapper'))){
					foreach($html->find('li.thumb2-wrapper') as $lst) {
						if(!empty($lst->find('a',0))){
							$anchor = $lst->find('a',0);
							$list[] = array(
								'user_url' => str_replace('/User/profile/', '', str_replace('/?ref=search', '', $anchor->href)),
								'profile_url' => 'http://www.finya.de'.$anchor->href,
								'username' => $anchor->plaintext,
							);
						}
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
			$content = $this->getHTTPContent('http://www.finya.de/Search/?nn='.$profile, $this->rootDomain, $cookiePath);
			if(!strpos($content,'Es wurden keine Treffer erzielt')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
