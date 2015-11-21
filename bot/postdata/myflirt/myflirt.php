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

class myflirt extends bot
{
	public $count_msg = 0;
	public function myflirt($post)
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
																	"username" => "NaughtyAndrea",
																	"password" => "warumnicht?"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "flirtmit net"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 18,
									"age_to" => 18,
									"gender" => 1,
									"msg_type" => "gb",
									"send_test" => 0,
									"distance" => 0,
									"start_city" => "Hamburg",
									"start_page" => 1,
									"version" => 1,
									"target" => "Male",
									//"target_cm" => "lovely-singles.com",
									"target_cm" => "flirt48.net",
									"profile_type" => 1,
									/*"options" => array(
														"lastlogin" => 1
														),*/
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 55;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 100;

		$this->cityID = "";
		$this->city = "";
		$this->databaseName = "myflirt";
		$this->usernameField = "username";
		$this->indexURL = "http://www.myflirt.com/n/index";
		$this->indexURLLoggedInKeyword = "n/logout&mode=profillogout";
		$this->loginURL = "http://www.myflirt.com/n/dummy&mode=profil/json/login";
		$this->loginRefererURL = "http://www.myflirt.com/de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.myflirt.com/n/logout&mode=profillogout";
		$this->searchPageURL = "http://www.myflirt.com/n/suche_load&nfs=1#expand_up";
		$this->searchURL = "http://www.myflirt.com/n/suche_load";
		$this->searchRefererURL = "http://www.myflirt.com/n/suche_load&nfs=1#expand_up";
		$this->searchResultsPerPage = 20;
		$this->profileURL = "http://www.myflirt.com/n/profil_index&profil=";
		$this->sendMessagePageURL = "http://www.myflirt.com/n/profil_index&profil=";
		$this->sendMessageURL = "http://www.myflirt.com/n/dummy&mode=tele/json/send";
		$this->sendGuestbookPageURL = "http://www.myflirt.com/n/profil_gb&profil=";
		$this->sendGuestbookURL = "http://www.myflirt.com/n/dummy&mode=gb/json/add&profil=";
		$this->inboxURL = "http://www.myflirt.com/n/post";
		$this->deleteInboxURL = "http://www.myflirt.com/n/post&folderid=0&sid=&mode=maildo";
		$this->outboxURL = "http://www.myflirt.com/n/post&folderid=100";
		$this->deleteOutboxURL = "http://www.myflirt.com/n/post&folderid=100&sid=&mode=maildo";
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
		$this->user_sex = "";
		$this->user_name = "";

		$this->addLoginData($this->command['profiles']);
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->cities = array(
								1717891 => "Hamburg",
								1708690 => "Berlin",
								1694527 => "Hannover",
								1694527 => "Bremen",
								1749811 => "Bonn",
								1728134 => "Stuttgart",
								1709611 => "München",
								1742660 => "Leipzig",
								1706816 => "Nürnberg",
								1745331 => "neubrandenburg",
								1751304 => "magdeburg",
								1724418 => "paderborn",
								1688898 => "essen",
								1734384 => "mainz",
								1751968 => "göttingen",
								1737420 => "fulda",
								1730609 => "erfurt",
								1762713 => "ulm",
								1738773 => "würzburg",
								1731277 => "hof",
								1716543 => "schwerin"
								);
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		/*if($this->command['gender'] == 1){
			$target = "Male";
		}elseif($this->command['gender'] == 2){
			$target = "Female";
		}*/

