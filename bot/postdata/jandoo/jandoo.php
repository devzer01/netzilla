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

class jandoo extends bot
{
	public function jandoo($post)
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
																	"username" => "Justina87",
																	"password" => "meinSchatz"
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
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 28,
									"age_to" => 35,
									//"gender" => "m",
									"gender" => 1,
									"country" => 1,
									"msg_type" => "gb",
									"options" => array(
														//"lastseen" => 1
														),
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 0;
		}

		$this->usernameField = "user";
		$this->loginURL = "http://www.jandoo.de/login/";
		$this->loginRefererURL = "http://www.jandoo.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.jandoo.de/logout/";
		$this->indexURL = "http://www.jandoo.de/";
		$this->indexURLLoggedInKeyword = "/logout/";
		$this->searchURL = "http://www.jandoo.de/mitglieder/suche/";
		$this->searchRefererURL = "http://www.jandoo.de/mitglieder/";
		$this->searchResultsPerPage = 21;
		$this->profileURL = "http://www.jandoo.de/profil/";
		$this->sendMessagePageURL = "http://www.jandoo.de/profil/";
		$this->sendMessageURL = "http://www.jandoo.de/scripts/saveText.php?x=";
		$this->outboxURL = "http://www.jandoo.de/mailbox";
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
			$login_arr = array(	"pwd" => $user['password'],
								"user" => $user['username']
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$option_log = "";
		foreach($this->command['options'] as $key=>$value)
		{
			if($option_log == "")
				$option_log = ", option: ";
			$option_log .= $key.": ".$value;
		}
		$this->savelog("Job criterias => gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$this->command['age_from']."-".$this->command['age_to'].", start time ".$this->command['start_h'].":".$this->command['start_m'].", end time ".$this->command['end_h'].":".$this->command['end_m'].$option_log);
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
			$page=1;
			$list=array();
			$last_username = "";
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
										"age1" => $age,
										"age2" => $age,
										"gender" => $this->command['gender'],
										"country" => $this->command['country'],
										"plz" => "",
										"ort" => ""
									);

				foreach($this->command['options'] as $key=>$value)
				{
					$search_arr[$key]=$value;
				}

				$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$age.", page ".$page);
				$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
				file_put_contents("search/".$username."-search-".$page.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);

				if(is_array($list) && count($list))
				{
					if($list[0]['username'] == $last_username)
					{
						$list = array();
					}
					else
					{
						$last_username = $list[0]['username'];
					}
				}

				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					$this->sleep(5);
					foreach($list as $key=>$item)
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
									$content = $this->getHTTPContent($this->profileURL.$item['username']."/", $this->searchURL, $cookiePath);

									$this->sleep(5);

									if($this->command['msg_type']=="pm")
									{
										/***********************************/
										/***** Go to send message page *****/
										/***********************************/
										$this->savelog("Go to send message page: ".$item['username']);
										$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username']."/nachricht/", $this->profileURL.$item['username']."/", $cookiePath);
										$this->sleep(5);

										/************************/
										/***** Send message *****/
										/************************/
										//RANDOM SUBJECT AND MESSAGE
										$this->savelog("Random new subject and message");
										$this->currentSubject = rand(0,count($this->command['messages'])-1);
										$this->currentMessage = rand(0,count($this->command['messages'])-1);

										//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
										$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
										$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

										$message_arr = array(
																"type" => "mail",
																"user" => $item['username'],
																"text" => $message
																);
										if(time() < ($this->lastSentTime + $this->messageSendingInterval))
											$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Sending message to ".$item['username']);
										$random = "0.".str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT).str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT);
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->sendMessageURL.$random, $this->sendMessagePageURL.$item['username']."/nachricht/", $cookiePath, $message_arr);
											file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

											$content = json_decode($content);

											if(isset($content->id))
											{
												$this->savelog("Sending message completed.");
												DBConnect::execute_q("INSERT INTO jandoo_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".addslashes($username)."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->lastSentTime = time();
											}
											else
											{
												$this->savelog("Sending message failed.");
											}
										}
										else
										{
											$this->cancelReservedUser($item['username']);
											$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
											$return = false;
										}
									}
									else
									{
										/***********************************/
										/***** Go to sign guestbook page *****/
										/***********************************/
										$this->savelog("Go to sign guestbook page: ".$item['username']);
										$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username']."/gaestebuch/", $this->profileURL.$item['username']."/", $cookiePath);
										$this->sleep(5);

										/************************/
										/***** Send message *****/
										/************************/
										//RANDOM SUBJECT AND MESSAGE
										$this->savelog("Random new subject and message");
										$this->currentSubject = rand(0,count($this->command['messages'])-1);
										$this->currentMessage = rand(0,count($this->command['messages'])-1);

										//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
										$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
										$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

										$message_arr = array(
																"type" => "gbook",
																"user" => $item['username'],
																"text" => $message
																);
										if(time() < ($this->lastSentTime + $this->messageSendingInterval))
											$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Signing guestbook to ".$item['username']);
										$random = "0.".str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT).str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT);
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->sendMessageURL.$random, $this->sendMessagePageURL.$item['username']."/gaestebuch/", $cookiePath, $message_arr);
											file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);

											$content = json_decode($content);

											if(isset($content->id))
											{
												$this->savelog("Signing guestbook completed.");
												DBConnect::execute_q("INSERT INTO jandoo_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".addslashes($username)."','".addslashes($subject)."','".addslashes($message)."',NOW())");
												$this->lastSentTime = time();
											}
											else
											{
												$this->savelog("Signing guestbook failed.");
											}
										}
										else
										{
											$this->cancelReservedUser($item['username']);
											$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']);
											$return = false;
										}
									}
									$this->cancelReservedUser($item['username']);
									$this->sleep(2);
								}
							}
							else
							{
								$this->savelog("Already send message to profile: ".$item['username']);
							}

							if(($key+1)%5==0)
							{
								/*******************************/
								/****** Go to outbox page ******/
								/*******************************/
								$this->savelog("Go to OUTBOX page.");
								$content = $this->getHTTPContent($this->outboxURL, $this->loginRefererURL, $cookiePath);
								$this->sleep(5);

								if(strpos($content,"Es ist noch keine Mail vorhanden!")===false)
								{
									$delete_arr = array(
															"deleteAll" => 1
														);

									$this->savelog("Deleting all outbox messages.");
									$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);
								}
								else
								{
									$this->savelog("There is no outbox message to delete.");
								}
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
		return true;
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<div class="tabContainer suche"'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'<div class="td">'));
		$content = substr($content,0,strrpos($content,'</div>'));

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->div[0]))
		{
			foreach($parser->document->div[0]->div as $item)
			{
				if($item->tagAttrs['class'] == "userContainer")
				{
					$profile = array(
										"username" => str_replace(array("/profil/","/"),"",$item->b[0]->a[0]->tagAttrs['href'])
									);

					array_push($list,$profile);
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM jandoo_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM jandoo_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO jandoo_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM jandoo_reservation WHERE username='".$username."' AND server=".$this->botID);
	}
}
?>