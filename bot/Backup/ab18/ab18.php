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

class ab18 extends bot
{
	public function ab18($post)
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
																	"username" => "Christine1991",
																	"password" => "dasselbePasswor"
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
									"age_to" => 18,
									"gender" => "m",
									"msg_type" => "pm",
									"send_test" => 0,
									"distance" => 20,
									"country" => "Deutschland",
									"start_plz" => "36100",
									"start_page" => 1,
									"version" => 1,
									//"full_msg" => 1,
									"options" => array(
														//"mitbild" => 1,
														"online" => 1
														),
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 54;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->token = "";
		$this->databaseName = "ab18";
		$this->usernameField = "nickname";
		$this->indexURL = "http://www.ab18.de/";
		$this->indexURLLoggedInKeyword = "ausloggen";
		$this->loginURL = "http://www.ab18.de/community/login/";
		$this->loginRefererURL = "http://www.ab18.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.ab18.de/community/logout/";
		$this->searchPageURL = "http://www.ab18.de/community/mitgliedersuche/";
		$this->searchURL = "http://www.ab18.de/community/mitgliedersuche/suchergebnisse.php";
		$this->searchRefererURL = "http://www.ab18.de/community/mitgliedersuche/";
		$this->searchResultsPerPage = 44;
		$this->profileURL = "http://www.ab18.de/hp/";
		$this->sendMessagePageURL = "http://www.ab18.de/hp/";
		$this->sendMessageURL = "http://www.ab18.de/hp/";
		$this->sendGuestbookPageURL = "http://www.ab18.de/hp/";
		$this->sendGuestbookURL = "http://www.ab18.de/hp/";
		$this->inboxURL = "http://www.ab18.de/community/mailbox/index.php?dir=posteingang&num=1000";
		$this->deleteInboxURL = "http://www.ab18.de/community/mailbox/index.php";
		$this->outboxURL = "http://www.ab18.de/community/mailbox/index.php?dir=postausgang";
		$this->deleteOutboxURL = "http://www.ab18.de/community/mailbox/index.php";
		$this->photoCommentURL = "http://www.kwick.de/socialobject/Profile_Photos_Photo/";
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
		$this->zipcodes = array(
							"Deutschland" => array(
													"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
										),
							"Österreich" => array(
													"1010", "4040", "5020", "6020", "7000", "8010", "9020"
										),
							"Schweiz" => array(
													"8045", "6300", "9000", "3150", "8200", "6023", "9217"
										)
						);
		
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
			$login_arr = array(	"nickname" => $user['username'],
								"pass1" => $user['password']
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
			if(isset($this->command['options']['online']))
			{
				$plz = array("-");
			}
			else
			{
				$plz = $this->zipcodes[$this->command['country']];
				if($age == $this->command['age_from'])
				{
					if($key = array_search($this->command['start_plz'],$plz))
					{
						$plz = array_slice($plz, $key);
					}
				}
			}

			foreach($plz as $zipcode)
			{
				$list=array();

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($zipcode==$this->command['start_plz']))
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
												"bin_geschlecht" => ($this->command['gender']=="m")?"w":"m",
												"geschlecht" => $this->command['gender'],
												"alter_von" => $age,
												"alter_bis" => $age,
												"land" => $this->command['country'],
												"plz" => ""
										);