		$target = $this->command['target'];
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->language = "DE";
		$this->messages_table = DBConnect::retrieve_value("SELECT setting_value FROM settings WHERE setting_name='MESSAGES_TABLE' LIMIT 1");
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM ".$this->messages_table);
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		for($i=1; $i<=$this->totalPart; $i++)
		{
			if($i<$this->totalPart)
			{
				$sql = "SELECT message FROM ".$this->messages_table." WHERE message!='' AND part=".$i." AND target='".$target."' AND language='".$this->language."'";
				$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D($sql);
			}
			else
			{
				$profile_type_sql = (isset($this->command['profile_type']) && is_numeric($this->command['profile_type']))?"AND profile_type=".$this->command['profile_type']:"";
				if(!isset($this->command['target_cm']) || !$this->command['target_cm'])
				{
					$sql = "SELECT message FROM ".$this->messages_table." WHERE message!='' AND part=".$i." AND target='".$target."' AND language='".$this->language."' AND target_cm='' ".$profile_type_sql;
					$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D($sql);
				}
				else
				{
					$sql = "SELECT message FROM ".$this->messages_table." WHERE message!='' AND part=".$i." AND target='".$target."' AND language='".$this->language."' AND target_cm='".$this->command['target_cm']."' ".$profile_type_sql;
					$backup_sql = "SELECT message FROM ".$this->messages_table." WHERE message!='' AND part=".$i." AND target='".$target."' AND language='".$this->language."' AND target_cm='".$this->command['target_cm']."' ".$profile_type_sql;
					$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D($sql);
					if(empty($this->messagesPart[$i]))
						$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D($backup_sql);
				}

				$backup_sql = "SELECT message FROM ".$this->messages_table." WHERE message!='' AND part=".$i." AND target='".$target."' AND language='".$this->language."' AND target_cm='".$this->command['target_cm']."'";
				$temp_messages = DBConnect::row_retrieve_2D_conv_1D($sql);
				if(empty($this->messagesPart[$i]))
					$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D($backup_sql);

			}
			$this->messagesPartTemp[$i] = array();
		}

		$this->savelog("Profile names for ".$this->command['target_cm']." => ".implode(", ",$this->messagesPart[$this->totalPart]));

		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	"username" => $user['username'],
								"al" => 1,
								"password" => $user['password']
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

	private function getCityID($content)
	{
		$content = substr($content, strpos($content, 'name="cityID" value=')+21);
		$content = substr($content, 0, strpos($content, '"'));
		return $content;
	}

	private function getCity($content)
	{
		$content = substr($content, strpos($content, 'name="city"'));
		$content = substr($content, strpos($content, 'value=')+7);
		$content = substr($content, 0, strpos($content, '"'));
		return $content;
	}

	public function getKeyPositionInArray($haystack, $keyNeedle)
	{
		$i = 0;
		foreach($haystack as $key => $value)
		{
			if($value == $keyNeedle)
			{
				return $i;
			}
			$i++;
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
		$this->count_msg = 0;

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$distance = $this->command['distance'];
			$cities = $this->cities;
			if(($this->workCount == 1) && ($age==$this->command['age_from']))
			{
				if($index = $this->getKeyPositionInArray($cities, $this->command['start_city']))
				{
					$cities = array_slice($cities, $index);
				}
			}

			foreach($cities as $cityID => $city)
			{
				$list=array();

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($city==$this->command['start_city']))
					$page=$this->command['start_page'];
				else
					$page=1;

				$log_distance = ", city: ".$city.", distance: ".$this->command['distance'];
				
				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$search_arr = array(
											"distance" => $distance,
											"cityID" => $cityID,
											"city" => $city,
											"age_von" => $age,
											"age_bis" => $age,
											"extsearch1" => (isset($this->command['options']['online']))?1:0,
											"extsearch2" => 0,
											"extsearch3" => 0,
											"sex" => $this->command['gender'],
											"start" => ($page-1)*$this->searchResultsPerPage
										);

						$details = "";
						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr[$key]=$value;
								$details .= ", $key = $value";
							}
						}

