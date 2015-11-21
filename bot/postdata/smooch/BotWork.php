<?php

// 
require_once('bot.php');
require_once('simple_html_dom.php');

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

class botwork extends bot
{
	private $_table_prefix = 'smooch_';
	private $_searchResultId = 0;
	private $nextSearchPage = '';
	public $sendmsg_total = 0;
	public $rootDomain = 'http://www.smooch.com/';
	public $searchActionURL = '';
	public $sendMessageActionURL = '';
	public $logged = FALSE;
	
		
	public function __construct($post)
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
													'username' => 'amelie1987',
													'password' => 'Whatthefox1'
													)
												),
									"messages" => array(
															array(
																	"subject" => "Hello",
																	"message" => "Hallo"
																),
															array(
																	"subject" => "Hey",
																	"message" => "Hallo"
																)
														),
									"start_h" => 8,
									"start_m" => 00,
									"end_h" => 23,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"age_from" => 60,
									"age_to" => 65,
									"gender" => 0,
									"status" => "all",
									"country" => 81,
									"postcode" => "91220",
									//"action" => "check"
									"action" => "send",
									"wait_for_login" => 1,
									'logout_after_sent' => 10,
									'version' => 1,
									'disabled_tor' => 0,
									'online' => 0
								);
			$commandID = time();
			$runCount = 1;
			$botID = 1;
			$siteID = 60;
		}
		$this->usernameField = 'usernameOrEmail';
		$this->loginURL = "http://www.smooch.com/";
		$this->loginActionURL = 'http://www.smooch.com/PublicServices/Validation.asmx/TryLogin';
		$this->loginRefererURL = "http://www.lotsofloves.com/";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.smooch.com/LogOut.aspx";
		$this->indexURL = "http://www.smooch.com/";
		$this->indexURLLoggedInKeyword = 'LogOut.aspx';
		$this->searchURL = "http://www.smooch.com/Member/search/CustomSearches.aspx";
		$this->searchNextURL = '';
		$this->searchRefererURL = "";
		$this->searchResultsPerPage = 16;
		$this->profileURL = "";
		$this->sendMessagePageURL = "h";
		$this->sendMessageURL = "";
		$this->proxy_ip = "127.0.0.1";
		$this->proxy_port = "9050";
		$this->proxy_control_port = "9051";
		$this->userAgent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0";
		$this->commandID = $commandID;
		$this->runCount = $runCount;
		$this->botID = $botID;
		$this->siteID = $siteID;
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
		
		if(!empty($_GET['test'])) {
			$this->command['test'] = 1;
			$this->command['test_username'] = $_GET['test_username'];
		}

		$target = "Male";
		if($this->command['gender'] == '1'){
			$target = "Female";
		}
		
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
		$this->postMessageTemp = array();
		$this->preMessageTemp = array();
		
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

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
				'usernameOrEmail' => $user['username'],
				'password' => $user['password']
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	private function getPreMessage()
	{
		if(count($this->preMessageTemp)==0)
		{
			shuffle($this->preMessage);
		}
		elseif(count($this->preMessage)==0)
		{
			$this->preMessage = $this->preMessageTemp;
			shuffle($this->preMessage);
			$this->preMessageTemp = array();
		}

		$msg = array_pop($this->preMessage);
		array_push($this->preMessageTemp, $msg);
		return $msg;
	}

	private function getPostMessage()
	{
		if(count($this->postMessageTemp)==0)
		{
			shuffle($this->postMessage);
		}
		elseif(count($this->postMessage)==0)
		{
			$this->postMessage = $this->postMessageTemp;
			shuffle($this->postMessage);
			$this->postMessageTemp = array();
		}

		$msg = array_pop($this->postMessage);
		array_push($this->postMessageTemp, $msg);
		return $msg;
	}
	

	private function sendUserMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']);
		if($this->reserveUser($item['username']))
		{
			// Go to profile page
			$this->savelog("Go to profile page: ".$item['username'].' / Profile : '.$item['profile_url']);
			$content = $this->getHTTPContent($item['profile_url'], $this->searchURL, $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$this->savelog("Random new subject and message");
			$this->currentSubject = rand(0,count($this->command['messages'])-1);
			$this->currentMessage = rand(0,count($this->message)-1);

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
			
			$this->savelog("Subject is : ".$subject);
			$this->savelog("Message is : ".$message);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
			{
				$referer = 'http://www.smooch.com/Member/Profile.aspx?ID='. $item['uid'];
				$ch = array();
				$data_string = array();
				
				$ch[1] = curl_init();
				$ch[2] = curl_init();
				
				$data_string[1] = json_encode(array('recipientID' => $item['uid']));
				$data_string[2] = json_encode(array(
					'subject' => $subject,
					'message' => $message,
					'recipientID' => $item['uid']
				),JSON_UNESCAPED_UNICODE);
				
				if($this->command['proxy_type'] != 3 && !empty($this->proxy_ip) && !empty($this->proxy_port) && !empty($this->proxy_type)){
					curl_setopt($ch[1], CURLOPT_PROXY, $this->proxy_ip);
					curl_setopt($ch[1], CURLOPT_PROXYPORT, $this->proxy_port);
					curl_setopt($ch[1], CURLOPT_PROXYTYPE, $this->proxy_type);
					curl_setopt($ch[2], CURLOPT_PROXY, $this->proxy_ip);
					curl_setopt($ch[2], CURLOPT_PROXYPORT, $this->proxy_port);
					curl_setopt($ch[2], CURLOPT_PROXYTYPE, $this->proxy_type);
				}
				
				curl_setopt($ch[1], CURLOPT_URL, 'http://www.smooch.com/Services/Messages.asmx/CheckMailFilter');
				curl_setopt($ch[1], CURLOPT_REFERER, $referer);
				curl_setopt($ch[1], CURLOPT_USERAGENT, $this->userAgent);
				curl_setopt($ch[1], CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch[1], CURLOPT_TIMEOUT, 20); 
				curl_setopt($ch[1], CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch[1], CURLOPT_HTTPHEADER, array(
						'Accept-Encoding: gzip, deflate',
						'Accept-Language: en-US,en;q=0.5',
						'Cache-Control: no-cache',
						'Connection: keep-alive',
						'Content-Length: '.strlen($data_string[1]),
						'Content-Type: application/json; charset=utf-8',
						'X-Requested-With: XMLHttpRequest'
				));
				curl_setopt($ch[1], CURLOPT_COOKIEFILE, $cookiePath);
				curl_setopt($ch[1], CURLOPT_COOKIEJAR, $cookiePath);
				curl_setopt($ch[1], CURLOPT_CUSTOMREQUEST, "POST"); 
				curl_setopt($ch[1], CURLOPT_POSTFIELDS, $data_string[1]);
				
				curl_setopt($ch[2], CURLOPT_URL, 'http://www.smooch.com/Services/Messages.asmx/SaveMessage');
				curl_setopt($ch[2], CURLOPT_REFERER, $referer);
				curl_setopt($ch[2], CURLOPT_USERAGENT, $this->userAgent);
				curl_setopt($ch[2], CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch[2], CURLOPT_TIMEOUT, 20); 
				curl_setopt($ch[2], CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch[2], CURLOPT_HTTPHEADER, array(
						'Accept-Encoding: gzip, deflate',
						'Accept-Language: en-US,en;q=0.5',
						'Cache-Control: no-cache',
						'Connection: keep-alive',
						'Content-Length: '.strlen($data_string[2]),
						'Content-Type: application/json; charset=utf-8',
						'X-Requested-With: XMLHttpRequest'
				));
				curl_setopt($ch[2], CURLOPT_COOKIEFILE, $cookiePath);
				curl_setopt($ch[2], CURLOPT_COOKIEJAR, $cookiePath);
				curl_setopt($ch[2], CURLOPT_CUSTOMREQUEST, "POST"); 
				curl_setopt($ch[2], CURLOPT_POSTFIELDS, $data_string[2]);
				curl_setopt($ch[2], CURLOPT_WRITEFUNCTION, 'do_nothing');
				
				
				//create the multiple cURL handle
				$mh = curl_multi_init();
				
				//add the two handles
				curl_multi_add_handle($mh,$ch[2]);
				curl_multi_add_handle($mh,$ch[1]);
				
				$running_handles = null; 
		        //execute the handles 
		        do { 
		            $status_cme = curl_multi_exec($mh, $running_handles); 
		        } while ($status_cme == CURLM_CALL_MULTI_PERFORM); 
		
		        while ($running_handles && $status_cme == CURLM_OK) { 
		            if (curl_multi_select($mh) != -1) { 
		                do { 
		                    $status_cme = curl_multi_exec($mh, $running_handles); 
		                   	echo "<br>''threads'' running = {$running_handles}"; 
		                } while ($status_cme == CURLM_CALL_MULTI_PERFORM); 
		            } 
		        } 
				
				$i = 1;
				foreach($ch as $c){ 
		            $res[$i]['data']  = curl_multi_getcontent($ch[$i]);  // get results 
		            curl_multi_remove_handle($mh, $ch[$i]);
		            $i++;
		        } 
				
				//close all the handles
				curl_multi_close($mh);
				
				var_dump($res);
				
				/* $data_string = json_encode(array(
					'subject' => $subject,
					'message' => $message,
					'recipientID' => $item['uid']
				),JSON_UNESCAPED_UNICODE);
				
				$this->jsonPost = 1;
				$response = $this->getHTTPContent('http://www.smooch.com/Services/Messages.asmx/SaveMessage',
					'http://www.smooch.com/Member/Profile.aspx?ID='. $item['uid'], 
					$cookiePath, 
					$data_string, 
					FALSE, 
					array(
						'Accept-Encoding: gzip, deflate',
						'Accept-Language: en-US,en;q=0.5',
						'Cache-Control: no-cache',
						'Connection: keep-alive',
						'Content-Length: '.strlen($data_string),
						'Content-Type: application/json; charset=utf-8',
						'Host: www.smooch.com',
						'Pragma: no-cache',
						'X-Requested-With: XMLHttpRequest',
					)
				);
					
				$data_string = json_encode(array('recipientID' => $item['uid']));
				$this->jsonPost = 1;
				$response = $this->getHTTPContent('http://www.smooch.com/Services/Messages.asmx/CheckMailFilter', $item['profile_url'], $cookiePath, $data_string, FALSE, array(
						'Accept-Encoding: gzip, deflate',
						'Accept-Language: en-US,en;q=0.5',
						'Cache-Control: no-cache',
						'Connection: keep-alive',
						'Content-Length: '.strlen($data_string),
						'Content-Type: application/json; charset=utf-8',
						'X-Requested-With: XMLHttpRequest'
				));
				$json = json_decode(trim($response));
				
				$this->sleep(2); */
				
				$json = json_decode(trim($res[1]['data']));
				
				if($json->d->IsFiltered === false && $json->d->ErrorMessage == null) {
					
					DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
					$this->savelog("Sending message completed.");
					$this->lastSentTime = time();
					$this->sendmsg_total++;
					
				} else {
					$this->savelog("Sending message failed / Error message : ".$json->d->ErrorMessage);
				}
				
				if(!empty($this->command['test'])) {
					$this->savelog("Sent to Test Users is completed and Exit");
					$this->savelog('FINISHED');
					$this->cancelReservedUser($item['username']);
					exit();
				}
			}
			else
			{
				$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
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
	
	private function searchMember($online = 0) {
			
		$content = '';
		$this->savelog("Job criterias => Target age: ".$this->command['age_from']." to ".$this->command['age_to'].' / Gender : '.$this->command['gender']);
		$this->savelog("Job started.");
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$content = $this->getHTTPContent($this->searchURL, $this->rootDomain, $cookiePath);
		
		if(empty($this->command['online'] )) {
			$this->command['online'] = 0;
		}

		$piece = explode('currentFilterData =', $content );
		$d = explode('};', $piece[1]);
		$json = json_decode(trim($d[0]).'}');
		
		/*******************************/
		/****** Go to search page ******/
		/*******************************/
		$this->savelog("Go to SEARCH page.");
		$this->sleep(5);

		for($age = $this->command['age_from']; $age <= $this->command['age_to']; $age++){
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				
				/******************/
				/***** search *****/
				/******************/
	
				$this->savelog("Search for Target age: ".$age." to ".$age.", / page ".$page);
				
				$search_arr = array(
					'AgeId' => $age.'-'.$age,
					'AgeName' => $age.' to '.$age,
					'Children' => 0,
					'ChildrenName' => '',
					'CountryId' => 165,
					'CountryName' => 'United Kingdom',
					'CountryQuickClass' => 'default-value',
					'DefaultFilterValues' => $json,
					'DistanceId' => '0',
					'DistanceName' => '',
					'EatingHabits' => 0,
					'EatingHabitsName' => '',
					'Ethnicity' => 0,
					'EthnicityName' => '',
					'Gender' => (($this->command['gender']) ? $this->command['gender'] : 0),
					'GenderName' => "Men Looking For Women",
					'GengerQuickClass' => "default-value",
					'HasPhoto' => '0',
					'HasPhotoName' => '',
					'Height' => 0,
					'HeightName' => '',
					'Id' => $json->Id,
					'IsDialog' => false,
					'IsDistanceVisible' => true,
					'IsOnline' => (($this->command['online'] == 1) ? 1 : 0),
					'IsOnlineName' => (($this->command['online'] == 1) ? 'Online Now' : ''),
					'IsOnlineQuickClass' => '',
					'KeyWord' => null,
					'KeyWordId' => null,
					'KeyWordName' => '',
					'LoadCompleted'=> true,
					'LocationSecondTierName' => '',
					'LocationThirdTierName' => '',
					'LookingFor' => 0,
					'LookingForName' => '',
					'MaxAge' => $age,
					'MemberName' => '',
					'MemberNameQuickView' => '',
					'MinAge' => $age,
					'NewPhotosQuickClass' => '',
					'NewTitle' => '',
					'Postcode' => '',
					'PostcodeName' => '',
					'Religion' => 0,
					'ReligionName' => '',
					'ReplasedAreaVisible' => false,
					'SecondTierTitle' => null,
					'Smoking' => 0,
					'SmokingName' => '',
					'SpecificSearch' => 0,
					'SubTitleDialog' => '',
					'ThirdTierTitle' => null,
					'Title' => "Custom Search",
					'TitleEditing' => false,
					'TitleWasEdited' => false,
					'_binding' => false,
					'anyCondition' => true,
					'currentFilterTitle' => "Custom Search"
				);
				
				if($page == 1){
					$data_string = json_encode(array('search' => $search_arr));
					$this->jsonPost = 1;
					$this->getHTTPContent('http://www.smooch.com/Services/Search.asmx/SaveSearchFilter', $this->searchURL, $cookiePath, $data_string, FALSE, array(
							'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
							'Accept-Encoding: gzip, deflate',
							'Accept-Language: en-US,en;q=0.5',
							'Cache-Control: no-cache',
							'Connection: keep-alive',
							'Content-Length: '.strlen($data_string),
							'Content-Type: application/json; charset=utf-8',
							'X-Requested-With: XMLHttpRequest'
					));
				}
	
				$data_string = json_encode(array('searchDto' => $search_arr));
				$this->jsonPost = 1;
				$content = $this->getHTTPContent('http://www.smooch.com/Services/Search.asmx/GetSearchResults', 
					(($page == 1) ? $this->searchURL : 'http://www.smooch.com/Member/search/CustomSearches.aspx?pn='.$page ), 
					$cookiePath, 
					$data_string, 
					FALSE, 
					array(
						'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
						'Accept-Encoding: gzip, deflate',
						'Accept-Language: en-US,en;q=0.5',
						'Cache-Control: no-cache',
						'Connection: keep-alive',
						'Content-Length: '.strlen($data_string),
						'Content-Type: application/json; charset=utf-8',
						'X-Requested-With: XMLHttpRequest'
				));
				
				// $this->savelog('Search Content : '.$content);
				
				$content = json_decode(trim($content));
				
				
				
				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = array();
				if(!empty($content)) {
					
					file_put_contents("search/".$username."-search-".$page.".html",$content);
					$list = $this->getMembersFromSearchResult($username, $page, $content, $age);
				} else {
					
					$this->savelog("No response #".__LINE__);
				}
				
				
				if(is_array($list))
				{
					
					if(count($list))
					{
						if($list[0]['username'] == $first_username && $first_username != '')
						{
							$list = array();
							$this->savelog("Skip this page because result duplicated previous page");
							break;
						}
						else
						{
							$first_username = $list[0]['username'];
						}
						
						$this->savelog("Found ".count($list)." member(s)");
						
						$this->sleep(5);
						$enableMoreThanOneMessage = FALSE;
						foreach($list as $item)
						{
													
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in runnig time period
							if($sleep_time==0)
							{
								if(!empty($this->command['test'])) {
									if($item['username'] == $this->command['test_username']) {
										$this->savelog("[Test] Found target profile : ".$item['username']);
										$this->sendUserMessage($item, $username, $cookiePath);	
									} else {
										$this->savelog("[Test] Skipped : ".$item['username']);
									}
								} else {
									// If not already sent
									if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
									{
										$this->sendUserMessage($item, $username, $cookiePath);	
									}
									else
									{
										$this->savelog("Already send message to profile: ".$item['username']);
									}
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
	
				// go to one of the profiles in search result, not in sent database
				// send gustbook message
				// save sent message with username in database
				
				$page++;
			}
			while(count($list)>=$this->searchResultsPerPage);
		}

		$this->savelog("Job completed.");
		return true;
	}

	public function work()
	{
		$this->searchMember();
	}

	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content, $age)
	{
		$list = array();
		
		if(!empty($content->d->Groups)){
			foreach($content->d->Groups as $group) {
				if(!empty($group->Items)) {
					foreach($group->Items as $item){
						$list[] = array(
							'uid' => $item->ID,
							'username' => $item->Name,
							'profile_url' => 'http://www.smooch.com'. $item->ProfileUrl,
							'age' => $item->Age,
							'online' => $item->IsOnline
							
						);	
					}
				}
			}
		}
		// var_dump($list);
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
	
	
	/**
	 * This function use for Check Profile !!
	 */
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
			// die('SESS ID : '.$this->_session_id);
			return true;
		}
	}

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->_table_prefix."sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM ".$this->_table_prefix."reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO ".$this->_table_prefix."reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM ".$this->_table_prefix."reservation WHERE username='".$username."' AND server=".$this->botID);
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

			$this->message=$this->getPreMessage()." ".$message." ".$this->getPostMessage();
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
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
			$this->savelog("failed : NO PROFILE MATCH RE-LOGIN RULES !!!");
			$this->savelog('FINISHED');
			die();
		} else {
			$this->loginArr = array();
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
}

function do_nothing($curl, $input) {
    return 0; // aborts transfer with an error
}