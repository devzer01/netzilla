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

class ktosexy extends bot
{
	public function ktosexy($post)
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
																	"username" => "umuller10",
																	"password" => "sawibaboru"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "Hallo :)"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"end_h" => 00,
									"end_m" => 00,
									"messages_per_hour" => 5,
									"age_from" => 32,
									"age_to" => 65,
									"gender" => "m",
									"msg_type" => "pm",
									"send_test" => 0,
									"umkreis" => 10,
									"start_city" => "Berlin",
									"options" => array(
														//"foto" => "true",
														//"online" => "true",
														//"new" => "true"
														),
									//"action" => "search"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 0;
		}

		$this->token = "";
		$this->usernameField = "username";
		$this->loginURL = "http://www.ktosexy.de/news.php";
		$this->loginRefererURL = "http://www.ktosexy.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.ktosexy.de/logout.php";
		$this->indexURL = "http://www.ktosexy.de/index.php";
		$this->indexURLLoggedInKeyword = "/mails.php";
		$this->searchURL = "http://www.ktosexy.de/do.php";
		$this->searchRefererURL = "http://www.ktosexy.de/user.php";
		$this->searchResultsPerPage = 150;
		$this->profileURL = "http://www.ktosexy.de/do.php";
		$this->sendMessageURL = "http://www.ktosexy.de/do.php";
		$this->sendGuestbookURL = "http://www.ktosexy.de/do.php";
		$this->outboxURL = "http://www.ktosexy.de/do_mail.php";
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
		$this->cities = array(
								"Berlin", "Hamburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Halle (Saale)", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Müllheim an der Ruhr", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Offenbach am Main", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		parent::bot();
	}

	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(	"username" => $user['username'],
								"passwort" => $user['password']
								);

			array_push($this->loginArr, $login_arr);
		}
	}

	public function work()
	{
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
		$this->token = $this->getToken($content);
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
			$cities = $this->cities;
			if($key = array_search($this->command['start_city'], $cities))
			{
				$cities = array_slice($cities, $key);
			}

			foreach($cities as $city)
			{
				$list=array();
				$log_distance = ", city: ".$city.", distance: ".$this->command['umkreis'];

				$latlon = $this->getLatLon($city, $cookiePath);
				if(!$latlon)
				{
					$this->savelog("Unable to get location for ".$city);
					continue;
				}

				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
									"tkn" => $this->token,
									"act" => "usersuche",
									"username" => "",
									"lon" => $latlon['lon'],
									"lat" => $latlon['lat'],
									"umkreis" => $this->command['umkreis'],
									"altervon" => $age,
									"alterbis" => $age+1,
									"w" => ($this->command['gender']=="w")?"true":"false",
									"m" => ($this->command['gender']=="m")?"true":"false",
								);

				$details = "";
				if(isset($this->command['options']))
				{
					foreach($this->command['options'] as $key=>$value)
					{
						$search_arr[$key]=$value;
						$details .= ", $key = $value";
						if(($key=="newuser") && ($value=="true"))
						{
							$search_arr['altervon'] = "";
							$search_arr['alterbis'] = "";
							$age = "-";
						}
					}
				}

				$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.$log_distance.$details);
				$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
				file_put_contents("search/".$username."-search-".$age."-".$city.".html",$content);

				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $city, $content);

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
								//$item['username'] = "Wastexed49";
								//$item['userid'] = "1219910";

								$this->work_sendMessage($username, $item, $cookiePath);
								//exit;
							}
							else
							{
								$this->savelog("Not in running time period.");
								$this->sleep($sleep_time);
								return true;
							}
						}
						//$this->deleteAllOutboxMessages($username, $cookiePath);
					}
				}
			}

			if($age=="-")
				$age=$this->command['age_to'];
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function getToken($content)
	{
		$token = substr($content, strpos($content, "$.ajaxSetup({data: {tkn: '")+26);
		$token = substr($token, 0, strpos($token, "'"));
		$token = stripslashes($token);
		return $token;
	}

	private function getLatLon($city, $cookiePath)
	{
		$result = array();
		$search_arr = array(
							"tkn" => $this->token,
							"act" => "ort_autocomplete",
							"ort" => $city
						);

		$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
		$content = json_decode($content);

		if(is_array($content) && isset($content[0][4]))
		{
			$result['lat'] = $content[0][4];
			$result['lon'] = $content[0][3];
		}

		return $result;
	}

	private function getMembersFromSearchResult($username, $city, $content){
		$list = array();
		$content = json_decode($content);

		if(is_array($content) && count($content))
		{
			foreach($content as $item)
			{
				$profile = array(
									"username" => $item[1],
									"userid" => $item[0]
								);
				array_push($list,$profile);
			}
		}
		return $list;
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
		$return = false;
		// If not already sent
		if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username'], $item['userid']))
			{
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$profile_arr = array(
							"tkn" => $this->token,
							"act" => "profil",
							"userid" => $item['userid']
						);

				$content = $this->getHTTPContent($this->profileURL, $this->searchRefererURL, $cookiePath, $profile_arr);

				$this->sleep(5);

				////////////////////////////////////////
				///////////// Send message /////////////
				////////////////////////////////////////
				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->command['messages'])-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
				$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

				//if($this->command['msg_type']=="pm")
				{
					$message_arr = array(
											"tkn" => $this->token,
											"act" => 'mail_send',
											"anid" => $item['userid'],
											"usertext" => $message,
											);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$headers = array();
						$headers[] = "Accept:application/json, text/javascript, */*; q=0.01";
						$headers[] = "Accept-Charset:ISO-8859-1,utf-8;q=0.7,*;q=0.3";
						$headers[] = "Accept-Language:en-US,en;q=0.8";
						$headers[] = 'Connection: Keep-Alive';
						$headers[] = "Content-Type:application/x-www-form-urlencoded; charset=UTF-8";
						$headers[] = "X-Requested-With:XMLHttpRequest";

						$content = $this->getHTTPContent($this->sendMessageURL, $this->searchRefererURL, $cookiePath, $message_arr, $headers);
						file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						$content = json_decode($content);

						if($content[0]=="red")
						{
							$this->savelog("Sending message failed. ".utf8_decode(strip_tags($content[1])));
							if(strpos(strip_tags($content[1]), "Dieser User hat dich auf die Ignore-Liste gesetzt")!==false)
							{
								$this->lastSentTime = time();
								$return = false;
							}
							else
							{
								$this->savelog("This profile '".$username."' is blocked.");
								$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".$this->siteID." LIMIT 1";	
								DBConnect::execute_q($sql);
								$this->savelog("FINISHED");
								exit;
							}
						}
						elseif($content[2]=="1")
						{
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ktosexy_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
						}
						else
						{
							$this->savelog("Sending message failed.");
							$this->lastSentTime = time();
							$return = false;
						}
					}
					else
					{
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}
				/*else
				{
					//////////////////////////////////////////
					/////// Go to sign guestbook page ////////
					//////////////////////////////////////////
					$this->savelog("Go to sign guestbook page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendGuestbookURL.$item['userid'], $this->profileURL.$item['userid'], $cookiePath);
					$this->sleep(5);
					$message_arr = array(
											"usertext" => $message,
											"act" => 'gbook_send',
											"anid" => $item['userid']
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item['userid']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendGuestbookURL, $this->profileURL.$item['userid'], $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username']."-".$item['userid'].".html",$content);

						if(strpos($content, 'Dein Beitrag wurde eingetragen')!==false)
						{
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ktosexy_sent_messages (to_username,to_userid,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$item['userid']."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$return = true;
						}
						elseif(strpos($content, "Du hast gegen die Mail,- oder Forumregeln")!==false)
						{
							$this->savelog("The profile '".$username."' is blocked.");
							$sql = "UPDATE user_profiles set status = 'false' WHERE username='".$username."' AND site_id=".$this->siteID." LIMIT 1";	
							DBConnect::execute_q($sql);
							$this->savelog("FINISHED");
							exit;
						}
						else
						{
							$this->savelog("Signing guestbook failed.");
							$this->savelog('KTOSexy returns : "'.trim(strip_tags($content)).'"');
							$this->lastSentTime = time();
							$return = false;
						}
					}
					else
					{
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Signing guestbook failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}*/
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ktosexy_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ktosexy_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO ktosexy_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ktosexy_reservation WHERE userid=".$userid." AND server=".$this->botID);
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
		$this->savelog("Receiving outbox messages.");
		$offset = 0;

		$outboxArr = array(
							"act" => "mailkontakte",
							"jsonp_callback" => "jQuery18003338546466547996_1351745261225",
							"_" => time().rand(0,9).rand(0,9).rand(0,9),
							"ab" => $offset
							);
		$outboxURL = $this->outboxURL."?".http_build_query($outboxArr);
		$content = $this->getHTTPContent($outboxURL, $this->outboxURL, $cookiePath);

		$content = substr($content, strpos($content, '['));
		$content = substr($content, 0, strrpos($content, ']')+1);
		$messages = json_decode($content);

		if(is_array($messages) && count($messages))
			return $messages;
		else
		{
			$this->savelog("No outbox message.");
			return false;
		}
	}

	private function deleteOutboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message[0]);
		$ch = curl_init();
		$delete_arr = array(
								"act" => "delmail_all",
								"id" => $message[0]
							);

		$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting message id: ".$message[0]." completed.");
		$this->savelog("Deleting contact id: ".$message[0]);

		$delete_arr = array(
								"act" => "delkontakt",
								"id" => $message[0]
							);
		
		$content = $this->getHTTPContent($this->outboxURL, $this->outboxURL, $cookiePath, $delete_arr);

		$this->savelog("Deleting contact id: ".$message[0]." completed.");
		curl_close($ch);
	}
}
?>