						if($zipcode!="-")
						{
							$search_arr["plz"]=$zipcode;
							$search_arr["umkreis"]=$this->command['distance'];
							$details = ", distance: ".$this->command['distance'].", plz: ".$zipcode;
						}

						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr[$key]=$value;
								$details .= ", $key = $value";
							}
						}

						$search_arr = array(
												"special" => "",
												"registerval" => "",
												"skip" => ($page-1)*$this->searchResultsPerPage,
												"suche_params" => base64_encode(serialize($search_arr))
											);

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.$details.", page: ".$page);
						$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
						file_put_contents("search/".$username."-search-".$age."-".$zipcode."-".$page.".html",$content);

						/***********************************************/
						/***** Extract profiles from search result *****/
						/***********************************************/
						$list = $this->getMembersFromSearchResult($username, $page, $content);

						if(is_array($list))
						{
							$this->savelog("Found ".count($list)." member(s)");
							if(count($list))
							{
								$this->sleep(5);
								foreach($list as $key => $item)
								{
									$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
									//If in runnig time period
									if($sleep_time==0)
									{
										if($this->command['version']==1)
										{
											$this->work_sendMessage($username, $item, $cookiePath);
										}
										elseif($this->command['version']==2)
										{
											$this->work_visitProfile($username, $item, $cookiePath);
										}
										else
										{
											$this->savelog("Wrong version selected.");
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

						if($this->command['version']==1)
						{
							$this->deleteAllOutboxMessages($username, $cookiePath);
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
									$this->deleteAllOutboxMessages($username, $cookiePath);
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

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
	}

	private function getMembersFromSearchResult($username, $page, $content){

		$list = array();
		$content = substr($content, strpos($content, '<div class="col_weissBg content_inner">'));
		$content = substr($content, 0, strpos($content, '<!-- content ende -->'));

		$parser = $this->convertToXML($username, $page, $content);

		if(isset($parser->document->div[0]))
		{
			foreach($parser->document->div[0]->div as $item)
			{
				if(strpos($item->tagAttrs['class'], "left_float")!==false)
				{
					$profile = array(
										"username" => $item->div[1]->table[0]->tr[0]->td[0]->a[0]->img[0]->tagAttrs['alt']
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
		$this->savelog("Go to profile page: ".$item['username']);
		$content = $this->getHTTPContent($this->profileURL.$item['username']."/", $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
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

			// Go to profile page
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				////////////////////////////////////////
				/////// Go to send message page ////////
				////////////////////////////////////////
				$this->savelog("Go to send message page: ".$item['username']);
				$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username']."/kontaktieren/", $this->profileURL.$item['username']."/", $cookiePath);
				$this->sleep(5);
				$message_arr = array(
										"subject" => $subject,
										"message" => $message,
										"attach" => array( "id" => "",
															"type" => "",
															"prev" => "",
															"prevpop" => ""
														),
										"aktion" => "versenden"
										);
				if(time() < ($this->lastSentTime + $this->messageSendingInterval))
					$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
				$this->savelog("Sending message to ".$item['username']);
				if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
				{
					$url = $this->sendMessageURL.$item['username']."/kontaktieren/";
					$url_referer = $this->sendMessagePageURL.$item['username']."/kontaktieren/";
					$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
					$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
					file_put_contents("sending/pm-".$username."-".$item['username'].".html",$url_log.$content);

					if(strpos($content, 'wurde versendet')!==false)
					{
						$this->newMessage=true;
						$this->savelog("Sending message completed.");
						DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
						$this->lastSentTime = time();
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
					else
					{
						$this->newMessage=true;
						$this->savelog("Sending message failed.");
						$this->lastSentTime = time();
						$this->sleep(120);
						$return = true;
					}
				}
				else
				{
					$this->newMessage=false;
					$this->cancelReservedUser($item['username']);
					$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
					if(isset($item['message']))
						$this->deleteInboxMessage($username, $item['message'], $cookiePath);
					$return = true;
				}
				$this->cancelReservedUser($item['username']);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE username='".$username."' AND server=".$this->botID);
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

		if(strpos($content, '<form name="MailboxForm"')!==false)
		{
			$content = substr($content, strpos($content, '<form name="MailboxForm"'));
			$content = substr($content, 0, strpos($content, '</form>')+7);
			$parser = $this->convertToXML($username, "outbox", $content);

			// Check if it's correct result
			if($parser->document->form[0]->tagAttrs['name']=="MailboxForm")
			{
				foreach($parser->document->form[0]->table[0]->tr as $item)
				{
					if(isset($item->td[1]->input[0]->tagAttrs['name']) && (strpos($item->td[1]->input[0]->tagAttrs['name'], "maildata[]")!==false))
					{
						$message = $item->td[1]->input[0]->tagAttrs['value'];
						array_push($list,$message);
					}
				}
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message);
		$ch = curl_init();
		$delete_arr = array(
								"maildata[]" => $message,
								"target_dir" => "delete",
								"dir" => "postausgang",
								"aktion" => "move",
								"num" => 40
							);

		$content = $this->getHTTPContent($this->deleteOutboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$page = 0;
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->indexURL, $cookiePath);

		if((strpos($content, '<span class="text_bold">posteingang</span> ')!==false))
		{
			$total = substr($content, strpos($content, '<span class="text_bold">posteingang</span>')+57);
			$total = substr($total, 0, strpos($total, '</td>'));
			$total = current(explode(" ",$total));

			if($total >= $this->inboxLimit)
			{
				if(strpos($content, '<form name="MailboxForm"')!==false)
				{
					$content = substr($content, strpos($content, '<form name="MailboxForm"'));
					$content = substr($content, 0, strpos($content, '</form>')+7);
					$parser = $this->convertToXML($username, "inbox", $content);

					// Check if it's correct result
					if($parser->document->form[0]->tagAttrs['name']=="MailboxForm")
					{
						foreach($parser->document->form[0]->table[0]->tr as $item)
						{
							if(isset($item->td[1]->input[0]->tagAttrs['name']) && (strpos($item->td[1]->input[0]->tagAttrs['name'], "maildata[]")!==false))
							{
								$message = array(
													"message" => $item->td[1]->input[0]->tagAttrs['value'],
													"username" => current(explode(":", $item->td[2]->span[0]->tagAttrs['title']))
												);
								array_push($list,$message);
							}
						}
					}
				}
			}
			else
			{
				$list = range(1, $total);
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message);
		$ch = curl_init();
		$delete_arr = array(
								"maildata[]" => $message,
								"target_dir" => "delete",
								"dir" => "posteingang",
								"aktion" => "move",
								"num" => 40
							);

		$content = $this->getHTTPContent($this->deleteInboxURL, $this->inboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}
}
?>