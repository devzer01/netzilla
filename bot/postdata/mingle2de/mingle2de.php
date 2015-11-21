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

class Mingle2 extends bot
{
	public function mingle2($post)
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
													"username" => "FaB1000",
													"password" => "Flirtatious"						
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
			$siteID = 143;
		}
		
		$this->databaseName = "mingle2de";
		$this->userhash = "";
		$this->token = "";
		$this->usernameField = "login";
		$this->loginURL = "http://mingle2.com/user/login";
		$this->loginRefererURL = "http://mingle2.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://mingle2.com/user/logout";
		$this->indexURL = "http://mingle2.com/";
		$this->indexPage = "http://mingle2.com/welcome/home";
		$this->indexURLLoggedInKeyword = '<a href="/user/logout">LOGOUT</a>';
		
		$this->searchIndex = "http://mingle2.com/search";
		$this->searchURL = "http://mingle2.com/search/update_preferences?new_action=index";
		$this->searchRefererURL = "http://mingle2.com/search";
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
		
		if($this->command['version']==1)
		{
			$this->preMessage = array(
										"Hello there sweety, i like you, but unfortunately i`m not here anymore, you will find me at",
										"Hi there! Nice profile! If you’d like to hook-up, this is not my login, simply look for me at",
										"Hello, you`re cute, I will be offline from here now and will only be available at",
										"Hello, are you single? Me too, interested in sharing a coffee? This is the login of my sis, but you`ll find me at",
										"Hey, finally found someone like you and you seem to be charming, find me at",
										"Hi nice profile ;) would like to know you better, but not over here, i`m currently at",
										"Are you a nice guy? Fancy a chat? Unfortunately this is not my account, but you will find me at",
										"Huh? What’s up? Lonely? Ok, me too and what shall we do about that? ;-) Unfortunately i`m not here anymore, but hook up with me at",
										"Not online anymore? Why not? Ok, if you like we maybe can chat around and find out more about each other by hanging out at a nice places over the weekend? If so, drop me a message at",
										"You`re out of my area, aren`t you? ;-) Would like to know more about you, but this account is the one of my sis, simply look for me at",
										"Oh, you seem to be handsome! I think you`re cute, single too? Fancy a chat? Ok, but I will be unavailable over here now, find me at",
										"Oh, one last mail from you before i logout, not getting too much messages over here but would like to know you better, currently i`m at",
										"I`m fine, you too? Whatsup? You`re cute, would like to meet up for a coffee? Next weekend? Unfortunately I will leave here, too much messages, but currently i`m at",
										"Nice, nice, isn`t it? Fancy a chat? But not over here anymore, ok? Too much idiots around, but if you like me simply drop me a message at",
										"Oh, living next to me, would love to chat with you a bit but i will leave here in short, not too much that`s going on over here, but I have images of myself at",
										"Hey, do you like blind dates too? If so, I have some images of myself, some of erotic nature too, at",
										"I like adventures, you 2? You`re out of my area but actually this is not my login so if you like you`ll find me at",
										"I`m that crazy type, like adventures, how about you? Maybe next weekend? Or am I too straight forward? Check out my pictures and decide on your own, ok? You`ll find them at",
										"Tought i should leave u a MSG cuz i`m single since quite a while now. I resettled into your area a short time ago and maybe you would like to show me the nightlife? Will leave here, too much MSGs coming up, but wou`ll find me at",
										);
			$this->postMessage = array(
										"Search for my nick, PattyR, ok?",
										"check out my Username over there, PattyR, ok?",
										"look after PattyR over there, c u soon!",
										"Simply use the nickname search and enter my nick: PattyR, ok?",
										"You`ll find me by using the nick search and entering PattyR, ok?",
										"Just look for my Nick, PattyR if you`re there! Cheers!",
										"I`m curiously awaiting your reply, simply search Patty Rover there, ok?",
										"Hopefully see u soon over there :P Cheers! PattyR",
										"Check out my Nick over there, it`s PattyR, ok?",
										"Simply drop PattyR a message over there, ok?",
										);
		}
		else
		{
			$this->preMessage = array(
										"Hello there sweety, i like you, but unfortunately i`m not here anymore, you will find me at",
										"Hi there! Nice profile! If you’d like to hook-up, this is not my login, simply look for me at",
										"Hello, you`re cute, I will be offline from here now and will only be available at",
										"Hello, are you single? Me too, interested in sharing a coffee? This is the login of my sis, but you`ll find me at",
										"Hey, finally found someone like you and you seem to be charming, find me at",
										"Hi nice profile ;) would like to know you better, but not over here, i`m currently at",
										"Are you a nice guy? Fancy a chat? Unfortunately this is not my account, but you will find me at",
										"Huh? What’s up? Lonely? Ok, me too and what shall we do about that? ;-) Unfortunately i`m not here anymore, but hook up with me at",
										"Not online anymore? Why not? Ok, if you like we maybe can chat around and find out more about each other by hanging out at a nice places over the weekend? If so, drop me a message at",
										"You`re out of my area, aren`t you? ;-) Would like to know more about you, but this account is the one of my sis, simply look for me at",
										"Oh, you seem to be handsome! I think you`re cute, single too? Fancy a chat? Ok, but I will be unavailable over here now, find me at",
										"Oh, one last mail from you before i logout, not getting too much messages over here but would like to know you better, currently i`m at",
										"I`m fine, you too? Whatsup? You`re cute, would like to meet up for a coffee? Next weekend? Unfortunately I will leave here, too much messages, but currently i`m at",
										"Nice, nice, isn`t it? Fancy a chat? But not over here anymore, ok? Too much idiots around, but if you like me simply drop me a message at",
										"Oh, living next to me, would love to chat with you a bit but i will leave here in short, not too much that`s going on over here, but I have images of myself at",
										"Hey, do you like blind dates too? If so, I have some images of myself, some of erotic nature too, at",
										"I like adventures, you 2? You`re out of my area but actually this is not my login so if you like you`ll find me at",
										"I`m that crazy type, like adventures, how about you? Maybe next weekend? Or am I too straight forward? Check out my pictures and decide on your own, ok? You`ll find them at",
										"Tought i should leave u a MSG cuz i`m single since quite a while now. I resettled into your area a short time ago and maybe you would like to show me the nightlife? Will leave here, too much MSGs coming up, but wou`ll find me at",
									);
			$this->postMessage = array(
										"Search for my nick, PattyR, ok?",
										"check out my Username over there, PattyR, ok?",
										"look after PattyR over there, c u soon!",
										"Simply use the nickname search and enter my nick: PattyR, ok?",
										"You`ll find me by using the nick search and entering PattyR, ok?",
										"Just look for my Nick, PattyR if you`re there! Cheers!",
										"I`m curiously awaiting your reply, simply search Patty Rover there, ok?",
										"Hopefully see u soon over there :P Cheers! PattyR",
										"Check out my Nick over there, it`s PattyR, ok?",
										"Simply drop PattyR a message over there, ok?",
									);
		}
		
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		$this->postMessageTemp = array();
		$this->preMessageTemp = array();
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
			$content = $this->getHTTPContent($this->indexURL, $this->indexURL);
			$login_arr = array();			


			if($html = str_get_html($content)){
				
				$nodes = $html->find("input[type=hidden]");
				
				foreach ($nodes as $node) {	
					$param[$node->name] = $node->value;
					if($node->name == "authenticity_token"){
						$this->token= $node->value;
					}
				}
			}			
			
			$login_arr["login"] = $user["username"];
			$login_arr["password"] = $user["password"];
			$login_arr["remember_me"] = 1;		

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
		
		$this->savelog("Go to Search page.");

		$endloop = false;$pages = 1;
		
		while($endloop == false)
		{
			if($this->command['gender'] == "M"){
				
				if($pages > 1){
					$url = "http://mingle2.com/online-dating/illinois/german/single-men?page=".$pages;
					$refer = "http://mingle2.com/online-dating/illinois/german/single-men?page=".($pages-1);
				}else{
					$url = "http://mingle2.com/online-dating/illinois/german/single-men";
					$refer = "http://mingle2.com";
				}

			}elseif($this->command['gender'] == "F"){
				
				if($pages > 1){
					$url = "http://mingle2.com/online-dating/illinois/german/single-women?page=".$pages;
					$refer = "http://mingle2.com/online-dating/illinois/german/single-women?page=".($pages-1);
				}else{
					$url = "http://mingle2.com/online-dating/illinois/german/single-women";
					$refer = "http://mingle2.com";
				}

			}
			
			$content = $this->getHTTPContent($url, $refer, $cookiePath);
			$result = $this->getMembersFromSearchResult($content);

			if(count($result) > 0){
				$this->savelog("Go to Page: ".$pages);
				$this->savelog("There were about ".count($result)." members found.");

				foreach($result as $mid => $item){
					//$item = array("username" => "Seductive11","uid" => "7854052", "link"=> "/user/view/7854052");
					$this->work_sendMessage($username, $item, $cookiePath);	
				}

			}else{
				$endloop = true;
			}

			$pages++;
		}
	
		exit;
		$this->savelog("Job completed.");
		return true;
	}	

	private function getMembersFromSearchResult($content){
		$list = array();
		
		if($html = str_get_html($content)){
					
			$nodes = $html->find("div.username a");
			$dup = 0;

			foreach ($nodes as $node) {
				if(strpos($node->href, '/user/view/') !== false)
				{		
					$vowels = array("/user/view/");
								
					$uname = trim($node->innertext);								
					$uid = trim(str_replace($vowels,"",$node->href));
					$link = $node->href;

					if(!in_array($uname, $this->u))
					{			
						$this->u[] = $uname;

						array_push($list,array(
							"username" => $uname,
							"uid" => $uid,
							"link" => $link
						));
					}
				}
				
			}
			
		}			
		
		return $list;
	}
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);		
		$content = $this->getHTTPContent("http://mingle2.com/user/view/".$item["uid"], $this->searchIndex, $cookiePath);
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
				
				//RANDOM SUBJECT AND MESSAGE
				//$this->savelog("Random new subject and message");
				//$this->currentSubject = rand(0,count($this->command['messages'])-1);
				//$this->currentMessage = rand(0,count($this->message)-1);

				//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
				if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
				{
					//RANDOM SUBJECT AND MESSAGE
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
					$content = $this->getHTTPContent("http://mingle2.com/user/view/".$item["uid"]."#send_email", "http://mingle2.com/user/view/".$item["uid"], $cookiePath);						
					$html = str_get_html($content);
					$token = $html->find('input[name=authenticity_token]',0)->value;
					$message_arr = array(
						"authenticity_token" => $token,
						"commit" => "Send Message",
						"message[body]" => $message,
						"message[subject]" => $subject,
						"utf8" => "✓"
					);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}
				
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{				
						$headers = array("Content-Type: application/x-www-form-urlencoded");		
						$content_post = $this->getHTTPContent("http://mingle2.com/inbox/new/".$item["uid"], "http://mingle2.com/user/view/".$item["uid"], $cookiePath, $message_arr, $headers);
						$html = str_get_html($content);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);
						
						$content = $this->getHTTPContent("http://mingle2.com/inbox/message_sent","http://mingle2.com/inbox/new/".$item["uid"], $cookiePath, NULL, array(
							'X-Requested-With: XMLHttpRequest'
						));
						if(strpos($content_post, 'verify-email-description')) {
							
							$this->savelog("failed : ".$html->find('div.verify-email-description',0)->plaintext);							
							$this->newMessage = true;													
							$this->lastSentTime = time();
							$return = false;
						}
						elseif(strpos($content, 'successfully'))
						{
							$this->newMessage = true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$this->count_msg++;								
							$return = true;
						}
						elseif(strpos($content, 'You cannot send a message until your profile is completed') !== false)
						{
							$this->savelog("You cannot send a message until your profile is completed.");							
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
	
	public function testLogin($profile) {
		$default_proxy = $this->command['proxy_type'];
		$this->command['proxy_type'] = 2;
		$this->setProxy();
		$loginRetry = 2;
		$this->userAgent = botutil::getAgentString();
		$username = $profile['username'];
		$cookiePath = $this->getCookiePath($username);

		if(!($this->isLoggedIn($username)))
		{
			$content = $this->getHTTPContent($this->indexURL, $this->indexURL);		
			$token = '';
			if(!empty($content)){
				$html = str_get_html($content);
				$token = $html->find("input[name=authenticity_token]",0)->value;
			}
			$login_arr = array(
				'authenticity_token' => $token,
				'login' => $profile['username'],
				'password' => $profile['password'],
				'remember_me' => 1
			);
			
			// count try to login
			for($count_login=1; $count_login<=$loginRetry; $count_login++)
			{
				$headers = array("Content-Type:application/x-www-form-urlencoded");
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, $login_arr, $headers);
				if(empty($content))
				{
					$this->loginRetry++;
				}
				if(!($this->isLoggedIn($username)))
				{
					if($count_login>($loginRetry-1))
					{
							return FALSE;
					}
					else
					{
						sleep(3);
					}
				}
				else
				{
					return TRUE;
				}
			}
		}
		else
		{
			return TRUE;
		}
	}
}
?>
