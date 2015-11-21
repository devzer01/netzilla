<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in netlog_uk()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class netlog_uk extends bot
{
	public $token = '';
	public function netlog_uk($post)
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
																	"username" => "measmeme",
																	"password" => "thtl19"
																	),
															array(
																	"username" => "Mariahillstrand",
																	"password" => "takeitnow"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hello",
																	"message" => "Hello"
																),
															array(
																	"subject" => "Hi there!",
																	"message" => "Hi there!"
																)
														),
									"messages_per_hour" => 60,
									"start_page" => 1,
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"age_from" => 20,
									"age_to" => 25,
									"gender" => "m",
									"country" => "GB",
									"region" => -1,
									"full_msg" => 1,
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 0;
		}

		$this->usernameField = "nickname";
		$this->loginURL = "http://en.netlog.com/go/login";
		$this->loginRefererURL = "http://en.netlog.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.twoo.com/login/?action=logout&lng=en&twoo_csrf_token=b530a7609554790992bfaa22815e1c64_1395631290";
		$this->indexURL = "http://en.netlog.com/";
		$this->indexURLLoggedInKeyword = "/go/login/action=logout";
		$this->searchURL = "http://en.netlog.com/go/ajax/action=getFilterResults";
		$this->searchRefererURL = "http://en.netlog.com/go/search/view=advanced";
		$this->searchResultsPerPage = 35;
		$this->profileURL = "http://en.netlog.com/";
		$this->sendMessageURL = "http://en.netlog.com/go/ajax/comments";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->workCount = 1;
		$this->message="";
		$this->newMessage=true;
		$this->preMessage = array(
									"Hello there sweety, i like you, but unfortunately i`m not here anymore, you will find me at",
									"Hi there! Nice profile! If you�d like to hook-up, this is not my login, simply look for me at",
									"Hello, you`re cute, I will be offline from here now and will only be available at",
									"Hello, are you single? Me too, interested in sharing a coffee? This is the login of my sis, but you`ll find me at",
									"Hey, finally found someone like you and you seem to be charming, find me at",
									"Hi nice profile ;) would like to know you better, but not over here, i`m currently at",
									"Are you a nice guy? Fancy a chat? Unfortunately this is not my account, but you will find me at",
									"Huh? What�s up? Lonely? Ok, me too and what shall we do about that? ;-) Unfortunately i`m not here anymore, but hook up with me at",
									"Not online anymore? Why not? Ok, if you like we maybe can chat around and find out more about each other by hanging out at a nice places over the weekend? If so, drop me a message at",
									"You`re out of my area, aren`t you? ;-) Would like to know more about you, but this account is the one of my sis, simply look for me at",
									"Oh, you seem to be handsome! I think you`re cute, single too? Fancy a chat? Ok, but I will be unavailable over here now, find me at",
									"Oh, one last mail from you before i logout, not getting too much messages over here but would like to know you better, currently i`m at",
									"I`m fine, you too? Whatsup? You`re cute, would like to meet up for a coffee? Next weekend? Unfortunately I will leave here, too much messages, but currently i`m at",
									"Nice, nice, isn`t it? Fancy a chat? But not over here anymore, ok? Too much idiots around, but if you like me simply drop me a message at",
									"Oh, living next to me, would love to chat with you a bit but i will leave here in short, not too much that`s going on over here, but I have images of myself at",
									"Hey, do you like blind dates too? If so, I have some images of myself, some of erotic nature too, at",
									"I like adventures, you 2? You`re out of my area but actually this is not my login so if you like you`ll find me at",
									"I`m that crazy type, like adventures, how about you? Maybe next weekend? Or am I too straight forward? Check out my pictures and decide on your own, ok? You`ll find them at",
									"Tought i should leave u a MSG cuz i`m single since quite a while now. I resettled into your area a short time ago and maybe you would like to show me the nightlife? Will leave here, too much MSGs coming up, but wou`ll find me at",
									);
		$this->postMessage = array(
									"Search for my nick, PattyR, ok?",
									"check out my Username over there, PattyR, ok?",
									"look after PattyR over there, c u soon!",
									"Simply use the nickname search and enter my nick: PattyR, ok?",
									"You`ll find me by using the nick search and entering PattyR, ok?",
									"Just look for my Nick, PattyR if you`re there! Cheers!",
									"I`m curiously awaiting your reply, simply search Patty Rover there, ok?",
									"Hopefully see u soon over there :P Cheers! PattyR",
									"Check out my Nick over there, it`s PattyR, ok?",
									"Simply drop PattyR a message over there, ok?",
									);
		$this->postMessageTemp = array();
		$this->preMessageTemp = array();
		
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
			$login_arr = array(	"action" => "login",
								"target" => "/",
								"remember" => "YES",
								"nickname" => $user['username'],
								"password" => $user['password'],
								"login" => "log in"
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	private function getPreMessage()
	{
		if(count($this->preMessageTemp)==0)
		{
			shuffle($this->preMessage);
		}
		elseif(count($this->preMessage)==0)
		{
			$this->preMessage = $this->preMessageTemp;
			shuffle($this->preMessage);
			$this->preMessageTemp = array();
		}

		$msg = array_pop($this->preMessage);
		array_push($this->preMessageTemp, $msg);
		return $msg;
	}

	private function getPostMessage()
	{
		if(count($this->postMessageTemp)==0)
		{
			shuffle($this->postMessage);
		}
		elseif(count($this->postMessage)==0)
		{
			$this->postMessage = $this->postMessageTemp;
			shuffle($this->postMessage);
			$this->postMessageTemp = array();
		}

		$msg = array_pop($this->postMessage);
		array_push($this->postMessageTemp, $msg);
		return $msg;
	}

	private function getMessage($new=true)
	{
		if($new)
		{
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
			$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

			$this->message=$this->getPreMessage()." ".$message." ".$this->getPostMessage();
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
	}

	public function work()
	{
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			if(($this->workCount == 1) && ($age==$this->command['age_from']))
				$page=$this->command['start_page'];
			else
				$page=1;
			$list=array();
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
										"type" => "",
										"q" => "",
										"page" => $page,
										"v" => "g",
										"g" => $this->command['gender'],
										"a" => "c",
										"aa" => $age,
										"az" => $age,
										"c" => $this->command['country'],
										"r" => $this->command['region'],
										"poc" => "",
										"reset" => "Reset filter"
									);

				if(isset($this->command['options']))
				{
					foreach($this->command['options'] as $key=>$value)
					{
						$search_arr[$key]=$value;
					}
				}

				$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].", region: ".$this->command['region'].", age: ".$age.", page ".$page);
				$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);

				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
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
								if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
								{
									//RANDOM SUBJECT AND MESSAGE
									$this->savelog("Random new subject and message");
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

								///reserve this user, so no other bot can send msg to
								$this->savelog("Reserving profile to send message: ".$item['username']);
								if($this->reserveUser($item['username']))
								{
									// Go to profile page
									$this->savelog("Go to profile page: ".$item['username']);
									$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchRefererURL, $cookiePath);

									// If guestbook enabled
									if(strpos($content, "Sign guestbook")!==false)
									{
										$this->sleep(5);

										/*************************************/
										/***** Go to sign guestbook page *****/
										/*************************************/
										$this->savelog("Go to sign guestbook page: ".$item['username']);
										$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook/#writeComment", $this->searchRefererURL, $cookiePath);
										$previousNum = $this->getGuestbookEntry($content);
										$this->savelog("Previous guestbook entry is ".$previousNum);
										$this->sleep(5);

										/**************************/
										/***** Sign guestbook *****/
										/**************************/
										$guestbook_arr = array(
																"action" => "addComment",
																"itemID" => $this->getInputValue("itemID", $content),
																"itemDistro" => "en",
																"type" => "GUESTBOOK",
																"ownerUserID" => $this->getInputValue("ownerUserID", $content),
																"quote_nickname" => "",
																"commentView" => "ITEM_ONE",
																"csrftoken_addcomment" => $this->getInputValue("csrftoken_addcomment", $content),
																"message" => $message,
																"postcomment" => "Add message"
																);
										if(time() < ($this->lastSentTime + $this->messageSendingInterval))
											$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Signing guestbook: ".$item['username']);
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->profileURL.$item['username']."/guestbook/&order=DESC#addCommentStatus", $this->profileURL.$item['username']."/guestbook/#writeComment", $cookiePath, $guestbook_arr);
											file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
											$currentNum = $this->getGuestbookEntry($content);
											$this->savelog("Current guestbook entry is ".$currentNum);

											if($currentNum>$previousNum)
											{
												$this->newMessage=true;
												$this->savelog("Signing guestbook completed.");
												DBConnect::execute_q("INSERT INTO netlog_uk_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->lastSentTime = time();
											}
											else
											{
												$this->newMessage=true;
												$this->savelog("Signing guestbook failed.");
											}
										}
										else
										{
											$this->newMessage=false;
											$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
										}
										$this->sleep(2);
									}
									else
									{
										$this->newMessage=false;
										$this->savelog("Profile ".$item['username']." has disabled guestbook signing.");
									}
									$this->cancelReservedUser($item['username']);
								}
							}
							else
							{
								$this->savelog("Already sign guestbook for profile: ".$item['username']);
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

				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database
				$page++;
			}
			while(count($list)>=$this->searchResultsPerPage);
		}

		$this->savelog("Job completed.");
		$this->workCount++;
		return true;
	}

	private function getGuestbookEntry($content){
		if(strpos($content, '<span class="counter">')!==false)
		{
			$content = substr($content,strpos($content,'<span class="counter">')+22);
			$content = substr($content,0,strpos($content,'</span>'));
			return $content;
		}
		else
			return 0;
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<ul class="profileList clearfix">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</ul>')+5);

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->ul[0]))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$profile = array(
									"username" => $item->div[0]->a[1]->span[0]->tagData
								);
				array_push($list,$profile);
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM netlog_uk_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM netlog_uk_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO netlog_uk_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM netlog_uk_reservation WHERE username='".$username."' AND server=".$this->botID);
	}
}
?>