						$url = $this->searchURL."&".http_build_query($search_arr);
						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.$log_distance.$details.", page: ".$page);
						$this->savelog("Search url is: ".$url);
						$content = $this->getHTTPContent($url, $this->searchRefererURL, $cookiePath);
						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",$content);

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
											if($this->command['logout_after_sent'] == "Y"){
												if($this->count_msg >= $this->command['messages_logout']){
													break 4;
												}
											}

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
											if($this->command['logout_after_sent'] == "Y"){
												if($this->count_msg >= $this->command['messages_logout']){
													break 4;
												}
											}

											if(!$this->work_sendMessage($username, $item, $cookiePath)){
												return false;
											}
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
		$content = substr($content, strpos($content, '<div class="ccontentbox bg_none">'));
		$content = substr($content, 0, strpos($content, '<div class="clear">'));

		$parser = $this->convertToXML($username, $page, $content);

		if(isset($parser->document->div))
		{
			// Check if it's correct result
			if($parser->document->div[0]->tagAttrs['class']=="ccontentbox bg_none")
			{
				foreach($parser->document->div[0]->div as $item)
				{
					if(strpos($item->tagAttrs['class'], "userliste")!==false)
					{
						$profile = array(
											"username" => $item->div[1]->div[0]->tagData,
										);
						array_push($list,$profile);
					}
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
		$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchRefererURL, $cookiePath);
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
				if($this->command['msg_type']=="pm")
				{
					////////////////////////////////////////
					/////// Go to send message page ////////
					////////////////////////////////////////
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL, $this->profileURL.$item['username']."/?ref=search", $cookiePath);
					$this->sleep(5);
					$message_arr = array(
											"to" => $item['username'],
											"subject" =>$subject,
											"msg" => $message,
											"zeichenuebrig" => "Noch 10000 Zeichen übrig"
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendMessageURL;
						$url_referer = $this->profileURL.$item['username'];
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$url_log.$content);
						$result = json_decode($content);

						if($result->success == "1")
						{
							$this->count_msg++;
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
							$this->newMessage=false;
							$this->savelog("Sending message failed.");
							$json = json_decode($content);
							if(isset($json->fail))
								$this->savelog("Fail: ".$json->fail);
							$this->lastSentTime = time();
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = true;
					}
				}
				elseif($this->command['msg_type']=="gb")
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendGuestbookPageURL.$item['username'], $this->profileURL.$item['username'], $cookiePath);
					$this->sleep(5);
					$message_arr = array(
											"subject" => $subject,
											"msg" => $message,
											"zeichenuebrig" => "Noch 9995 Zeichen übrig"
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendGuestbookURL.$item['username'], $this->sendGuestbookPageURL.$item['username'], $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
						$result = json_decode($content);

						if($result->success == "1")
						{
							$this->count_msg++;
							$this->newMessage=true;
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$this->newMessage=false;
							$this->savelog("Signing guestbook failed.");
							$json = json_decode($content);
							if(isset($json->fail))
								$this->savelog("Fail: ".$json->fail);
							$this->lastSentTime = time();
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Signing guestbook failed. This profile has been sent message to.");
						$return = true;
					}
				}
				$this->cancelReservedUser($item['username']);
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

		$content = substr($content, strpos($content, '<table class="table table-striped">'));
		$content = substr($content, 0, strpos($content, '</form>'));
		$parser = $this->convertToXML($username, "outbox", $content);

		// Check if it's correct result
		if($parser->document->table[0]->tagAttrs['class']=="table table-striped")
		{
			foreach($parser->document->table[0]->tr as $item)
			{
				if(isset($item->td[1]->tagAttrs['class']) && (strpos($item->td[1]->tagAttrs['class'], "postusername")!==false))
				{
					$message = $item->td[0]->input[0]->tagAttrs['name'];
					array_push($list,$message);
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
								$message => "selected",
								"domode" => "del"
							);

		$content = $this->getHTTPContent($this->deleteOutboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->indexURL, $cookiePath);

		$content = substr($content, strpos($content, '<table class="table table-striped">'));
		$content = substr($content, 0, strpos($content, '</form>'));
		$parser = $this->convertToXML($username, "inbox", $content);

		// Check if it's correct result
		if($parser->document->table[0]->tagAttrs['class']=="table table-striped")
		{
			foreach($parser->document->table[0]->tr as $item)
			{
				if(isset($item->td[1]->tagAttrs['class']) && (strpos($item->td[1]->tagAttrs['class'], "postusername")!==false))
				{
					$message = array(
										"message" => $item->td[0]->input[0]->tagAttrs['name'],
										"username" => $item->td[1]->a[0]->tagData
									);
					array_push($list,$message);
				}
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message);
		$ch = curl_init();
		$delete_arr = array(
								$message => "selected",
								"domode" => "del"
							);

		$content = $this->getHTTPContent($this->deleteInboxURL, $this->inboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message." completed.");
		$this->savelog("Deleting contact id: ".$message);
		curl_close($ch);
	}
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.myflirt.com/n/profil_index&profil='.$profile, $this->indexURL, $cookiePath);
			if(!strpos($content,'Profil existiert')) {
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