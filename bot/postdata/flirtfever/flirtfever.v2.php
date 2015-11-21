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

class flirtfever extends bot
{
	public $sendmsg_total = 0;
	public function flirtfever($post){
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
																	"username" => "nicolett_77",
																	"password" => "9121989a"
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
									"age_from" => 18,
									"age_to" => 20,
									//"gender" => "m",
									"gender" => "maennlich",
									"country" => 1,
									"fever_time" => 120,
									"sort" => "beliebtheit",
									"start_plz" => 10179,
									"msg_type" => "pm",
									"send_test" => 0,
									"options" => array(
														"online" => "y",
														"distance" => 500
														),
									"fevers" => array(1,3,9),
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 46;
		}

		$this->usernameField = "name";
		$this->loginURL = "https://www.flirt-fever.de/login.php5";
		$this->loginRefererURL = "http://www.flirt-fever.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.flirt-fever.de/login.php5?logout=1";
		$this->indexURL = "http://www.flirt-fever.de/index.php5";
		$this->indexURLLoggedInKeyword = "/login.php5?logout=1";
		$this->searchURL = "http://www.flirt-fever.de/suche.php";
		$this->searchRefererURL = "http://www.flirt-fever.de/suche.php";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "http://www.flirt-fever.de/profil.php5?id=";
		$this->sendMessageURL = "http://www.flirt-fever.de/mail_schreiben.php5?id=";
		$this->sendFeverURL = "http://www.flirt-fever.de/teaser_senden.php?id=";
		$this->sendGuestbookURL = "http://www.flirt-fever.de/gaestebuch.php5?id=";
		$this->outboxURL = "http://www.flirt-fever.de/mailbox_ausgang.php5";
		$this->inboxURL = "http://www.flirt-fever.de/mailbox_eingang.php5";
		$this->feverBoxURL = "http://www.flirt-fever.de/teaser.php";
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
		$this->zipcodes = array(
							"1" => array(
											"short" => array(
																"01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089"
															),
											"long" => array(
																"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
															)
										),
							"2" => array(
											"short" => array(
																"1010", "4040", "5020", "6020", "7000", "8010", "9020"
															),
											"long" => array(
																"1010", "4040", "5020", "6020", "7000", "8010", "9020"
															)
										),
							"3" => array(
											"short" => array(
																"8045", "6300", "9000", "3150", "8200", "6023", "9217"
															),
											"long" => array(
																"8045", "6300", "9000", "3150", "8200", "6023", "9217"
															)
										)
						);
		$this->startTime = 0;
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		if($this->command['gender'] == "maennlich"){
			$target = "Male";
		}elseif($this->command['gender'] == "weiblich"){
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

	public function resetPLZ(){
		$this->command['start_plz'] = "00000";
	}

	public function addLoginData($users){
		foreach($users as $user)
		{
			$login_arr = array(	"x" => 54,
								"y" => 12,
								"log" => array (
													"passwort" => $user['password'],
													"name" => $user['username']
												)
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

	public function work(){
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser]["log"][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		list($subject, $message)=$this->getMessage($this->newMessage);

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			if(isset($this->command['options']['online']))
			{
				$plz = array("-");
			}
			else
			{
				if($this->command['options']['distance']<=500)
				{
					$plz = $this->zipcodes[$this->command['country']]['long'];
					if($age == $this->command['age_from'])
					{
						if($key = array_search($this->command['start_plz'],$plz))
						{
							$plz = array_slice($plz, $key);
						}
					}
				}
				else
				{
					$plz = $this->zipcodes[$this->command['country']]['short'];
					if($age == $this->command['age_from'])
					{
						if($key = array_search($this->command['start_plz'],$plz))
						{
							$plz = array_slice($plz, $key);
						}
					}
				}
			}

			foreach($plz as $zipcode)
			{
				$page=1;
				$list=array();
				do
				{
					$list = $this->work_doSearch($username, $age, $zipcode, $page, $cookiePath);

					if(is_array($list))
					{
						$this->savelog("Found ".count($list)." member(s)");
						$this->sleep(5);
						foreach($list as $key => $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								$this->work_sendFever($username, $item, $cookiePath);
							}
							else
							{
								$this->savelog("Not in running time period.");
								$this->sleep($sleep_time);
								return true;
							}
						}
					}

					$messages = $this->getMessagesFromInbox($username, $cookiePath);
					if(is_array($messages))
					{
						$this->savelog("Found ".count($messages)." member(s)");
						$this->sleep(5);
						foreach($messages as $key => $item)
						{
							if(DBConnect::retrieve_value("SELECT id FROM flirtfever_sent_fevers WHERE to_userid='".$item['userid']."'"))
							{
								$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
								//If in runnig time period
								if($sleep_time==0)
								{
									if($this->work_sendMessage($username, $item, $cookiePath))
										$this->work_deleteInbox($item, $cookiePath);
								}
								else
								{
									$this->savelog("Not in running time period.");
									$this->sleep($sleep_time);
									return true;
								}
							}
							else
							{
								$this->savelog("User: ".$item['username']." is not the person ".$username." sent fever to.");
								$this->work_deleteInbox($item, $cookiePath);
							}
						}
					}

					$fevers = $this->getFevers($username, $cookiePath);
					if(is_array($fevers))
					{
						$this->savelog("Found ".count($fevers)." member(s)");
						$this->sleep(5);
						foreach($fevers as $key => $item)
						{
							if(DBConnect::retrieve_value("SELECT id FROM flirtfever_sent_fevers WHERE to_userid='".$item['userid']."'"))
							{
								$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
								//If in runnig time period
								if($sleep_time==0)
								{
									if($this->work_sendMessage($username, $item, $cookiePath))
										$this->work_deleteFever($item, $cookiePath);
								}
								else
								{
									$this->savelog("Not in running time period.");
									$this->sleep($sleep_time);
									return true;
								}
							}
							else
							{
								$this->savelog("User: ".$item['username']." is not the person ".$username." sent fever to.");
								$this->work_deleteFever($item, $cookiePath);
							}
						}
					}

					$page++;
				}
				while((count($list)>=$this->searchResultsPerPage) && ($page<=50));
			}
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function getMembersFromSearchResult($username, $page, $content, $isOnline=null){
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<div class="content_bordered">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'<div class="bottom"></div>'));
		$content = substr($content,0,strrpos($content,'</div>'));

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->div[0]))
		{
			foreach($parser->document->div[0]->div as $item)
			{
				if($item->tagAttrs['class'] == "box middle")
				{
					$profile = array(
										"username" => $item->div[1]->div[0]->p[0]->a[0]->tagData,
										"userid" => str_replace("/profil.php5?id=","",$item->div[1]->div[0]->p[0]->a[0]->tagAttrs['href'])
									);
					if(isset($item->div[1]->div[1]->img[0]) && ($item->div[1]->div[1]->img[0]->tagAttrs['src']=="./images/online.gif"))
						$profile['online'] = true;

					if((($isOnline=="y") && (isset($profile['online']))) || ($isOnline==null))
					{
						array_push($list,$profile);
					}
				}
			}
		}
		return $list;
	}

