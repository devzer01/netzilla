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

class teamULM extends bot
{
	public $sessionID = "";
	public $sendmsg_total = 0;
	
	public function teamULM($post)
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
						"username" => "-Monik",
						"password" => "wMonik35"
					)
				),
				"messages" => array(
					array(
						"subject" => "Hallo",
						"message" => "Hallo again"
					),
					array(
						"subject" => "Hallo",
						"message" => "Hallo again"
					)
				),
				"start_h" => 00,
				"start_m" => 00,
				"end_h" => 00,
				"end_m" => 00,
				"messages_per_hour" => 30,
				//"send_test" => 1,
				"age_from" => 18,
				"age_to" => 40,
				"gender" => "m",
				"start_page" => 1,
				"msg_type" => "gb",
				"options" => array(
					"only_online" => "on"
				),
				//"action" => "check"
				//"full_msg" => 1,
				"action" => "send",
				'logout_after_sent' => 'Y',
				'messages_logout' => 1,
				'wait_for_login' => 1
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 50;
		}

		$this->usernameField = "benutzer";
		$this->loginURL = "http://www.team-ulm.de/login.php";
		$this->loginRefererURL = "http://www.team-ulm.de";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.team-ulm.de/logout.php?sid=";
		$this->indexURL = "http://www.team-ulm.de";
		$this->indexURLLoggedInKeyword = "/grafiken/layout_n/menue/logout.jpg";
		$this->searchURL = "http://www.team-ulm.de/usersuche.ajax.php";
		$this->searchRefererURL = "http://www.team-ulm.de/usersuche.php";
		$this->searchResultsPerPage = 50;
		$this->profileURL = "http://www.team-ulm.de/Profil/";
		$this->sendMessagePageURL = "http://www.team-ulm.de/popup/msg_write.php?src=prfl&recp=";
		$this->sendMessageURL1 = "http://www.team-ulm.de/popup/msg_rcp_check.php";
		$this->sendMessageURL2 = "http://www.team-ulm.de/popup/";
		$this->signGuestbookPageURL = "http://www.team-ulm.de/popup/p_addguestposting.php?p_userid=43531";
		$this->signGuestbookURL = "http://www.team-ulm.de/p_guestbook_save.php";
		$this->outboxURL = "http://www.team-ulm.de/CommuniX/Outbox";
		$this->deleteOutboxURL = "http://www.team-ulm.de/msg_del.php";
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
		
		if($this->command['gender'] == "m"){
			$target = "Male";
		}elseif($this->command['gender'] == "w"){
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
								"benutzer" => $user['username'],
								"passwort" => $user['password'],
								"imageField.x" => 9,
								"imageField.y" => 11
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
		
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);
		$this->newMessage=false;
		
		if(isset($this->command['send_test']) && $this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			if($age==$this->command['age_from'])
				$page=$this->command['start_page'];
			else
				$page=1;
			$list=array();
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
										"p_username" => "",
										"rb_geschl" => $this->command['gender'],
										"p_vorname" => "",
										"rb_single" => "egal",
										"p_nachname" => "",
										"rb_p_bild" => "egal",
										"p_alter_start" => $age,
										"p_alter_ende" => $age,
										"submit" => ">>> Usersuche starten",
										"page" => $page-1
									);

				if(isset($this->command['options']))
				{
					foreach($this->command['options'] as $key=>$value)
					{
						$search_arr[$key]=$value;
					}
				}

				$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.", page: ".$page);
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
						
						$first_username = $list[0]['username'];

						$this->sleep(5);
						foreach($list as $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								//$item['username'] = "anabelz";
								//$item['userid'] = "1024358";
								if(!$this->work_sendMessage($username, $item, $search_arr, $cookiePath))
									return false;
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
				
				
				if($this->command['version'] == 2) {
						
					$message_id = array();
					$inbox = $this->getInboxMessages($username, $cookiePath);
					$numInbox = count($inbox);
					if($numInbox != 0) {
						// Inbox Message
						$this->savelog("Found ".number_format($numInbox)." inbox messages");
						
						foreach($inbox as $item) {
							$this->savelog('[INBOX] - Reply message back to '. $item['username']);
							$this->work_sendMessage($username, $item, $search_arr, $cookiePath);
							$message_id[] = $item['message_id'];
						}
						
						// Delete reply inbox Message
						$this->savelog("Delete inbox messages");
						$this->getHTTPContent('http://www.team-ulm.de/msg_del.php','http://www.team-ulm.de/CommuniX/Inbox/0', $cookiePath, array(
							'msgdel' => $message_id,
							'page' => 'inbox',
							'startlimit' => 'limit'
						));
					}
		
				}
				$page++;
			}
			while(count($list)>=$this->searchResultsPerPage);
		}

		$this->savelog("Job completed.");
		return true;
	}
	
	/**
	 * Get Inbox list users (Pok)
	 * 
	 * @access public
	 * @return array
	 */
	private function getInboxMessages($username, $cookiePath) {
			
		$list = array();
		$inbox = array();
		$i = 0;
		$j = 0;
		$this->savelog("Go to inbox to reply message back");
		$content = $this->getHTTPContent('http://www.team-ulm.de/CommuniX/Inbox', $this->indexURL, $cookiePath);
		
		if(!empty($content)) {
			$html = str_get_html($content);
			if($html->find('form[id="form1"]',0)) {
				foreach($html->find('form[id="form1"]',0)->find("a") as $anchor) {
					if(($i%2) == 0) {
						$inbox[$j] = array();
						$inbox[$j]['message_id'] = str_replace(');','',str_replace('javascript:openmsg(','',$anchor->href));
					} else {
						$user_id = str_replace('/Profil/','',$anchor->href);
						if($user_id != '') {
							$inbox[$j]['username'] = trim($anchor->plaintext);
							$inbox[$j]['userid'] = $user_id;
						}
						
						$j++;
					}
					$i++;
				}
			}
		}
		
		
		foreach($inbox as $in) {
			if($in['username'] != '' && $in['userid'] != '' && $in['message_id'] != '') {
				$list[] = $in;
			}
		}
		var_dump($list);
		return $list;
	}
	
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->table[0]))
		{
			foreach($parser->document->table[0]->tr as $row)
			{
				if(!isset($row->tagAttrs['class']))
				{
					array_push($list, array(
												"username"=>$row->td[1]->a[0]->tagData,
												"userid"=>str_replace("/Profil/","",$row->td[1]->a[0]->tagAttrs['href'])
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

	private function work_sendMessage($username, $item, $search_arr, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
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
				if($search_arr)
					$referer = $referer."?".http_build_query($search_arr);
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$content = $this->getHTTPContent($this->profileURL.$item['userid'], $referer, $cookiePath);
				$this->sleep(5);

				if($this->command["msg_type"] == "pm")
				{
					/***********************************/
					/***** Go to send message page *****/
					/***********************************/
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);
					$this->sleep(5);

					/************************/
					/***** Send message *****/
					/************************/
					$message_arr1 = array(
											"recipientid" => $item['userid'],
											"betreff" => $subject,
											"message" => $message,
											"senden" => "Bitte warten..."
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendMessageURL1, $this->sendMessagePageURL.$item['userid'], $cookiePath, $message_arr1);
						file_put_contents("sending/pm-".$username."-".$item['username']."-1.html",$content);

						$token = $this->getToken($content);

						$message_arr2 = array(
												"recipientid" => $item['userid'],
												"betreff" => $subject,
												"message" => $message
											);

						$content = $this->getHTTPContent($this->sendMessageURL2.$token, $this->sendMessagePageURL.$item['userid'], $cookiePath, $message_arr2);
						file_put_contents("sending/pm-".$username."-".$item['username']."-2.html",$content);
						
						// Die Nachricht wurde erfolgreich verschickt!	
						if(strpos($content, "erfolgreich")!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							$this->lastSentTime = time();
							DBConnect::execute_q("INSERT INTO teamulm_sent_messages (to_username, to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$return = true;
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Sending message failed.");
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = true;
					}
					$this->cancelReservedUser($item['userid']);
					$this->sleep(2);

					///////////////////////////////////
					//////// Go to outbox page ////////
					///////////////////////////////////
					$this->savelog("Go to OUTBOX page.");
					$content = $this->getHTTPContent($this->outboxURL, $this->loginRefererURL, $cookiePath);
					$this->sleep(5);

					///////////////////////////////////
					////// Extract messages list //////
					///////////////////////////////////
					$messages = $this->getMessagesFromOutbox($username, $content);

					if(is_array($messages) && (count($messages)>0))
					{
						$this->savelog("Total ".count($messages)." outbox message(s).");
						
						$delete_arr = array(
												"page" => "outbox",
												"startlimit" => 0,
												"msgdel" => $messages
											);

						$this->savelog("Deleting all outbox messages.");
						$content = $this->getHTTPContent($this->deleteOutboxURL, $this->outboxURL, $cookiePath, $delete_arr);
					}
					else
					{
						$this->savelog("There is no outbox message to delete.");
					}
				}
				else
				{
					/*************************************/
					/***** Go to sign guestbook page *****/
					/*************************************/
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);
					$this->sleep(5);

					/**************************/
					/***** sign guestbook *****/
					/**************************/
					$message_arr1 = array(
											"eintrag" => $message,
											"p_userid" => $item['userid'],
											"_" => ""
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$previousGuestbookWritten = (int)$this->getGuestbookWritten($username, $cookiePath);
						$this->savelog("Previous guestbook written: ".$previousGuestbookWritten);
						$this->savelog("Signing guestbook to ".$item['username']);
						$content = $this->getHTTPContent($this->signGuestbookURL, $this->signGuestbookPageURL.$item['userid'], $cookiePath, $message_arr1);
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);

						if(strpos($content, "done")!==false)
						{
							$this->sleep(10);
							$currentGuestbookWritten = (int)$this->getGuestbookWritten($username, $cookiePath);
							$this->savelog("Current guestbook written: ".$currentGuestbookWritten);
							if($currentGuestbookWritten > $previousGuestbookWritten)
							{
								$this->newMessage=true;
								$this->savelog("Sign guestbook completed.");
								$this->lastSentTime = time();
								DBConnect::execute_q("INSERT INTO teamulm_sent_messages (to_username, to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$return = true;
							}
							else
							{
								$this->newMessage=true;
								$this->savelog("Profile ".$username." is blocked.");
								DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE site_id=".$this->siteID." AND username='".$username."'");
								unset($this->loginArr[$this->currentUser]);
								$return = false;
							}
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Sign guestbook failed.");
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']);
						$return = true;
					}
					$this->cancelReservedUser($item['userid']);
					$this->sleep(2);
					
				}
				$this->sendmsg_total++;
				
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
			$this->savelog("Already send message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function getGuestbookWritten($username, $cookiePath)
	{
		$this->savelog("Checking guestbook entries written.");
		$content = $this->getHTTPContent($this->profileURL, $this->indexURL, $cookiePath);

		$content = substr($content,strpos($content,"GB-Eintr&auml;ge geschrieben"));
		$content = substr($content,strpos($content,"<td>")+4);
		$content = substr($content,0, strpos($content,"</td>"));
		return $content;
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

	public function setSessionID()
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$cookie = $this->parse_curl_cookie($cookiePath);
		$this->sessionID = $cookie['PHPSESSID']['value'];
	}

	private function getToken($content)
	{
		$content = substr($content, strpos($content, "action")+10);
		$content = substr($content, 0, strpos($content, "\""));
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM teamulm_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM teamulm_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO teamulm_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM teamulm_reservation WHERE userid=".$userid." AND server=".$this->botID);
	}
	
	public function getNewProfile($forceNew = FALSE) {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$this->loginArr = array();
		$this->savelog("Site ID : ". $this->siteID);
		// $fetch = DBConnect::assoc_query_2D("SELECT * FROM user_profiles WHERE status != 'false' AND site_id=".$this->siteID." AND in_use = 'false' ORDER BY rand() LIMIT 1");
			
		if($this->command['login_by'] == 1 || $forceNew === TRUE ){
			
			$row = botutil::getNewProfile($this->siteID, $username, $this->command);
			$fetch[0] = $row;
			
		}else{

			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (in_use='false') AND (username='".$username."') LIMIT 1";
			$fetch = DBConnect::assoc_query_2D($sql);
			
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
}
?>