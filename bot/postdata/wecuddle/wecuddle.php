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

class wecuddle extends bot 
{
	private $_table_prefix = 'wecuddle_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.wecuddle.de/';
	public $sendMessageActionURL = 'http://www.wecuddle.de/messages';
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
													'username' => 'nayana@corp-gems.com',
													'password' => 'x2c4eva'
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
									"svon" => 60, //svon
									"sbis" => 60, //sbis
									"ib" => "m", //ib
									"is" => 'w', //is
									"status" => "all",
									"country" => 'de',
									"umkreis" => 75,
									"plz" => '53225',
									"on" => "false",
									"goldfisch" => 0,
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 96;
		}
		
		$this->usernameField = 'login';
		$this->loginURL = "http://www.wecuddle.de/";
		$this->loginActionURL = "http://www.wecuddle.de/user_sessions";
		$this->loginRefererURL = "http://www.wecuddle.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.wecuddle.de/logout";
		$this->indexURL = "http://www.wecuddle.de/meine_seite/flirtstrom";
		$this->indexURLLoggedInKeyword = 'Abmelden';
		$this->searchURL = "";
		$this->searchActionURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "";
		$this->sendMessagePageURL = "";
		$this->sendMessageURL = "http://www.wecuddle.de/messages";
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
				"login" => $user['username'],
				"password" => $user['password'],
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		
		$this->savelog("Going to user profile and changing plz");
		
		if (!$this->changePlz($this->command['country'], $this->command['plz'])) {
			$this->savelog("invalid plz provided, please correct and re-run");
			return false;
		}
		
		$this->savelog("Going to flirt list and selecting 7 users.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$content = $this->getHTTPContent('http://www.wecuddle.de/followings/followings_index', 'http://www.wecuddle.de/', $cookiePath);
		$csrf_token = $this->getCsrfToken($content);
		
		$sidebar = "http://www.wecuddle.de/followings/followings?page=1&sort=status";
		
		$headers = array(
				'X-CSRF-Token:' . $csrf_token,
				'X-Requested-With:XMLHttpRequest'
		);
		
		$list = $this->getHTTPContent($sidebar, 'http://www.wecuddle.de/followings/followings_index', $cookiePath, null, $headers);
		
		$html_list = str_get_html($list);
		
		$users = array();
		
		foreach ($html_list->find("div.follower") as $human) {
			$link = $human->find("a", 0)->href;
			$userid = preg_replace("/\/users\//", "", $link);
			$username = $human->find("a", 1)->plaintext;
			$users[] = array('link' => $link, 'userid' => $userid, 'username' => $username);
		}
		
		foreach ($users as $item) {
			
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
						$content = $this->getHTTPContent('http://www.wecuddle.de/' . $item['link'], $this->searchURL, $cookiePath);
						
						echo "<textarea>" . htmlspecialchars($content) . "</textarea> <br/>";
						
						$csrf_token = $this->getCsrfToken($content);
						
						$html = str_get_html($content);
						
						$message_receiver_id = $html->find("#message_receiver_id", 0)->value;
						$message_user_id = $html->find("#message_user_id", 0)->value;
						$utf8 = $html->find("input[name='utf8']", 0)->value;
						$authenticity_token = $html->find("input[name='authenticity_token']", 0)->value;
						$external = $html->find("input[name='external']", 0)->value;
			
						$interval = 1;
						$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
						sleep($interval);
			
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
			
						if(time() < ($this->lastSentTime + $this->messageSendingInterval)) {
							$sleep_time = ($this->lastSentTime + $this->messageSendingInterval)-time();
							$this->savelog("Sleeping for [" . $sleep_time . "] second(s)");
							$this->sleep($sleeping_time);
						}
						
						$this->savelog("Sending message to ".$item['username']);
			
						if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
						{
								
							$headers = array(
									'X-CSRF-Token:' . $csrf_token,
									'Origin:http://www.wecuddle.de',
									'X-Requested-With:XMLHttpRequest'
							);
							
							 
							$post_data = "utf8=" . $utf8 . "&authenticity_token=" . $authenticity_token . "&message%5Bcontent%5D=" . $message . "&message%5Breceiver_id%5D=" . $message_receiver_id . "&message%5Buser_id%5D=" . $message_user_id . "&external=" . $external;
								
							$content = $this->getHTTPContent('http://www.wecuddle.de/messages', 'http://www.wecuddle.de' . $item['link'], $cookiePath, $post_data, $headers);
								
							file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
								
							$this->savelog("Message Received [" . htmlspecialchars($content) . "]");
								
							if(preg_match("/Success/", $content))
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
		
				
		$this->savelog("Job completed.");
		return true;
	}

	public function changePlz($country_code, $plz)
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$content = $this->getHTTPContent('http://www.wecuddle.de/einstellungen', 'http://www.wecuddle.de/', $cookiePath);
		
		$csrf_token = $this->getCsrfToken($content);
		
		$html = str_get_html($content);
		
		$action = $html->find('form.edit_user', 0)->action;
		$user_login = $html->find('#user_login', 0)->value;
		$auth_token = $html->find("input[name='authenticity_token']", 0)->value;
		$user_profile_attributes_birthday_3i = $html->find("#user_profile_attributes_birthday_3i", 0)->value;
		$user_profile_attributes_birthday_2i = $html->find("#user_profile_attributes_birthday_2i", 0)->value;
		$user_profile_attributes_birthday_1i = $html->find("#user_profile_attributes_birthday_1i", 0)->value;
		$user_profile_attributes_id = $html->find("#user_profile_attributes_id", 0)->value;
		
		$headers = array(
			'X-CSRF-Token:' . $csrf_token,
			'X-Requested-With:XMLHttpRequest'
		);
		
		$ajax_lookup_url = "http://www.wecuddle.de/ajax/geo_data/" . $country_code . "/" . $plz;
		$geo_content = $this->getHTTPContent($ajax_lookup_url, 'http://www.wecuddle.de/einstellungen', $cookiePath, null, $headers);
		
		if (trim($geo_content) == '') return false;
		
		$html_geo = str_get_html($geo_content);
		
		$geo_datum_id = $html_geo->find("input", 0)->value;
		
		$post_str = "utf8=%E2%9C%93&_method=put&authenticity_token=" . $auth_token . "&user%5Blogin%5D=" . $user_login . "&country_code=" . $country_code 
				  . "&zip_code=" . $plz . "&=user%5Baddress_attributes%5D%5Bgeo_datum_id%5D=" . $geo_datum_id
				  . "&user%5Bprofile_attributes%5D%5Bbirthday%283i%29%5D=" . $user_profile_attributes_birthday_3i 
				  . "user%5Bprofile_attributes%5D%5Bbirthday%282i%29%5D=" . $user_profile_attributes_birthday_2i
				  . "user%5Bprofile_attributes%5D%5Bbirthday%281i%29%5D=" . $user_profile_attributes_birthday_1i
				  . "user%5Bprofile_attributes%5D%5Bid%5D=" . $user_profile_attributes_id
				  . "commit=Save";
		
		$this->getHTTPContent("http://www.wecuddle.de/" . $action, 'http://www.wecuddle.de/einstellungen', $post_str);
		
		return true;
	}
	
	private function getCsrfToken($contnet)
	{
		$html = str_get_html($contnet);
		return $html->find("meta[name='csrf-token']", 0)->content;
	}
		
	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$html = str_get_html($content);
		if(!empty($html)) {
			foreach($html->find('div.kategoriediv') as $div) {
				$list[] = array(
					'username' => $div->find("a.black",0)->href,
					'uid' => $div->find("a.black",0)->href,
					'link' => $div->find("a.black",0)->href
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
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!! / Debug : " . $sql);
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
	
}