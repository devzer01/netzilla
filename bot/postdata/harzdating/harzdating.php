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

class Harzdating extends bot
{
	public function harzdating($post)
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
													"username" => "BrideOfChucky",
													"password" => "mgeue07"						
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
									"messages_per_hour" => 30,
									"logout_after_sent" => "Y",
									"messages_logout" => 1,
									"wait_for_login" => 1,
									"login_by" => 1,
									"within" => 10,
									"ageStart" => 19,
									"ageEnd" => 88,									
									"msg_type" => "pm",
									"proxy_type" => 3,									
									"gender" =>"w",									
									"send_test" => 0,
									"version" => 1,
									"online" => 0,
									"radius" => 100,
									"ziport" => "08525",
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
			$siteID = 105;
		}
		
		$this->databaseName = "harzdating";
		$this->userhash = "";
		$this->token = "";
		$this->usernameField = "alias_log";
		$this->loginURL = "http://harzdating.de/design08/log/login_1.php?act=login";
		$this->loginRefererURL = "http://harzdating.de/domain/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://harzdating.de/design08/log/logout_1.php?PHPSESSID=";
		$this->indexURL = "http://www.verlieb-dich.com";
		$this->indexPage = "http://www.verlieb-dich.com/user/index.php";
		$this->indexURLLoggedInKeyword = 'log/logout_1.php';
		
		$this->searchIndex = "http://harzdating.de/design08/mitglieder/suche.php";		
		$this->searchRefererURL = "http://harzdating.de/design08/profil/index.php";
		$this->searchResultsPerPage = 150;		
		
		$this->sendMessageURL = "http://handicap-love.de/nachrichten/senden/";		
		$this->outboxURL = "http://www.ktosexy.de/do_mail.php";
		
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:26.0) Gecko/20100101 Firefox/26.0";
		$this->commandID = $commandID;
		$this->runCount = $runCount;		
		$this->siteID = $siteID;
		$this->botID = $botID;
		
		$this->currentSubject = 0;
		$this->currentMessage = 0;
		$this->count_msg = 0;
		$this->user_sex = "";
		$this->user_name = "";
		$this->session = "";

		$this->addLoginData($this->command['profiles']);
		$this->timeWorking = 60*$this->command['within'];
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];		
		
		$this->cities = array("Berlin", "Hamburg", "München", "Köln", "Frankfurt am Main", "Stuttgart", "Düsseldorf", "Dortmund", "Essen", "Bremen", "Leipzig", "Dresden", "Hannover", "Nürnberg", "Duisburg", "Bochum", "Wuppertal", "Bonn", "Bielefeld", "Mannheim", "Karlsruhe", "Münster", "Wiesbaden", "Augsburg", "Aachen", "Mönchengladbach", "Gelsenkirchen", "Braunschweig", "Chemnitz", "Krefeld", "Magdeburg", "Freiburg im Breisgau", "Oberhausen", "Lübeck", "Erfurt", "Rostock", "Mainz", "Kassel", "Hagen", "Hamm", "Saarbrücken", "Ludwigshafen am Rhein", "Osnabrück", "Herne", "Oldenburg", "Leverkusen", "Solingen", "Potsdam", "Neuss", "Heidelberg", "Darmstadt", "Paderborn", "Regensburg", "Würzburg", "Ingolstadt", "Heilbronn", "Ulm", "Wolfsburg", "Göttingen", "Pforzheim", "Recklinghausen", "Bottrop", "Fürth", "Bremerhaven", "Reutlingen", "Remscheid", "Koblenz", "Erlangen", "Bergisch Gladbach", "Trier", "Jena", "Moers", "Siegen", "Hildesheim", "Cottbus", "Salzgitter", "Dessau-Roßlau", "Gera", "Görlitz", "Kaiserslautern", "Plauen", "Schwerin", "Wilhelmshafen", "Witten", "Zwickau"
								);
		$this->plz = array("01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089");
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		$this->count_member = 0;
		
		if($this->command['gender'] == "m"){
			$target = "Male";
		}elseif($this->command['gender'] == "w"){
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
	
	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				"alias_log" => $user["username"],			
				"passw_log" => $user["password"],
				"submit" => "einloggen"
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
		$time_working = time()+$this->timeWorking;

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);
		
		$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/suche.php", "http://harzdating.de/design08/profil/index.php", $cookiePath);
		$resultperpages = 0; $endloop = false; $pages = 1;		
		$this->count_msg = 0;
		
		if($this->command['online'] == 1){
			$pages = 1;
			$this->savelog("Go to search online user.");

			while($endloop == false){
				$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/jetzt_online.php", "http://harzdating.de/design08/mitglieder/suche.php", $cookiePath);			
				
				if($pages == 1){
					
					$this->savelog("Search => Age: ".$this->command['ageStart']." - ".$this->command['ageEnd']);
					$search_option = array(
						"alterbis" => $this->command['ageEnd'],
						"altervon" => $this->command['ageStart'],
						"geschlecht" => "",
						"submit" => "zeigen"
					);
					
					$headers = array("Content-Type: application/x-www-form-urlencoded");
					$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/jetzt_online.php?act=suche", "http://harzdating.de/design08/mitglieder/suche.php", $cookiePath, $search_option, $headers);
				}
				
				$result = $this->getOnlineMembersFromSearchResult($content);
				
				if(count($result)> 0){
						$this->savelog("Go to Page: ".$pages);
						$this->savelog("There were about ".count($result)." members found.");

						foreach($result as $mid => $item){						
												
							if($this->command['version']==1){
								if($this->command['logout_after_sent'] == "Y"){
									if($this->count_msg >= $this->command['messages_logout']){
										break 2;
									}
								}

								$this->work_sendMessage($username, $item, $cookiePath);
													
							}

						}
				}else{						
					$endloop = true;
				}

				$pages++;
				$resultperpages += 20;
			}

		}else{
			
			$this->savelog("Go to Search Page.");

			while($endloop == false){
				
				if($pages == 1){
					
					$this->savelog("Search => Gender: ".$this->command['gender'].", Age: ".$this->command['ageStart']." - ".$this->command['ageEnd']);
					$search_option = array(
						"alias11" => "",
						"mw1" => ($this->command['gender'] == "w")? "m":"w",
						"submit" => "SUCHEN",
						"sucht1" => ($this->command['gender'] == "w")? "w":"m",
						"suchtbis1" => $this->command['ageEnd'],
						"suchtvon1" => $this->command['ageStart']
					);
			
					$headers = array("Content-Type: application/x-www-form-urlencoded");
					$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/suche.php?act=suchen", "http://harzdating.de/design08/mitglieder/suche.php", $cookiePath, $search_option, $headers);
				
				}else{
					
					$url = "http://harzdating.de/design08/mitglieder/suche.php?act=suchen&anfang=".$resultperpages."&mw1=m&sucht1=w&suchtvon1=16&suchtbis1=80";
					if($resultperpages == 0){
						$refer = "http://harzdating.de/design08/mitglieder/suche.php?act=suchen";
					}else{
						$refer = "http://harzdating.de/design08/mitglieder/suche.php?act=suchen&anfang=".($resultperpages-20)."&mw1=m&sucht1=w&suchtvon1=16&suchtbis1=80";
					}

					$content = $this->getHTTPContent($url, $refer, $cookiePath);

				}

				file_put_contents("search/".$username."-".$this->command['gender']."-".$this->command['ageStart']."-".$this->command['ageEnd']."-".$pages.".html",$content);
			
				if(strpos($content, 'Bitte vervollständige Dein Profil - vorher ist leider keine Suche möglich') !== false)
				{
					$this->savelog("Please Complete your profile - before is unfortunately no search possible.");
					$endloop = true;

				}else{

					$result = $this->getMembersFromSearchResult($content);
					
					if(count($result)> 0){
						$this->savelog("Go to Page: ".$pages);
						$this->savelog("There were about ".count($result)." members found.");

						foreach($result as $mid => $item){						
												
							if($this->command['version']==1){
								if($this->command['logout_after_sent'] == "Y"){
									if($this->count_msg >= $this->command['messages_logout']){
										break 2;
									}
								}

								$this->work_sendMessage($username, $item, $cookiePath);
													
							}

						}	

					}else{
						$this->savelog("Profile not found.");
						$endloop = true;
					}
				}
				
				$pages++;
				$resultperpages += 20;
			}
		}
				
		$this->savelog("Job completed.");
		return true;
	}	
	
	private function getOnlineMembersFromSearchResult($content){
		$list = array();
		$gender = "(".$this->command['gender'].")";

		$content = substr($content,strpos($content,'<form action="jetzt_online.php?act=suche" method="post">'));
		$content = substr($content,0,strpos($content,'</html>'));

		$content = substr($content,strpos($content,'<table width=100% cellpadding=5 cellspacing=0 class=tableborder1 bgcolor=#EFEFEF>'));
		$content = substr($content,0,strpos($content,'</table>'));

		if($html = str_get_html($content)){
					
			$nodes = $html->find("tr");
			
			foreach ($nodes as $node) {
				
				if(strpos($node->innertext, $gender) !== false)
				{
					if($html2 = str_get_html($node->innertext)){
					
						$nodes2 = $html2->find("a");
						$u = array();

						foreach ($nodes2 as $node2) {
							if(strpos($node2->href, 'profil1.php?alias=') !== false)
							{
								$name = trim(str_replace('profil1.php?alias=','',$node2->href));
								if (!in_array($name, $u)){
									$u[] = $name;						
									array_push($list,array("username" => trim($name),"links"=>$node->href));
								}					
							}
						}
					}		
				}
			}
		}				
		
		return $list;
	}

	private function getMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content,strpos($content,'<u>Tipp:</u>'));
		$content = substr($content,0,strpos($content,'[>> Seite vor >>]'));
				
		if($html = str_get_html($content)){
					
			$nodes = $html->find("tr a");
			$u = array();

			foreach ($nodes as $node) {
				if(strpos($node->href, 'mitglieder/profil1.php?alias') !== false)
				{
					$name = trim(str_replace('../mitglieder/profil1.php?alias=','',$node->href));
					if (!in_array($name, $u)){
						$u[] = $name;						
						array_push($list,array("username" => trim($name),"links"=>$node->href));
					}					
				}
			}
		}				
		
		return $list;
	}
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);		
		$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/profil1.php?alias=".$item["username"], $this->searchRefererURL, $cookiePath);
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

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
		// If not already sent
		if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				// Go to profile page				
				$this->work_visitProfile($username, $item, $cookiePath);				
				
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

				if($this->command['msg_type']=="pm")
				{					
					$content = $this->getHTTPContent("http://harzdating.de/design08/post/new1.php?alias=".$item["username"], "http://harzdating.de/design08/mitglieder/profil1.php?alias=".$item["username"], $cookiePath);
					
					$message_arr = array(
						"nachricht" => utf8_decode($message),
						"remLen" => strlen($message),
						"submit" => "SENDEN"
					);
					
					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item['username']);
					
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{				
						$headers = array("Content-Type: application/x-www-form-urlencoded");		
						$content = $this->getHTTPContent("http://harzdating.de/design08/post/new1.php?alias=".$item["username"]."&act=new", "http://harzdating.de/design08/post/new1.php?alias=".$item["username"], $cookiePath, $message_arr, $headers);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, 'Nachricht versandt') !== false)
						{
							$this->newMessage = true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$this->count_msg++;								
							$return = true;
						
						}elseif(strpos($content, 'Profil wurde ein Personalausweischeck angeordnet') !== false){
							
							$this->savelog("This profile has been arranged an identity card check.");							
							$this->newMessage = true;													
							$this->lastSentTime = time();
							$return = false;
						}
						else
						{		
							$this->savelog("Sending message failed.");							
							$this->newMessage = true;													
							$this->lastSentTime = time();
							$return = false;
						}
					}
					else
					{
						$this->newMessage = false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						$return = false;
					}
				}elseif($this->command['msg_type']=="gb"){
					$this->savelog("Go to sign guestbook page: ".$item['username']);					
					$message_arr = array(
						"g_text" => utf8_decode($message),
						"sumbit" => "EINTRAGEN"
					);

						if(time() < ($this->lastSentTime + $this->messageSendingInterval))
							$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					
						$this->savelog("Signing guestbook to ".$item['username']);
						
						if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
						{
							$headers = array("Content-Type: application/x-www-form-urlencoded");
							$content = $this->getHTTPContent("http://harzdating.de/design08/mitglieder/gb1.php?act=Eintrag&alias_gb=".$item['username'],"http://harzdating.de/design08/mitglieder/gb1.php?alias_gb=".$item['username'], $cookiePath, $message_arr, $headers);							
							file_put_contents("sending/gb-".$username."-".$item['username'].".html",$content);
						
							if(strpos($content, 'Eintrag vorgenommen') !== false)
							{
								$this->newMessage=true;
								$this->savelog("Signing guestbook completed.");
								DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
								$this->lastSentTime = time();
								$this->count_msg++;
								$return = true;
							}
							else
							{
								$this->newMessage=false;
								$this->savelog("Signing guestbook failed.");														
								$this->lastSentTime = time();
								$return = false;
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
				$this->sleep(5);
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
