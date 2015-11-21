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

class werKenntWen extends bot
{
	public $sessionID = "";
	public function werKenntWen($post)
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
																	"username" => "neubert.enrico60@yahoo.de",
																	"password" => "godZilla60"
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
									"send_test" => 0,
									"age_from" => 18,
									"age_to" => 40,
									"gender" => "2",
									"online" => "0",
									"start_city" => "Aachen",
									//"action" => "check"
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 51;
		}

		$this->usernameField = "loginName";
		$this->loginURL = "https://secure.wer-kennt-wen.de/login/index";
		$this->loginRefererURL = "http://www.wer-kennt-wen.de/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.wer-kennt-wen.de/logout/";
		$this->indexURL = "http://www.wer-kennt-wen.de/start";
		$this->indexURLLoggedInKeyword = "/login/logout";
		$this->searchURL = "http://www.wer-kennt-wen.de/find/users/0/";
		$this->searchRefererURL = "http://www.wer-kennt-wen.de/find/users";
		$this->searchResultsPerPage = 10;
		$this->searchOnlinePageURL = "http://www.wer-kennt-wen.de/users/online/sort/userOnline/0/0/";
		$this->profileURL = "http://www.wer-kennt-wen.de/person/";
		$this->signGuestbookURL = "http://www.wer-kennt-wen.de/guestbook/new/";
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
		$this->cities = array(
								"Berlin", "Hamnburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Halle (Saale)", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Müllheim an der Ruhr", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Offenbach am Main", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		if($this->command['gender'] == 2){
			$target = "Male";
		}elseif($this->command['gender'] == 1){
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
								"loginName" => $user['username'],
								"pass" => $user['password'],
								"logIn" => 1
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

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);

		if(isset($this->command['online']) && ($this->command['online']=="1"))
		{
			$pages = 1; $allpages = 1;
			$list = array();
			$endloop = false;
			
			$this->savelog("Go to ONLINE page.");
			
			while($endloop == false){
				
				$content = $this->getHTTPContent("http://www.wer-kennt-wen.de/online/users/sort/known/show/list/page/".$pages, $this->loginRefererURL, $cookiePath);
				
				if($pages == 1){
					$paginator = substr($content,strpos($content,'<ul  class="list-paginator">'));
					$paginator = substr($paginator,0,strpos($paginator,'</ul>'));

					
					if($html = str_get_html($paginator)){
							
						$nodes = $html->find("a");

						foreach ($nodes as $node) {	
							$allpages = trim(str_replace("/online/users/sort/known/show/list/page/","",$node->href));
						}
					}
				}
				
				$this->savelog("Go to Page: ".$pages);
				$list = $this->getMembersFromOnlineResult($username, $content);
				
				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					if(count($list))
					{
						$this->sleep(5);
						foreach($list as $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								$this->work_sendMessage($username, $item, $this->searchOnlinePageURL.$offset, $cookiePath);
								//$this->savelog($item["username"]);
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

				if($pages == $allpages){
					$endloop = true;
				}

				$pages++;
			}
		}
		else
		{
			/*******************************/
			/****** Go to search page ******/
			/*******************************/
			$this->savelog("Go to SEARCH page.");
			$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
			$this->sleep(5);
			$txt = $this->rand_string();
			
			for($birthyear=(date("Y")-$this->command['age_from']); $birthyear>=(date("Y")-$this->command['age_to']); $birthyear--)
			{
				$cities = $this->cities;
				if($key = array_search($this->command['start_city'],$cities))
				{
					$cities = array_slice($cities, $key);
				}

				foreach($cities as $city)
				{
					$pages = 1; $allpages = 1;
					$list = array();
					$endloop = false;
					$first_username = '';
					
					while($endloop == false){
						
						$this->savelog("Search for gender: ".$this->command['gender'].", city: ".$city.", birthyear: ".$birthyear);

						$content = $this->getHTTPContent("http://www.wer-kennt-wen.de/search/users/index/search/".$txt."/city/".$city."/location//gender/".$this->command['gender']."/birthyear/".$birthyear."/page/".$pages, $this->loginRefererURL, $cookiePath);
												
						if($pages == 1){
							if(strpos($content, '<ul  class="list-paginator">')!==false)
							{
								$paginator = substr($content,strpos($content,'<ul  class="list-paginator">'));
								$paginator = substr($paginator,0,strpos($paginator,'</ul>'));

								
								if($html = str_get_html($paginator)){
										
									$nodes = $html->find("a");

									foreach ($nodes as $node) {	
										$allpages = trim(str_replace("/search/users/index/search/".$txt."/city/".$city."/location//gender/".$this->command['gender']."/birthyear/".$birthyear."/page/","",$node->href));
									}
								}
							}
						}
						
						$list = $this->getMembersFromOnlineResult($username, $content);
						
						if(count($list) > 0)
						{
							//print_r($list);exit;
							$this->savelog("Go to Page: ".$pages);
							$this->savelog("Found ".count($list)." member(s)");
							$this->sleep(5);
							foreach($list as $item)
							{
								$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
								
								//If in runnig time period
								if($sleep_time==0)
								{
									$this->work_sendMessage($username, $item, $cookiePath);
									//$this->savelog($item["username"]);
								}
								else
								{
									$this->savelog("Not in running time period.");
									$this->sleep($sleep_time);
									return true;
								}
							}
						}						
						

						if($pages == $allpages){
							$endloop = true;
						}

						$pages++;
					}				
				}
			}
		}

		$this->savelog("Job completed.");
		return true;
	}

	private function rand_string() {
		$chars = "abcdefghijklmnopqrstuvwxyz";	
		$str = "";

		$size = strlen( $chars );
		for( $i = 0; $i < 2; $i++ ) {
			$str .= $chars[ rand( 0, $size - 1 ) ];
		}

		return $str;
	}

	private function getMembersFromSearchResult($username, $content)
	{
		$list = array();


		$content = substr($content,strpos($content,'<div class="uiWkwList  user" >'));
		$content = substr($content,0,strpos($content,'<p class="cl pagination center">'));

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content);

		// Check if it's correct result
		if(isset($parser->document->div[0]))
		{
			foreach($parser->document->div[0]->div as $row)
			{
				if(strpos($row->tagAttrs['class'],"uiWkwListItem")!==false)
				{
					array_push($list, array(
												"username"=>$row->div[1]->a[0]->tagData,
												"userid"=>str_replace("/person/","",$row->div[1]->a[0]->tagAttrs['href'])
											)
								);
				}
			}
		}

		return $list;
	}

	private function getMembersFromOnlineResult($username, $content)
	{
		$list = array();

		if($html = str_get_html($content)){
						
			$nodes = $html->find("div.listing-item");

			foreach ($nodes as $node) {
				
				$user = substr($node->outertext,strpos($node->outertext,'<h5 class="single-line">')+24);
				$user = substr($user,0,strpos($user,'</h5>'));

				$uid = substr($node->outertext,strpos($node->outertext,'<a href="/person/')+17);
				$uid = substr($uid,0,strpos($uid,'"'));

				array_push($list, array(
						"username" => trim($user),
						"userid"=> trim($uid)
						)
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
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);
		$content = $this->getHTTPContent("http://www.wer-kennt-wen.de/person/".$item["userid"], $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item["username"]) || $enableMoreThanOneMessage)
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
			$this->savelog("Go to profile page: ".$item['username']);
			$content = $this->work_visitProfile($username, $item, $cookiePath);

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item["username"]);
			
			if($this->reserveUser($item["username"]))
			{				
				$this->savelog("Go to sign guestbook page: ".$item['username']);			

				if(time() < ($this->lastSentTime + $this->messageSendingInterval))
					$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
				
				$this->savelog("Signing guestbook to ".$item['username']);
						
				if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
				{
					$content = $this->getHTTPContent("http://www.wer-kennt-wen.de/person/".$item['userid']."/tab/guestbook", "http://www.wer-kennt-wen.de/person/".$item['userid']."/tab/guestbook", $cookiePath);
					$content = substr($content,strpos($content,'<form action="/guestbook/addentry" method="post" enctype="multipart/form-data">'));
					$content = substr($content,0,strpos($content,'</form>'));

					$param = array();
					if($html = str_get_html($content)){
							
						$nodes = $html->find("input[type=hidden]");

						foreach ($nodes as $node) {	
							$param[$node->name] = $node->value;
						}

					}
					
					/*$param = array(
						hash	b317ffb613cf5225a1d9d2974d54e859
						hashKey	1440278842066
						body	hallo
						att_nothumb	on
						att_id	
						att_url	
						att_hash	
						profile	9cxf4x31
						type_id	0
						decorator_id	0
					);*/

					$param["body"] = $message;
					$param["profile"] = $item['userid'];

					$content = $this->getHTTPContent("http://www.wer-kennt-wen.de/guestbook/addentry", "http://www.wer-kennt-wen.de/person/".$item['userid']."/tab/guestbook", $cookiePath, $param);							
					file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
						
					if(strpos($content, $username) !== false)
					{
						$this->newMessage=true;
						$this->savelog("Signing guestbook completed.");
						DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								
						$this->lastSentTime = time();
						$return = true;
					}
					else
					{
						$this->newMessage=false;
						$this->savelog("Signing guestbook failed.");							
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
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item["username"]);
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM werKenntWen_sent_messages WHERE to_userid='".$userid."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username, $userid)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM werKenntWen_reservation WHERE userid='".$userid."'");

		if(!$server)
		{
			$sql = "INSERT INTO werKenntWen_reservation (username, userid, server, created_datetime) VALUES ('".addslashes($username)."','".$userid."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM werKenntWen_reservation WHERE userid='".$userid."' AND server=".$this->botID);
	}
}
?>