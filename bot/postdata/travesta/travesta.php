19<?php
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

class Travesta extends bot
{
	public function travesta($post)
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
													"username" => "Etidorhpa",
													"password" => "ziyfa899"						
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
									"gender" =>"m",	//m,w								
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
			$siteID = 119;
		}
		
		$this->databaseName = "travesta";
		$this->userhash = "";
		$this->token = "";
		$this->usernameField = "nick";
		$this->loginURL = "https://www.travesta.de/phpinc/cc.php";
		$this->loginRefererURL = "http://www.travesta.de";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.travesta.de/phpinc/cc.php?do=start&what=logout";
		$this->indexURL = "http://www.travesta.de/index.php/index.html";
		$this->indexURLLoggedInKeyword = 'href="/phpinc/cc.php?do=start&what=logout"';
		
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
		$this->sid = "";
		$this->endloop = false;

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
		$login_arr = array();
		$username = $this->command['profiles'][0]["username"];
		$cookiePath = $this->getCookiePath($username);
		
		$content = $this->getHTTPContent("http://www.travesta.de/", "http://www.travesta.de/", $cookiePath);
		$content = substr($content, strpos($content, '<form accept-charset="ISO-8859-1" method="post" action="https://www.travesta.de/phpinc/cc.php">'));
		$content = substr($content, 0, strpos($content, '</form>'));

		foreach($users as $user)
		{				
			if($html = str_get_html($content)){
				
				$nodes = $html->find("input[type=hidden]");

				foreach ($nodes as $node) {	
					$login_arr[$node->name] = $node->value;

					if($node->name == "SID"){
						$this->sid = $node->value;
					}
				}
			}

			$login_arr["Submit"] = "Betreten";
			$login_arr["nick"] = $user["username"];
			$login_arr["pass"] = $user["password"];
			$login_arr["remember"] = "yes";

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

	public function work()
	{
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		$time_working = time()+$this->timeWorking;		

		if($this->command['online'] == 1){

			$this->savelog("Go to Online User Page.");
			
			$pages = 1; $endloop = false; $bis = 20; $ab = 0; $amount_online = 0; $this->count_msg = 0; $pagee=0;

			while($endloop == false)
			{
				if($pages == 1){
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community/useronline.html","http://www.travesta.de/index.php/index.html", $cookiePath);
					
					$useronline = substr($content, strpos($content, '<b>Mitglieder aktuell online:</b>')+33);
					$useronline = substr($useronline, 0, strpos($useronline, '</span>'));
					$amount_online = str_replace('<span class="FontColorAlert">','',$useronline);
					$total_pages = trim($amount_online) / 36;
				}else{
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community/useronline.html?pagee=".$pagee,"http://www.travesta.de/index.php/community/useronline.html?pagee=".($pagee-36), $cookiePath);
				}
				
				$result = $this->getOnlineMembersFromSearchResult($content);				

				if(count($result) > 0){
					
					$this->savelog("Online User Pages: ".$pages);
					$this->savelog("Found ".count($result)." member(s)");

					if($this->command['version']==1){					
							
						foreach($result as $mid => $item){	
							
							if($this->command['logout_after_sent'] == "Y"){
								if($this->count_msg >= $this->command['messages_logout']){
									break 2;
								}
							}

							$this->work_sendMessage($username, $item, $cookiePath);	
							
						}

					}
					elseif($this->command['version']==2)
					{
						
						foreach($result as $mid => $item){
							//Step :: 1
								$this->work_visitProfile($username, $item, $cookiePath);

							//Step :: 2
								$this->work_first_sendMessage($username, $item, $cookiePath);

							//Step :: 3
								$inbox = $this->getInboxMessages($username, $cookiePath);

								if(is_array($inbox))
								{
									$this->savelog("Found ".count($inbox)." inbox message(s)");
									$this->sleep(5);
														
									foreach($inbox as $key => $v)
									{
										if($this->command['logout_after_sent'] == "Y"){
											if($this->count_msg >= $this->command['messages_logout']){
												break 2;
											}
										}

										$this->work_sendMessage($username, $v, $cookiePath);
									}
								}
									
							//Step :: 4
								$this->deleteAllOutboxMessages($username, $cookiePath);
						}

					}
					else
					{
						$this->savelog("Wrong version selected.");
					}
					
				}	

				$pages++;
				$pagee += 36;

				if($pages > $total_pages){
					$endloop = true;
				}
			}
			
		}else{

			$pagesamount = 0;
			$pages = 1; $endloop = false; $bis = 20; $ab = 0; $amount_online = 0; $this->count_msg = 0; $pagee=0;

			while($endloop == false)
			{
				if($pages == 1)
				{
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community/alluser.html", "http://www.travesta.de/index.php/index.html", $cookiePath);
					
					$pagesamount = substr($content,strpos($content,'>Z</a> ] </td></tr></table><center><b><font color=#ff0000>')+58);
					$pagesamount = substr($pagesamount,0,strpos($pagesamount,'</font> Mitglieder vorhanden</b>'));

				}else{
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community/alluser.html?plz=-1&bis=".$bis."&ab=".$ab, "http://www.travesta.de/index.php/index.html", $cookiePath);
				}

				$content = substr($content,strpos($content,'<td align="center"><b><font size="-2">Letztes Login</b></font></td>'));
				$content = substr($content,0,strpos($content,'<!-- <strong>Seite: &nbsp; </strong> -->'));

				$result = $this->getMembersFromSearchResult($content);

				if(count($result) > 0){
					
					$this->savelog("Go to Page: ".$pages);
					$this->savelog("There were about ".count($result)." members found.");

					if($this->command['version']==1){					
						
						foreach($result as $mid => $item){			
							
							if($this->command['logout_after_sent'] == "Y"){
								if($this->count_msg >= $this->command['messages_logout']){
									break 2;
								}
							}

							$this->work_sendMessage($username, $item, $cookiePath);						
						}

					}
					elseif($this->command['version']==2)
					{
						foreach($result as $mid => $item){
							//Step :: 1
								$this->work_visitProfile($username, $item, $cookiePath);

							//Step :: 2
								$this->work_first_sendMessage($username, $item, $cookiePath);

							//Step :: 3
								$inbox = $this->getInboxMessages($username, $cookiePath);

								if(is_array($inbox))
								{
									$this->savelog("Found ".count($inbox)." inbox message(s)");
									$this->sleep(5);
														
									foreach($inbox as $key => $v)
									{
										if($this->command['logout_after_sent'] == "Y"){
											if($this->count_msg >= $this->command['messages_logout']){
												break 2;
											}
										}

										$this->work_sendMessage($username, $v, $cookiePath);
									}
								}
									
							//Step :: 4
								$this->deleteAllOutboxMessages($username, $cookiePath);
						}
					}
					else
					{
						$this->savelog("Wrong version selected.");
					}
					
				}

				$pages++;
				$ab += 20;

				if($pages > $pagesamount){
					$endloop = true;
				}
			}
		}
		
		$this->savelog("Job completed.");
		return true;
	}	
	
	private function getInboxMessages($username, $cookiePath)
	{
		$list = array();
		$gender = ($this->command['gender'] == "m")? "<b class='FontColorHL2'>männlich" : "<b class='FontColorHL2'>weiblich";

		$this->savelog("Receiving inbox messages.");
		$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=messenger&what=inboxmsg", "http://www.travesta.de/", $cookiePath);
		
		$content = substr($content,strpos($content,'<form accept-charset="ISO-8859-1"  name="messenger" action="/index.php/community.html" method="post">'));
		$content = substr($content,0,strpos($content,'<input type="checkbox" name="all" value="del" onclick="selalldelmsg(this);" />'));

		if($html = str_get_html($content)){
					
			$nodes = $html->find("tr big");

			foreach ($nodes as $node) {
				$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=profiles&what=profil&user=".$node->innertext, "http://www.travesta.de/index.php/community.html?do=messenger&what=inboxmsg", $cookiePath);
				
				if(strpos($content, $gender) !== false)
				{
					array_push($list,array("username" => trim($node->innertext)));
				}
			}

		}
		
		return $list;
	}

	private function getOnlineMembersFromSearchResult($content){
		$list = array();
		
		$content = substr($content, strpos($content, '<a href="/index.php/community.html?do=profiles&amp;what=profil&amp;user=')-100);
		$content = substr($content, 0, strpos($content, '</table>'));
				
		$gender = ($this->command['gender'] == "m")? "m.gif" : "w.gif";

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
		$content = str_replace('""','"',$content);
		$gender = ($this->command['gender'] == "m")? "m.gif" : "w.gif";		

		if($html = str_get_html($content)){
					
			$nodes = $html->find("tr");

			foreach ($nodes as $node) 
			{				
				if(strpos($node->innertext, $gender) !== false)
				{				
					if($html2 = str_get_html($node->innertext)){
					
						$nodes2 = $html2->find("b a");

						foreach ($nodes2 as $e) 
						{
							$username = str_replace("/index.php/community.html?do=profiles&amp;what=profil&amp;user=","",$e->href);
							array_push($list,array("username" => trim($username)));
						}
					}
				}
				
			}

		}
		
		return $list;
	}	
	
	private function work_visitProfile($username, $item, $cookiePath)
	{
		$this->savelog("Go to profile page: ".$item["username"]);		
		$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $this->indexURL, $cookiePath);
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
	
	private function work_first_sendMessage($username, $item, $cookiePath, $enableMoreThanOneMessage=false){
		$return = false;
		// If not already sent
		if(!$this->isAlreadySentFirst($item['username']) || $enableMoreThanOneMessage)
		{
			///reserve this user, so no other bot can send msg to
			$this->savelog("Reserving profile to send message: ".$item['username']);
			if($this->reserveUser($item['username']))
			{
				//Go to profile page				
				$this->work_visitProfile($username, $item, $cookiePath);				
				$subject = "Kurznachricht von '".$item["username"]."'";
				//$words = array("Hallo","Guten Morgen","Wie geht's?","Wie heißt du?","Nett, Sie kennen zu lernen.");
				$words = array("hallo","hi","hallo wie gehts?","klpf klopf","sag mal guten tag","schau nu mal so rum","hy","hey");
				$random_key = array_rand($words,8);
				$message = $words[$random_key[0]];

				//$message = "Hallo, ich kann mit dir befreundet sein.";
				$this->savelog("Message is : ".utf8_decode($message));

				if($this->command['msg_type']=="pm")
				{					
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=messenger&what=newMessage&user=".$item["username"], "	http://www.travesta.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $cookiePath);

					$message_arr = array(
						"SID" => $this->sid,
						"do" => "messenger",
						"reid" => "",
						"what" => "sendMessage",
						"user" => $item["username"],
						"ueberschrift" => utf8_decode($subject),
						"size" => "",	
						"face" => "",	
						"color" => "",	
						"text" => utf8_decode($message)
					);


					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending first message to ".$item['username']);
					if(!$this->isAlreadySentFirst($item['username']) || $enableMoreThanOneMessage)
					{				
						$headers = array("Content-Type: application/x-www-form-urlencoded");		
						$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html", "http://www.travesta.de/index.php/community.html?do=messenger&what=newMessage&user=".$item["username"], $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, 'Deine Nachricht wurde verschickt') !== false)
						{
							$this->newMessage = true;
							$this->savelog("Sending message completed.");
							DBConnect::execute_q("INSERT INTO ".$this->databaseName."_sent_messages (to_username,from_username,subject,message,sent_datetime,first) VALUES ('".addslashes($item["username"])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW(),'Y')");
							$this->lastSentTime = time();
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
			$this->savelog("Already send first message to profile: ".$item['username']);
			$return = true;
		}
		return $return;
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
				//Go to profile page				
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
					$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=messenger&what=newMessage&user=".$item["username"], "	http://www.travesta.de/index.php/community.html?do=profiles&what=profil&user=".$item["username"], $cookiePath);

					$message_arr = array(
						"SID" => $this->sid,
						"do" => "messenger",
						"reid" => "",
						"what" => "sendMessage",
						"user" => $item["username"],
						"ueberschrift" => utf8_decode($subject),
						"size" => "",	
						"face" => "",	
						"color" => "",	
						"text" => utf8_decode($message)
					);


					if(time() < ($this->lastSentTime + $this->messageSendingInterval)){
						$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
					}

					$this->savelog("Sending message to ".$item['username']);
					if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
					{				
						$headers = array("Content-Type: application/x-www-form-urlencoded");		
						$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html", "	http://www.travesta.de/index.php/community.html?do=messenger&what=newMessage&user=".$item["username"], $cookiePath, $message_arr);
						file_put_contents("sending/pm-".$username."-".$item['username'].".html",$content);

						if(strpos($content, 'Deine Nachricht wurde verschickt') !== false)
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

	private function isAlreadySentFirst($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM ".$this->databaseName."_sent_messages WHERE to_username='".$username."' and first='Y'");

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
		$this->savelog("go to delete outbox message.");
		$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html?do=messenger&what=outboxmsg", "http://www.travesta.de/index.php/community.html?do=messenger&what=inboxmsg", $cookiePath);
		
		if($html = str_get_html($content)){
				
			$nodes = $html->find("input[type=checkbox]");

			foreach ($nodes as $node) {	
				$param = array(
					"SID" => $this->sid,
					"ab" => 0,
					"del[]" => $node->value,
					"delete" => "Markierte Nachrichten löschen",
					"do" => "messenger",
					"outbox" => 1,
					"what" => "delMessage"
				);

				$headers = array("Content-Type:application/x-www-form-urlencoded");
				$content = $this->getHTTPContent("http://www.travesta.de/index.php/community.html", "http://www.travesta.de/index.php/community.html?do=messenger&what=outboxmsg", $cookiePath);
				$nodes = $html->find("message id: ".$node->value." has been deleted");
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
