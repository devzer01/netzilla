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

class smudoo extends bot 
{
	private $_table_prefix = 'smudoo_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://smudoo.com';
	public $sendMessageActionURL = 'http://smudoo.com/index.php';
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
													'username' => 'schulzeemma890@yahoo.de',
													'password' => 'Kaktus56'
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
									"s_alter_von" => 18, //svon
									"s_alter_bis" => 19, //sbis
									"s_geschlecht" => "Herr", //ib
									"status" => "all",
									"s_plz" => '',
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 121;
		}
		
		$this->usernameField = 'uname';
		$this->loginURL = "http://smudoo.com/";
		$this->loginActionURL = "http://smudoo.com/index.php";
		$this->loginRefererURL = "http://smudoo.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://smudoo.com/index.php?logout=1";
		$this->indexURL = "http://smudoo.com/";
		$this->indexURLLoggedInKeyword = 'logout';
		$this->searchURL = "http://smudoo.com/index.php?content=suche";
		$this->searchActionURL = 'http://smudoo.com/index.php';
		$this->searchRefererURL = "http://smudoo.com/index.php?content=suche";
		$this->searchResultsPerPage = 4;
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
				"uname" => $user['username'],
				"pwd" => $user['password'],
				"login" => ""
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['s_alter_von'])) ? $this->command['s_alter_von'] : $this->command['s_alter_von'])." to ".((empty($this->command['s_alter_bis'])) ? $this->command['s_alter_bis'] : $this->command['s_alter_bis']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);


		
		for($age=$this->command['s_alter_von']; $age<=$this->command['s_alter_bis']; $age++)
		{
			$page=0;
			$list=array();
			
			$searchArray = array(
					'content' => 'suche',
					'start_suche' => '1',
					'reset' => '1',
					's_name' => '',
					's_geschlecht' => $this->command['s_geschlecht'],
					's_plz' => $this->command['s_plz'],
					's_alter_von' => $age,
					's_alter_bis' => $age,
					's_fid_34' => ''
			);
			
			$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);
			$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
			$content = $this->getHTTPContent($this->searchActionURL, $this->searchURL, $cookiePath, $searchArray);
			
			// 					$searchURL = "http://smudoo.com/index.php?content=suche&start_suche=1&start=" . ($page * 4);
			// 					$this->savelog("Navigating to Search URL " . $searchURL);
			do
			{

				file_put_contents("search/".$username."-search-".$page.".html",$content);
				$list = $this->getMembersFromSearchResult($username, $page, $content);
	
					if(is_array($list))
					{
						$this->savelog("Found ".count($list)." member(s)");
						if(count($list))
						{

							$this->sleep(5);
							
							foreach($list as $item)
							{
								$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
								//If in runnig time period
								if($sleep_time==0)
								{
									// If not already sent
									if(!$this->isAlreadySent($item['username']))
									{
										///reserve this user, so no other bot can send msg to
										$this->savelog("Reserving profile to send message: ".$item['username']);
										if($this->reserveUser($item['username']))
										{
											// Go to profile page
											$this->savelog("Go to profile page: ".$item['username']);
											$content = $this->getHTTPContent('http://smudoo.com/' . $item['link'], $this->searchURL, $cookiePath);
											$html = str_get_html($content);
											
											$interval = rand(0,4);
											$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
											sleep($interval);
											
											$html = str_get_html($content);
											
											$empf = $html->find("input[name='empf']", 0)->value;
											
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
											
											if(!$this->isAlreadySent($item['username']))
											{
												
												$messageURL = 'http://smudoo.com/index.php?';
												
												$post_data = array(
														'empf' => $empf,
														'content' => 'send_msg',
														'nachricht' => $message,
												);
												
												$this->savelog("Sending Message (debug) ");
												
												$content = $this->getHTTPContent($messageURL, $item['message_link'], $cookiePath, $post_data);
												
												file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
												
												
												if(preg_match("/Deine.Nachricht.wurde.erfolgreich.verschickt/", $content))
												{
													$chat_disabled = FALSE;
													
													DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
													$this->savelog("Sending message completed.");
													$this->lastSentTime = time();
	
													if($this->command['logout_after_sent'] == "Y"){
														if(++$this->count_msg >= $this->command['messages_logout']){
															$this->logout();
															$this->getNewProfile();
															$this->savelog("Sleeping Before Login In " . $this->command['wait_for_login'] . " minutes(s)");
															$this->sleep($this->command['wait_for_login'] * 60);
															$this->login();
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
							} // end foreach
							
						}
					} // end if	
					$page++;
					$searchURL = "http://smudoo.com/index.php?content=suche&start_suche=1&start=" . ($page * 4);
					$content = $this->getHTTPContent($searchURL, $this->searchURL, $cookiePath);
					
				} while(count($list)>=$this->searchResultsPerPage); // end page loop
			} // end age loop
				
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
		$doc = new DOMDocument();
		$doc->strictErrorChecking = FALSE;
		@$doc->loadHTML($content);
		
		//echo "<textarea>" . $content . "</textarea>";
		
		$xml = simplexml_import_dom($doc);
		
		$elmUsername = $xml->xpath("//td[@class='hg_box']/table/tr[2]/td[1]/table/tr[1]/td/table/tr[1]/td/b");
		$elmLinkBox = $xml->xpath("//td[@class='hg_box']/table/tr[2]/td[1]/table/tr[2]/td/table/tr[1]/td[2]/a");
		$list[] = array('username' => 
							trim(preg_replace("/Profil von /","",(string)$elmUsername[0])),
						'link' => (string)$elmLinkBox[0]['href']);
		
		
		$elmUsername = $xml->xpath("//td[@class='hg_box']/table/tr[2]/td[2]/table/tr[1]/td/table/tr[1]/td/b");
		$elmLinkBox = $xml->xpath("//td[@class='hg_box']/table/tr[2]/td[2]/table/tr[2]/td/table/tr[1]/td[2]/a");
		$list[] = array('username' => 
							trim(preg_replace("/Profil von /","",(string)$elmUsername[0])),
						'link' => (string)$elmLinkBox[0]['href']);
		
		$elmUsername = $xml->xpath("//td[@class='hg_box']/table/tr[3]/td[1]/table/tr[1]/td/table/tr[1]/td/b");
		$elmLinkBox = $xml->xpath("//td[@class='hg_box']/table/tr[3]/td[1]/table/tr[2]/td/table/tr[1]/td[2]/a");
		$list[] = array('username' => 
							trim(preg_replace("/Profil von /","",(string)$elmUsername[0])),
						'link' => (string)$elmLinkBox[0]['href']);
		
		$elmUsername = $xml->xpath("//td[@class='hg_box']/table/tr[3]/td[2]/table/tr[1]/td/table/tr[1]/td/b");
		$elmLinkBox = $xml->xpath("//td[@class='hg_box']/table/tr[3]/td[2]/table/tr[2]/td/table/tr[1]/td[2]/a");
		$list[] = array('username' => 
							trim(preg_replace("/Profil von /","",(string)$elmUsername[0])),
						'link' => (string)$elmLinkBox[0]['href']);
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