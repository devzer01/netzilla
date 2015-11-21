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

class fischkopf extends bot 
{
	private $_table_prefix = 'fischkopf_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.fischkopf.de';
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
								// 'username' => 'wilseide',
								// 'password' => 'schaatzen11'
								'username' => 'devzer0',
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
				"svon" => 46, //svon
				"sbis" => 60, //sbis
				"ib" => "m", //ib
				"is" => 'w', //is
				"status" => "all",
				"country" => 'DE',
				"umkreis" => 75,
				"splz" => '53225',
				"on" => "false",
				"goldfisch" => 0,
				"action" => "send",
				'logout_after_sent' => 'Y',
			    'messages_logout' => 1,
			    'wait_for_login' => 0.1,
			    'login_by' => 1
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 94;
		}
		
		$this->usernameField = 'lbenutzer';
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
				"lbenutzer" => $user['username'],
				"lpasswort" => $user['password'],
				"login" => ""
			);
			array_push($this->loginArr, $login_arr);
		}
	}

	public function sendOnline()
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$this->savelog("Going to online users only page");
		$content = $this->getHTTPContent('http://www.fischkopf.de/index.php?page=useronline', 'http://www.fischkopf.de/index.php', $cookiePath);
		
		$html = str_get_html($content);
		$users_online = $html->find("form[name='sortierung']", 0)->find("p", 2)->find("b", 0)->plaintext;
		
		$this->savelog("Users Online: " . $users_online);
		
		$this->savelog("Selecting only male");
		
		$online_url = "http://www.fischkopf.de/index.php?page=useronline&ug=m&srt=l&gf=f&img=f&start=0";
		
		$content = $this->getHTTPContent($online_url, "http://www.fischkopf.de/index.php?page=useronline", $cookiePath);
		
		$html = str_get_html($content);
		
		$list = array();
		
		foreach ($html->find("div.entry") as $entry) {
			$username = $entry->find("span.username", 0)->find("a", 0)->href;
			$userid = $username;
			$link = $username;
			
			$list[] = array('userid' => $userid, 'username' => $username, 'link' => $link);
		}
				
		$page = 1;
		
		do {
		
			$next_page = false;
			
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
							$content = $this->getHTTPContent('http://www.fischkopf.de/' . $item['link'], $this->searchURL, $cookiePath);
							$html = str_get_html($content);
			
							$interval = rand(0,4);
							$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
							sleep($interval);
			
							$item['message_link'] = "http://www.fischkopf.de/index.php?page=mail&touser=" . $item['username'];
							do {
								$this->savelog($item['message_link']);
								$content = $this->getHTTPContent($item['message_link'], $item['link'], $cookiePath);
								sleep(1);
							} while ($content == '');
			
							$html = str_get_html($content);
			
							$xsrf = $html->find("input[type='hidden']", 1)->value;
							$xsrf_name = $html->find("input[type='hidden']", 1)->name;
			
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
									
								$messageURL = 'http://www.fischkopf.de/index.php?page=mail';
									
								$post_data = array(
										'touser' => $item['username'],
										$xsrf_name => $xsrf,
										'betreff' => $subject,
										'nachricht' => $message,
										'keinekopie' => 'on',
										'submit' => 'Send Message',
										'wysiwygloaded'  => 'true'
								);
									
								$this->savelog("Sending Message (debug) " . $messageURL . " - " . $xsrf . " - " . $xsrf_name);
									
								$content = $this->getHTTPContent($messageURL, $item['message_link'], $cookiePath, $post_data, false, $x);
									
								file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
									
									
								if(preg_match("/Aktion.erfolgreich/", $content))
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
			} // end foreach
			
			$this->savelog("Navigating to next page");
			$next = "http://www.fischkopf.de/index.php?page=useronline&p=" . (++$page);
			$content = $this->getHTTPContent($next, 'http://www.fischkopf.de/index.php?page=useronline', $cookiePath);
			$html = str_get_html($content);
			
			if (count($html->find("div.entity")) > 0) $next_page = true;
			
			if ($next_page) {
				$list = array();
				
				foreach ($html->find("div.entry") as $entry) {
					$username = $entry->find("span.username", 0)->find("a", 0)->href;
					$userid = $username;
					$link = $username;
						
					$list[] = array('userid' => $userid, 'username' => $username, 'link' => $link);
				}
				
				$this->savelog("Found " . count($list) . " user(s) on the next page");
				
			} else {
				$this->savelog("Next page has no user(s)");
				$next_page = false;
			}
			
			
		} while ($next_page == true);
		
		
		$this->savelog("Job completed.");
		return true;
	}
	
	public function work()
	{

		$this->savelog("Job criterias => Target age: ".((empty($this->command['svon'])) ? $this->command['svon'] : $this->command['svon'])." to ".((empty($this->command['sbis'])) ? $this->command['sbis'] : $this->command['sbis']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);


		$plz = $this->zipcodes[1]['long'];
		if($key = array_search($this->command['start_plz'],$plz))
		{
			$plz = array_slice($plz, $key);
		}
		
		
		foreach ($plz as $splz) {
			
			$this->savelog("Trying plz" . $splz);
			
			for($age=$this->command['svon']; $age<=$this->command['sbis']; $age++)
			{
				$page=1;
				$list=array();
				$first_username = '';
				do
				{
					
					$content = $this->getHTTPContent($this->searchURL, $this->searchURL, $cookiePath);
	
					$this->savelog("Search for Target age: ".$age." to ".$age.", page ".$page);
					
					$searchURL = "http://www.fischkopf.de/index.php?page=suchen&p=" . $page . "&ib=" . $this->command['ib'] . "&is=" . $this->command['is'] . "&svon=" . $age . "&sbis=" . $age . "&umkreis=" . $this->command['umkreis'] . "&splz=" . $splz . "&on=" . $this->command['on'] . "&img=false&srt=1&gff=1&submitsearch=Search&gvon=&gbis=&sucheart_uo_suche=1&figur_uo_suche=1&haarfarbe_uo_suche=1&frisur_uo_suche=1&augenfarbe_uo_suche=1&koerperschmuck_uo_suche=1&modestil_uo_suche=1&charakter_uo_suche=1&wichtig_uo_suche=1&ichbin_uo_suche=1&sport_uo_suche=1&sport_aktiv_uo_suche=1&hobbies_uo_suche=1&freizeit_uo_suche=1&musik_uo_suche=1&tv_uo_suche=1&zeitungen_uo_suche=1&haustiere_uo_suche=1&raucher_uo_suche=1&sternzeichen_uo_suche=1&alkohol_uo_suche=1&wohnen_uo_suche=1&schule_uo_suche=1&beruf_uo_suche=1&religion_uo_suche=1&sprachen_uo_suche=1&famstatus_uo_suche=1&page=suchen&sres=1";
					
					$this->savelog("Navigating to Search URL " . $searchURL);
					$content = $this->getHTTPContent($searchURL, $this->searchURL, $cookiePath, null, false, $header);
					file_put_contents("search/".$username."-search-".$page.".html",$content);
	
					/***********************************************/
					/***** Extract profiles from search result *****/
					/***********************************************/
					
					$list = $this->getMembersFromSearchResult($username, $page, $content);
					
	
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
											$content = $this->getHTTPContent('http://www.fischkopf.de/' . $item['link'], $this->searchURL, $cookiePath);
											$html = str_get_html($content);
											
											$interval = rand(0,4);
											$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
											sleep($interval);
											
											$item['message_link'] = "http://www.fischkopf.de/index.php?page=mail&touser=" . $item['username'];
											do {
												$this->savelog($item['message_link']);
												$content = $this->getHTTPContent($item['message_link'], $item['link'], $cookiePath);
												sleep(1);
											} while ($content == '');
											
											$html = str_get_html($content);
											
											$xsrf = $html->find("input[type='hidden']", 1)->value;
											$xsrf_name = $html->find("input[type='hidden']", 1)->name;
											
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
												
												$messageURL = 'http://www.fischkopf.de/index.php?page=mail';
												
												$post_data = array(
														'touser' => $item['username'],
														$xsrf_name => $xsrf,
														'betreff' => $subject,
														'nachricht' => $message,
														'keinekopie' => 'on',
														'submit' => 'Send Message',
														'wysiwygloaded'  => 'true'
												);
												
												$this->savelog("Sending Message (debug) " . $messageURL . " - " . $xsrf . " - " . $xsrf_name);
												
												$content = $this->getHTTPContent($messageURL, $item['message_link'], $cookiePath, $post_data, false, $x);
												
												file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
												
												
												if(preg_match("/Aktion.erfolgreich/", $content))
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
					}
	
					// go to one of the profiles in search result, not in sent database
					// send gustbook message
					// save sent message with username in database
	
					$page++;
				} while(count($list)>=$this->searchResultsPerPage); // end page loop
			} // end age loop
		} // end plz loop 
				
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

	public function getNewProfile() {
		
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
			$content = $this->getHTTPContent('http://www.fischkopf.de/'.$profile, $this->indexURL, $cookiePath);
			if(!strpos($content,'nicht vorhanden')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
}