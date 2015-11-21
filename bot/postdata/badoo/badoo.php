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

class badoo extends bot
{
	private $_table_prefix = 'badoo_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.badoo.com';
	public $sendMessageActionURL = '';
	private $nextSearchPage = '';
	
	private $sendmsg_total = 0;
	
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
													// array(
													// 	"username" => "libler@live.de",
													// 	"password" => "jywodoku"
													// ),
													array(
													'username' => 'theissen66@yahoo.com',
													'password' => 'qisajaca'
													),
													// array(
													// 	'username' => 'ykleatt44@outlook.com',
													// 	'password' => 'fumuhuha'
													// )
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
									"age_from" => 66,
									"age_to" => 70,
									"gender" => 1,
									"status" => "all",
									"country" => 81,
									//"action" => "check"
									"action" => "send",
									"wait_for_login" => '1',
									"location" => "Berlin, Deutschland"
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 60;
			
			if(!empty($_GET['test'])) {
				$this->command['test'] = 1;
				$this->command['test_username'] = $_GET['test_username'];
			}
		}
		$this->usernameField = 'email';
		$this->loginURL = "https://badoo.com/";
		$this->loginActionURL = 'https://badoo.com/signin/';
		$this->loginRefererURL = "https://badoo.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "https://badoo.com/signout/";
		$this->indexURL = "https://badoo.com/";
		$this->indexURLLoggedInKeyword = '/signout/';
		$this->searchURL = "https://badoo.com/search/";
		$this->searchActionURL = 'https://badoo.com/search/';
		$this->searchRefererURL = "https://badoo.com/search/";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "http://www.planetromeo.com/00000000000000000000000000000000/auswertung/setcard/index.php?set=";
		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "http://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
		$this->sendMessageURL = "http://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
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

		$target = "Male";
		if($this->command['gender'] == 'F'){
			$target = "Female";
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

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
				"rt" => "",
				"email" => $user['username'],
				"password" => $user['password'],
				"remember" => 1,
				"" => "Einloggen!"
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	private function sendUserMessage($item, $username, $cookiePath) {
		// If not already sent
		if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']." / Debug : ".$item['link']);
				$content = $this->getHTTPContent($item['link'], $this->searchURL, $cookiePath);
				$html = str_get_html($content);
				if(empty($html)) {
					break;
				} else {
					if($html->find('a.mnpltn_link',0)) {
						$item['message_link'] = $html->find('a.mnpltn_link',0)->href;
					} else {
						continue;
					}
				}
				
				/**
					Pre Sending Check
				**/
				// if(empty($this->rt)) {
				// 	$ex = explode('?', $html->find('a.logo_anchor',0)->href);
				// 	$this->rt = str_replace('rt=', '', $ex[1]);
				// }
				$pf = parse_url($item['link']);
				parse_str($pf['query'], $dm);
				if(!empty($dm['rt'])) {
					$this->rt = $dm['rt'];
				}
				unset($dm);
				$content = $this->getHTTPContent($item['message_link'].'?rt='.$this->rt.'&wa=1&ws=1', $item['link'], $cookiePath);
				if(strpos($content, 'Ups, Du hast noch kein Foto')) {
					$this->savelog("failed : This profile can't send message because not set User's Avatar");
					$this->logout();
					$this->savelog("Get a new Profile for Send Message");
					$this->getNewProfile();
					$this->sleep(($this->command['wait_for_login']*60));
					$this->login();												
					break 2;
				}
				$json = json_decode($content, true);

				/**
					GET Host
				**/
				$dom = parse_url($item['message_link']);
				$this->sleep(5);

				/***********************************/
				/***** Go to send message page *****/ 
				/***********************************/
				// 
				// $this->savelog("Go to send message page: ".$item['username']);
				// $content = $this->getHTTPContent('https://'.$dom['host'].'/connections/wp-post.phtml?ws=1&rt='.$this->rt 
				// 	, $item['link']
				// 	, $cookiePath);
				// $this->sleep(5);

				/************************/
				/***** Send message *****/
				/************************/
				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->_message)-1);

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
				$message_arr = array(
					"act" => 'add',
					"contact_user_id" => $item['uid'],
					"message" => $message,
					"photo" => "",
					"rt" => $this->rt,
					"s1" => $this->_session_id
				);
				
				
				if(time() < ($this->lastSentTime + $this->messageSendingInterval))
					$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
				$this->savelog("Sending message to ".$item['username']);
				if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
				{
					// $content = $this->getHTTPContent($this->sendMessageActionURL, $this->sendMessagePageURL.$item['uid'], $cookiePath, $message_arr);
					
					$content = $this->getHTTPContent('https://'.$dom['host'].'/connections/ws-post.phtml?ws=1&rt='.$this->rt 
					 	, $item['link']
					 	, $cookiePath
					 	, $message_arr );
					file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
					$response = json_decode($content, true);
					if($response['errno'] == 0)
					{
						$chat_disabled = FALSE;
						if(!empty($response['data']['message']['chat_disabled'])) {
							if($response['data']['message']['chat_disabled'] == 1) {
								$chat_disabled = TRUE;
							}
						}

						if($chat_disabled === FALSE) {
							
							$this->savelog("Sending message completed.");
							if(empty($this->command['test'])){
								DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$this->sendmsg_total++;
							}
						} else {
							$html = str_get_html(stripslashes($response['data']['message']['html']));
							$this->savelog("Sending message failed".((!empty($html->find('p',0))) ? ' / Badoo response : '.$html->find('p',0)->plaintext : '') );
							
							$this->logout();
							$this->savelog("Get a new Profile for Send Message");
							$this->getNewProfile();
							$this->sleep($this->command['waitfornext']);
							$this->login();
							break 2;
						}
						
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
							$this->work();
						}
					}
				}
				/* End of logout */
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
		}
	}
	
	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['age_f'])) ? $this->command['age_from'] : $this->command['age_f'])." to ".((empty($this->command['age_t'])) ? $this->command['age_to'] : $this->command['age_t']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		if(!empty($this->command['age_f'])) {
			$this->command['age_from'] = $this->command['age_f'];
		}
		if(!empty($this->command['age_t'])) {
			$this->command['age_to'] = $this->command['age_t'];
		}

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$page=1;
			$list=array();
			$first_username = 'FIRSTPAGE';
			do
			{
				/**
				 	PRE-SEARCH
				**/
				$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);
				if(empty($this->rt)) {
					$html = str_get_html($content);
					if($html->find('a.logo_anchor',0)){
						$ex = explode('?', $html->find('a.logo_anchor',0)->href);
						$this->rt = str_replace('rt=', '', $ex[1]);
						$ex = explode('"r":"',$content);
						if(!empty($ex[1])) {
							$b = explode('"', $ex[1]);
							if(!empty($b[0])){
								$r = $b[0];
							}
							unset($b);
						}
						unset($ex);
					}
				}	

				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					'age_t' => $age,
					'age_f' => $age,
					'filter' => $this->command['filter'],
					'gender' => array($this->command['gender']),
					'interest' => '',
					'interest_id' => '',
					'location' => $this->command['location'],
					// 'location_id' => '18_845_117759',
					// 'location' => $this->command['location'],
					'distance' => $this->command['distance'],
					// 'location_id' => $this->command['location'],
					'r' => $r,
					'rt' => $this->rt,
					'search_button' => 'search_button',
					'ws' => 1
				);

				/**
				 	END PRE SEARCH
				**/

				$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
				if($page == 1) {
					$content = $this->getHTTPContent($this->searchURL . '?'.http_build_query($search_arr), $this->searchURL, $cookiePath);
				} else {
					$content = $this->getHTTPContent($this->searchURL . '?'. http_build_query($search_arr).'&page='.$page, $this->searchURL, $cookiePath);
				}
				
				// $content = $this->getHTTPContent($this->searchURL . '?'.http_build_query($search_arr), $this->searchURL, $cookiePath);
			
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);

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
						$first_username = $list[0]['username'];
						
						
						$this->savelog("Found ".count($list)." member(s)");
						
						$this->sleep(5);
						$enableMoreThanOneMessage = FALSE;
						foreach($list as $item)
						{
													
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								if(!empty($this->command['test'])){
									if(!empty($item['username'])) {									
										if($item['username'] == $this->command['test_username']) {
											$this->savelog("[Test] Found target profile : ".$item['username']);
											$this->sendUserMessage($item, $username, $cookiePath);	
										} else {
											$this->savelog("[Test] Skipped : ".$item['username']." / UID : ". $item['uid']);
										}
									}
								} else {
									if(!empty($item['username'])) {
										$this->sendUserMessage($item, $username, $cookiePath);	
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

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		$json = $this->json_validate($content);
		if(empty($json->error)) {
			if(!empty($json->html)){
				$content = stripslashes( $json->html);
			}

			if(!empty($json->fatal)){
				$this->savelog('failed : Badoo response => '.strip_tags($json->fatal));
				$this->logout();
				$this->sleep(60);
				$this->login();
				return array();
			}
		}

		$html = str_get_html($content);
		if(!empty($html)) {
			$i = 0;
			foreach($html->find('div.user-card') as $div) {
				if($div->find("div.user-card__section",0)) {
					if(!empty($div->find("a.user-name",0))){
						$list[] = array(
							'username' => $div->find("a.user-name",0)->plaintext,
							'uid' => str_replace('uid', '', $div->find("a.user-name",0)->id),
							'link' => $div->find("a.user-name",0)->href
						);
					} else {
						$list[] = array(
							'username' => $div->find("a.msgr-lnk",0)->plaintext,
							'uid' => str_replace('my_', '', $div->find("a.msgr-lnk",0)->target),
							'link' => $div->find("a.msgr-lnk",0)->href
						);
					}
				}
				$i++;
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

	private function json_validate($json, $assoc_array = FALSE)
	{
	    // decode the JSON data
	    $result = json_decode($json, $assoc_array);

	    // switch and check possible JSON errors
	    switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            $error = ''; // JSON is valid
	            break;
	        case JSON_ERROR_DEPTH:
	            $error = 'Maximum stack depth exceeded.';
	            break;
	        case JSON_ERROR_STATE_MISMATCH:
	            $error = 'Underflow or the modes mismatch.';
	            break;
	        case JSON_ERROR_CTRL_CHAR:
	            $error = 'Unexpected control character found.';
	            break;
	        case JSON_ERROR_SYNTAX:
	            $error = 'Syntax error, malformed JSON.';
	            break;
	        // only PHP 5.3+
	        case JSON_ERROR_UTF8:
	            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
	            break;
	        default:
	            $error = 'Unknown JSON error occured.';
	            break;
	    }

	    if($error !== '') {
	    	$object = new stdClass();
	    	$object->error = $error;
	        return $object;
	    } else {
	    	return $result;
	    }
	}
}