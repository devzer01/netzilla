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

class badoo extends bot
{
	public function badoo($post)
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
																	"username" => "klein.connie@yahoo.de",
																	"password" => "qinaxely",
																	"userid" => "01236435536"
																	//"username" => "annetthoch@rocketmail.com",
																	//"password" => "xelejohe"
																	//"userid" => "01236435860"
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
									"gender" => "M",
									"msg_type" => "pm",
									"send_test" => 0,
									"distance" => "",
									"start_city" => "Berlin",
									"start_page" => 1,
									"version" => 1,
									//"full_msg" => 1,
									"filter" => "online",
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
		$this->r = "";
		$this->databaseName = "badoo";
		$this->usernameField = "email";
		$this->indexURL = "http://badoo.com/search/";
		$this->indexURLLoggedInKeyword = "http://badoo.com/signout/?rt=";
		$this->loginURL = "https://badoo.com/signin/";
		$this->loginRefererURL = "http://badoo.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://badoo.com/signout/?rt=";
		$this->searchPageURL = "http://badoo.com/search/";
		$this->searchURL = "http://badoo.com/search/";
		$this->searchRefererURL = "http://badoo.com/search/";
		$this->searchResultsPerPage = 20;
		$this->sendMessagePageURL = "http://badoo.com/connections/message/";
		$this->sendMessageURL = "http://badoo.com/connections/ws-post.phtml?ws=1&rt=";
		$this->profileURL = "http://badoo.com/";
		$this->inboxURL = "http://badoo.com/connections/ws-list.phtml";
		$this->deleteContactURL = "http://badoo.com/ws/contacts-move.phtml?action=remove_from_contact_list&delete=1&skip=1&ws=1&rt=";
		$this->deleteInboxURL = "http://single.de/Rest/postfach-threadlist/";
		$this->deleteInboxRefererURL = "http://messages.meetme.com/#Inbox";
		$this->outboxURL = "http://messages.meetme.com/ajax/getPage/sent/1";
		$this->deleteOutboxURL = "http://messages.meetme.com/ajax/deleteThread";
		$this->deleteOutboxRefererURL = "http://messages.meetme.com/#Sent";
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
		$this->cities = array(
								"Berlin", "Hamburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Halle", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Müllheim an der Ruhr", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Offenbach am Main", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
								"rt" => $this->token,
								"email" => $user['username'],
								"password" => $user['password'],
								"remember" => "1",
								"post" => ""
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
		$this->r = $this->getR($content);

		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$first_username = '';
			$cities = $this->cities;
			if(($this->workCount == 1) && ($age==$this->command['age_from']))
			{
				if($key = array_search($this->command['start_city'],$cities))
				{
					$cities = array_slice($cities, $key);
				}
			}

			foreach($cities as $city)
			{
				$list=array();
				$page=1;

				$location = $this->getLocation($city, $cookiePath);
				if(!$location)
				{
					$this->savelog("Unable to get location for ".$city);
					continue;
				}

				$first_round = true;
				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$details = "";
						if($first_round)
						{
							$search_arr = array(
													"gender[]" => $this->command['gender'],
													"age_f" => $age,
													"age_t" => $age,
													"rt" => $this->token,
													"location_id" => $location['id'],
													"location" => $location['name'],
													"r" => $this->r,
													"ws" => 1,
													"to_custom" => "neuen Freunden"
											);
						}

						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);

						$search_arr = array(
												"r" => $this->r,
												"page" => $page,
												"ws" => 1,
												"hold" => 0,
												"rt" => $this->token
										);

						$distance = "";
						if($this->command['distance'])
						{
							$distance = ", distance: ".$this->command['distance'];
							if($this->command['distance'] != "c")
								$search_arr['area_id'] = "a_".$location['id']."_".$this->command['distance']."_Km";
							else
								$search_arr['area_id'] = $location['id']."_".$this->command['distance'];
						}

						if(isset($this->command['filter']) && ($this->command['filter']!=""))
							$search_arr['filter'] = $this->command['filter'];

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.", city: ".$city.$distance.", page: ".$page);

						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",$content);

