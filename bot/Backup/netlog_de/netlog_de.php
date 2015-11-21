<?php
require_once('bot.php');

/*******************************/
/************ TO DO ************/
/*******************************/
/*
- change all URL variables in netlog_de()
- change login post array in addLoginData()
- change search post array in work()
- ...
*/
/*******************************/
/************ / TO DO ************/
/*******************************/

class netlog_de extends bot
{
	public function netlog_de($post)
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
																	"username" => "regelinde",
																	"password" => "ShinA5p"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "[URL]"
																),
															array(
																	"subject" => "Hallo",
																	"message" => "[URL]"
																)
														),
									"messages_per_hour" => 60,
									"start_page" => 1,
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"age_from" => 20,
									"age_to" => 20,
									"gender" => "m",
									"country" => "DE",
									"region" => -1,
									//"o" => 1,
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 0;
		}

		$this->usernameField = "nickname";
		$this->loginURL = "http://de.netlog.com/go/login";
		$this->loginRefererURL = "http://de.netlog.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://de.netlog.com/go/login/view=loggedout&didlogout=2";
		$this->indexURL = "http://de.netlog.com/";
		$this->indexURLLoggedInKeyword = "/go/login/action=logout";
		$this->searchURL = "http://de.netlog.com/go/ajax/action=getFilterResults";
		$this->searchRefererURL = "http://de.netlog.com/go/search/view=advanced";
		$this->searchResultsPerPage = 35;
		$this->profileURL = "http://de.netlog.com/";
		$this->sendMessageURL = "http://de.netlog.com/go/ajax/comments";
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
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i);
			$this->messagesPartTemp[$i] = array();
		}
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

	public function work()
	{
		$option_log = "";
		if(isset($this->command['options']))
		{
			foreach($this->command['options'] as $key=>$value)
			{
				if($option_log == "")
					$option_log = ", option: ";
				$option_log .= $key.": ".$value;
			}
		}
		$this->savelog("Job criterias => gender: ".$this->command['gender'].", country: ".$this->command['country'].", age: ".$this->command['age_from']."-".$this->command['age_to'].", start time ".$this->command['start_h'].":".$this->command['start_m'].", end time ".$this->command['end_h'].":".$this->command['end_m'].$option_log);
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$page=$this->command['start_page'];
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
										"reset" => "Filter zurücksetzen"
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
									if(strpos($content, "Schreib ins Gästebuch")!==false)
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
																"itemDistro" => "de",
																"type" => "GUESTBOOK",
																"ownerUserID" => $this->getInputValue("ownerUserID", $content),
																"quote_nickname" => "",
																"commentView" => "ITEM_ONE",
																"csrftoken_addcomment" => $this->getInputValue("csrftoken_addcomment", $content),
																"message" => $message,
																"postcomment" => "Nachricht hinzufügen."
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
												DBConnect::execute_q("INSERT INTO netlog_de_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".addslashes($username)."','".addslashes($subject)."','".addslashes($message)."',NOW())");
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
											$this->cancelReservedUser($item['username']);
											$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
										}
										$this->cancelReservedUser($item['username']);
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM netlog_de_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM netlog_de_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO netlog_de_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM netlog_de_reservation WHERE username='".$username."' AND server=".$this->botID);
	}
}
?>