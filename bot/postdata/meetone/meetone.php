<?php
require_once('bot.php');
require_once('simple_html_dom.php');
require_once('pop_lib.php');
require_once('meetone_lib.php');
require_once('pop3.php');


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

class meetone extends bot 
{
	private $_table_prefix = 'meetone_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://api.meetone.com/api/phoneapi.php';
	
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
													'username' => 'katjaheidenreich@gmx.net',
													'password' => 'kunter7bunt'
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
									"minAge" => 20, //svon
									"maxAge" => 21, //sbis
									"gender" => 1, //ib
									"status" => "all",
									"country_code" => 'DE',
									"region_id" => 1364,
									"action" => "send",
									"create_account" => 1
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 127;
		}
		
		$this->usernameField = 'username';
		$this->loginURL = "http://www.fischkopf.de/";
		$this->loginActionURL = "http://www.fischkopf.de/index.php?page=account&aktion=login";
		$this->loginRefererURL = "http://www.fischkopf.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.fischkopf.de/index.php?page=account&aktion=logout";
		$this->indexURL = "http://www.fischkopf.de/index.php?page=account";
		$this->indexURLLoggedInKeyword = 'Logout';
		$this->searchURL = "http://www.fischkopf.de/index.php?page=suchen&sres=1";
		$this->searchActionURL = 'http://www.fischkopf.de/index.php?page=suchen';
		$this->searchRefererURL = "http://www.fischkopf.de/index.php?page=suchen";
		$this->searchResultsPerPage = 10;
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
		
		$this->zipcodes = array(
				"1" => array(
						"short" => array(
								"01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089"
						),
						"long" => array(
								"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
						)
				),
				"2" => array(
						"short" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						),
						"long" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						)
				),
				"3" => array(
						"short" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						),
						"long" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						)
				)
		);
		
		$target = "Male";
		if($this->command['gender'] == '2'){
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
				"username" => $user['username'],
				"password" => $user['password'],
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	function getPopServerFromEmail($username)
	{
		$host = "";
		if (preg_match("/gmx/", $username)) {
			$host = "ssl://pop.gmx.net";
		} else if(preg_match("/outlook|hotmail|live/", $username)) {
			$host = "ssl://pop-mail.outlook.com";
		} else if (preg_match("/web.de/", $username)) {
			$host = "pop3.web.de";
		}
	
		return $host;
	}
	
	function clear_inbox($username, $password) {
	
		$host = $this->getPopServerFromEmail($username);
		
		$res = pop_open($host, 995, $username, $password);
	
		$msg_count = pop_messagecount($res);
	
		$this->savelog(sprintf("Found %d Message(s)\n", $msg_count));
	
		for ($i=1; $i<= $msg_count; $i++) {
			pop_delete($res, $i);
			$this->savelog(sprintf("Deleting Message %d", $i));
		}
	
		pop_close($res);
	}
	
	function getActiveLink($username, $password)
	{
		$message = $this->get_message($username, $password);
	
		preg_match('/a.href="([^"]+)"/', $message, $matches);
	
		return $matches[1];
	}
	
	
	function get_message($username, $password) {
	
		$host = $this->getPopServerFromEmail($username);
		
		$res = pop_open($host, 995, $username, $password);
	
		$this->savelog(sprintf("Connected to host \n"));
	
		$message = "";
	
		if (pop_messagecount($res) == 1) {
			$this->savelog("One message found on mail box");
			$message = pop_message($res, 1);
		}
	
		pop_close($res);
	
		return $message;
	
	}
	
	public function startCreateAccount()
	{
		$account = $this->getNextEmailAccount();
		
		$this->savelog("Login into mailbox and deleting old entries, this may take a while - [" . $account['user'] . "]");
		
		$this->clear_inbox($account['user'], $account['password']);
		
		list($name, $host) = preg_split("/@/", $account['user'], 2);
		$gender = 'FEMALE';
		if ($this->command['gender'] == 2) $gender = 'MALE';
		$dob = getBirthDayFromAge($this->command['minAge'] + 2);
		
		$this->savelog("Generate a random birthday based on age range " . $dob);
		
		list($city_id, $city_name) = getRandomCityFromRegion($this->command['region_id']);
		
		$this->savelog("Picking a random city in Region - " . $city_name);
		
		$ret = $this->createAccount($account['user'], strrev($account['password']), $name, $gender, $dob, $this->command['country_code'], $this->command['region_id'], $city_id);
		if ($ret) {
			$this->savelog("Account created, Waiting for activation email arrival");
			$this->sleep(120);
			
			$link = $this->getActiveLink($account['user'], $account['password']);
			
			$this->savelog("Got Activation Link " . $link);
			
			file_get_contents($link);
		} else {
			$this->savelog("Account creation failed");
		}
		
		return $ret;
		
	}

	
	public function createAccount($user, $password, $name, $gender, $dob, $country_code, $region_id, $city_id) 
	{
		$year = date("Y", strtotime($dob));
		$month = date("m", strtotime($dob));
		$day = date("d", strtotime($dob));
		$signup = "http://api.meetone.com/api/phoneapi.php?format=json&service=member&action=signUpAccount"
		        . "&sex=$gender&firstName=$name&password=$password&password2=$password&email=$user&dateOfBirth_day=$day&dateOfBirth_month=$month&dateOfBirth_year=$year&countryCode=$country_code&regionID=$region_id&cityID=$city_id&origin=&acceptConditions=1&height=&deviceType=android";
		
		$signup_resp = file_get_contents($signup);
		$json = json_decode($signup_resp, true);
		
		if (isset($json['authorization']) && $json['authorization']['result'] == 'true') {
			$this->session_id = $json['authorization']['sessionId'];
			$this->user_id = $json['memberId'];
			$this->savelog("Created account for : ".$user);
			
			$sql = "INSERT INTO user_profiles (userid, username, password, status, site_id, sex, created_datetime) "
			     . "VALUES (" . $this->user_id . ", '" . $user . "', '" . $password . "', 'true', " . $this->siteID . ", '" . $gender ."', NOW())";
			$this->savelog($sql);
			DBconnect::execute($sql); 
			return true;
		} else {
			if ($json['errors'][0]['code'] == 2002) {
				$this->savelog("Error, email account already has profile, password unknown, adding a disabled profile");
				$sql = "INSERT INTO user_profiles (userid, username, password, status, site_id, sex, created_datetime) "
				. "VALUES (0, '" . $user . "', '" . $password . "', 'false', " . $this->siteID . ", '" . $gender ."', NOW())";
				DBconnect::execute($sql);
				return false;
			} else {
				$this->savelog($signup_resp);
			}
		}
		
		return false;
	}
	
	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['minAge'])) ? $this->command['minAge'] : $this->command['minAge'])." to ".((empty($this->command['maxAge'])) ? $this->command['maxAge'] : $this->command['maxAge']));
		$this->savelog("Job started.");
		$usernameFrom = $this->loginArr[$this->currentUser][$this->usernameField];

		for($age=$this->command['minAge']; $age<=$this->command['maxAge']; $age++)
		{	
			$searchURL = "http://api.meetone.com/api/phoneapi.php?format=json&service=member&action=searchMembers&minAge=" . $age . "&maxAge=" . ($age + 1) . "&countryCode=" . $this->command['country_code'] . "&regionID=" . $this->command['region_id'] . "&status=" . $this->command['status'] . "&gender=" . $this->command['gender'] . "&lang=en&sid=" . $this->session_id;	
			$searchJSON = file_get_contents($searchURL);
			$results = json_decode($searchJSON, true);
			
			if ($results['result'] != 'true') {
				$this->savelog("Search rendered no results " . $searchJSON);
				continue;
			}
			
			$this->savelog("Found ".count($results['members']['city']['data'])." member(s)");
			
			for ($i=0; $i< count($results['members']['city']['data']); $i++) {
				
				$member_id = $results['members']['city']['data'][$i];
				
				$memberDetailURL = "http://api.meetone.com/api/phoneapi.php?format=json&service=member&action=getMultiMembersData&ids%5B%5D=" . $member_id;
				$memberDetailJSON = file_get_contents($memberDetailURL);
				
				$memberDetail = json_decode($memberDetailJSON, true); 
				
				if ($memberDetail['result'] != 'true') {
					$this->savelog("Error receiving member detail " . $memberDetailJSON);
					break;
				}
				
				$this->sleep(5);
				
				$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
				//If in runnig time period
				if($sleep_time==0) {
					
					$username = $member_id . $memberDetail['data'][$member_id]['username'];
					
					if(!$this->isAlreadySent($username)) {
						///reserve this user, so no other bot can send msg to
						$this->savelog("Reserving profile to send message: ".$username);
						if($this->reserveUser($username))
						{								
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
							
							$this->savelog("Sending message to ".$username);
								
							if(!$this->isAlreadySent($username))
							{
					
								$messageURL = "http://api.meetone.com/api/phoneapi.php?format=json&service=member&action=sendNewEmail&recipientMemberID=" . $member_id . "&deviceType=iphone&subject=" . urlencode($subject) . "&message= " . urlencode($message) . "&sid=" . $this->session_id;
								$messageJSON = file_get_contents($messageURL);
								$messageSent = json_decode($messageJSON, true);
					
								if($messageSent['result'] == 1)
								{
									DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($username)."','".$usernameFrom."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->savelog("Sending message completed.");
									$this->lastSentTime = time();
					
									if($this->command['logout_after_sent'] == "Y"){
										if(++$this->count_msg >= $this->command['messages_logout']){
											return true;
										}
									}
								}
								else
								{
									if (isset($messageSent['errors']) && $messageSent['errors']['code'] == 1009) {
										$this->savelog("Account disabled, exiting loop");
										return false;
									} else {
										$this->savelog("Sending message failed.");
										$this->savelog($messageJSON);
									}
								}
							}
												
					} else {
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$username);
					}
						
					$this->cancelReservedUser($username);
					$this->sleep(2);
					
					} else {
						$this->savelog("Already send message to profile: ".$username);
					} 
				} else {
					$this->savelog("Not in running time period.");
					$this->sleep($sleep_time);
				}
			}
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
	
}