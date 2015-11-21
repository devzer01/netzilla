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

class SinglesLeipzig extends bot
{
	public $sessionID = "";
	public $sendmsg_total = 0;
	
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
																	"username" => "ernstakienast",
																	"password" => "schnell"
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
									"msg_type" => "pm",
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
		$this->newMessage = true;
		
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		$target = "Male";
		if($this->command['gender'] == 1){
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
			$this->savelog("Go to SEARCH ONLINE page");
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
								//if(($this->msg_count%30)<15)
									$this->command['msg_type'] = "pm";
								//else
								//	$this->command['msg_type'] = "gb";

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
					"sexori" => 0,
					"ausbildung" => 5,
					"sternzeichen" => (($this->command['sternzeichen'] == '0') ? 13 : $this->command['sternzeichen']),
					"Button" => "Suchen",
				);
		
				if(isset($this->command['options']))
				{
					foreach($this->command['options'] as $key=>$value)
					{
						$search_arr[$key]=$value;
					}
				}
				
				// http://www.singlesleipzig.de/search2.php?
				/*fs=7&
				sgender=2&
				grossraum=&
				agefrom=18&
				ageto=100&
				nurfoto=&
				land=0&
				r_bez=1&
				r_fre=1&
				r_hei=1&
				r_ons=1&
				r_aff=1&
				1=1&
				r_rei=1&
				r_bri=1&
				gender=&
				nurnofake=&
				status=egal&
				raucher=3&
				trinker=3&
				figur=4&
				sexori=0&
				searchid=a1385972169b1386043121&
				ausbildung=5&
				seizeto=230&
				seizefrom=130&
				username=&
				nurohnekind=x&
				wohnort=&
				sternzeichen=13*/
				
		
				$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
				$searchid = $this->getSearchID($content);
				
				if(strpos($content,'SMS-Aktivierungscode')) {
					$this->savelog('failed : Wir möchten auf SinglesLeipzig nur echte Menschen. Um Fakeaccounts zu unterbinden, benötigen wir deine Telefonnummer. An diese senden wir einen Aktivierungscode, mit dem du SinglesLeipzig dann komplett nutzen kannst. Die Telefonnummer wird ausschließlich für die Aktivierung verwendet. Das Versenden des Aktivierungscodes kann einige Zeit in Anspruch nehmen. Bitte haben Sie Geduld.');
				} elseif (strpos($content,'dich und was du suchst')) {
					$this->savelog('failed : Alle hier angegebenen Daten sind öffentlich auf deiner Nickpage sichtbar. Die Angaben sind freiwillig und können jederzeit geändert werden.');
				} else {
		
					do
					{
						$search_arr['fs'] = ($page - 1);
						$search_arr['searchid'] = $searchid;
			
						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr[$key]=$value;
							}
						}
					
						$this->savelog("Search for gender: ".$this->command['gender'].", sternzeichen: ".(($this->command['sternzeichen'] == 0) ? 'All' : $this->command['sternzeichen']).", age: ".$age.", page: ".$page);
						
