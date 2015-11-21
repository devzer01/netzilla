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

class okcupid extends bot
{
	public function okcupid($post)
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
																	"username" => "Becky_82",
																	"password" => "journey"
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
									"gender" => "17", //34
									"msg_type" => "im",
									"send_test" => 0,
									"distance" => 25,
									"start_city" => "Cardiff",
									"start_page" => 1,
									"version" => 1,
									//"full_msg" => 1,
									//"online" => 3600,				//now
									//"online" => 86400,			//last day
									//"online" => 604800,			//last week
									//"online" => 2678400,		//last month
									"online" => 315360000,		//last year
									//"online" => 315360000,		//last decade
									"withImage" => 0,
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

		$this->databaseName = "okcupid";
		$this->usernameField = "username";
		$this->indexURL = "http://www.okcupid.com";
		$this->indexURLLoggedInKeyword = "/logout";
		$this->loginURL = "https://www.okcupid.com/login";
		$this->loginRefererURL = "http://www.okcupid.com";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.meetme.com/apps/home";
		$this->searchPageURL = "http://www.okcupid.com/match";
		$this->searchURL = "http://www.okcupid.com/match";
		$this->searchRefererURL = "http://www.okcupid.com/match";
		$this->locationQueryURL = "http://www.okcupid.com/locquery?func=query&query=";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "http://www.okcupid.com/profile/";
		$this->sendMessagePageURL = "http://www.okcupid.com/profile";
		$this->sendMessageURL = "http://www.okcupid.com/mailbox";
		$this->sendQuestionURL = "http://feed.meetme.com/askMe/json/submit";
		$this->sendIMURL = "http://www.okcupid.com/instantevents";
		$this->sendGuestbookURL = "http://single.de/Rest/postfach-message";
		$this->inboxURL = "http://www.okcupid.com/messages";
		$this->deleteInboxURL = "http://www.okcupid.com/mailbox";
		$this->deleteInboxRefererURL = "http://www.okcupid.com/messages";
		$this->outboxURL = "http://www.okcupid.com/messages?folder=2";
		$this->deleteOutboxURL = "http://www.okcupid.com/mailbox";
		$this->deleteOutboxRefererURL = "http://www.okcupid.com/messages?folder=2";
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
		$this->message="";
		$this->newMessage=true;
		$this->cities = array(
								"Belfast", "Cardiff", "Castlereagh", "Aberdeen", "Dundee", "Edinburgh", "Glasgow", "Craigavon", "Derry", "Bangor", "Beeston and Stapleford", "Carlton", "Chesterfield", "Corby", "Derby", "Kettering", "Leicester", "Lincoln", "Loughborough", "Mansfield", "Northampton", "Nottingham", "Wellingborough", "West Bridgford", "Basildon", "Bedford", "Cambridge/Milton", "Chelmsford", "Cheshunt", "Clacton-on-Sea", "Colchester", "Dunstable", "Grays", "Great Yarmouth", "Harlow/Sawbridgeworth", "Hemel Hempstead", "Ipswich", "Lowestoft", "Luton", "Norwich", "Peterborough", "Saint Albans", "Southend-on-Sea", "Stevenage", "Watford", "London", "Lisburn", "Newport", "Newtownabbey", "Darlington", "Gateshead", "Hartlepool", "Middlesbrough", "Newcastle upon Tyne", "South Shields", "Stockton-on-Tees", "Sunderland", "Washington", "Bebington", "Birkenhead", "Blackburn", "Blackpool", "Bolton", "Bootle", "Burnley", "Bury", "Carlisle", "Cheadle and Gatley", "Chester", "Crewe", "Crosby", "Ellesmere Port", "Greasby/Moreton", "Huyton-with-Roby", "Liverpool", "Macclesfield", "Manchester", "Morecambe", "Oldham", "Preston", "Rochdale", "Runcorn", "Saint Helens", "Sale", "Salford", "Southport", "Stockport", "Wallasey", "Warrington", "Widnes", "Wigan", "Paisley", "Rhondda", "Aldershot", "Ashford", "Aylesbury", "Basingstoke", "Bletchley", "Bognor Regis", "Bracknell", "Brighton", "Chatham", "Crawley", "Dartford", "Eastbourne", "Eastleigh", "Epsom and Ewell", "Esher/Molesey", "Fareham/Portchester", "Farnborough", "Gillingham", "Gosport", "Gravesend", "Guildford", "Hastings", "High Wycombe", "Horsham", "Hove", "Littlehampton", "Maidenhead", "Maidstone", "Margate", "Oxford", "Portsmouth", "Reading", "Reigate/Redhill", "Royal Tunbridge Wells", "Slough", "Southampton", "Staines", "Walton and Weybridge", "Waterlooville", "Woking/Byfleet", "Wolverton/Stony Stratford", "Worthing", "East Kilbride", "Bath", "Bournemouth", "Bristol", "Cheltenham", "Exeter", "Gloucester", "Kingswood", "Paignton", "Plymouth", "Poole", "Swindon", "Taunton", "Torquay", "Weston-super-Mare", "Weymouth", "City", "Swansea", "Barry", "Livingston", "Birmingham", "Cannock", "Coventry", "Dudley", "Halesowen", "Hereford", "Kidderminster", "Newcastle-under-Lyme", "Nuneaton", "Oldbury/Smethwick", "Redditch", "Royal Leamington Spa", "Rugby", "Shrewsbury", "Solihull", "Stafford", "Stoke-on-Trent", "Stourbridge", "Sutton Coldfield", "Tamworth", "Walsall", "West Bromwich", "Wolverhampton", "Worcester", "Barnsley", "Batley", "Bradford", "Dewsbury", "Doncaster", "Grimsby", "Halifax", "Harrogate/Knaresborough", "Huddersfield", "Keighley", "Kingston upon Hull", "Leeds", "Morley", "Rotherham", "Scunthorpe", "Sheffield", "Wakefield", "York"
								);
		
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
		parent::bot();
	}

	public function addLoginData($users)
	{
		foreach($users as $user)
		{
			$login_arr = array(
								"username" => $user['username'],
								"password" => $user['password'],
								"dest" => "/?"
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

	private function getLocationID($location, $cookiePath)
	{
		$content = $this->getHTTPContent($this->locationQueryURL.$location, $this->searchPageURL, $cookiePath);
		$content = json_decode($content);
		if(is_object($content))
			return $content->locid;
		else
			return false;
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
		$this->sleep(5);

		for($age=$this->command['age_from']; $age<=$this->command['age_to']; $age++)
		{
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

				if(($this->workCount == 1) && ($age==$this->command['age_from']) && ($city==$this->command['start_city']))
					$page=$this->command['start_page'];
				else
					$page=1;

				do
				{
					if($this->isLoggedIn($username))
					{
						/******************/
						/***** search *****/
						/******************/
						$details = "";
						$search_arr = array(
											"filter1" => "0,".$this->command['gender'],
											"filter2" => "2,".$age.",".$age,
											"filter3" => "3,".$this->command['distance'],
											"filter4" => "5,".$this->command['online'],
											"filter5" => "1,".$this->command['withImage'],
											"filter6" => "35,0",
											"locid" => $this->getLocationID($city, $cookiePath),
											"lquery" => $city,
											"timekey" => 1,
											"matchOrderBy" => "LOGIN",
											"custom_search" => 0,
											"fromWhoOnline" => 0,
											"mygender" => ($this->command['gender']==17)?"f":"m",
											"update_prefs" => 1,
											"sort_type" => 0,
											"sa" => 1,
											"using_saved_search" => "",
											"low" => (($page-1)*$this->searchResultsPerPage)+1,
											"count" => $this->searchResultsPerPage,
											"ajax_load" => 1
										);

						$this->savelog("Search for gender: ".(($this->command['gender']==17)?"Male":"Female").", age: ".$age.", last online: ".$this->command['online'].", city: ".$city.", with image: ".$this->command['withImage'].", page: ".$page);

						$content = $this->getHTTPContent($this->searchURL."?".http_build_query($search_arr), $this->searchRefererURL, $cookiePath);
						$content = json_decode($content);
						file_put_contents("search/".$username."-search-".$age."-".$city."-".$page.".html",print_r($content,true));

						/***********************************************/
						/***** Extract profiles from search result *****/
						/***********************************************/
						$list = $this->getMembersFromSearchResult($username, $page, $content);

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
										if($this->command['version']==1)
										{
											//$item['username'] = "MichaellaX";
											//$item['username'] = "Measmeme";
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

						if($this->command['version']==1)
						{
							$this->deleteAllOutboxMessages($username, $cookiePath);
						}
						elseif($this->command['version']==2)
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
									$this->deleteAllOutboxMessages($username, $cookiePath);
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

		// Make it to XML object
		$parser = $this->convertToXML($username, $page, $content->html);

		if(isset($parser->document->div))
		{
			foreach($parser->document->div as $item)
			{
				if(isset($item->tagAttrs['class']) && (strpos($item->tagAttrs['class'],"match_row")!==false))
				{
					$profile = array(
										"username" => $item->div[0]->div[0]->h4[0]->a[0]->span[0]->tagData
									);
					array_push($list,$profile);
				}
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

	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item['username']);
		$content = $this->getHTTPContent($this->profileURL.$item['username']."?cf=regular", $this->searchRefererURL, $cookiePath);
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

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$subject = $this->randomText($this->command['messages'][$this->currentSubject]['subject']);
			$message = $this->randomText($this->command['messages'][$this->currentMessage]['message']);

			$this->message=$this->getPreMessage()." ".$message." ".$this->getPostMessage();
			$this->subject=$subject;
		}
		return array($this->subject, $this->message);
	}

	private function work_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = true;
		// If not already sent
		if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
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
			$utime = $this->utime();
			$content = $this->work_visitProfile($username, $item, $cookiePath);
			$item['userid'] = $this->getUserID($content);
			$item['authcode'] = urldecode($this->getAuthCode($content));
			//if(strpos($content,"InstantEvents.openAnImWindow")!==false)
				//$this->command['msg_type']=="im";
			/*if($this->command['online']==3600)
				$this->command['msg_type']="im";
			else*/
				$this->command['msg_type']="pm";

			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				if($this->command['msg_type']=="pm")
				{
					////////////////////////////////////////
					/////// Go to send message page ////////
					////////////////////////////////////////
					$message_arr = array(
											"cb" => "",
											"showsuccess" => "",
											"winktype" => "",
											"ajaxEdit" => 1,
											"shadowbox" => "send_message",
											"tuid" => $item['userid']
										);

					$this->savelog("Go to send message page: ".$item['username']);
					$content = $this->getHTTPContent($this->sendMessagePageURL, $this->profileURL.$item['username']."?cf=regular", $cookiePath, $message_arr);
					$this->sleep(5);
					$authcode = urldecode($this->getAuthCode($content));
					$message_arr = array(	"ajax" => 1,
											"sendmsg" => 1,
											"r1" => $item['username'],
											"subject" => "",
											"body" => $message,
											"threadid" => 0,
											"authcode" => $item['authcode'],
											"reply" => 0,
											"from_profile" => 1
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendMessageURL;
						$url_referer = $this->profileURL.$item['username']."?cf=regular";
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						$content = json_decode($content);

						if($content->status==3)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Sending message failed. ");
							$this->lastSentTime = time();
							$this->sleep(120);
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				elseif($this->command['msg_type']=="im")
				{
					$message_arr = array(
											"from_profile" => 1,
											"send" => 1,
											"attempt" => 1,
											"rid" => $item['userid'],
											"recipient" => $item['username'],
											"topic" => "false",
											"body" => $message,
											"rand" => (float)rand()/(float)getrandmax()
										);
					if(time() < ($this->lastSentTime + $this->messageSendingInterval))
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					$this->savelog("Sending instant message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{
						$url = $this->sendIMURL;
						$url_referer = $this->profileURL.$item['username']."?cf=regular";
						$content = $this->getHTTPContent($url, $url_referer, $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						$content = json_decode($content);

						if($content->message_sent==1)
						{
							$this->newMessage=true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username, from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
							$this->lastSentTime = time();
							if(isset($item['message']))
								$this->deleteInboxMessage($username, $item['message'], $cookiePath);
							$return = true;
						}
						else
						{
							$this->newMessage=true;
							$this->savelog("Sending message failed. ");
							$this->lastSentTime = time();
							$this->sleep(120);
							$return = true;
						}
					}
					else
					{
						$this->newMessage=false;
						$this->cancelReservedUser($item['username']);
						$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
						if(isset($item['message']))
							$this->deleteInboxMessage($username, $item['message'], $cookiePath);
						$return = true;
					}
				}
				$this->cancelReservedUser($item['username']);
				$this->sleep(2);
			}
		}
		else
		{
			$this->savelog("Already send message to profile: ".$item['username']);
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

	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent($this->inboxURL, $this->indexURL, $cookiePath);
		
		$content = substr($content, strpos($content, '<ul id="messages"'));
		$content = substr($content, 0, strpos($content, '</ul>')+5);
		$parser = $this->convertToXML($username, "inbox", $content);

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