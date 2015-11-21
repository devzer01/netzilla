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

class flirt1 extends bot
{
	public function flirt1($post)
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
									"start_h" => 17,
									"start_m" => 00,
									"end_h" => 18,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 16,
									"age_to" => 40,
									"gender" => 1,
									"status" => "all",
									"country" => 81,
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 48;
		}

		$this->usernameField = "user";
		$this->loginURL = "http://www.flirt1.net/join.php?cmd=login";
		$this->loginRefererURL = "http://www.flirt1.net/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.flirt1.net/index.php?cmd=logout";
		$this->indexURL = "http://www.flirt1.net/";
		$this->indexURLLoggedInKeyword = "index.php?cmd=logout";
		$this->searchURL = "http://www.flirt1.net/search_results.php";
		$this->searchRefererURL = "http://www.flirt1.net/search.php";
		$this->searchResultsPerPage = 16;
		$this->profileURL = "http://www.flirt1.net/";
		//$this->profileURL = "http://www.flirt1.net/search_results.php?display=profile&name=";
		$this->sendMessagePageURL = "http://www.flirt1.net/nachricht_schreiben.php?id=";
		$this->sendMessageURL = "http://www.flirt1.net/nachricht_schreiben.php?cmd=sent";
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
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	
								"user" => $user['username'],
								"password" => $user['password']
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job criterias => gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$this->command['age_from']."-".$this->command['age_to'].", status: ".$this->command['status'].", start time ".$this->command['start_h'].":".$this->command['start_m'].", end time ".$this->command['end_h'].":".$this->command['end_m']);
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
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
										"p_orientation[]" => $this->command['gender'],
										"cmd" => "",
										"p_age_from" => $age,
										"p_age_to" => $age,
										"country" => $this->command['country'],
										"state" => 0,
										"city" => 0,
										"keyword" => "",
										"status" => $this->command['status'],
										"search_name" => "My search",
										"offset" => (($page-1)*$this->searchResultsPerPage)+1
									);

				if(isset($this->command['options']))
				{
					foreach($this->command['options'] as $key=>$value)
					{
						$search_arr[$key]=$value;
					}
				}

				$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$age.", page ".$page);
				$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
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
										$content = $this->getHTTPContent($this->profileURL.$item['url'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										//$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchURL."?".http_build_query($search_arr), $cookiePath);
										$item['userid'] = substr($content, strpos($content, "nachricht_schreiben.php?id=")+27);
										$item['userid'] = substr($item['userid'], 0, strpos($item['userid'], "\""));

										$this->sleep(5);

										/***********************************/
										/***** Go to send message page *****/
										/***********************************/
										$this->savelog("Go to send message page: ".$item['username']);
										$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid'], $this->profileURL.$item['url'], $cookiePath);
										//$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid'], $this->profileURL.$item['username'], $cookiePath);
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
																"page_from" => $this->profileURL.$item['url'],
																//"page_from" => $this->profileURL.$item['username'],
																"name" => $item['username'],
																"subject" => $subject,
																"text" => $message
																);
										if(time() < ($this->lastSentTime + $this->messageSendingInterval))
											$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
										$this->savelog("Sending message to ".$item['username']);
										if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
										{
											$content = $this->getHTTPContent($this->sendMessageURL, $this->sendMessagePageURL.$item['username'], $cookiePath, $message_arr);
											file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);

											if(strpos($content, "Nachricht erfolgreich gesendet")!==false)
											{
												DBConnect::execute_q("INSERT INTO flirt1_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
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

		$content = substr($content,strpos($content,'<table id="online_c" cellspacing="0" cellpadding="0" border="0">'));
		$content = substr($content,0,strpos($content,'</table>')+8);

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->table[0]))
		{
			foreach($parser->document->table[0]->tr as $row)
			{
				foreach($row->td as $member)
				{
					if(isset($member->div[0]->div[1]->span[0]->a[0]->tagAttrs['href']))
					{
						array_push($list, array(
													"username"=>$member->div[0]->div[1]->span[0]->a[0]->tagData,
													"url"=>$member->div[0]->div[1]->span[0]->a[0]->tagAttrs['href']
												)
									);
					}
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM flirt1_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM flirt1_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO flirt1_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM flirt1_reservation WHERE username='".$username."' AND server=".$this->botID);
	}
}
?>