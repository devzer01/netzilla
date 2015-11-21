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

class Mollylove extends bot
{
	public function mollylove($post)
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
																	"username" => "kreidefelsen",
																	"password" => "kiza7921"
																	)
														),
									"messages" => array(
															array(
																	"subject" => "Hallo",
																	"message" => "freuemich punkt net"
																)
														),
									"start_h" => 00,
									"start_m" => 00,
									"time_h" => 00,
									"time_m" => 02,
									"sleep_h" => 00,
									"sleep_m" => 01,
									"end_h" => 00,
									"end_m" => 00,								
									"messages_per_hour" => 30,
									"logout_after_sent" => "Y",
									"messages_logout" => 1,
									"wait_for_login" => 1,
									"proxy_type" => 0,
									"login_by" => 1,
									"within" => 10,
									"ageStart" => 18,
									"ageEnd" => 45,									
									"msg_type" => "pm",
									"send_test" => 0,															
									"version" => 1,
									"target" => "Male", //Male,Female,Gay,Lesbian
                                    "gender" => 1,
									"search" => 2,
									"start_city" => "Berlin",
									"distance" => 100,
									"affection" => 0,
									"lookfor" => 0,
									"country" => "Deutschland",
									"plz_option" => "",
									"online" => 0,
									"start_page" => 1,
									"state" => "",
									//"full_msg" => 1,																
									"action" => "send"
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 135;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->databaseName = "mollylove";
		$this->userhash = "";

		//Login
			$this->usernameField = "nick";
			$this->indexURL = "http://www.mollylove.de/index.php/index.html";
			$this->indexURLLoggedInKeyword = 'logout';
			$this->loginURL = "http://www.mollylove.de/phpinc/cc.php";
			$this->loginRefererURL = "	http://www.mollylove.de/";
			$this->loginRetry = 3;
			$this->logoutURL = "http://www.mollylove.de/phpinc/cc.php?do=start&what=logout";
		
		//Search
			$this->searchIndex = "http://www.gothic-singles.de/suche.htm";
			$this->searchURL = "http://triff-chemnitz.de/mitglieder/suche.html";
			$this->searchRefererURL = "http://triff-chemnitz.de/mitglieder/suche.html";			
			
			$this->searchResultsPerPage = 10;		

		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (Windows NT 6.1; rv:20.0) Gecko/20100101 Firefox/20.0";
		$this->commandID = $commandID;
		$this->workCount = 1;
		$this->siteID = $siteID;
		$this->botID = $botID;
		$this->runCount = $runCount;
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->count_msg = 0;
		$this->user_sex = "";
		$this->user_name = "";
		$this->sid = "";
		$this->profile_tmp = array();

		$this->addLoginData($this->command['profiles']);
		$this->timeWorking = 60*$this->command['within'];
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		$this->message="";
		$this->newMessage=true;	
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		
		$this->plz = array("01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089");
		$this->cities = array("Aachen","Augsburg","Berlin","Bielefeld","Bochum","Bonn","Braunschweig","Bremen","Dortmund","Dresden","Duisburg","Düsseldorf","Essen","Frankfurt","Gelsenkirchen","Hamburg","Hannover","Karlsruhe","Köln","Leipzig","Mannheim","Mönchengladbach","München","Münster","Nürnberg","Stuttgart","Wiesbaden","Wuppertal");

		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		if($this->command['gender'] == 1){
			$target = "Male";
		}elseif($this->command['gender'] == 2){
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
			$username = $user["username"];
			$cookiePath = $this->getCookiePath($username);		
			$content = $this->getHTTPContent("http://www.mollylove.de", "http://www.mollylove.de", $cookiePath);

			$param = array();
			if($html = str_get_html($content)){
					
				$nodes = $html->find("input[type=hidden]");

				foreach ($nodes as $node) {	
					$param[$node->name] = $node->value;
					if($node->name == "SID"){
						$this->sid = $node->value;
					}
				}
			}

			$login_arr = array(
				"SID" => $this->sid,
				"Submit" => "Betreten",
				"do" => "start",
				"nick" => $user["username"],
				"pass" => $user["password"],
				"remember" => "yes",
				"what" => "login"
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

	private function getLocationID($location, $cookiePath)
	{
		/*
		$content = $this->getHTTPContent($this->locationQueryURL.$location, $this->searchPageURL, $cookiePath);
		$content = json_decode($content);
		if(is_object($content))
			return $content->locid;
		else
			return false;
		*/
		return 7063;
	}

	private function getUserID($content)
	{
		$userid = substr($content, strpos($content, "var user_info =")+16);
		$userid = substr($userid, 0, strpos($userid, "}")+1);
		$userid = json_decode($userid);

		if(is_object($userid))
			return $userid->userid;
		else
			return false;
	}

	private function getAuthCode($content)
	{
		$content=substr($content,strpos($content,"authid=")+7);
		$content=substr($content,0,strpos($content,"&"));
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
		$this->newMessage = false;		
		$this->count_msg = 0;

		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		
		$pages = 1; $endloop = false; $bis = 20; $ab = 0; $amount_online = 0; $this->count_msg = 0;
		
		if($this->command['online'] == 1)
		{
			$this->savelog("Go to Online User Page.");
			
			while($endloop == false)
			{
				if($pages == 1){
					$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community/useronline.html","http://www.mollylove.de/index.php/index.html", $cookiePath);
					
					$useronline = substr($content, strpos($content, '<b>Mitglieder aktuell online:</b>')+33);
					$useronline = substr($useronline, 0, strpos($useronline, '</span>'));
					$amount_online = str_replace('<span class="FontColorAlert">','',$useronline);
					$total_pages = round(trim($amount_online) / 36);
				}else{
					$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community/useronline.html?pagee=".$pages,"http://www.mollylove.de/index.php/community/useronline.html?pagee=".($pages-1), $cookiePath);
				}
				
				$result = $this->getOnlineMembersFromSearchResult($content);
				$this->savelog("Online User Pages: ".$pages);
				$this->savelog("Found ".count($result)." member(s)");

				if(count($result) > 0)
				{

					foreach($result as $mid => $item){	
						
						if($this->command['logout_after_sent'] == "Y"){
							if($this->count_msg >= $this->command['messages_logout']){
								break 2;
							}
						}

						$this->work_sendMessage($username, $item, $cookiePath);
						
						
						//$item = array("username" => "AndresB","link"=> "/index.php/community.html?do=messenger&what=newMessage&user=AndresB");
						//$this->work_sendMessage($username, $item, $cookiePath);
					}
				
				}
				
				$pages++;

				if($pages > $total_pages){
					$endloop = true;
				}
			}
		}
		else
		{
			while($endloop == false)
			{
				if($pages == 1)
				{
					$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community/alluser.html", "http://www.mollylove.de/index.php/index.html", $cookiePath);				
				}else{
					$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community/alluser.html?plz=-1&bis=".$bis."&ab=".$ab, "http://www.mollylove.de/index.php/index.html", $cookiePath);
				}
				
				$content = substr($content,strpos($content,'<td class="BGContent" align="center"><b><font size="-2">Letztes Login</b></font></td>'));
				$content = substr($content,0,strpos($content,'<!-- <strong>Seite: &nbsp; </strong> -->'));

				$result = $this->getMembersFromSearchResult($content);

				if(count($result) > 0){
									
					$this->savelog("Go to Page: ".$pages);
					$this->savelog("There were about ".count($result)." members found.");

					foreach($result as $mid => $item){		
						/*
						"username" => "MarcoKlinkmann",
																	"password" => "rego6835"
						*/
						//$item = array("username" => "MarcoKlinkmann","link"=> "/index.php/community.html?do=messenger&what=newMessage&user=MarcoKlinkmann");
						if($this->command['logout_after_sent'] == "Y"){
							if($this->count_msg >= $this->command['messages_logout']){
								break 2;
							}
						}

						$this->work_sendMessage($username, $item, $cookiePath);
					}
				}else{
					$endloop = true;
				}

				$pages++;
				$ab += 20;
			}
		}
		
		$this->savelog("Job completed.");
		return true;

	}
	
	private function getOnlineMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content, strpos($content, '<a href="/index.php/community.html?do=profiles&amp;what=profil&amp;user=')-100);
		$content = substr($content, 0, strpos($content, '</table>'));
			
		if($this->command['gender'] == 1)
		{
			$gender = "m.gif";
		}elseif($this->command['gender'] == 2){
			$gender = "w.gif";
		}

		if($html = str_get_html($content)){
					
			$nodes = $html->find("td");

			foreach ($nodes as $node) {					

				if(strpos($node->innertext, $gender) !== false)
				{	
					if($html2 = str_get_html($node->innertext)){
					
						$nodes2 = $html2->find("a");

						foreach ($nodes2 as $e) {	
							$username = str_replace("/index.php/community.html?do=profiles&amp;what=profil&amp;user=","",$e->href);
							array_push($list,array("username" => trim($username)));
						}
					}
				}
				
			}
		}

		return $list;
	}

	private function getMembersFromSearchResult($content){
		
		$list = array();
		
		$gender = ($this->command['gender'] == 1)? "m.gif" : "w.gif";

		if($html = str_get_html($content)){
					
			$nodes = $html->find(".BG1");

			foreach ($nodes as $node) 
			{
				if(strpos($node->innertext, $gender) !== false)
				{
					if($html2 = str_get_html($node->innertext)){
					
						$nodes2 = $html2->find("big a");

						foreach ($nodes2 as $e) 
						{
							if($e->innertext != "")
							{
								array_push($list, array(
										"username" => trim($e->innertext), 
										"link" => trim($e->href)
									)
								);
							}
						}
					}
				}
			}

		}
		
		return $list;
	}	
	
	private function work_vote($item,$cookiePath){
		$this->savelog("Go to vote user: ".$item["username"]);
		$content = $this->getHTTPContent($this->indexURL."/".$item["username"]."?vote", $this->searchRefererURL, $cookiePath);
		$this->sleep(5);
		return $content;
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
		$this->savelog("Go to profile page: ".$item["username"]);	
		$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $this->searchIndex, $cookiePath);
		
		$this->sleep(5);
		return $content;
	}

	private function utime(){
		$utime = preg_match("/^(.*?) (.*?)$/", microtime(), $match);
		$utime = $match[2] + $match[1];
		$utime *=  1000;
		return ceil($utime);
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

			$this->savelog("Message is : ".utf8_decode($message));

			// Go to profile page
			$content = $this->work_visitProfile($username, $item, $cookiePath);
			
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				if($this->command['msg_type']=="pm")
				{
					$this->savelog("Go to send message page: ".$item['username']);					
					$this->sleep(5);

					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item['username']);

					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community.html?do=messenger&what=newMessage&user=".$item['username'], "	http://www.mollylove.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $cookiePath);						
					
						$msg_arr = array(
							"SID" => $this->sid,
							"do" => "messenger",
							"reid" => "",	
							"what" => "sendMessage",
							"user" => $item['username'],
							"ueberschrift" => $subject,
							"size" => "",	
							"face" => "",	
							"color" => "",
							"text" => utf8_decode($message)
						);						

						$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community.html", "http://www.mollylove.de/index.php/community.html?do=messenger&what=newMessage&user=".$item['username'], $cookiePath, $msg_arr);

						$url_log = "URL =>http://www.mollylove.de/index.php/community.html \nREFERER => http://www.mollylove.de/index.php/community.html?do=messenger&what=newMessage&user=".$item['username']." \n";
						file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

						if(strpos($content, 'Deine Nachricht wurde verschickt') !== false)
						{
							$this->newMessage = true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();								
							$return = true;
							$this->count_msg++;
						}
						else
						{		
							if(strpos($content, 'upgrade_form.php') !== false){
								$this->savelog("To be paid before.");							
							}else{
								$this->savelog("Sending message failed.");
							}

							$this->newMessage = true;													
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
				}elseif($this->command['msg_type']=="gb")
				{
					$this->savelog("Go to sign guestbook page: ".$item['username']);			

					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}
					
					$this->savelog("Signing guestbook to ".$item['username']);
							
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community.html?do=gb&what=gbshow&user=".$item["username"], "http://www.mollylove.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $cookiePath);
						
						$param = array(
							"Button" => "Eintrag vornehmen",
							"SID" => $this->sid,
							"color" => "",
							"do" => "gb",
							"face" => "",	
							"size" => "",	
							"text" => utf8_decode($message),
							"user" => $item['username'],
							"what" => "gbeintrag"
						);
						
						$headers = array("Content-Type:application/x-www-form-urlencoded");
						$content = $this->getHTTPContent("http://www.mollylove.de/index.php/community.html", "http://www.mollylove.de/index.php/community.html?do=gb&what=gbshow&user=".$item["username"], $cookiePath, $param, $headers);
								
						file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
							
						if(strpos($content, 'Dein Eintrag war erfolgreich') !== false)
						{
							$this->newMessage=true;
							$this->savelog("Signing guestbook completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
									
							$this->lastSentTime = time();
							$return = true;
							$this->count_msg++;
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

				$this->cancelReservedUser($item["username"]);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item["username"]);
			$return = true;
		}
		
		return $return;
	}

	private function sendTestMessage($username, $item, $cookiePath){
		
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

		$this->savelog("Message is : ".utf8_decode($message));

		// Go to profile page
		$content = $this->work_visitProfile($username, $item, $cookiePath);

		$this->savelog("Go to send message page: ".$item["username"]);

		if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
			$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
		}

		$this->savelog("Sending message to ".$item["username"]);
		echo $item["link"];
		$p = @explode("?",$item["link"]);					
		$links = @explode("&",$p[1]);
		$frid = str_replace("RID=","",$links[0]);
		$rid = str_replace("NI=","",$links[1]);

		//RID
			list($frid,$rid) = @explode("=",$links[0]);

		$item["link"] = "/alpha/mitglieder/profilpopup/formneu.php?RID=".$rid."&N=".$item["username"];				
		$content = $this->getHTTPContent($this->indexURL.$item["link"], $this->searchIndex, $cookiePath);					
		
		$param = array();
		if($html = str_get_html($content)){
				
			$nodes = $html->find("input[type=hidden]");

			foreach ($nodes as $node) {	
				$param[$node->name] = $node->value;
			}
		}
					
		$param["Mail"] = $message;
		$param["absenden"] = "absenden »";
						
		$headers = array('Content-Type: application/x-www-form-urlencoded');
		$content = $this->getHTTPContent($this->messageURL, $this->searchIndex, $cookiePath, $param, $headers);

		$url_log = "URL => ".$this->messageURL."\nREFERER => ".$this->messageURL."\n";
		file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

		if(strpos($content, 'Die Nachricht wurde erfolgreich verschickt') !== false)
		{
			$this->newMessage=true;
			$this->savelog("Sending message completed.");			
			$this->lastSentTime = time();							
			$return = true;
		}
		else
		{
			$this->newMessage=false;
			$this->savelog("Sending message failed.");							
			$this->lastSentTime = time();
			$return = true;
		}

		return $return;		
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

	private function deleteAllOutboxMessages($username,$cookiePath)
	{
		$content = $this->getHTTPContent("http://www.gibsmir.de/mail.php", "http://www.gibsmir.de/mail.php", $cookiePath);		
		
		$folders = substr($content,strpos($content,'"last_update":')+14);
		$folders = substr($folders,0,strpos($folders,','));

		$headers = array(
			'Content-Type: application/x-www-form-urlencoded',
			'X-Requested-With: XMLHttpRequest'
		);

		$param = array(
			"empty_folder" => "sent",
			"folder" => "sent",
			"state" => '{"mail_client":0,"folders":'.trim($folders).',"vfolders":0,"messages":-1,"history":-1}',
			"user_agent" => "IM_Web_Client"
		);

		$content = $this->getHTTPContent("http://www.gibsmir.de/mail.php", "http://www.gibsmir.de/mail.php", $cookiePath, $param,$headers);
		$this->savelog("Delete All Outbox message Completed.");		
	}

	private function getOutboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving outbox messages.");
		$content = $this->getHTTPContent($this->outboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "outbox", $content);

		if(isset($parser->document->ul[0]) && isset($parser->document->ul[0]->li))
		{
			foreach($parser->document->ul[0]->li as $item)
			{
				$message = array(
					"message" => str_replace("checkbox_","",$item->input[0]->tagAttrs['id'])
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
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteOutboxURL."?".http_build_query($delete_arr), $this->deleteOutboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}

	private function getInboxMessages($cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");

		$count = 1;
		$endloop = false;
		$content = $this->getHTTPContent("http://www.gibsmir.de/mail.php", "http://www.gibsmir.de/mypage.php?source=login&chapter=mypage", $cookiePath);
	
		$content = substr($content,strpos($content,'"html":"')+8);
		$content = substr($content,0,strpos($content,'"},"empty_inbox_folders"'));
		
		$vowels = array('\n');
		$content = trim(stripslashes(str_replace($vowels,"",$content)));
		

		if($html = str_get_html($content)){					
					
			$nodes = $html->find("a.username");	
			
			foreach($nodes as $node){
					
				$vowels = array("/review/profile/",".html");
				$uname = trim($node->innertext);				
				$uid = trim(str_replace($vowels,"",$node->href));

				array_push($list, 
					array(
						"uid" => trim($uid),
						"username" => trim($uname),
						"link" => trim($node->href)
					)
				);					
			}
			
		}
		
		return $list;
	}

	private function deleteInboxMessage($username, $message, $cookiePath)
	{
		$this->savelog("Deleting message id: ".$message['message']);
		$delete_arr = array(
							"ajax" => 1,
							"deletethread" => 1,
							"deletemsg" => 0,
							"threadid" => $message['message'],
							"msgid" => 0
			);

		$content = $this->getHTTPContent($this->deleteInboxURL."?".http_build_query($delete_arr), $this->deleteInboxRefererURL, $cookiePath);

		$this->savelog("Deleting message id: ".$message['message']." completed.");
	}
}
?>
