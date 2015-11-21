<?php
require_once('bot.php');

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

class meetme extends bot
{
	private $target = 'Male';
	public $sendmsg_total = 0;
	public function meetme($post)
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
			file_put_contents("logs/".$commandID."_post.log",print_r($post,true));
			file_put_contents("logs/".$commandID."_run_count.log",$runCount);
		}
		else
		{
			$this->command = array(
									"profiles" => array(
															array(
																	//"username" => "andrews_alexandra@rocketmail.com",
																	//"password" => "workably"
																	"username" => "schuster.simmerath@gmx.de",
																	"password" => "eingerichtet"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "hallo"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 5,
									"age_from" => 18,
									"age_to" => 20,
									"gender" => "male",
									"msg_type" => "qa",
									"send_test" => 1,
									"distance" => 20,
									"country" => "United Kingdom",
									"start_city" => "London",
									"start_page" => 1,
									"version" => 1,
									//"full_msg" => 1,
									"online" => "f",
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 58;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->token = "";
		$this->databaseName = "meetme";
		$this->usernameField = "username";
		$this->indexURL = "http://www.meetme.com/apps/home";
		$this->indexURLLoggedInKeyword = "//profile.meetme.com/views";
		$this->loginURL = "https://ssl.meetme.com/login";
		$this->loginRefererURL = "http://www.meetme.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.meetme.com/apps/home";
		$this->searchPageURL = "http://friends.meetme.com/locals";
		$this->searchURL = "http://friends.meetme.com/locals/browserData";
		$this->searchRefererURL = "http://friends.meetme.com/locals";
		$this->searchResultsPerPage = 30;
		$this->profileURL = "http://www.meetme.com/member/";
		$this->sendMessagePageURL = "";
		$this->sendMessageURL = "http://www.meetme.com/ajax/ajax_myfriendsupdate.php";
		$this->sendQuestionURL = "http://feed.meetme.com/askMe/json/submit";
		$this->inboxURL = "http://messages.meetme.com/ajax/deleteThread";
		$this->deleteInboxURL = "http://single.de/Rest/postfach-threadlist/";
		$this->deleteInboxRefererURL = "http://messages.meetme.com/#Inbox";
		$this->outboxURL = "http://messages.meetme.com/ajax/getPage/sent/1";
		$this->deleteOutboxURL = "http://messages.meetme.com/ajax/deleteThread";
		$this->deleteOutboxRefererURL = "http://messages.meetme.com/#Sent";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
		$this->commandID = $commandID;
		$this->workCount = 1;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->runCount = $runCount;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->message="";
		$this->newMessage=true;
		$this->cities = array(
			"Germany" => array(
				"Berlin", "Hamnburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Halle (Saale)", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Müllheim an der Ruhr", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Offenbach am Main", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
			),
			"United Kingdom" => array(
				"london", "Birmingham", "Leeds", "glasgow", "Sheffield", "Bradford", "Liverpool", "Edinburgh", "manchester", "bristol", "Kirklees", "Fife", "Wirral", "North Lanarkshire", "Wakefield", "Cardiff", "Dudley", "Wigan", "East Riding", "South Lanarkshire", "Coventry", "Belfast", "Leicester", "Sunderland", "Sandwell", "Doncaster", "Stockport", "Sefton", "Nottingham", "Newcastle-upon-Tyne", "Kingston-upon-Hull", "Bolton", "Walsall", "Plymouth", "Rotherham", "Stoke-on-Trent", "Wolverhampton")
		);
		
		
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
				"username" => $user['username'],
				"password" => $user['password'],
				"quicklogin" => 1,
				"currentLocale" => "en-US"
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	public function work()
	{
		$this->savelog("Job started, bot version ".$this->command['version']);
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);
		$this->newMessage=false;

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchPageURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$first_username = '';
			$cities = $this->cities[$this->command['country']];
			if(($this->workCount == 1) && ($age==$this->command['age_from']))
			{
				if($key = array_search($this->command['start_city'],$cities))
				{
					$cities = array_slice($cities, $key);
				}
			}

			foreach($cities as $city)
			{
				$list=array();

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($city==$this->command['start_city']))
					$page=$this->command['start_page'];
				else
					$page=1;

				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$details = "";
						$search_arr = array(
												"minAgeFilter" => $age,
												"maxAgeFilter" => $age,
												"gender" => $this->command['gender'],
												"sortBy" => "lastlogin",
												"online" => $this->command['online'],
												"friends" => "t",
												"p" => ($page-1),
												"pgs" => 1,
												"ignoreNewUsers" => "f",
												"location" => strtolower($city).", ".strtolower($this->command['country'])
										);

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.",online: ".$this->command['online'].", city: ".$city.", country: ".$this->command['country'].", page: ".$page);

						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",$content);

						/***********************************************/
						/***** Extract profiles from search result *****/
						/***********************************************/
						$list = $this->getMembersFromSearchResult($username, $page, $content);

						if(is_array($list))
						{
							$this->savelog("Found ".count($list)." member(s)");

							if($list[0]['username'] == $first_username)
							{
								$list = array();
								break;
							}
							
							$first_username = $list[0]['username'];

							if(count($list))
							{
								$this->sleep(5);
								foreach($list as $key => $item)
								{
									$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
									//If in runnig time period
									if($sleep_time==0)
									{
										$this->work_sendMessage($username, $item, $cookiePath);
										/*if($this->command['version']==1)
										{
											//$item['username'] = "MichaellaX";
											//$item['userid'] = "61255115";
											//$item['username'] = "Andrews";
											//$item['userid'] = "61200778";
											$this->work_sendMessage($username, $item, $cookiePath);
											//exit;
										}
										elseif($this->command['version']==2)
										{
											$this->work_visitProfile($username, $item, $cookiePath);
											$this->work_sendMessage($username, $item, $cookiePath);
										}
										else
										{
											$this->savelog("Wrong version selected.");
										}*/
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

						if($this->command['version']==1)
						{
							//$this->deleteAllOutboxMessages($username, $cookiePath);
						}
						elseif($this->command['version']==2)
						{
							$inbox = $this->getInboxMessages($username, $cookiePath);
							if(is_array($inbox))
							{
								$this->savelog("Found ".count($inbox)." inbox message(s)");
								$this->sleep(5);
								if(count($inbox)>=$this->inboxLimit)
								{
									foreach($inbox as $key => $item)
									{
										$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
										//If in runnig time period
										if($sleep_time==0)
										{
											if(!$this->work_sendMessage($username, $item, $cookiePath))
												return false;
										}
										else
										{
											$this->savelog("Not in running time period.");
											$this->sleep($sleep_time);
											return true;
										}
									}
									//$this->deleteAllOutboxMessages($username, $cookiePath);
								}
							}
						}
						$page++;
					}
					else
					{
						$this->savelog("This profile: ".$username." does not log in.");
						if($this->login())
						{
							$list = range(1,$this->searchResultsPerPage);
						}
						else
						{
							return false;
						}
					}
				}
				while(count($list)>=$this->searchResultsPerPage);
			}

			if($age=="-")
				$age=$this->command['age_to'];
		}

		$this->savelog("Job completed.");
		$this->workCount++;
		return true;
	}

	private function getMembersFromSearchResult($username, $page, $content){

		$list = array();
		$data = json_decode($content);

		if(isset($data->success) && ($data->success==1))
		{
			foreach($data->localsData[0] as $item)
			{
				if(isset($item->member_id))
				{
					$profile = array(
										"username" => $item->firstName,
										"userid" => $item->member_id
									);
					array_push($list,$profile);
				}
			}
		}
		return $list;
	}

	private function getProfilePhoto($username, $name, $content){
		$photo = "";
		if(strpos($content, '<div class="colOne top profileHeader"')!==false)
		{
			$content = substr($content, strpos($content, '<div class="colOne top profileHeader"'));
			$content = substr($content, 0, strpos($content, '<div class="tabnav'));
			$parser = $this->convertToXML($username, "photo-".$name, $content);

			// Check if it's correct result
			if(strpos($parser->document->div[0]->tagAttrs['class'], "profileHeader")!==false)
			{
				$photo = $parser->document->div[0]->a[0]->tagAttrs['href'];
			}
		}
		return $photo;
	}

	public function getAction(){
		return $this->command['action'];
	}

	public function getSiteID(){
		return $this->siteID;
	}

	public function checkLogin($username, $password){
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

	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item['username']." [".$item['userid']."]");
		$content = $this->getHTTPContent($this->profileURL.$item['userid'], $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		$this->token = $this->getToken($content);
		return $content;
	}

	private function getToken($content)
	{
		$token = substr($content, strpos($content, "var tok =")+11);
		$token = substr($token, 0, strpos($token, "\""));
		return $token;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
		{
			$text = botutil::getMessageText($this, $this->target, 'EN');
            $subject = $text['subject'];
            $message = $text['message'];
			$this->savelog("Message is : ".$message);

			// Go to profile page
			$utime = $this->utime();
			$callback = "jQuery17103374412574339658_".$utime;
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']." [".$item['userid']."]");
			if($this->reserveUser($item['username'], $item['userid']))
			{
				if($this->command['msg_type']=="pm")
				{
					$message_arr = array(
											"callback" => $callback,
											"token" => $this->token,
											"type" => "quickmessage",
											"userid" => $item['userid'],
											"qmsubject" => $subject,
											"qmbody" => $message
									);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']." [".$item['userid']."]");
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$engagementType = array("MessagesNonCoreCountry", "MessagesCoreCountry");
						for($i=0; $i<2; $i++)
						{
							if($i == 2)
								$this->savelog("Retrying to send message to ".$item['username']." [".$item['userid']."]");
							$message_arr['engagementType'] = $engagementType[$i];
							$message_arr['_'] = $this->utime();
							$url = $this->sendMessageURL."?".http_build_query($message_arr);
							$url_referer = $this->profileURL.$item['userid'];
							$content = $this->getHTTPContent($url, $url_referer, $cookiePath);
							$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
							file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$url_log.$content);

							$content=substr($content, strpos($content, "(")+1);
							$content=substr($content, 0, strrpos($content, ")"));
							$content = json_decode($content);

							if($content->messageSent==1)
							{
								$this->newMessage=true;
								$this->savelog("Sending message completed.");
								DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								if(isset($item['message']))
									$this->deleteInboxMessage($username, $item['message'], $cookiePath);
								$return = true;
								break;
							}
							else
							{
								$this->newMessage=true;
								$this->savelog("Sending message failed. ".$content->msg);
								$this->lastSentTime = time();
								$this->sleep(120);
								$return = true;
							}
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']." [".$item['userid']."]");
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				elseif($this->command['msg_type']=="qa")
				{
					$message_arr = array(
											"callback" => $callback,
											"question" => $message,
											"anonymous" => "false",
											"token" => $this->token,
											"captcha" => 0,
											"callback" => "AskMe.submitQuestionCallback",
											"memberId" => $item['userid'],
											"_" => $this->utime()
									);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending question to ".$item['username']." [".$item['userid']."]");
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendQuestionURL."?".http_build_query($message_arr);
						$url_referer = $this->profileURL.$item['userid'];
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath);
						$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
						file_put_contents("sending/qa-".$username."-".$item['username']."-".$item['userid'].".html",$url_log.$content);

						$content=substr($content, strpos($content, "(")+1);
						$content=substr($content, 0, strrpos($content, ")"));
						$content = json_decode($content);

						if($content->success==1)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
							$this->sendmsg_total++;
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Sending message failed. ".$content->error);
							$this->lastSentTime = time();
							$this->sleep(120);
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']." [".$item['userid']."]");
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				/*else
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']." [".$item['userid']."]");
					$content = $this->getHTTPContent($this->sendGuestbookPageURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);

					$this->sleep(5);
					$message_arr = array(
											"gb_text" => $message,
											"x" => 10,
											"y" => 10
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']." [".$item['userid']."]");
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->profileURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						if(strpos($this->getHTTPContent($this->sendGuestbookPageURL.$item['userid']."/entry/1", $this->profileURL.$item['url'], $cookiePath),"Ihr Gästebucheintrag wurde weitergeleitet")!==false)
						{
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
						}
						else
						{
							$this->savelog("Signing guestbook failed.");
							$return = false;
						}
					}
					else
					{
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']." [".$item['userid']."]");
						$return = false;
					}
				}*/
				$this->cancelReservedUser($item['userid']);
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
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']." [".$item['userid']."]");
			if(isset($item['message']))
				$this->deleteInboxMessage($username, $item['message'], $cookiePath);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $cookiePath){
		$this->savelog("Sending test message.");
		$profiles = DBConnect::assoc_query_1D("SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID);
		if($this->command['gender']=="maennlich")
		{
			$receiverId			= $profiles['male_id'];
			$receiverUsername	= $profiles['male_user'];
		}
		else
		{
			$receiverId			= $profiles['female_id'];
			$receiverUsername	= $profiles['female_user'];
		}

		if(($receiverId!='') && ($receiverUsername!=''))
		{
			$item = array(	"username" => $receiverUsername,
							"userid" => $receiverId
							);

			$this->work_sendMessage($username, $item, $cookiePath, true);
		}
		else
		{
			$this->savelog("Get test profile failed.");
		}
	}

	private function isAlreadySent($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".addslashes($userid)."',".$this->botID.",NOW())";
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

	private function cancelReservedUser($userid)
	{
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE userid='".$userid."' AND server=".$this->botID);
	}

	private function deleteAllOutboxMessages($username, $cookiePath)
	{
		while($list = $this->getOutboxMessages($username, $cookiePath))
		{
			$this->savelog("Found ".count($list)." outbox messages.");
			foreach($list as $message)
			{
				$this->deleteOutboxMessage($username, $message, $cookiePath);
			}
		}
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);
		$content = json_decode($content);
		$parser = $this->convertToXML($username, "outbox", $content->content);

		if($parser->document->table[0]->tagAttrs['id']=="messages")
		{
			foreach($parser->document->table[0]->tr as $item)
			{
				$message = array(
									"message" => $item->td[7]->span[0]->tagData,
									"username" => $item->td[3]->a[0]->tagData,
									"userid" => str_replace("http://www.meetme.com/member/","",$item->td[3]->a[0]->tagAttrs['href'])
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
			"threads[]" => $message['message'],
			"timestamp" => time()
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL, $this->deleteOutboxRefererURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$page = 0;
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->indexURL, $cookiePath);

		$content = json_decode($content);
		$parser = $this->convertToXML($username, "outbox", $content->content);

		if($parser->document->table[0]->tagAttrs['id']=="messages")
		{
			foreach($parser->document->table[0]->tr as $item)
			{
				$message = array(
									"message" => $item->td[7]->span[0]->tagData,
									"username" => $item->td[3]->a[0]->tagData,
									"userid" => str_replace("http://www.meetme.com/member/","",$item->td[3]->a[0]->tagAttrs['href'])
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
			"threads[]" => $message['message'],
			"timestamp" => time()
			);

		$content = $this->getHTTPContent($this->deleteInboxURL, $this->deleteInboxRefererURL, $cookiePath, json_encode($delete_arr));

		$this->savelog("Deleting message id: ".$message['message']." completed.");
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
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.meetme.com/?mysession=c2VhcmNoX2VtYWlsc2VhcmNo'.$profile, $this->rootDomain, $cookiePath, array(
				'addr5' => '',	
				'useremail' => $profile
			));
			if(!strpos($content,'No records found')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
?>