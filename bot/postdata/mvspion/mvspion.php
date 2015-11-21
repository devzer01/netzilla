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

class mvspion extends bot 
{
	private $_table_prefix = 'mvspion_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.mv-spion.de';
	public $sendMessageActionURL = 'http://www.mv-spion.de/messages/messenger/send';
	private $nextSearchPage = '';
	
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
													'username' => 'minchentoll@live.de',
													'password' => 'Minilit17'
													),
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
									"messages_logout" => 1,
									"logout_after_sent" => "Y",
									"ageMin" => 18,
									"ageMax" => 60,
									"gender" => 'm',
									"status" => "all",
									"country" => 'DE',
									"wait_for_login" => 1,
									"login_by" => 1,
									"plz" => '',
									"only_online" => "true",
									"radius" => "ALL",
									"login_by" => 1,
									"profile_type" => 1,
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 90;
		}
		
		$this->usernameField = 'name';
		$this->loginURL = "http://www.mv-spion.de/";
		$this->loginActionURL = "http://www.mv-spion.de/einloggen";
		$this->loginRefererURL = "http://www.mv-spion.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.mv-spion.de/ausloggen";
		$this->indexURL = "http://www.mv-spion.de/einloggen";
		$this->indexURLLoggedInKeyword = 'Ausloggen';
		$this->searchURL = "http://www.mv-spion.de/suche.html";
		$this->searchActionURL = 'http://www.mv-spion.de/suche.html';
		$this->searchRefererURL = "http://www.mv-spion.de/suche.html";
		$this->searchResultsPerPage = 35;
		$this->profileURL = "http://www.pof.de/de_viewprofile.aspx?profile_id=";
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
		$this->count_msg = 0;
		
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

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
				"name" => $user['username'],
				"kwd" => $user['password'],
				"login" => ""
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['ageMin'])) ? $this->command['ageMin'] : $this->command['age_min'])." to ".((empty($this->command['ageMax'])) ? $this->command['ageMax'] : $this->command['age_max']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		if(!empty($this->command['ageMin'])) {
			$this->command['age_from'] = $this->command['ageMin'];
		}
		if(!empty($this->command['ageMax'])) {
			$this->command['age_to'] = $this->command['ageMax'];
		}

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$page=0;
			$list=array();
			$first_username = '';
			do
			{
				
				$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);

				$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
				
				$searchURL = "http://www.mv-spion.de/ajax/lc?content=search&offset=" . ($page * 30) . "&ageMin=" . $age . "&ageMax=" . $age . "&gender=" . $this->command['gender'];
				
				if (trim($this->command['plz']) != '') {
					$searchURL .= "&plz=" . $this->command['plz'];
					
					if ($this->command['radius'] != 'ALL') {
						$searchURL .= "&radius=" . $this->command['radius']; 	
					}
				}
				
				if (isset($this->command['only_online']) && $this->command['only_online'] == 'true') {
					$searchURL .= "&online=true";
				}
								
				$this->savelog("Navigating to Search URL " . $searchURL);
				$content = $this->getHTTPContent($searchURL, $this->searchURL, $cookiePath, null, false, $header);
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				
				if (isset($this->command['online_user_only']) && $this->command['online_user_only'] == 1) {
					$list = $this->getOnlineMembersFromXml($this->command['online_type']);	
				} else {
					$list = $this->getMembersFromSearchResult($username, $page, $content);
				}

				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					if(count($list))
					{
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
									///reserve this user, so no other bot can send msg to
									$this->savelog("Reserving profile to send message: ".$item['username']);
									if($this->reserveUser($item['username']))
									{
										// Go to profile page
										$this->savelog("Go to profile page: ".$item['username']);
										$content = $this->getHTTPContent('http://www.mv-spion.de' . $item['link'], $this->searchURL, $cookiePath);
										$html = str_get_html($content);
										
										$interval = rand(0,4);
										$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
										sleep($interval);
										
										$chatlink = substr($item['link'], 1, strlen($item['link']) - 1);
										$user_link = $chatlink;
										$chatlink .= "/neue-Nachricht";
										
										$item['message_link'] = "http://www.mv-spion.de/ajax/lc?content=" . $chatlink;
										do {
											$this->savelog($item['message_link']);
											$content = $this->getHTTPContent($item['message_link'], $item['link'], $cookiePath);
											sleep(1);
										} while ($content == '');
										
										$html = str_get_html($content);
										
										
										$empfaengerId = $html->find("input[name='empfaengerId']", 0)->value;
										
										if ($empfaengerId == null) {
											$this->savelog("empfaengerId is not found");
											return false;
										}
										
										$memberLink = $item['link'];

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
										
										if(time() < ($this->lastSentTime + $this->messageSendingInterval)) $this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Sending message to ".$item['username']);
										
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											
											$messageURL = 'http://www.mv-spion.de/ajax/lc?content=' . $user_link . "/mail";
											
											$post_data = array(
													'message' => $message,
													'empfaengerId' => $empfaengerId
											);
											
											$this->savelog("Sending Message (debug) " . $messageURL . " - " . $empfaengerId);
											
											$content = $this->getHTTPContent($messageURL, $item['link'], $cookiePath, $post_data, false, $x, true);
											
											file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
											$response = json_decode($content, true);
											$this->savelog("response " . $content);
											if(strpos($content, 'ok') !== false)
											{
												$chat_disabled = FALSE;												
												DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->savelog("Sending message completed.");
												$this->lastSentTime = time();

												if($this->command['logout_after_sent'] == "Y"){
													if(++$this->count_msg >= $this->command['messages_logout']){
														break 3;
													}
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
									}
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
							}
						}
						
					}
				}

				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database

				$page++;
			} while(count($list)>=$this->searchResultsPerPage);
		}
				
		$this->savelog("Job completed.");
		return true;
	}

	
	private function getOnlineMembersFromXml($type)
	{
		$this->savelog("Trying to read xml from site - " . $type);
		
		$xml = file_get_contents("http://www.poppen.de/xml/normalUsers_" .$type . ".xml");
		
		$xml = simplexml_load_string($xml);

		$list = array();
	
		foreach ($xml->{$type}->guy as $guy) {
	        $list[] = array('uid' => (string) $guy->id, 'username' => (string) $guy->nickname , 'link' => '');
		}
				
		return $list;
		
	}
	
	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$html = str_get_html($content);
		if(!empty($html)) {
			foreach($html->find('div') as $div) {
				$adiv = $div->find("a", 0);
				if ($adiv == null) continue;
				$username = $div->find("a",0)->href;
				$list[$username] = array(
					'username' => $username,
					'link' => $div->find("a",0)->href
				);
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
	
	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}
	
	public function testLogin($profile) {
			
		$default_proxy = $this->command['proxy_type'];
		$this->command['proxy_type'] = 2;
		$this->setProxy();
		$loginRetry = 1;
		$this->userAgent = botutil::getAgentString();
		$username = $profile['username'];
		$cookiePath = $this->getCookiePath($username);

		if(!($this->isLoggedIn($username)))
		{
			// $this->savelog("==[TEST]==> This profile: ".$username." is not logged in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				
				// $this->savelog("==[TEST]==> Logging in.");
				
				
				$this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath);
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, NULL);
				
				if (trim($content) == '') {
					// $this->savelog("==[TEST]==> Timeout Occured, no data received");
					return false;	
				}
				
				$html = str_get_html($content);
				
				$content = $this->getHTTPContent($this->loginActionURL, $this->loginRefererURL, $cookiePath, array(
					"name" => $profile['username'],
					"kwd" => $profile['password'],
					"login" => ""
				));
				$html = str_get_html($content);
				if(!empty($html->find('div.input-error',0))) {
					$error = trim($html->find('div.input-error',0)->plaintext);
				}

				if(empty($content))
				{
					$loginRetry++;
				}
				elseif(!($this->isLoggedIn($username)))
				{
					if(!empty($error)) {
						// $this->savelog('==[TEST]==> Log in failed message is '.$error);
					}
					if($count_login>($loginRetry-1))
					{
						$this->command['proxy_type'] = $default_proxy;
						return FALSE;
					}
					else
					{
						$sleep_time = 1; 
						// $this->savelog("==[TEST]==> Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
				}
				else
				{
					$this->command['proxy_type'] = $default_proxy;
					return TRUE;
				}
			}
		}
		else
		{
			$this->command['proxy_type'] = $default_proxy;
			return TRUE;
		}
	}
		
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.mv-spion.de/'.$profile, $this->indexURL, $cookiePath);
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