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

class poppende extends bot 
{
	private $_table_prefix = 'poppende_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.poppende.com';
	public $sendMessageActionURL = 'http://www.poppen.de/messages/messenger/send';
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
													'username' => 'BarKleatt21',
													'password' => 'woringe1'
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
									"age_from" => 60,
									"age_to" => 60,
									"gender" => 'm',
									"status" => "all",
									"country" => 'DE',
									"plz" => '34305',
									"online_user_only" => 2,
									"online_type" => 'w',
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 85;
		}
		
		$this->usernameField = 'nickname';
		$this->loginURL = "http://www.poppen.de/";
		$this->loginActionURL = "http://www.poppen.de/login_prod.php";
		$this->loginRefererURL = "http://www.poppen.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.poppen.de/logout";
		$this->indexURL = "http://www.poppen.de/";
		$this->indexURLLoggedInKeyword = 'Suchen';
		$this->searchURL = "http://www.poppen.de/suche";
		$this->searchActionURL = 'http://www.poppen.de/suche';
		$this->searchRefererURL = "http://www.poppen.de/suche";
		$this->searchResultsPerPage = 25;
		$this->profileURL = "http://www.pof.de/de_viewprofile.aspx?profile_id=";
		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "http://www.poppen.de/newMessenger/profile?render=1&uid=5004397&username=";
		$this->sendMessageURL = "http://www.pof.de/de_sendmessage.aspx";
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
		if($this->getAction() == 'check' || $_GET['action'] == 'check') {
			$this->command['proxy_type'] = 2;
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
				"nickname" => $user['username'],
				"password" => $user['password'],
				"" => "E-Mails lesen!"
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['age_min'])) ? $this->command['age_min'] : $this->command['age_min'])." to ".((empty($this->command['age_max'])) ? $this->command['age_max'] : $this->command['age_max']));
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
			$this->command['age_from'] = $this->command['age_min'];
		}
		if(!empty($this->command['age_t'])) {
			$this->command['age_to'] = $this->command['age_max'];
		}

		for($age=$this->command['age_min']; $age<=$this->command['age_max']; $age++)
		{
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				
				$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);

				
				$search_arr = array(
					'age_min' => $age,
					'age_max' => $age,
					'nickname' => '',
					'gender' => $this->command['gender'],
					'couple_type' => '',
					'country' => $this->command['country'],
					'plz' => $this->command['plz'],
					'with' => 2
				);

				$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
				
				$searchURL = "http://www.poppen.de/suche/gender/" . $this->command['gender'] . "/age_min/" . $age . "/age_max/" . $age . "/with/2/country/" . $this->command['country'];
				
				if (trim($this->command['plz']) != '') {
					$searchURL .= "/plz/" . $this->command['plz'];
				}
				
				$searchURL .= "/page/" . $page;
				
				$this->savelog("Navigating to Search URL " . $searchURL);
				$content = $this->getHTTPContent($searchURL, $this->searchURL, $cookiePath, null, false, $header);
				//$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath, $search_arr, false, $header);
				$this->savelog("Header " . print_r($header, true));
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
						if($list[0]['username'] == $first_username)
						{
							$list = array();
							break;
						}
						if($page == 1)
						{
							$first_username = $list[0]['username'];
						}

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
										$content = $this->getHTTPContent($item['link'], $this->searchURL, $cookiePath);
										$html = str_get_html($content);
										
										$interval = rand(0,4);
										$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
										sleep($interval);
										
										$item['message_link'] = "http://www.poppen.de/newMessenger/profile?render=1&uid=" . $item['uid'] . "&username=" . $item['username'];
										//$item['message_link'] = "http://www.poppen.de/newMessenger/profile?render=1&uid=5007820&username=marC_583";
										$content = $this->getHTTPContent($item['message_link'], $item['link'], $cookiePath);

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
											"action_type" => 0,
											"uid" => $item['uid'],
											//"uid" => 5007820,
											"content" => $message,
										);
										
										
										if(time() < ($this->lastSentTime + $this->messageSendingInterval)) $this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Sending message to ".$item['username']);
										
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->sendMessageActionURL, $item['message_link'], $cookiePath, $message_arr);
											
											file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
											$response = json_decode($content, true);
											if($response['code'] == 0)
											{
												$chat_disabled = FALSE;

												
												DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->savelog("Sending message completed.");
												$this->lastSentTime = time();
												
												$post = array('uid' => $item['uid']);
												$content = $this->getHTTPContent('http://www.poppen.de/messages/messenger/delete_message', 'http://www.poppen.de/posteingang', $cookiePath, $post);
												

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
						
						//reply to inbox messages
					
						//referer http://www.poppen.de/posteingang
						
						$this->savelog("Checking for new messages");
						
						$p = 1;
						
						do {
							
							$this->savelog("Checking for new messages via Json");
							
							//$post = array('page' => $p, 'category' => 'all', 'unread' => 0);
							$content = $this->getHTTPContent('http://www.poppen.de/messages/messenger/threads', 'http://www.poppen.de/posteingang', $cookiePath);
							
							if (trim($content) == '') {
								$this->savelog("invalid json input received");
								return false;
							}
							
							$json = json_decode($content, true);
							
							$this->savelog("Checking Inbox --- last page index received is => ". $json['data']['last_page']);
							
							foreach ($json['data']['threads'] as $thread) {
								
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
										"action_type" => 0,
										"uid" => $thread['uid'],
										//"uid" => 5007820,
										"content" => $message,
								);
								
								
								if(time() < ($this->lastSentTime + $this->messageSendingInterval)) $this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
								$this->savelog("Sending message to ".$thread['name']);
								if(!$this->isAlreadySent($thread['name']) || $enableMoreThanOneMessage)
								{
									$content = $this->getHTTPContent($this->sendMessageActionURL, $item['message_link'], $cookiePath, $message_arr);										
									file_put_contents("sending/pm-".$username."-".$thread['name']."-".$thread['name'].".html",$content);
									$response = json_decode($content, true);
									if($response['code'] == 0)
									{
										
										DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($thread['name'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
										$this->savelog("Sending message completed.");
										$this->lastSentTime = time();
										 
										$post = array('uid' => $thread['uid']);
										$content = $this->getHTTPContent('http://www.poppen.de/messages/messenger/delete_message', 'http://www.poppen.de/posteingang', $cookiePath, $post);

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
								
							}
							
						} while ($json['data']['last_page']  <= ++$p && $json['data']['last_page'] != null);
						
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
			$i = 0;
			foreach($html->find('.profile_li li') as $div) {
				$list[] = array(
					'username' => $div->find("a.lnk_popup",0)->target,
					'uid' => str_replace('poke_', '', $div->find("div",0)->id),
					'link' => $div->find("a.lnk_popup",0)->href
				);
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
		if($this->command['login_by'] == 1 ){
			$username = $this->loginArr[$this->currentUser][$this->usernameField];
			$this->loginArr = array();
			$this->savelog("Site ID : ". $this->siteID);
			$fetch = botutil::getNewProfile($this->siteID, $username, $this->command);
			
			if(empty($fetch['username'])) {
				$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!");
				$this->savelog('FINISHED');
				die();
			} else {
				$this->addLoginData(array(
					array(
						'username' => $fetch['username'],
						'password' => $fetch['password']
					)
				));
				$this->savelog('New profile account is '. $fetch['username']);
				$this->currentUser=0;
			}
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
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.poppen.de/'.$profile, $this->rootDomain, $cookiePath);
			if(strpos($content, 'Benutzername') && !strpos($content,'Zur Startseite')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
}