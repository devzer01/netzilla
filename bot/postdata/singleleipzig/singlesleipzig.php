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

class SinglesLeipzig extends bot
{
	public $sessionID = "";
	public function SinglesLeipzig($post)
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
			ignore_user_abort(false);
			$this->command = array(
									"profiles" => array(
															array(
																	"username" => "annabellsss",
																	"password" => "1qazxsw2"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "Hallo"
																),
															array(
																	"subject" => "Hallo",
																	"message" => "XXX"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"send_test" => 1,
									"age_from" => 23,
									"age_to" => 25,
									"gender" => 0,
									"country" => 1,
									"sternzeichen" => 5,
									"msg_type" => "gb",
									"online" => 1,
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 52;
		}

		$this->msg_count = 0;
		$this->usernameField = "username";
		$this->loginURL = "http://www.singlesleipzig.de/account.php";
		$this->loginRefererURL = "http://www.singlesleipzig.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.singlesleipzig.de/account_logout.php";
		$this->indexURL = "http://www.singlesleipzig.de";
		$this->indexURLLoggedInKeyword = "account_logout.php";
		$this->onlinePageURL = "http://www.singlesleipzig.de/online.php?onlinefotoansicht=1&showonly=";
		$this->searchURL = "http://www.singlesleipzig.de/search2.php";
		$this->searchRefererURL = "http://www.singlesleipzig.de/search.php";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "http://www.singlesleipzig.de/np.php?uid=";
		$this->sendMessagePageURL = "http://www.singlesleipzig.de/account_msg.php?msgaction=neu&uname=";
		$this->sendMessageURL = "http://www.singlesleipzig.de/account_msg.php";
		$this->signGuestbookPageURL = "http://www.singlesleipzig.de/np.php?uid=";
		$this->signGuestbookURL = "http://www.singlesleipzig.de/np.php?uid=";
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
		$this->sternzeichen_arr = range(1,12);
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===

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
			$login_arr = array(	
									"securelogin" => 1,
									"action" => "login",
									"username" => $user['username'],
									"passwrd" => $user['password'],
									"setdauerlogin" => 1,
									"submit.x" => 11,
									"submit.y" => 13
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

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$subject = $this->command['messages'][$this->currentSubject]['subject'];
			$message = $this->command['messages'][$this->currentMessage]['message'];

			$this->message = "";
			for($i=1; $i<=$this->totalPart; $i++)
			{
				$this->message .= $this->getMessagePart($i)." ";
				if($i == round($this->totalPart/2))
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
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		if(isset($this->command['online']) && ($this->command['online']=="1"))
		{
			$this->savelog("Go to ONLINE page");
			$page=1;
			do
			{
				$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$this->command['age_from']."-".$this->command['age_to'].", page: ".$page);
				$content = $this->getHTTPContent($this->onlinePageURL.$this->command['gender']."&fs=".($page-1), $this->indexURL, $cookiePath);
				file_put_contents("search/".$username."-online-".$page.".html",$content);
				$this->sleep(5);

				$list = $this->getMembersFromOnlineResult($username, $page, $content);

				if(is_array($list))
				{
					$list_arr = array();
					foreach($list as $item)
					{
						if(($item['age'] >= $this->command['age_from']) && ($item['age'] <= $this->command['age_to']))
							array_push($list_arr, $item);
					}

					$this->savelog("Found ".count($list_arr)." member(s)");
					if(count($list_arr))
					{
						$this->sleep(5);
						foreach($list_arr as $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								if(($this->msg_count%30)<15)
									$this->command['msg_type'] = "pm";
								else
									$this->command['msg_type'] = "gb";

								$this->work_sendMessage($username, $item, $cookiePath);

								$this->msg_count++;
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

				$page++;
			}
			while(count($list)>=30);
		}
		else
		{
			if($this->command['sternzeichen']!=0)
				$this->sternzeichen_arr = array($this->command['sternzeichen']);

			/*******************************/
			/****** Go to search page ******/
			/*******************************/
			$this->savelog("Go to SEARCH page.");
			$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
			$this->sleep(5);

			for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
			{
				foreach($this->sternzeichen_arr as $sternzeichen)
				{
					$page=1;
					$list=array();
					$first_username = '';

					/*******************************/
					/***** set search criteria *****/
					/*******************************/

					$search_arr = array(
											"username" => "",
											"sgender" => $this->command['gender'],
											"agefrom" => $age,
											"ageto" => $age,
											"wohnort" => "",
											"land" => $this->command['country'],
											"status" => "egal",
											"seizefrom" => 130,
											"seizeto" => 230,
											"nurohnekind" => "x",
											"raucher" => 3,
											"trinker" => 3,
											"figur" => 4,
											"sexori" => 1,
											"ausbildung" => 5,
											"sternzeichen" => $sternzeichen,
											"Button" => "Suchen"
										);

					if(isset($this->command['options']))
					{
						foreach($this->command['options'] as $key=>$value)
						{
							$search_arr[$key]=$value;
						}
					}

					$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);

					$searchid = $this->getSearchID($content);

					do
					{
						$search_arr = array(
												"sgender" => $this->command['gender'],
												"grossraum" => "",
												"agefrom" => $age,
												"ageto" => $age,
												"nurfoto" => "",
												"land" => $this->command['country'],
												"gender" => "",
												"nurnofake" => "",
												"status" => "egal",
												"raucher" => 3,
												"trinker" => 3,
												"figur" => 4,
												"sexori" => 1,
												"page" => $page,
												"searchid" => $searchid,
												"ausbildung" => 5,
												"seizeto" => 230,
												"seizefrom" => 130,
												"username" => "",
												"nurohnekind" => "x",
												"wohnort" => "",
												"sternzeichen" => $sternzeichen
											);

						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr[$key]=$value;
							}
						}

						$search_arr = http_build_query($search_arr);

						$this->savelog("Search for gender: ".$this->command['gender'].", sternzeichen: ".$sternzeichen.", age: ".$age.", page: ".$page);

						/******************/
						/***** search *****/
						/******************/
						$content = $this->getHTTPContent($this->searchURL."?".$search_arr, $this->searchRefererURL, $cookiePath);

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
								
								$first_username = $list[0]['username'];

								$this->sleep(5);
								foreach($list as $item)
								{
									$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
									//If in runnig time period
									if($sleep_time==0)
									{
										/*if(($this->msg_count%30)<15)
											$this->command['msg_type'] = "pm";
										else*/
											$this->command['msg_type'] = "gb";

										$this->work_sendMessage($username, $item, $cookiePath);

										$this->msg_count++;
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
						$page++;
					}
					while(count($list)>=$this->searchResultsPerPage);
				}
			}
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		$content = substr($content,strpos($content,'<table width=97% border=0 cellpadding=0 cellspacing=0 class=tablehead>'));
		$content = substr($content,0,strpos($content,'</table>')+8);

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->table[0]))
		{
			foreach($parser->document->table[0]->tr as $row)
			{
				if($row->tagAttrs['class'] == "bb")
				{
					$userid = $row->td[1]->a[0]->tagAttrs['href'];
					$userid = str_replace("np.php?uid=","",$userid);
					$userid = substr($userid, 0, strpos($userid, "&"));
					array_push($list, array(
												"username"=>$row->td[1]->a[0]->tagData,
												"userid"=>$userid
											)
								);
				}
			}
		}
		return $list;
	}

	private function getMembersFromOnlineResult($username, $page, $content)
	{
		$list = array();

		$content = substr($content,strpos($content,'<table><tr><td><a href=\'np.php'));
		$content = substr($content,0,strpos($content,'</table>')+8);

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->table[0]))
		{
			foreach($parser->document->table[0]->tr as $row)
			{
				foreach($row->td as $item)
				{
					$userid = $item->a[0]->tagAttrs['href'];
					$userid = str_replace("np.php?uid=","",$userid);
					$username = explode(" ", $item->a[0]->tagData);
					array_push($list, array(
												"username"=>$username[0],
												"age" => str_replace(array("(",")"),"", $username[1]),
												"userid"=>$userid
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

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
		// If not already sent
		if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
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
			if($this->reserveUser($item['username'], $item['userid']))
			{
				$referer = $this->searchURL;
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$content = $this->getHTTPContent($this->profileURL.$item['userid'], $referer, $cookiePath);

				$this->sleep(5);

				if($this->command['msg_type']=="pm")
				{
					/***********************************/
					/***** Go to send message page *****/
					/***********************************/
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username'], $this->profileURL.$item['userid'], $cookiePath);

					$this->sleep(5);

					/************************/
					/***** Send message *****/
					/************************/

					$ptkn2 = substr($content, strpos($content, "name='ptkn2' value='")+20);
					$ptkn2 = substr($ptkn2, 0, strpos($ptkn2, "'"));

					$uise = substr($content, strpos($content, "name='uise' value='")+19);
					$uise = substr($uise, 0, strpos($uise, "'"));

					$message_arr = array(
											"msgaction" => "sendmsg",
											"ptkn2" => $ptkn2,
											"altemsgid" => "",
											"receiver" => $item['username'],
											"mailbuddy" => "",
											"subject" => utf8_decode($subject),
											"count" => 20000-strlen($message),
											"nachricht" => utf8_decode($message),
											"uise" => $uise,
											"submitfrm" => "Abschicken [STRG+ENTER]"
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendMessageURL, $this->sendMessagePageURL.$item['username'], $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);

						if(strpos($content, "Deine Nachricht wurde erfolgreich an")!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							$this->lastSentTime = time();
							DBConnect::execute_q("INSERT INTO singlesleipzig_sent_messages (to_username, to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."', '".addslashes($item['userid'])."','".$username."', '".addslashes($subject)."', '".addslashes($message)."', NOW())");
						}
						else
						{
							$this->newMessage=true;
							$this->lastSentTime = time();
							$this->savelog("Sending message failed.");
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}
				elseif($this->command['msg_type']=="gb")
				{
					/***********************************/
					/***** Go to sign guestbook page *****/
					/***********************************/
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->signGuestbookPageURL.$item['userid']."&subnp=gb", $this->profileURL.$item['userid'], $cookiePath);

					$this->sleep(5);

					/**************************/
					/***** Sign guestbook *****/
					/**************************/
					$ptkn2 = substr($content, strpos($content, "name='ptkn2' value='")+20);
					$ptkn2 = substr($ptkn2, 0, strpos($ptkn2, "'"));

					$message_arr = array(
											"ptkn2" => $ptkn2,
											"subnp" => "gb",
											"uid" => $item['userid'],
											"uname" => $item['username'],
											"action" => "addgb",
											"count" => 20000-strlen($message),
											"nachricht" => utf8_decode($message),
											"button" => "Eintragen"
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->signGuestbookURL.$item['userid']."&subnp=gb", $this->signGuestbookPageURL.$item['userid']."&subnp=gb", $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['username'].".html",$content);

						if(strpos($content, "Dein Eintrag wurde in")!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sign guestbook completed.");
							$this->lastSentTime = time();
							DBConnect::execute_q("INSERT INTO singlesleipzig_sent_messages (to_username, to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."', '".addslashes($item['userid'])."','".$username."', '".addslashes($subject)."', '".addslashes($message)."', NOW())");
						}
						else
						{
							$this->newMessage=true;
							$this->lastSentTime = time();
							$this->savelog("Sign guestbook failed.");
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}
				$this->cancelReservedUser($item['userid']);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function sendTestMessage($username, $cookiePath){
		$this->savelog("Sending test message.");
		$profiles = DBConnect::assoc_query_1D("SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID);
		if($this->command['gender']=="2")
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

			$this->work_sendMessage($username, $item, null, $cookiePath, true);
		}
		else
		{
			$this->savelog("Get test profile failed.");
		}
	}

	private function parse_curl_cookie($cookie_file)
    {
		if(file_exists($cookie_file))
		{
			$cookie = file_get_contents($cookie_file);
			$cookie = str_replace("\r\n","\n",$cookie);
			$cookie = str_replace("\r","\n",$cookie);
			$lines = explode("\n",$cookie);
			$result = array();
			foreach($lines as $line)
			{
				if(strpos($line,"www.team-ulm.de")>-1)
				{
					$contents = explode("\t",$line);
					$result[$contents[5]]=array("value"=>$contents[6],"expired"=>$contents[4]);
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
    }

	private function getSearchID($content)
	{
		$content = substr($content, strpos($content, "searchid=")+9);
		$content = substr($content, 0, strpos($content, "&"));
		return $content;
	}

	private function getMessagesFromOutbox($username, $content){
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<form name="form1" id="form1" method="post" action="/msg_del.php" style="width:100%;">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</form>')+7);

		// Make it to XML object
		$parser = $this->convertToXML($username, "outbox", $content);

		// Check if it's correct result
		if(isset($parser->document->form[0]))
		{
			foreach($parser->document->form[0]->table[0]->tr[0]->td[0]->table[0]->tr as $item)
			{
				if(isset($item->td[0]->a))
				{
					$message = $item->td[3]->input[0]->tagAttrs['value'];
					array_push($list,$message);
				}
			}
		}
		return $list;
	}

	private function isAlreadySent($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM singlesleipzig_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM singlesleipzig_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO singlesleipzig_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM singlesleipzig_reservation WHERE userid='".$userid."' AND server=".$this->botID);
	}
}
?>