	private function getMessagesFromOutbox($username, $content){
		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<form method="post" name="boxes" action="mailbox_ausgang.php5">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</form>')+7);

		// Make it to XML object
		$parser = $this->convertToXML($username, "outbox", $content);

		// Check if it's correct result
		if(isset($parser->document->form[0]))
		{
			foreach($parser->document->form[0]->div as $item)
			{
				if($item->tagAttrs['class'] == "box middle")
				{
					$message = $item->div[3]->input[0]->tagAttrs['value'];
					array_push($list,$message);
				}
			}
		}
		return $list;
	}

	private function getMessagesFromInbox($username, $cookiePath){
		$this->savelog("Go to INBOX page.");
		$content = $this->getHTTPContent($this->inboxURL, $this->loginRefererURL, $cookiePath);

		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<form method="post" name="boxes" action="mailbox_eingang.php5">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</form>')+7);

		// Make it to XML object
		$parser = $this->convertToXML($username, "inbox", $content);

		// Check if it's correct result
		if(isset($parser->document->form[0]))
		{
			foreach($parser->document->form[0]->div as $item)
			{
				if($item->tagAttrs['class'] == "box middle")
				{
					$message = array(
										"id" => $item->div[3]->input[0]->tagAttrs['value'],
										"username" => $item->div[1]->p[0]->a[0]->tagData,
										"userid" => str_replace("/profil.php5?id=","",$item->div[1]->p[0]->a[0]->tagAttrs['href'])
									);

					array_push($list,$message);
				}
			}
		}
		return $list;
	}

	private function getFevers($username, $cookiePath){
		$this->savelog("Go to FEVER page.");
		$content = $this->getHTTPContent($this->feverBoxURL, $this->loginRefererURL, $cookiePath);

		$list = array();

		// Cut top
		$content = substr($content,strpos($content,'<form action="teaser.php" name="boxes" method="post">'));
		// Cut bottom
		$content = substr($content,0,strpos($content,'</form>')+7);

		// Make it to XML object
		$parser = $this->convertToXML($username, "fever", $content);

		// Check if it's correct result
		if(isset($parser->document->form[0]))
		{
			foreach($parser->document->form[0]->div as $item)
			{
				if($item->tagAttrs['class'] == "box middle teaser")
				{
					$fever = array(
										"id" => $item->div[0]->div[2]->input[0]->tagAttrs['value'],
										"username" => $item->div[1]->div[1]->a[0]->tagData,
										"userid" => str_replace("/profil.php5?id=","",$item->div[1]->div[1]->a[0]->tagAttrs['href'])
									);

					array_push($list,$fever);
				}
			}
		}
		return $list;
	}