						/***********************************************/
						/***** Extract profiles from search result *****/
						/***********************************************/
						$list = $this->getMembersFromSearchResult($username, $page, $content);

						if(is_array($list))
						{
							$this->savelog("Found ".count($list)." member(s)");

							if($list[0]['username'] == $first_username)
							{
								$list = array();
								break;
							}
							
							$first_username = $list[0]['username'];

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
											//$item['username'] = "Annett";
											//$item['userid'] = "01236435860";
											//$item['url'] = "http://badoo.com/01236435860/";
											$this->work_sendMessage($username, $item, $cookiePath);
											//exit;
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

						if($this->command['version']==2)
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
					$first_round = false;
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

	private function getMembersFromSearchResult($username, $page, $content){
		$list = array();
		$data = json_decode($content);

		if(isset($data->html) && ($data->html!=""))
		{
			$parser = $this->convertToXML($username, $page, $data->html);

			// Check if it's correct result
			if($parser->document->div[0]->tagAttrs['id']=="contacts_list")
			{
				foreach($parser->document->div[0]->div[0]->div as $column)
				{
					if($column->tagAttrs['class']=="search__col")
					{
						foreach($column->div as $item)
						{
							if(isset($item->div[0]->div[1]->a[0]->tagAttrs['id']))
							{
								$profile = array(
													"userid" => str_replace("uid", "", $item->div[0]->div[1]->a[0]->tagAttrs['id']),
													"username" => $item->div[0]->div[0]->span[0]->tagData,
													"url" => $item->div[0]->div[1]->a[0]->tagAttrs['href']
											);
								array_push($list, $profile);
							}
							else
							{
								$url = $item->div[0]->div[1]->a[0]->tagAttrs['href'];
								$userid = substr($url, strpos($url, "message/")+8);
								$userid = substr($userid, 0, strpos($userid, "/"));
								$profile = array(
													"username" => $item->div[0]->div[0]->span[0]->tagData,
													"userid" => $userid,
													"url" => $this->profileURL.$userid."/"
											);
								array_push($list, $profile);
							}
						}
					}
					elseif(strpos($column->tagAttrs['class'],"search__col")!==false)
					{
						$item = $column;
						if(isset($item->div[0]->div[1]->a[0]->tagAttrs['id']))
						{
							$profile = array(
												"userid" => str_replace("uid", "", $item->div[0]->div[1]->a[0]->tagAttrs['id']),
												"username" => $item->div[0]->div[0]->span[0]->tagData,
												"url" => $item->div[0]->div[1]->a[0]->tagAttrs['href']
										);
							array_push($list, $profile);
						}
						else
						{
							$url = $item->div[0]->div[1]->a[0]->tagAttrs['href'];
							$userid = substr($url, strpos($url, "message/")+8);
							$userid = substr($userid, 0, strpos($userid, "/"));
							$profile = array(
												"username" => $item->div[0]->div[0]->span[0]->tagData,
												"userid" => $userid,
												"url" => $this->profileURL.$userid."/"
										);
							array_push($list, $profile);
						}
					}
				}
			}
		}
		return $list;
	}

	private function getLocation($city, $cookiePath)
	{
		$result = array();
		$search_arr = array(
							"show" => "no_regions",
							"ep" => 2,
							"no_region_name" => 1,
							"format" => "full",
							"s_lang_id" => 106,
							"q" => $city.", Deutschland",
							"ws" => 1,
							"rt" => $this->token
						);

		$content = $this->getHTTPContent("http://us1.badoo.com/ws/suggest-ws.phtml?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
		$content = json_decode($content);

		if(is_object($content) && isset($content->locations))
		{
			$parser = $this->convertToXML($city, "", $content->locations);

			// Check if it's correct result
			if($parser->document->ul[0]->li[0]->tagAttrs['class']=="selected")
			{
				foreach($parser->document->ul[0]->li as $item)
				{
					$location = array(
										"id" => substr($item->tagAttrs['id'], strpos($item->tagAttrs['id'], "_")+1),
										"name" => (isset($item->span)?$item->span[0]->tagData:"").$item->tagData
									);
					array_push($result,$location);
				}
			}
		}

		return $result[0];
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

	private function getR($content)
	{
		$token = substr($content, 0, strpos($content, '&amp;page=1'));
		$token = substr($token, strrpos($token, "?r=")+3);
		return $token;
	}

	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item['username']." [".$item['userid']."]");
		$content = $this->getHTTPContent($item['url'], $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
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

			// Go to profile page
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']." [".$item['userid']."]");
			if($this->reserveUser($item['username'], $item['userid']))
			{
				if($this->command['msg_type']=="pm")
				{
					// Go to send message page
					$this->savelog("Go to send message page: ".$item['username']." [".$item['userid']."]");
					$url = $this->sendMessagePageURL.$item['userid']."/?wa=1&ws=1&rt=".$this->token;
					$content = $this->getHTTPContent($url, $item['url'], $cookiePath);
					$this->sleep(5);

					$cookie_arr = $this->parse_curl_cookie($cookiePath);
					$message_arr = array(
											"act" => "add",
											"rt" => $this->token,
											"message" => $message,
											"contact_user_id" => $item['userid'],
											"s2" => $cookie_arr['s2']['value'],
									);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']." [".$item['userid']."]");
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
							$url = $this->sendMessageURL.$this->token;
							$url_referer = $item['url'];
							$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
							$url_log = "URL => ".$url."\nREFERER => ".$url_referer."\n";
							file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$url_log.$content);

							$content = json_decode($content);

							if($content->errno==0)
							{
								$this->newMessage=true;
								$this->savelog("Sending message completed.");
								DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$this->deleteContact($item, $cookiePath);
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
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']." [".$item['userid']."]");
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				$this->cancelReservedUser($item['userid']);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']." [".$item['userid']."]");
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

	private function isAlreadySent($userid)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->databaseName."_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->databaseName."_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".addslashes($userid)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ".$this->databaseName."_reservation WHERE userid='".$userid."' AND server=".$this->botID);
	}

	private function deleteContact($item, $cookiePath)
	{
		$this->savelog("Deleting ".$item['username']." from contact list.");
		$delete_arr = array(
								"users_ids[".$item['userid']."]" => 1,
								"is_del_hide" => 0
							);
		$content = $this->getHTTPContent($this->deleteContactURL.$this->token, $this->searchRefererURL, $cookiePath, $delete_arr);

		$content = json_decode($content);
		if($content->errno == "0")
		{
			$this->savelog("Deleting ".$item['username']." from contact list completed.");
		}
		else
		{
			$this->savelog("Deleting ".$item['username']." from contact list failed.");
		}
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
		$parser = $this->convertToXML($username, "outbox", $content->content);

		if($parser->document->table[0]->tagAttrs['id']=="messages")
		{
			foreach($parser->document->table[0]->tr as $item)
			{
				$message = array(
									"message" => $item->td[7]->span[0]->tagData,
									"username" => $item->td[3]->a[0]->tagData,
									"userid" => str_replace("http://www.meetme.com/member/","",$item->td[3]->a[0]->tagAttrs['href'])
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
			"threads[]" => $message['message'],
			"timestamp" => time()
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL, $this->deleteOutboxRefererURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->searchRefererURL, $cookiePath);

		$content = json_decode($content);

		if(isset($content->data->list))
		{
			foreach($content->data->list as $item)
			{
				$message = array(
									"username" => $item->name,
									"userid" => $item->user_id
								);
				array_push($list,$message);
			}
		}
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
			"threads[]" => $message['message'],
			"timestamp" => time()
			);

		$content = $this->getHTTPContent($this->deleteInboxURL, $this->deleteInboxRefererURL, $cookiePath, json_encode($delete_arr));

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}
}
?>