						/******************/
						/***** search *****/
						/******************/
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
										// if(($this->msg_count%30)<15)
											$this->command['msg_type'] = "pm";
										// else
											// $this->command['msg_type'] = "gb";
			
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
		$html = str_get_html($content);
		if($html->find('ol',0)) {
			$ol = $html->find('ol',0);
			foreach($ol->find('li') as $li) {
				$id = explode('&',str_replace('np.php?uid=','',$li->find('a',1)->href));
				$list[] = array(
					'username' => $li->find('a',1)->plaintext,
					'uid' => $id[0]
				);
			}	
		}
		return $list;
	}

	private function getMembersFromOnlineResult($username, $page, $content)
	{
		$list = array();

		$html = str_get_html($content);
		if($html->find('table',3)) {
			foreach($html->find('table',3)->find('a') as $anchor) {
				$uid = str_replace('np.php?uid=','',$anchor);
				$text = explode(' ',$anchor->plaintext);
				$list[] = array(
					'username' => trim($text[0]),
					'age' => str_replace(array('(',')'),'', $text[1]),
					'userid' => $userid
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
			return true;
		}
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
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
				list($subject, $message) = $this->getMessage($this->newMessage);
			}
			
			
			$this->savelog("Message is : ".$message);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				$referer = $this->searchURL;
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$content = $this->getHTTPContent($this->profileURL.$item['userid'], $referer, $cookiePath);

				$this->sleep(5);

				// if($this->command['msg_type']=="pm")
				// {
					/***********************************/
					/***** Go to send message page *****/
					/***********************************/
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username'], $this->profileURL.$item['userid'], $cookiePath);

					$this->sleep(5);

					/************************/
					/***** Send message *****/
					/************************/
				
					$html = str_get_html($content);
					$message_arr = array(
						"msgaction" => "sendmsg",
						"ptkn2" => ((!empty($html->find('input[name=ptkn2]',0))) ? $html->find('input[name=ptkn2]',0)->value : ''),
						"altemsgid" => "",
						"receiver" => $item['username'],
						"mailbuddy" => "",
						"subject" => utf8_decode($subject),
						"count" => 20000-mb_strlen($message),
						"nachricht" => utf8_decode($message),
						"uise" => ((!empty($html->find('input[name=uise]',0))) ? $html->find('input[name=uise]',0)->value : ''),
						"submitfrm" => 'Abschicken [STRG+ENTER]',
						'tastaturanschlaege' => mb_strlen($message),
						'hittastaturanschlaege' => (mb_strlen($message)+30),
						'flagoutbox' => 'checkbox'
					);
					
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendMessageURL, $this->sendMessagePageURL.$item['username'], $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, "Deine Nachricht wurde erfolgreich verschickt")!==false)
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
				// }
				// elseif($this->command['msg_type']=="gb")
				// {
					// /***********************************/
					// /***** Go to sign guestbook page *****/
					// /***********************************/
					// $this->savelog("Go to sign guestbook page: ".$item['username']);
					// $content = $this->getHTTPContent($this->signGuestbookPageURL.$item['userid']."&subnp=gb", $this->profileURL.$item['userid'], $cookiePath);
// 
					// $this->sleep(5);
// 
					// /**************************/
					// /***** Sign guestbook *****/
					// /**************************/
					// $ptkn2 = substr($content, strpos($content, "name='ptkn2' value='")+20);
					// $ptkn2 = substr($ptkn2, 0, strpos($ptkn2, "'"));
// 
					// $message_arr = array(
											// "ptkn2" => $ptkn2,
											// "subnp" => "gb",
											// "uid" => $item['userid'],
											// "uname" => $item['username'],
											// "action" => "addgb",
											// "count" => 20000-strlen($message),
											// "nachricht" => utf8_decode($message),
											// "button" => "Eintragen"
											// );
					// if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						// $this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					// $this->savelog("Signing guestbook to ".$item['username']);
					// if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					// {
						// $content = $this->getHTTPContent($this->signGuestbookURL.$item['userid']."&subnp=gb", $this->signGuestbookPageURL.$item['userid']."&subnp=gb", $cookiePath, $message_arr);
						// file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['username'].".html",$content);
// 
						// if(strpos($content, "Dein Eintrag wurde in")!==false)
						// {
							// $this->newMessage=true;
							// $this->savelog("Sign guestbook completed.");
							// $this->lastSentTime = time();
							// DBConnect::execute_q("INSERT INTO singlesleipzig_sent_messages (to_username, to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."', '".addslashes($item['userid'])."','".$username."', '".addslashes($subject)."', '".addslashes($message)."', NOW())");
						// }
						// else
						// {
							// $this->newMessage=true;
							// $this->lastSentTime = time();
							// $this->savelog("Sign guestbook failed.");
						// }
					// }
					// else
					// {
						// $this->newMessage=false;
						// $this->cancelReservedUser($item['userid']);
						// $this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						// $return = false;
					// }
				// }
				
				$this->cancelReservedUser($item['userid']);
				$this->sleep(2);
				
				
				/* Logout after send x message completed */
				if($this->command['version']==1){
					if($this->command['logout_after_sent'] == "Y"){
						if($this->sendmsg_total >= $this->command['messages_logout']){
							$this->sendmsg_total = 0;
							$this->logout();
							$this->savelog('Logout after sent '.$this->command['messages_logout'].' messages(s) completed');
							$this->savelog("Get a new Profile for Send Message");
							$this->getNewProfile();
							$this->sleep(($this->command['wait_for_login']*60));
							$this->login();
							break 2;
						}
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
		$return = '';
		if(!empty($content)) {
			$html = str_get_html($content);
			$page = $html->find('center',0);
			if(!empty($page)) {
				$anchor = $page->find('a',0)->href;
				parse_str(str_replace('/search2.php?', '', $anchor), $output);
				$return = $output['searchid'];
			} else {
				$this->savelog("Unable to find search id");
			}
		}
		return $return;
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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM singlesleipzig_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid = 0)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM singlesleipzig_reservation WHERE username='".$username."'");

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
}
?>