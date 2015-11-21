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

class single extends bot
{
	public $sendmsg_total = 0;
	public function single($post)
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
																	"username" => "richteoliv",
																	"password" => "physisch"
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
									"age_to" => 20,
									"gender" => "5",
									"msg_type" => "gb",
									"send_test" => 0,
									"distance" => 20,
									"start_plz" => "36100",
									"start_page" => 1,
									"version" => 1,
									//"full_msg" => 1,
									"options" => array(
														"image" => 1,
														"online" => 1
														),
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 57;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->token = "";
		$this->databaseName = "single";
		$this->usernameField = "username";
		$this->indexURL = "http://single.de/Cockpit";
		$this->indexURLLoggedInKeyword = "/index/logout";
		$this->loginURL = "https://auth.single.de/default/login.php";
		$this->loginRefererURL = "http://single.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://single.de/index/logout";
		$this->searchPageURL = "http://single.de/Suche/Singlesuche";
		$this->searchURL = "http://single.de/Suche/Singlesuche/";
		$this->searchRefererURL = "http://single.de/Suche/Singlesuche";
		$this->searchResultsPerPage = 100;
		$this->profileURL = "http://single.de";
		$this->sendMessagePageURL = "http://single.de";
		$this->sendMessageURL = "http://single.de/Rest/postfach-message";
		$this->sendGuestbookPageURL = "http://single.de/Profil/guestbook/profile/";
		$this->sendGuestbookURL = "http://single.de/Rest/postfach-message";
		$this->inboxURL = "http://single.de/Rest/postfach-threadlist?oldestThreadMessageId=0&oldestThreadProfileId=0&pagesize=100&displayFilter=all";
		$this->deleteInboxURL = "http://single.de/Rest/postfach-threadlist/";
		$this->outboxURL = "http://single.de/Rest/postfach-threadlist?oldestThreadMessageId=0&oldestThreadProfileId=0&pagesize=100&displayFilter=answered";
		$this->deleteOutboxURL = "http://single.de/Rest/postfach-threadlist/";
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
									"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
						);
		
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
		
		if($this->command['gender'] == 5){
			$target = "Male";
		}elseif($this->command['gender'] == 6){
			$target = "Female";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='".$target."'");
			$this->messagesPartTemp[$i] = array();
		}
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				"callback" => "http://single.de/",
				"world" => 3,
				"username" => $user['username'],
				"password" => $user['password'],
				"#" => "Passwort"
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
				$plz = $this->zipcodes;
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
					if($page==1)
						$pageurl = "suche/startsearch/1";
					else
						$pageurl = "nextpage/page/".($page-1);
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$details = "";
						$search_arr = array(
							"params" => "gender=".$this->command['gender']."&age_min=".$age."&age_max=".$age."&height_min=0&height_max=0&weight_min=0&weight_max=0&sort=relevance&sort_direction=ASC&image_size=small&nickname=&expandedFilters=&city=&filter=1&searchId=null&cockpitSearch=0",
							"image_size" => "small",
							"searchId" => "null"
						);

						if($zipcode!="-")
						{
							$search_arr["params"].="&zip=".$zipcode;
							$search_arr["params"].="&distance=".$this->command['distance'];
							$details = ", distance: ".$this->command['distance'].", plz: ".$zipcode;
						}
						else
						{
							$search_arr["params"].="&zip=";
						}

						if(isset($this->command['options']))
						{
							foreach($this->command['options'] as $key=>$value)
							{
								$search_arr["params"].="&".$key."=1";
								$details .= ", $key: yes";
							}
						}

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.$details.", page: ".$page);
						$content = $this->getHTTPContent($this->searchURL.$pageurl, $this->searchRefererURL, $cookiePath, $search_arr);
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
											//$item['username'] = "maritv";
											//$item['userid'] = "12597114";
											//$item['url'] = "/Modautal-Frau-maritv.html?liste=schnellsuche&page=1";
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
		$parser = $this->convertToXML($username, $page, $content);

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $item)
			{
				if(isset($item->tagAttrs['class']))
				{
					if(strpos($item->tagAttrs['class'], "singleResult")!==false)
					{
						$profile = array(
											"username" => $item->div[1]->div[0]->a[0]->tagAttrs['title'],
											"userid" => str_replace("pid_","",$item->tagAttrs['id']),
											"url" => $item->div[1]->div[0]->a[0]->tagAttrs['href']
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
		$content = $this->getHTTPContent($this->profileURL.$item['url'], $this->searchRefererURL, $cookiePath);
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
					$message_arr = '{"msgText":"'.$message.'","profileId":'.$item['userid'].'}';
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendMessageURL;
						$url_referer = $this->profileURL.$item['url'];
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$url_log.$content);

						if(strpos($content, '{"success":1}')!==false)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
							$this->sendmsg_total++;
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
				}
				else
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendGuestbookPageURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);

					$this->sleep(5);
					$message_arr = array(
											"gb_text" => $message,
											"x" => 10,
											"y" => 10
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->profileURL.$item['url'], $this->profileURL.$item['url'], $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						if(strpos($this->getHTTPContent($this->sendGuestbookPageURL.$item['userid']."/entry/1", $this->profileURL.$item['url'], $cookiePath),"Ihr Gästebucheintrag wurde weitergeleitet")!==false)
						{
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
							$this->sendmsg_total++;
						}
						else
						{
							$this->savelog("Signing guestbook failed.");
							$return = false;
						}
					}
					else
					{
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}
				$this->cancelReservedUser($item['username']);
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
		$content = json_decode($content);

		if(is_array($content))
		{
			foreach($content as $item)
			{
				if(isset($item->profile_id) && isset($item->answered))
				{
					$message = $item;
					array_push($list,$message);
				}
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message->profile_id);
		$ch = curl_init();
		$delete_arr = $message;
		$delete_arr->id = $delete_arr->profile_id;
		$delete_arr->dateTimeString = date("d.m.Y H:i");
		$delete_arr->action = "trash";

		$content = $this->getHTTPContent($this->deleteOutboxURL.$message->profile_id, $this->outboxURL, $cookiePath, json_encode($delete_arr));

		$this->savelog("Deleting message id: ".$message->profile_id." completed.");
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
		$this->savelog("Deleting message id: ".$message->profile_id);
		$ch = curl_init();
		$delete_arr = $message;
		$delete_arr->id = $delete_arr->profile_id;
		$delete_arr->dateTimeString = date("d.m.Y H:i");
		$delete_arr->action = "trash";

		$content = $this->getHTTPContent($this->deleteInboxURL.$message->profile_id, $this->inboxURL, $cookiePath, json_encode($delete_arr));

		$this->savelog("Deleting message id: ".$message->profile_id." completed.");
		curl_close($ch);
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
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!! / Debug : " . $sql);
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

	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://single.de/Profile/User/'.$profile, $this->indexURL, $cookiePath, null, array(
				'X-Requested-With: XMLHttpRequest'
			));
			if(!strpos($content,'PROFILE_NOT_FOUND')) {
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