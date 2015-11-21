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

class lebensfreude50 extends bot
{

	public $age_range = array();
	public $_sex = array(
		'0' => 'Woman',
		'1' => 'Man',
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
												"username" => "NichtnurMama",
												"password" => "Nettikette"
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
									"start_h" => 10,
									"start_m" => 00,
									"end_h" => 20,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 45,
									"age_to" => 50,
									"gender" => 1,
									"status" => "all",
									"country" => 81,
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 99;
		}

		// $this->usernameField = "username";
		$this->loginURL = "http://www.lebensfreude50.de/index.php";
		$this->loginRefererURL = "http://www.lebensfreude50.de/index.php";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.lebensfreude50.de/login.php?ac=logout";
		$this->indexURL = "http://www.lebensfreude50.de/";
		$this->indexURLLoggedInKeyword = 'meinprofil.php';
		$this->searchURL = "http://www.lebensfreude50.de/partnersuche.php";
		$this->searchRefererURL = "http://www.lebensfreude50.de/partnersuche.php";
		$this->searchResultsPerPage = 12;
		$this->profileURL = "http://www.lebensfreude50.de/showprofil.php?shuser=";

		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "http://www.lebensfreude50.de/kontakt.php?mto=";
		$this->sendMessageURL = "http://www.lebensfreude50.de/kontakt.php?mto=";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
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
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		$target = "Female";
		if($this->command['Suche'] == 1){
			$target = "Male";
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
				"NickName" => $user['username'],
				"Passwort" => $user['password'],
				"gologin" => "Senden"
			);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job criterias => Target gender: ".((empty($this->command['Suche'])) ? 'N/A' : $this->_sex[$this->command['Suche']]).", age: ".((empty($this->command['WunschAlterPartnerVon'])) ? 'N/A' : $this->command['WunschAlterPartnerVon'])." to ".((empty($this->command['WunschAlterPartnerBis'])) ? 'N/A' : $this->command['WunschAlterPartnerBis']).", status: ".$this->command['status'].", start time ".$this->command['start_h'].":".$this->command['start_m'].", end time ".$this->command['end_h'].":".$this->command['end_m']);
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		if(empty($this->command['WunschAlterPartnerVon'])) {
			$this->command['WunschAlterPartnerVon'] = 45;
		}
		if(empty($this->command['WunschAlterPartnerBis'])) {
			$this->command['WunschAlterPartnerBis'] = 80;
		}

		for($age=$this->command['WunschAlterPartnerVon']; $age<=$this->command['WunschAlterPartnerBis']; $age++)
		{
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					"Bin" => 0,
					"Suche" => 1,
					"FuerBeziehung" => 1,
					"FuerFlirt" => 1,
					"FuerReisepartnerschaft" => 1,
					"FuerWohngemeinschaft" => 1,
					"WunschAlterPartnerVon" => $age,
					"WunschAlterPartnerBis" => $age,
					"us_umkreis" => 75,
					"searchbutton" => "suchen",
					"f" => (($page-1)*$this->searchResultsPerPage)
				);

				$_arr = array("Bin",
					"Suche",
					"FuerBeziehung",
					"FuerFlirt",
					"FuerReisepartnerschaft",
					"FuerWohngemeinschaft",
					"WunschAlterPartnerVon",
					"WunschAlterPartnerBis",
					"us_umkreis",
					"searchbutton");

				foreach($this->command as $key=>$value)
				{
					if(in_array($key, $_arr)){
						$search_arr[$key]=$value;
					}
				}				


				
				$search_arr['WunschAlterPartnerVon'] = $age;
				$search_arr['WunschAlterPartnerBis'] = $age;
				

				for($i = $search_arr['WunschAlterPartnerVon']; $i <= $search_arr['WunschAlterPartnerBis']; $i++) {
					$this->age_range[] = $i; 
				}

				$url = $this->searchURL."?".http_build_query($search_arr);
				$this->savelog("Search for I'm ".$this->_sex[$this->command['Bin']]." Target gender: ".$this->_sex[$this->command['Suche']].", age: ".$age." to ".$age.", page ".$page." URL : ".$url);
				$content = $this->getHTTPContent($url, $this->searchRefererURL, $cookiePath);
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content, $search_arr['WunschAlterPartnerVon'], $search_arr['WunschAlterPartnerBis']);

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
										$content = $this->getHTTPContent($this->profileURL.$item['uid'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										//$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										$item['userid'] = substr($content, strpos($content, "nachricht_schreiben.php?id=")+27);
										$item['userid'] = substr($item['userid'], 0, strpos($item['userid'], "\""));

										$this->sleep(5);

										/***********************************/
										/***** Go to send message page *****/
										/***********************************/
										$this->savelog("Go to send message page: ".$item['username']);
										$content = $this->getHTTPContent($this->sendMessagePageURL.$item['uid'], $this->profileURL.$item['uid'], $cookiePath);

										if(strpos($content,"url=transaction.php") == false) {
											$this->sleep(5);

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
												"page_from" => $this->searchURL,
												//"page_from" => $this->profileURL.$item['username'],
												"mtonickname" => $item['username'],
												"mto" => $item['uid'],
												"betreff" => $subject,
												"longmessage" => $message,
												"sendmessage" => "Senden"
											);
											if(time() < ($this->lastSentTime + $this->messageSendingInterval))
												$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
											$this->savelog("Sending message to ".$item['username']);
											if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
											{
												$content = $this->getHTTPContent($this->sendMessageURL.$item['uid'], $this->sendMessagePageURL.$item['uid'], $cookiePath, $message_arr);
												file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);

												if(strpos($content, "wurde versendet")!==false)
												{
													DBConnect::execute_q("INSERT INTO lebensfreude50_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
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
										} else {
											$this->savelog("[failed] - This profile is Paid required to send message");
											exit();
										}
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

	private function getMembersFromSearchResult($username, $page, $content, $ageSearch = 50, $max = 50)
	{
		$list = array();
		$html = str_get_html($content);
		if(!empty($html)) {
			$i = 0;
			$pointer = 0;
			$table1 = $html->find('td.StartFliessWhite11',1);
			if(!empty($table1)) {
				if(strpos($table1,"Anzeigen") !== false) {
					$pointer = 1;
				} else {
					$table2 = $html->find('td.StartFliessWhite11',2);	
					if(!empty($table2)) {
						if(strpos($table2,"Anzeigen") !== false) {
							$pointer = 2;
						}
					}
				}
			}
			
			$this->savelog("Set pointer: ".$pointer);
			echo "<p>pointer : ".$pointer."</p>";
			
			if($pointer != 0) {
				foreach($html->find('td.StartFliessWhite11',$pointer)->find('table') as $td) {
					if(($i%3) == 0) {
						$html = htmlspecialchars($td);
						$pos = strpos($html, "Jahre");
						$age = (int) trim(substr($html, ( $pos - 4 ), 4));
						if($age == $ageSearch){
							$username = $td->find('b',0)->plaintext;
							$link = trim($td->find('a.button',4)->href);
							if(!empty($username)){
								array_push($list, 
									array(
										"uid" => str_replace('sendGruss.php?grussID=', '', $link),
										"username" => trim($td->find('b',0)->plaintext),
										"link" => $link
									)
								);
							}
						}
					}
					$i++;
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM lebensfreude50_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM lebensfreude50_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO lebensfreude50_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM lebensfreude50_reservation WHERE username='".$username."' AND server=".$this->botID);
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
}