	private function work_deleteInbox($message, $cookiePath){
		if(is_array($message) && (count($message)>0))
		{
			$this->savelog("Deleting message id: ".$message['id']);
			
			$delete_arr = array(
									"x" => 8,
									"y" => 10,
									"dbdel2" => array($message['id'])
								);

			$content = $this->getHTTPContent($this->inboxURL, $this->inboxURL, $cookiePath, $delete_arr);
		}
	}

	private function work_deleteFever($fever, $cookiePath){
		if(is_array($fever) && (count($fever)>0))
		{
			$this->savelog("Deleting fever id: ".$fever['id']);
			
			$delete_arr = array(
									"x" => 8,
									"y" => 10,
									"tdel" => array($fever['id'])
								);

			$content = $this->getHTTPContent($this->feverBoxURL, $this->feverBoxURL, $cookiePath, $delete_arr);
		}
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

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
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
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$content = $this->getHTTPContent($this->profileURL.$item['userid'], $this->searchRefererURL, $cookiePath);

				$this->sleep(5);

				////////////////////////////////////////
				///////////// Send message /////////////
				////////////////////////////////////////
				if($this->command['msg_type']=="pm")
				{
					////////////////////////////////////////
					/////// Go to send message page ////////
					////////////////////////////////////////
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessageURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);

					if(strpos($content, 'action="https://www.flirt-fever.de/anmeldung.php5"')===false)
					{
						$this->sleep(5);
						$message_arr = array(
												"subject" => $subject,
												"nachricht" => utf8_decode($message),
												"x" => 27,
												"y" => 14
												);
						if(time() < ($this->lastSentTime + $this->messageSendingInterval))
							$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
						$this->savelog("Sending message to ".$item['username']);
						if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
						{
							$content = $this->getHTTPContent($this->sendMessageURL.$item['userid'], $this->sendMessageURL.$item['userid'], $cookiePath, $message_arr);
							file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$content);

							if(strpos($content, 'Deine Nachricht wurde in seiner/ihrer Mailbox gespeichert.')!==false)
							{
								$this->newMessage=true;
								$this->savelog("Sending message completed.");
								DBConnect::execute_q("INSERT INTO flirtfever_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$return = true;
								$this->sendmsg_total++;
							}
							else
							{
								$this->newMessage=true;
								$this->savelog("Sending message failed.");
								$return = false;
							}
						}
						else
						{
							$this->newMessage=false;
							$this->cancelReservedUser($item['userid']);
							$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
							$return = false;
						}

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
													"x" => 8,
													"y" => 10,
													"dbdel2" => $messages
												);

							$this->savelog("Deleting all outbox messages.");
							$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);
						}
						else
						{
							$this->savelog("There is no outbox message to delete.");
						}
					}
					else
					{
						$this->newMessage=false;
						$this->savelog("This profile is blocked: ".$username);
						DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE site_id=".$this->siteID." AND username='".$username."'");
						unset($this->loginArr[$this->currentUser]);
						exit;
					}
				}
				else
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendGuestbookURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);

					if(strpos($content, 'action="https://www.flirt-fever.de/anmeldung.php5"')===false)
					{
						$previousNum = $this->getGuestbookEntry($content);
						$this->savelog("Previous guestbook entry is ".$previousNum);
						$this->sleep(5);
						$message_arr = array(
												"gbeintrag" => utf8_decode($message),
												"x" => 27,
												"y" => 14
												);
						if(time() < ($this->lastSentTime + $this->messageSendingInterval))
							$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
						$this->savelog("Signing guestbook to ".$item['username']);
						if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
						{
							$content = $this->getHTTPContent($this->sendGuestbookURL.$item['userid'], $this->sendGuestbookURL.$item['userid'], $cookiePath, $message_arr);
							file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['userid'].".html",$content);
							$currentNum = $this->getGuestbookEntry($content);
							$this->savelog("Current guestbook entry is ".$currentNum);

							if(is_numeric($currentNum) && is_numeric($previousNum))
							{
								if($currentNum>$previousNum)
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook completed.");
									DBConnect::execute_q("INSERT INTO flirtfever_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									$this->lastSentTime = time();
									$return = true;
									$this->sendmsg_total++;
								}
								else
								{
									$this->newMessage=true;
									$this->savelog("Signing guestbook failed.");
									$return = false;
								}
							}
							else
							{
								$this->newMessage=true;
								$this->savelog("Signing guestbook completed.");
								DBConnect::execute_q("INSERT INTO flirtfever_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$return = true;
								$this->sendmsg_total++;
							}
						}
						else
						{
							$this->newMessage=false;
							$this->cancelReservedUser($item['userid']);
							$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']);
							$return = false;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->savelog("This profile is blocked: ".$username);
						DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE site_id=".$this->siteID." AND username='".$username."'");
						unset($this->loginArr[$this->currentUser]);
						exit;
					}
				}
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
			$this->savelog("Already send message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
	}

	private function getGuestbookEntry($content){
		$content = substr($content,strpos($content,'<p class="left green">'));
		$content = substr($content,0,strpos($content,'</p>')+4);

		$content = strip_tags($content);
		$content = str_replace(array(" ","Eintrag","Einträge","Eintr&auml;ge"),"",$content);
		return $content;
	}

	private function work_sendFever($username, $item, $cookiePath){
		// If not already sent
		if(!DBConnect::retrieve_value("SELECT id FROM flirtfever_sent_fevers WHERE to_userid='".$item['userid']."'"))
		{
			// Go to profile page
			$this->savelog("Go to profile page: ".$item['username']);
			$content = $this->getHTTPContent($this->profileURL.$item['userid'], $this->searchRefererURL, $cookiePath);

			$this->sleep(5);

			////////////////////////////////////////
			/////// Go to fever page ////////
			////////////////////////////////////////
			$this->savelog("Go to fever page: ".$item['username']);
			$content = $this->getHTTPContent($this->sendFeverURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);
			$this->sleep(5);

			//////////////////////////////////////
			///////////// Send fever /////////////
			//////////////////////////////////////
			if(count($this->command['fevers']))
			{
				//RANDOM FEVER
				shuffle($this->command['fevers']);
				$fever = $this->command['fevers'][0];
			}
			else
			{
				$fever=1;
			}

			$fever_arr = array(
									"teaser_id" => $fever,
									"x" => 50,
									"y" => 7
								);
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending fever to ".$item['username']);
			$content = $this->getHTTPContent($this->sendFeverURL.$item['userid'], $this->sendFeverURL.$item['userid'], $cookiePath, $fever_arr);
			file_put_contents("sending/fever-".$username."-".$item['username']."-".$item['userid'].".html",$content);

			if((strpos($content, 'Du hast')!==false) && (strpos($content, 'gefevert')!==false))
			{
				$this->savelog("Sending fever completed.");
				DBConnect::execute_q("INSERT INTO flirtfever_sent_fevers (to_username,to_userid,from_username,sent_datetime) VALUES ('".$item['username']."','".$item['userid']."','".$username."',NOW())");
				$this->lastSentTime = time();
			}
			else
			{
				$this->savelog("Sending fever failed.");
			}
			$this->sleep(2);
		}
		else
		{
			$this->savelog("Already send fever to profile: ".$item['username']);
		}
	}

	private function work_doSearch($username, $age, $zipcode, $page, $cookiePath){
		/******************/
		/***** search *****/
		/******************/
		$searchURL = $this->searchURL."?search=".$this->command['gender']."/alter_".$age."-".$age."/";
		if(isset($this->command['options']['pic']))
			$searchURL .= "mit_bild/";

		switch($this->command['country'])
		{
			case "2":
				$searchURL .= "oesterreich/";
				break;
			case "3":
				$searchURL .= "schweiz/";
				break;
			default:
				break;
		}

		$log_distance = "";
		if(($zipcode != "-") && is_numeric($this->command['options']['distance']))
		{
			$searchURL .= "umkreis_".$this->command['options']['distance']."km_um_plz_".$zipcode."/";
			$log_distance = ", distance: ".$this->command['options']['distance'].", plz: ".$zipcode;
		}
		$searchURL .= "sortierung_".$this->command['sort']."/online_zuerst,".$page;

		$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].$log_distance.", age: ".$age.", page ".$page);
		$content = $this->getHTTPContent($searchURL, $this->searchRefererURL, $cookiePath);
		file_put_contents("search/".$username."-search-".$page.".html",$content);

		/***********************************************/
		/***** Extract profiles from search result *****/
		/***********************************************/
		$list = $this->getMembersFromSearchResult($username, $page, $content, isset($this->command['options']['online'])?$this->command['options']['online']:null);
		return $list;
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM flirtfever_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM flirtfever_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO flirtfever_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM flirtfever_reservation WHERE userid=".$userid." AND server=".$this->botID);
	}
}
?>