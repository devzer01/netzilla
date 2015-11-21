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

class loomin extends bot
{
	public $sessionID = "";
	public function loomin($post)
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
																	"username" => "nazmiye2013",
																	"password" => "Verlobungsfeier"
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
									"send_test" => 1,
									"age_from" => 23,
									"age_to" => 25,
									"gender" => "Herr",
									"umkreis" => 15,
									"msg_type" => "gb",
									"start_plz" => "00000",
									"online" => "1",
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 52;
		}

		$this->usernameField = "nickname";
		$this->loginURL = "http://www.loomin.de/login/";
		$this->loginRefererURL = "http://www.loomin.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.loomin.de/logout/";
		$this->indexURL = "http://www.loomin.de/";
		$this->indexURLLoggedInKeyword = "http://www.loomin.de/logout/";
		$this->searchURL = "http://www.loomin.de/ajax/suchen.php";
		$this->searchPageURL = "http://www.loomin.de/suchen/#quick";
		$this->searchRefererURL = "http://www.loomin.de/ajax/suchen.php";
		$this->searchResultsPerPage = 9;
		$this->profileURL = "http://www.loomin.de/profil/";
		$this->sendMessagePageURL = "http://www.loomin.de/ajax/_send-message.php?idreceiver=";
		$this->sendMessageURL = "http://www.loomin.de/ajax/_send-message.php?idreceiver=";
		$this->signGuestbookURL = "http://www.loomin.de/ajax/userprofile-guestbook.php";
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
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
									"nickname" => $user['username'],
									"password" => $user['password'],
									"login" => "1"
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
		$this->sleep(5);

		if(isset($this->command['online']) && ($this->command['online']=="1"))
		{
			$this->savelog("Searching for online users.");
			$content = $this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath);

			$list = $this->getOnlineMembersFromSearchResult($username, $content);
			if(is_array($list))
			{
				if(count((array)$list[$this->command['gender']]))
				{
					$list_arr = array();
					foreach($list[$this->command['gender']] as $item)
					{
						if(($item->user_age >= $this->command['age_from']) && ($item->user_age <= $this->command['age_to']))
							array_push($list_arr, $item);
					}
					$this->savelog("Found ".count($list_arr)." member(s)");
					$this->sleep(5);
					foreach($list_arr as $item)
					{
						$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
						//If in runnig time period
						if($sleep_time==0)
						{
							$item_arr = array();
							$item_arr['username'] = $item->user_nickname;
							$item_arr['userid'] = $item->id;
							$this->work_sendMessage($username, $item_arr, $cookiePath);
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
		else
		{
			for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
			{
				$zipcodes = array(
									"short" => array(
														"01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089"
													),
									"long" => array(
														"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
													)
								);

				if($this->command['umkreis']<=500)
				{
					$plz = $zipcodes['long'];
					if($key = array_search($this->command['start_plz'],$plz))
					{
						$plz = array_slice($plz, $key);
					}
				}
				else
					$plz = $zipcodes['short'];

				foreach($plz as $zipcode)
				{
					$page=1;
					$list=array();
					$first_username = '';
					do
					{
						/******************/
						/***** search *****/
						/******************/

						$this->savelog("Search for gender: ".$this->command['gender'].", age: ".$age.", zipcode: ".$zipcode.", page: ".$page);
						if($page == 1)
						{
							$search_arr = array(	"title" => $this->command['gender'],
													"age_from" => $age,
													"age_to" => $age,
													"postcode" => $zipcode,
													"umkreis" =>  $this->command['umkreis'],
													"quicksearch" => 1,
													"country" => "DE"
													);
							if(isset($this->command['options']))
							{
								foreach($this->command['options'] as $key=>$value)
								{
									$search_arr[$key]=$value;
								}
							}

							$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr);
						}
						else
						{
							$content = $this->getHTTPContent($this->searchURL."?iPage=".($page-1), $this->searchRefererURL, $cookiePath, $search_arr);
						}

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
										//$item['username'] = "GaBriELa91";
										//$item['userid'] = "3085028";
										$this->work_sendMessage($username, $item, $cookiePath);
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

	private function getOnlineMembersFromSearchResult($username, $content)
	{
		$list = array();

		$males = substr($content,strpos($content,'var userListMale = ')+19);
		$males = substr($males,0,strpos($males,'}};')+2);
		$males = json_decode($males);

		$females = substr($content,strpos($content,'var userListFemale = ')+21);
		$females = substr($females,0,strpos($females,'}};')+2);
		$females = json_decode($females);

		return array("Herr" => $males, "Frau" => $females);
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->div))
		{
			foreach($parser->document->div[2]->table[0]->tbody[0]->tr as $row)
			{
				$username = $row->td[1]->p[0]->a[0]->tagData;
				list($username, $age) = explode(" ", $username);
				$age = str_replace(array("(",")"),"",$age);
				$userid = str_replace(array("http://www.loomin.de/profil/","/s/"),"",$row->td[1]->p[0]->a[0]->tagAttrs['href']);

				$pic = "";
				if(isset($row->td[0]->div[0]->a[0]->img[0]))
					$pic = str_replace("96x74","180x260",$row->td[0]->div[0]->a[0]->img[0]->tagAttrs['src']);

				$profile = array(	"username" => $username,
									"userid" => $userid,
									"age" => $age,
									"pic" => $pic
									);

				array_push($list, $profile);
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
		if(!$this->isAlreadySent($item) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username'], $item['userid']))
			{
				$referer = $this->searchPageURL;
				// Go to profile page
				$this->savelog("Go to profile page: ".$item['username']);
				$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid']."/s/", $referer, $cookiePath);

				$this->sleep(5);

				//RANDOM SUBJECT AND MESSAGE
				$this->savelog("Random new subject and message");
				$this->currentSubject = rand(0,count($this->command['messages'])-1);
				$this->currentMessage = rand(0,count($this->command['messages'])-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
				$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

				// If guestbook enabled
				if($this->command['msg_type']=="pm")
				{
					/***********************************/
					/***** Go to send message page *****/
					/***********************************/
					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL.$item['userid'], $this->profileURL.$item['userid']."/s/", $cookiePath);

					$this->sleep(5);

					/************************/
					/***** Send message *****/
					/************************/
					$message_arr = array(	"subject" => $subject,
											"messagetext" => $message,
											"sendmessage" => 1
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->sendMessageURL.$item['userid'], $this->profileURL.$item['userid']."/s/", $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, "Die Nachricht wurde abgesendet!")!==false)
						{
							$this->savelog("Sending message completed.");
							$this->lastSentTime = time();
							DBConnect::execute_q("INSERT INTO loomin_sent_messages (to_username, to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."', '".addslashes($item['userid'])."','".$username."', '".addslashes($subject)."', '".addslashes($message)."', NOW())");
						}
						else
						{
							$this->savelog("Sending message failed.");
						}
					}
					else
					{
						$this->cancelReservedUser($item['userid']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}
				elseif($this->command['msg_type']=="gb")
				{
					/**************************/
					/***** Sign guestbook *****/
					/**************************/
					$message_arr = array(	"gbentry" => $message,
											"idowner" => $item['userid']
											);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Signing guestbook to ".$item['username']);
					if(!$this->isAlreadySent($item) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent($this->signGuestbookURL, $this->profileURL.$item['userid']."/s/", $cookiePath, $message_arr);
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);

						if(strpos($content, $username)!==false)
						{
							$this->savelog("Sign guestbook completed.");
							$this->lastSentTime = time();
							DBConnect::execute_q("INSERT INTO loomin_sent_messages (to_username, to_userid, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."', '".addslashes($item['userid'])."','".$username."', '".addslashes($subject)."', '".addslashes($message)."', NOW())");
						}
						else
						{
							$this->savelog("Sign guestbook failed.");
						}
					}
					else
					{
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

	private function isAlreadySent($item)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM loomin_sent_messages WHERE to_username='".$item['username']."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM loomin_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO loomin_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM loomin_reservation WHERE userid='".$userid."' AND server=".$this->botID);
	}
}
?>