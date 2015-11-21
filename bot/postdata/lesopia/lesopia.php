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

class Lesopia extends bot
{
	public function lesopia($post)
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
													"username" => "annaschlaucher@outlook.de",
													"password" => "busensuse"						
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
									"ageStart" => 18,
									"ageEnd" => 98,									
									"msg_type" => "pm",
									"proxy_type" => 1,

									"ageStart" => 16,
									"ageEnd" => 18,
									"height_from" => 140,
									"height_to" => 215,
									"gender" =>"m",									
									"send_test" => 0,
									"version" => 2,	
									"online" => 0,
									"start_city" => 21,
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
			$siteID = 104;
		}
		
		$this->databaseName = "lesopia";
		$this->userhash = "";
		$this->token = "";
		$this->usernameField = "email";
		$this->loginURL = "http://www.lesopia.net/index.php/login";
		$this->loginRefererURL = "http://www.lesopia.net/index.php";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.lesopia.net/index.php/logout";
		$this->indexURL = "http://www.lesopia.net/index.php/members/home";
		$this->indexURLLoggedInKeyword = '/index.php/logout';
		
		$this->searchOnlineUser = "http://www.lesopia.net/index.php/pages/online";
		$this->searchResultsPerPage = 150;		
		
		
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
		$this->usertemp = array();		

		$this->addLoginData($this->command['profiles']);
		$this->timeWorking = 60*$this->command['within'];
		$this->messageSendingInterval = (60*60) / $this->command['messages_per_hour'];
		
		$this->cities = array(19=>"Baden-Württemberg",20=>"Bayern",21=>"Berlin",22=>"Brandenburg",23=>"Bremen",24 =>"Hamburg",25=>"Hessen",26=>"Mecklenburg-Vorpommern",27=>"Niedersachsen",28=>"Nordrhein-Westfalen",29=>"Rheinland-Pfalz",30=>"Saarland",31=>"Sachsen",32=>"Sachsen-Anhalt",33=>"Schleswig-Holstein",34=>"Thüringen",35=>"Burgenland",36=>"Kärnten",37=>"Niederösterreich",38=>"Oberösterreich",39=>"Salzburg",40=>"Steiermark",41=>"Tirol",42=>"Vorarlberg",43=>"Wien",44=>"Aargau",45=>"Appenzell Ausserrhoden",46=>"Appenzell Innerrhoden",47=>"Basel-Landschaft",48=>"Basel-Stadt",49=>"Bern",50=>"Freiburg",51=>"Genf",52=>"Glarus",53=>"Graubünden",54=>"Jura",55=>"Luzern",56=>"Neuenburg",57 =>"Nidwalden",58=>"Obwalden",59=>"Schaffhausen",60=>"Schwyz",61=>"Solothurn",62=>"St. Gallen",63=>"Tessin",64=>"Thurgau",65=>"Uri",66=>"Waadt",67=>"Wallis",68 =>"Zug",69=>"Zürich",70=>"Lichtenstein");

		$this->plz = array("01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089");
		$this->subject="";
		$this->message="";
		$this->newMessage=true;
		$this->totalPart = DBConnect::retrieve_value("SELECT MAX(part) FROM messages_part");
		$this->messagesPart = array();
		$this->messagesPartTemp = array();
		$this->count_member = 0;
		
		if($this->command['gender'] == 1){
			$target = "Lesbian";
		}

		for($i=1; $i<=$this->totalPart; $i++)
		{
			$this->messagesPart[$i] = DBConnect::row_retrieve_2D_conv_1D("SELECT message FROM messages_part WHERE part=".$i." and target='Male'");
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
				"email" => $user["username"],
				"password" => $user["password"],
				"remember" => "",
				"return_url" => "",	
				"submit" => ""	
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
	
	public function array_slice_assoc ($array, $key, $length, $preserve_keys = true){
	  
	   $offset = array_search($key, array_keys($array));
	   if (is_string($length))
		  $length = array_search($length, array_keys($array)) - $offset;

	   return array_slice($array, $offset, $length, $preserve_keys);
	   
	}
	
	private function checkInbox() {
			
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$list = array();
			
		// Go inbox and parse to list array
		$content = $this->getHTTPContent('http://www.lesopia.net/index.php/messages/inbox', 'www.lesopia.net/index.php/members/home', $cookiePath);
		if(!empty($content)) {
			// Get Inbox
			$html = str_get_html($content);
			if(!empty($html->find('div.messages_list li'))) {
				foreach($html->find('div.messages_list li') as $li){
					$href = $li->find('p.messages_list_info_title',0)->find('a',0)->href;
					$list[] = array(
						'username' => $li->find('p.messages_list_from_name',0)->find('a',0)->plaintext,
						'inbox_url' => 'http://www.lesopia.net'.$href,
						'inbox_id' => str_replace('/index.php/messages/view/id/','',$href)
					);
				}
			}
		}
		var_dump($list);
		
		// if found list
		if(!empty($list)) {
			foreach($list as $item) {
				// Show Inbox
				$this->savelog("[INBOX] : Inbox id #".$item['inbox_id']);
				
				// Reply
				$this->replyInboxMessage($username, $item, $cookiePath);	
				
				// Delete : Die ausgewählten Nachrichten wurden gelöscht
				$this->savelog("[INBOX] : Deleted message inbox id #".$item['inbox_id']);
				$content = $this->getHTTPContent('http://www.lesopia.net/index.php/messages/delete/place/view/message_ids/'.$item['inbox_id'], $item['inbox_url'], $cookiePath, array(
					'message_ids' => $item['inbox_id'],
					'place' => 'view'	
				));
			}
		} else {
			$this->savelog("No inbox message at this time");
		}
	}
	
	private function replyInboxMessage($username, $item, $cookiePath) {
		// If not already sent
		if(!$this->isAlreadySent($item['username']))
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("[INBOX] : Reserving profile to send reply message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				if(isset($this->command['full_msg']) && ($this->command['full_msg']==1))
				{
					//RANDOM SUBJECT AND MESSAGE
					$this->savelog("[INBOX] : Random new subject and message");
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
				
				$this->savelog("[INBOX] : message is ".$message);
				if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
					$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
				}

				$this->savelog("[INBOX] : Sending reply message to ".$item['username']);
				if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
				{				
					$content = $this->getHTTPContent($item['inbox_url'], $item['inbox_url'], $cookiePath, array(
						'body' => $message,
						'submit' => ''
					));
					
					$content = $this->getHTTPContent($item['inbox_url'], $this->indexURL, $cookiePath);
					
					if(strpos($content, $item['username']))
					{
						$this->newMessage = true;
						$this->savelog("[INBOX] : Sending reply message completed.");
						DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
						$this->lastSentTime = time();				
						$return = true;
					}
					else
					{		
						$this->savelog("[INBOX] : Sending message failed.");							
						$this->newMessage = true;													
						$this->lastSentTime = time();
						$return = false;
					}
				}
				else
				{
					$this->newMessage = false;
					$this->cancelReservedUser($item['username']);
					$this->savelog("[INBOX] : Sending message failed. This profile reserved by other bot: ".$item['username']);
					$return = false;
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
	}
	
	public function work()
	{
			
		$this->savelog("Job started.");
		
		if($this->command['version'] == 2) {
			$this->savelog("Running Bot version 2: Read inbox message");
			$this->checkInbox();
		}
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$time_working = time()+$this->timeWorking;

		if($this->command['send_test'])
			$this->sendTestMessage($username, $cookiePath);
		
		$content = $this->getHTTPContent($this->indexURL, $this->loginURL, $cookiePath);

		if($this->command['online'] == 1){
			$this->savelog("Go to Search online user.");
			$content = $this->getHTTPContent($this->searchOnlineUser, $this->indexURL, $cookiePath);
			
			$result = $this->getOnlineMembersFromSearchResult($content);
			
			if(count($result) > 0){

				$this->savelog("There were about ".count($result)." members found.");

				foreach($result as $mid => $item){			
									
					if($this->command['logout_after_sent'] == "Y"){
						if($this->count_msg >= $this->command['messages_logout']){
							break;
						}
					}
					
					//$item = array("username" => "littlemeexD", "link" => "/index.php/profile/littlemeexD");
					$this->work_sendMessage($username, $item, $cookiePath);
				}
			}
		}
		else
		{
			$this->savelog("Go to Search Page.");
			$content = $this->getHTTPContent("http://www.lesopia.net/index.php/members", $this->indexURL, $cookiePath);
			
			$cities = $this->cities;			
			if($key = array_search($this->command['start_city'],array_keys($cities)))
			{
				$cities = array_slice($cities, $key);
			}
			
			foreach($cities as $city => $cityname)
			{				
				$cityid = array_search($cityname, $this->cities);

				for($age=$this->command['ageStart']; $age<=$this->command['ageEnd']; $age++)
				{
					$this->savelog("Search => City:".$cityname.", Age:".$age);
					$pages = 1;$endloop = false;
					
					while($endloop == false)
					{
						if($pages == 1)
						{
							$search_option = array(
								"1_1_10_field_10[max]" => "",	
								"1_1_10_field_10[min]" => "",	
								"1_1_11_field_11" => "",	
								"1_1_12_field_12" => $cityid,	
								"1_1_13_field_13" => "",
								"1_1_19_field_19" => "",	
								"1_1_21_field_21" => "",	
								"1_1_22_field_22" => "",	
								"1_1_23_field_23" => "",	
								"1_1_24_field_24" => "",	
								"1_1_25_field_25" => "",	
								"1_1_26_field_26" => "",	
								"1_1_27_field_27" => "",	
								"1_1_29_field_29" => "",	
								"1_1_32_field_32" => "",	
								"1_1_33_field_33" => "",	
								"1_1_34_field_34" => "",	
								"1_1_36_field_36" => "",	
								"1_1_3_field_3" => "",	
								"1_1_40_field_40" => "",	
								"1_1_4_alias_birthdate[max]" => $age,
								"1_1_4_alias_birthdate[min]" => $age,
								"1_1_5_field_5" => "",	
								"1_1_6_field_6" => "",	
								"1_1_7_field_7" => "",	
								"ajax" => 1,
								"displayname" => "",	
								"extra[has_photo]" => "",	
								"extra[is_online]" => "",					
								"format" => "html"
							);
							
							$headers = array("Content-Type:application/x-www-form-urlencoded","X-Requested-With:XMLHttpRequest");
							$content = $this->getHTTPContent("http://www.lesopia.net/index.php/members", "http://www.lesopia.net/index.php/members/home", $cookiePath,$search_option,$headers);
						}else{
							$content = $this->getHTTPContent("http://www.lesopia.net/index.php/members?1_1_12_field_12=".$cityid."&extra[has_photo]=&extra[is_online]=&1_1_4_alias_birthdate[min]=".$age."&1_1_4_alias_birthdate[max]=".$age."&1_1_10_field_10[min]=&1_1_10_field_10[max]=&page=".$pages, "http://www.lesopia.net/index.php/members/home", $cookiePath);
						}

						$result = $this->getMembersFromSearchResult($content);

						if(count($result) > 0){
							
							$this->savelog("Page: ".$pages);
							$this->savelog("There were about ".count($result)." members found.");

							foreach($result as $mid => $item){	
								
								if($this->command['logout_after_sent'] == "Y"){
									if($this->count_msg >= $this->command['messages_logout']){
										break;
									}
								}

								$this->work_sendMessage($username, $item, $cookiePath);
							}

						}else{							
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
	
	private function getOnlineMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content, strpos($content, '<h3> Members Online</h3>'));
		$content = substr($content, 0, strrpos($content, '<div id="global_footer">'));

		if($html = str_get_html($content)){
				
			$nodes = $html->find("div.whosonline_thumb a");
				
			foreach ($nodes as $node) {	
				$vowels = array("/index.php/profile/");
				$uname = trim(str_replace($vowels,"",$node->href));
				
				array_push($list,array("username" => $uname,"link" => $node->href));
			}
		}

		return $list;
	}

	private function getMembersFromSearchResult($content){
		$list = array();
		if($html = str_get_html($content)){
				
			$nodes = $html->find("div.browsemembers_results_info a");
			foreach($nodes as $node){					
				if (!in_array(trim($node->innertext), $this->usertemp, TRUE))
				{
					if(strpos(trim($node->innertext), 'http://www.youtube.com') !== false)
					{
					}else{
						array_push($list,array("username" => trim($node->innertext)));
						$this->usertemp[] = trim($node->innertext);
					}

				}

			}
		}		
		
		return $list;
	}
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);		
		$content = $this->getHTTPContent("http://www.verlieb-dich.com/user/profil.php?showuser=".$item["username"], $this->searchIndex, $cookiePath);
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
				//$this->work_visitProfile($username, $item, $cookiePath);				
				
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
				
				// Go to profile page
				$content = $this->getHTTPContent("http://www.lesopia.net/index.php/profile/".$item["username"], "http://www.lesopia.net/index.php/members", $cookiePath);
					
				$uid = substr($content, strpos($content, '<a  href="/index.php/members/friends/add/user_id/')+49);
				$uid = substr($uid, 0, strpos($uid, '"'));	
				
				$content = $this->getHTTPContent("http://www.lesopia.net/index.php/messages/compose/to/".$uid."/format/smoothbox", "http://www.lesopia.net/index.php/profile/".$item["username"], $cookiePath);

				$this->savelog("Message is : ".$message);

				if($this->command['msg_type']=="pm")
				{					
					// $content = $this->getHTTPContent("http://www.verlieb-dich.com/user/user_popup.php?send=message&to=".$item["username"], "http://www.verlieb-dich.com/user/profil.php?showuser=".$item["username"], $cookiePath);

					$message_arr = array(
						"body" => $message,
						"submit" => "",
						"title" => $subject,
						"to" => "",	
						"toValues" => "user_".$uid
					);


					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{				
						$headers = array("Content-Type: application/x-www-form-urlencoded");		
						$content = $this->getHTTPContent("http://www.lesopia.net/index.php/messages/compose/to/".$uid."/format/smoothbox", "	http://www.lesopia.net/index.php/messages/compose/to/".$uid."/format/smoothbox", $cookiePath, $message_arr, $headers);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, 'Your message has been sent successfully') !== false)
						{
							$this->newMessage = true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							$this->count_msg++;								
							$return = true;
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
