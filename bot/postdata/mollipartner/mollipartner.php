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

class mollipartner extends bot
{
	private $_postcode = array('20095','10115','30159','28195','39175','53111','70173','80331','04103','90402','66687');
	public $messagesPart;
	public $messagesPartTemp;
	private $_table_prefix = 'mollipartner_';
	private $_searchResultId = 0;
	private $_currentUser;
	public $rootDomain = 'http://www.mollipartner.de';
	public $sendMessageActionURL = 'http://www.mollipartner.de/message/index/sendofflinemessage/';
	private $nextSearchPage = 'http://www.mollipartner.de/search/result/index/page/';
	public $_sex = array(
		'0' => 'Man',
		'1' => 'Woman',
		'2' => 'Both'
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
																	"username" => "ArabellaB",
																	"password" => "Atzenbarbie"
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
									"start_h" => 09,
									"start_m" => 00,
									"end_h" => 19,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 18,
									"age_to" => 32,
									"gender" => 1,
									"status" => "all",
									"country" => 81,
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 99;
		}
		$this->usernameField = 'email';
		$this->loginURL = "https://www.mollipartner.de/user/login/";
		$this->loginRefererURL = "http://www.mollipartner.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.mollipartner.de/user/logout/";
		$this->indexURL = "http://www.mollipartner.de/";
		$this->indexURLLoggedInKeyword = 'mein Profil';
		$this->searchURL = "http://www.mollipartner.de/search/index/";
		$this->searchActionURL = 'http://www.mollipartner.de/search/';
		$this->searchRefererURL = "http://www.mollipartner.de/search/index/";
		$this->searchResultsPerPage = 6;
		$this->profileURL = "";
		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "http://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
		$this->sendMessageURL = "http://www.planetromeo.com/00000000000000000000000000000000/msg/?uid=";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:24.0) Gecko/20100101 Firefox/24.0";
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
		if($this->command['mp_orientation'] == 'female'){
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
				"referer" => $this->loginRefererURL,
				"email" => $user['username'],
				"password" => $user['password'],
				"password_text" => "Passwort",
				"" => ""
			);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job criterias => Target age: ".((empty($this->command['mp_min_age'])) ? 'N/A' : $this->command['mp_min_age'])." to ".((empty($this->command['mp_max_age'])) ? 'N/A' : $this->command['mp_max_age']));
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$this->_currentUser = $username;

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		if(!empty($this->command['mp_min_age'])) {
			$this->command['age_from'] = $this->command['mp_min_age'];
		}
		if(!empty($this->command['mp_max_age'])) {
			$this->command['age_to'] = $this->command['mp_max_age'];
		}

		foreach($this->_postcode as $postcode) { // Start Loop Postcode
			
			for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
			{
				$page = 1;
				$list=array();
				$first_username = '';
				do
				{
					/******************/
					/***** search *****/
					/******************/
					$search_arr = array(
						'mp_distance' => 100,
						'mp_max_age' => $age,
						'mp_min_age' => $age,
						'mp_orientation' => $this->command['mp_orientation'],
						'mp_postcode' => $postcode,
						'mp_salutation' => (($this->command['mp_orientation'] == 'female') ? 'male' : 'female'),
						"f" => (($page-1)*$this->searchResultsPerPage)
					);
	
					if(isset($this->command))
					{
						foreach($this->command as $key=>$value)
						{
							if($key != 'mp_postcode'){
								$search_arr[$key]=$value;
							}
						}
					}
					
// 					if(!empty($search_arr['mp_postcode'])) {
// 						if(! in_array($search_arr['mp_postcode'], $this->_postcode )) {
// 							$this->_postcode[] = $search_arr['mp_postcode'];	
// 						}
// 					}
					
					
						
					/**
					 	PRE-SEARCH
					**/
					$this->getHTTPContent($this->searchActionURL, $this->searchURL, $cookiePath, $search_arr);				
					/**
					 	END PRE SEARCH
					**/
	
					$this->savelog("Search for Target age: ".$age." to ".$age.", Postcode : ".$postcode." , Page ".$page);
					$content = $this->getHTTPContent($this->nextSearchPage . $page, $this->searchURL, $cookiePath);
				
					file_put_contents("search/".$username."-search-".$page.".html",$content);
	
					/***********************************************/
					/***** Extract profiles from search result *****/
					/***********************************************/
					$list = $this->getMembersFromSearchResult($username, $page, $content);
					$enableMoreThanOneMessage = FALSE;
	
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
							
							$first_username = $list[0]['username'];
	
							$this->sleep(5);
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
											$this->sendMessageURL = $this->rootDomain . $html->find('a.mp_mail',0)->href;
											$this->sleep(5);
	
											/***********************************/
											/***** Go to send message page *****/
											/***********************************/
											$this->savelog("Go to send message page: ".$item['username']);
											$content = $this->getHTTPContent($this->sendMessagePageURL, $item['link'], $cookiePath);
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
											$message_arr = array(
												"form_to" => $item['username'],
												"form_subject" => $subject,
												"form_text" => $message,
												"" => "Nachricht senden"
											);
																					
											if(time() < ($this->lastSentTime + $this->messageSendingInterval))
												$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
											$this->savelog("Sending message to ".$item['username']);
											if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
											{
												$content = $this->getHTTPContent($this->sendMessageActionURL, $this->sendMessagePageURL, $cookiePath, $message_arr);
												file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
	
												if(!strpos($content, "Posteingang"))
												{
													DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
													$this->savelog("Sending message completed.");
													$this->lastSentTime = time();
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
									return true;
								}
							}
						}
					} // END
					// go to one of the profiles in search result, not in sent database
					// send gustbook message
					// save sent message with username in database
	
					$page++;
				}
				while(count($list)>=$this->searchResultsPerPage);
			}
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
		$html = str_get_html($content);
		if(!empty($html->find('a.mp_link',0))) {
			foreach ($html->find('a.mp_link') as $a) {
				$href = $a->href;
				$username = trim(str_replace('/profile/', '', mb_substr($href, 1)));
				if(!empty($username) && $username != $this->_currentUser){
					array_push($list, 
						array(
							"username" => $username,
							"link" => $this->indexURL . $username . '/profile/'
						)
					);
				}
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

				die(print_r($this->loginArr[$this->currentUser]));
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
}
?>