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

class Damdoo extends bot
{
	public function damdoo($post)
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
													"username" => "kaeferchen84",
													"password" => "a595xwh3"						
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
									"messages_logout" => 2,
									"wait_for_login" => 1,
									"login_by" => 1,
									"within" => 10,
									"ageStart" => 51,
									"ageEnd" => 60,									
									"msg_type" => "pm",
									"proxy_type" => 3,

									"ageStart" => 25,
									"ageEnd" => 63,
									"gender" =>"M",
									"online" => 0,
									"send_test" => 0,
									"version" => 1,											
									"distance" => 15,
									"start_city" => "Stuttgart",
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
			$siteID = 110;
		}
		
		$this->databaseName = "damdoo";
		$this->userhash = "";
		$this->token = "";
		$this->sid = "";
		$this->usernameField = "lnick";
		$this->loginURL = "http://www.damdoo.de/9check.php";
		$this->loginRefererURL = "http://www.damdoo.de/index.html?m=UNKNOWN";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.damdoo.de/logout.php";
		$this->indexURL = "http://www.damdoo.de";
		$this->indexPage = "http://www.damdoo.de/index3.php";
		$this->indexURLLoggedInKeyword = 'http://www.damdoo.de/logout.php';
		
		$this->searchIndex = "http://www.damdoo.de/index3.php#";
		$this->searchURL = "http://mingle2.com/search/update_preferences?new_action=index";
		$this->searchRefererURL = "http://www.damdoo.de/index3.php#";
		$this->searchResultsPerPage = 150;		
		
		$this->sendMessageURL = "http://mingle2.com/inbox/new/";
		$this->messageReferer = "http://mingle2.com/user/view/";
		
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
		$this->u = array();

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
		
		if($this->command['gender'] == "M"){
			$target = "Male";
		}elseif($this->command['gender'] == "F"){
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
				"action" => "LOGIN",
				"lnick" => $user["username"],
				"lpw" => $user["password"]
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
		$this->count_msg = 0;
		
		if($this->command['online'] == 1){

			$pages = 1;$endloop = false;

			while($endloop == false)
			{
				$this->savelog("Go to online user Gender: ".$this->command['gender'].", Page: ".$pages);
				$content = $this->getHTTPContent("http://www.damdoo.de/mod/onlines.php?page=".$pages."&ON_sex=".$this->command['gender'], "http://www.damdoo.de/index3.php", $cookiePath);

				$result = $this->getOnlineMembersFromSearchResult($content);
				
				if(count($result) > 0){
					
					$this->savelog("Go to Page: ".$pages);
					$this->savelog("There were about ".count($result)." members found.");
				
					foreach($result as $mid => $item){						
						
						if($this->command['logout_after_sent'] == "Y"){
							if($this->count_msg >= $this->command['messages_logout']){
								return true;
							}

							$return = $this->work_sendMessage($username, $item, $cookiePath);
						}

					}
				}else{
					$endloop = true;
				}

				$pages++;
			}
		
		}else{
			
			for($age=$this->command['ageStart']; $age<=$this->command['ageEnd']; $age++)
			{
				$this->savelog("Search => Gender: ".$this->command['gender'].", Age: ".$age);
				$pages = 1;$endloop = false;

				while($endloop == false)
				{
					if($pages == 1)
					{
						$search_option = array(
								"action" => "Suche starten",
								"my_agebis" => $age,
								"my_agevon" => $age,
								"my_augen" => "-",
								"my_bart" => "-",
								"my_famstand" => "-",
								"my_fgal" => "egal",
								"my_figur" => "-",
								"my_foto0" => "egal",
								"my_gw_bis" => 	"",
								"my_gw_von" => 	"",
								"my_haare" => "-",
								"my_habekinder" => "-",
								"my_kg_bis" => 	"",
								"my_kg_von" => 	"",
								"my_lookingsx[]" => ($this->command['gender'] == "W")? "W":"M",
								"my_moechtekinder" => "-",
								"my_raucher" => "-",
								"my_sx[]" => ($this->command['gender'] == "W")? "M":"W",
								"my_uk" => ""	
						);

						$headers = array("Content-Type: application/x-www-form-urlencode");
						$content = $this->getHTTPContent("http://www.damdoo.de/mod/dresults.php", "http://www.damdoo.de/mod/dsearch.php?mode=cls", $cookiePath, $search_option, $headers);
					}else{
						$content = $this->getHTTPContent("http://www.damdoo.de/mod/dresults.php?page=".$pages, "http://www.damdoo.de/mod/dresults.php", $cookiePath);
					}
					
					$result = $this->getMembersFromSearchResult($content);

					if(count($result) > 0){
						
						$this->savelog("Go to Page: ".$pages);
						$this->savelog("There were about ".count($result)." members found.");
					
						foreach($result as $mid => $item){						
							
							if($this->command['logout_after_sent'] == "Y"){
								if($this->count_msg >= $this->command['messages_logout']){
									return true;
								}

								$return = $this->work_sendMessage($username, $item, $cookiePath);
							}

						}
					}else{
						$endloop = true;
					}

					$pages++;
				}
			}
		}		
	
		$this->savelog("Job completed.");
		return true;
	}	
	
	private function getOnlineMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content, strpos($content, '<a href="?ON_sex=P" style="padding:0px;color:#fff;font-Family:Arial;font-size:8pt;font-weight:bold;text-decoration:none;">P</a>'));
		$content = substr($content, 0, strrpos($content, '<div id="listfoot" style="border-top:1px solid #000;">'));
				
		if($html = str_get_html($content)){
						
			$nodes = $html->find("a");

			foreach ($nodes as $node) {

				if(strpos($node->href, 'http://www.damdoo.de/mod/profil.php?sid=') !== false)
				{
					$uname = substr($node->innertext, strpos($node->innertext, '<b>')+3);
					$uname = substr($uname, 0, strrpos($uname, '</b>'));
					$uid = str_replace("http://www.damdoo.de/mod/profil.php?sid=","",$node->href);

					array_push($list,array(
						"username" => trim($uname),
						"uid" => trim($uid),
						"link" => trim($node->href)
					));
				}
			}

		}
		
		return $list;
	}

	private function getMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content, strpos($content, '<div class="list_area" style="width:803px;height:455px;padding:10px;background:#ffffff;overflow-x:hidden;overflow-y:auto;">'));
		$content = substr($content, 0, strrpos($content, '<div id="listfoot" style="border-top:1px solid #000;">'));
				
		if($html = str_get_html($content)){
						
			$nodes = $html->find("a");

			foreach ($nodes as $node) {

				if(strpos($node->href, 'http://www.damdoo.de/mod/profil.php?sid=') !== false)
				{
					$uname = substr($node->innertext, strpos($node->innertext, '<b>')+3);
					$uname = substr($uname, 0, strrpos($uname, '</b>'));							
					$uid = str_replace("http://www.damdoo.de/mod/profil.php?sid=","",$node->href);
					//$item = array("username" => "littlemeexD", "uid" => "v9jG782aI]TR1qrP2gE1Ij", "link" => "http://www.damdoo.de/mod/profil.php?sid=v9jG782aI]TR1qrP2gE1Ij");
					array_push($list,array(
						"username" => trim($uname),
						"uid" => trim($uid),
						"link" => trim($node->href)
					));
				}
			}

		}			
		
		return $list;
	}
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);		
		$content = $this->getHTTPContent("http://www.damdoo.de/mod/profil.php?sid=".$item["uid"], $this->searchIndex, $cookiePath);
		$this->sleep(5);
		return $content;
	}

	public function getAction(){
		return $this->command['action'];
	}

	public function getSiteID(){
		return $this->siteID;
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
				
				$this->savelog("Message is : ".$message);

				if($this->command['msg_type']=="pm")
				{					
					$content = $this->getHTTPContent("http://www.damdoo.de/mod/write_msg.php?to_id=".$item["uid"], "http://www.damdoo.de/index3.php#", $cookiePath);						
					
					$message_arr = array();
					if($html = str_get_html($content)){
				
						$nodes = $html->find("input[type=hidden]");

						foreach ($nodes as $node) {	
							$message_arr[$node->name] = $node->value;
						}
					}
					
					$message_arr["mod"] = "";
					$message_arr["xmsg_subject"] = $subject;
					$message_arr["xmsg_body"] = "<p>".$message."</p>";
					$message_arr["xmsg_att"] = "";
					$message_arr["action"] = "Nachricht senden";
					
					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}
				
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$content = $this->getHTTPContent("http://www.damdoo.de/mod/write_msg.php", "http://www.damdoo.de/mod/write_msg.php?to_id=".$item["uid"], $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, "Die Nachricht wurde") !== false)
						{
							$this->newMessage = true;							
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$this->count_msg++;								
							$return = true;
						}elseif(strpos($content, "Leider sind Deine Aktivitäten derzeit gesperrt") !== false){
							$this->savelog("Unfortunately, your activities are currently blocked! Your personal Butler has recognized incomplete data based on your information or insufficient PQ value of your profile. Please correct your data (Settings / Basic Information or Settings / Profile)");							
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
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".trim($username)."'");

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
