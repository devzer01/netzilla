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
	private $_table_prefix = 'lotsofloves_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	private $target = 'Male';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.lotsofloves.com/';
	public $searchActionURL = 'http://www.lotsofloves.com/search/results/';
	public $sendMessageActionURL = '';
	
	private $_city = array(
		'72' => 'Avon',
		'73' => 'Bedfordshire',
		'74' => 'Berkshire',
		'75' => 'Borders',
		'76' => 'Buckinghamshire',
		'77' => 'Cambridgeshire',
		'78' => 'Central',
		'79' => 'Cheshire',
		'80' => 'Cleveland',
		'81' => 'Clwyd',
		'82' => 'Cornwall',
		'83' => 'Cumbria',
		'84' => 'Derbyshire',
		'85' => 'Devon',
		'86' => 'Dorset',
		'87' => 'Dumfries and Galloway',
		'88' => 'Durham',
		'89' => 'Dyfed',
		'90' => 'East Sussex',
		'91' => 'Essex',
		'92' => 'Fife',
		'93' => 'Gloucestershire',
		'94' => 'Grampian',
		'95' => 'Greater London',
		'96' => 'Greater Manchester',
		'97' => 'Gwent',
		'98' => 'Gwynedd',
		'99' => 'Hampshire',
		'100' => 'Hereford and Worcester',
		'101' => 'Hertfordshire',
		'102' => 'Highland',
		'103' => 'Humberside',
		'104' => 'Isle of Man',
		'105' => 'Isle of Wight',
		'106' => 'Kent',
		'107' => 'Lancashire',
		'108' => 'Leicestershire',
		'109' => 'Limavady',
		'110' => 'Lincolnshire',
		'111' => 'Lothian',
		'112' => 'Merseyside',
		'113' => 'Mid Glamorgan',
		'114' => 'Norfolk',
		'115' => 'North Yorkshire',
		'116' => 'Northamptonshire',
		'117' => 'Northumberland',
		'118' => 'Nottinghamshire',
		'120' => 'Orkney',
		'121' => 'Oxfordshire',
		'122' => 'Powys',
		'123' => 'Shetland',
		'124' => 'Shropshire',
		'125' => 'Somerset',
		'126' => 'South Glamorgan',
		'127' => 'South Yorkshire',
		'128' => 'Staffordshire',
		'129' => 'Strathclyde',
		'130' => 'Suffolk',
		'131' => 'Surrey',
		'132' => 'Tayside',
		'133' => 'Tyne and Wear',
		'134' => 'Warwickshire',
		'135' => 'West Glamorgan',
		'136' => 'West Midlands',
		'137' => 'West Sussex',
		'138' => 'West Yorkshire',
		'139' => 'Western Isles',
		'140' => 'Wiltshire'
	);
	
		
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
													'username' => 'Annabellsss2014',
													'password' => '1qazxsw2'
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
									"gender" => 'M',
									"status" => "all",
									"country" => 81,
									"postcode" => "91220",
									//"action" => "check"
									"action" => "send",
									'version' => 1,
									'disabled_tor' => 0,
									'online' => 0,
									'logout_after_sent' => 'Y',
								    'messages_logout' => 1,
								    'wait_for_login' => 0.1,
								    'login_by' => 1
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 5;
		}
		$this->usernameField = 'username';
		$this->loginURL = "http://www.lotsofloves.com/profile/login";
		$this->loginActionURL = 'http://www.lotsofloves.com/profile/login';
		$this->loginRefererURL = "http://www.lotsofloves.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.lotsofloves.com/profile/logout";
		$this->indexURL = "http://www.lotsofloves.com/";
		$this->indexURLLoggedInKeyword = 'profile/logout';
		$this->searchURL = "http://www.lotsofloves.com/search/";
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

		if($this->command['gender'] == 'F'){
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
				'_method' => 'POST',
				'data' => array(
					'User' => array(
						'password' => $user['password'],
						'remember_me' => 0,
						'remember_me' => 1,
						'username' => $user['username'],
						
					)
				)
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
						
				$message_arr = array(
					'_method' => 'POST',
					'data' => array(
						'Mail' => array(
							'subject' => $subject,
							'text' => $message
						)
					)
				);
				$content = $this->getHTTPContent($item['mail_url'], $item['profile_url'], $cookiePath, $message_arr);
				
				if(strpos($content, 'Sent')) {
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
	
	private function searchMember($online = 0) {
			
		$content = '';
		$this->savelog("Job criterias => Target age: ".((empty($this->command['a1'])) ? $this->command['age_from'] : $this->command['a1'])." to ".((empty($this->command['a2'])) ? $this->command['age_to'] : $this->command['a2']));
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser]['data']['User'][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);
		
		if(empty($this->command['country'])) {
			$this->command['country'] = 3;
		}

		if($this->command['age_from'] == $this->command['age_to']) {
			$this->command['age_to'] = $this->command['age_to']+3;
		}
		
		$max = count($this->_city);
		$next_cid = $this->command['city'];
		for($i = 1; $i <= $max; $i++)
		{
			if($next_cid > 140) {
				$next_cid = 72;
			}
			
			if(empty($this->_city[$next_cid])) {
				$next_cid++;
			}
			
			
			$page=1;
			$list=array();
			$age = $this->command['age_from'];
			$age2 = $this->command['age_to'];
			$first_username = '';
			do
			{
				
				/******************/
				/***** search *****/
				/******************/
	
				$search_arr = '_method=POST&data%5BUser%5D%5BCountry%5D='.$this->command['country'].'&data%5BUser%5D%5BCounty%5D='.$next_cid.'&data%5BUser%5D%5BAgeTo%5D2='.$age.'&data%5BUser%5D%5BAgeFrom%5D2='.$age2.'&data%5BUser%5D%5BGender%5D='.$this->command['gender'].'&data%5BUser%5D%5BSexuality%5D=1&data%5BCriteria1%5D%5BType%5D=0&data%5BCriteria1%5D%5BOption%5D=0&data%5BCriteria1%5D%5BValue%5D=0&data%5BCriteria2%5D%5BType%5D=0&data%5BCriteria2%5D%5BOption%5D=0&data%5BCriteria2%5D%5BValue%5D=0&data%5BCriteria3%5D%5BType%5D=0&data%5BCriteria3%5D%5BOption%5D=0&data%5BCriteria3%5D%5BValue%5D=0';
				$this->savelog("Search for Target age: ".$age." to ".$age2.", City: ".$this->_city[$next_cid]." / page ".$page);
				$this->_special_post = 1;
				$prefix = '';
				if($page != 1) {
					$prefix = 'page:'.$page;
				}
				$content = $this->getHTTPContent($this->searchActionURL . $prefix, $this->searchURL, $cookiePath, $search_arr);
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
			
			$next_cid++;
		}

		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		$this->searchMember($online);
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		if(!empty($content)){
			$html = str_get_html($content);
			if(!empty($html->find('div.WideSectionContainer'))){
				foreach($html->find('div.WideSectionContainer') as $lst) {
					if(!empty($lst->find('a',0))){
						$anchor = $lst->find('a',1);
						$list[] = array(
							'user_url' =>  'http://www.lotsofloves.com' . $anchor->href,
							'mail_url' => 'http://www.lotsofloves.com' . $lst->find('a',2)->href,
							'profile_url' => 'http://www.lotsofloves.com' . $anchor->href,
							'username' => trim($anchor->plaintext),
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
		$username = $this->loginArr[$this->currentUser]['data']['User'][$this->usernameField];
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
		$username = $this->loginArr[$this->currentUser]['data']['User'][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.lotsofloves.com/search/usernameresults/', 'http://www.lotsofloves.com/search/username/', $cookiePath, array(
				'_method' => 'POST',
				'data' => array(
					'User' => array(
						'username' => $profile
					),
				)
			));
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