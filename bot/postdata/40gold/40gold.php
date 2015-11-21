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

class FourtyGold extends bot
{
	public function fourtygold($post)
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
																	"username" => "___antonR___",
																	"password" => "Mannheim5"
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
									"login_by" => 1,
									"within" => 10,
									"ageStart" => 18,
									"ageEnd" => 79,									
									"msg_type" => "pm",
									"send_test" => 0,															
									"version" => 1,
									"target" => "Male", //Male,Female,Gay,Lesbian
                                    "gender" => "m",
									"search" => 2,
									"city" => "Berlin",
									"distance" => 100,
									"affection" => 0,
									"lookfor" => 0,
									"country" => "Deutschland",
									"picture" => 1,
									"online" => 0,
									"start_page" => 1,
									"state" => "",
									'proxy_type' => 2,
									//"full_msg" => 1,																
									"action" => "send"
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 122;
		}

		if(isset($this->command['inboxLimit']) && is_numeric($this->command['inboxLimit']))
			$this->inboxLimit = $this->command['inboxLimit'];
		else
			$this->inboxLimit = 10;

		$this->databaseName = "40gold";
		$this->userhash = "";

		//Login
			$this->usernameField = "login";
			$this->indexURL = "http://www.40gold.de/extraprofile.php";
			$this->indexURLLoggedInKeyword = "/logout/";
			$this->loginURL = "http://www.40gold.de/login/";
			$this->loginRefererURL = "http://www.40gold.de/";
			$this->loginRetry = 3;
			$this->logoutURL = "http://www.40gold.de/logout/";
		
		//Search
			$this->searchIndex = "http://www.40gold.de/suche/";
			$this->searchURL = "http://www.40gold.de/suche/get1_suche1/";
			$this->searchRefererURL = "http://www.40gold.de/suche/";			
			
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
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===
		
		if($this->command['gender'] == "m"){
			$target = "Male";
		}elseif($this->command['gender'] == "f"){
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
				"login" => $user["username"],
				"password" => $user["password"],
				"username" => $user["username"]
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
		$this->newMessage=false;

		/*******************************/
		/****** Go to search page ******/
		/*******************************/

		$this->savelog("Go to SEARCH page.");  
		
		$content = $this->getHTTPContent($this->searchIndex, "http://www.40gold.de/extraprofile.php", $cookiePath);
	
		if($this->command['online'] == 1){
			
			$search_arr = array(
				"xajax" => "return_".$this->command['gender'],
				"xajaxargs[]" => $this->command['gender'],
				"xajaxr" => time()+(5 * 24 * 60 * 60)
			);
			
			$headers = array("Content-Type:application/x-www-form-urlencoded");
			$content = $this->getHTTPContent("http://www.40gold.de/onlines.php", "http://www.40gold.de/suche/", $cookiePath, $search_arr, $headers);
			
			$result = $this->getOnlineMembersFromSearchResult($content);
			
			if(count($result) > 0){
									
				$this->savelog("Go to Page: ".$pages);
				$this->savelog("There were about ".count($result)." members found.");
				$this->count_msg = 0;

				foreach($result as $mid => $item){

					if($this->command['logout_after_sent'] == "Y"){
						if($this->count_msg >= $this->command['messages_logout']){
							break;
						}
					}

					$this->work_sendMessage($username, $item, $cookiePath);
				}
			}

		}else{
			
			for($age = $this->command['ageStart']; $age<=$this->command['ageEnd']; $age++)
			{
				$pages = 1; $endloop = false;
				$this->count_msg = 0;
				
				while($endloop == false)
				{
					$this->savelog("Search Country: ".$this->command['country'].", Age: ".$age.", Gender: ".$this->command['gender']);

					if($pages == 1){
						
						$cat = ($this->command['gender'] == "m")? 1:2;

						$search_arr = array(
							"age_end" => $age,
							"age_start" => $age,
							"cat" => $cat,
							"cat2" => $this->command['gender'],
							"country" => $this->command['country'],
							"picture" => "nein",
							"searchFAST" => "suchen",
							"sortierung" => 1,
							"ziport" => ""	
						);
						
						$headers = array("Content-Type:application/x-www-form-urlencoded");
						$content = $this->getHTTPContent($this->searchURL, $this->searchRefererURL, $cookiePath, $search_arr, $headers);
					}else{				
						$content = $this->getHTTPContent("http://www.40gold.de/suche/get1_suche1/page".$pages."/", "http://www.40gold.de/suche/get1_suche1/page".($pages-1)."/", $cookiePath);
					}
					
					$result = $this->getMembersFromSearchResult($content);
					
					if(count($result) > 0){
										
						$this->savelog("Go to Page: ".$pages);
						$this->savelog("There were about ".count($result)." members found.");

						foreach($result as $mid => $item){
							//$this->savelog($item["username"]);

							if($this->command['logout_after_sent'] == "Y"){
								if($this->count_msg >= $this->command['messages_logout']){
									break 2;
								}
							}

							//$item = array("username" => "Anna45", "link" => "/profil/Anna45/");
							$this->work_sendMessage($username, $item, $cookiePath);
						}
					}else{
						$rndloop = true;
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
		
		$content = substr($content,strpos($content,'<![CDATA[DeleteSelectBox();]]></cmd>')+36);
			$content = substr($content,0,strpos($content,'</xjx>'));
			
			if($html = str_get_html($content)){
					
				$nodes = $html->find('<cmd n="js">');

				foreach ($nodes as $node) {
					$data = @explode(",",$node->innertext);
					$uname = trim(str_replace("'","",$data[1]));
					array_push($list, array("username" => $uname, "link" => "/profil/".$uname."/"));
				}
			}	
		
		return $list;
	}

	private function getMembersFromSearchResult($content){
		$list = array();
		
		if($html = str_get_html($content)){						
			$nodes = $html->find(".profilbild a.user_pic");
						
			foreach ($nodes as $node) {
				$vowels = array("/profil/","/");
				array_push($list, array("username" => trim(str_replace($vowels,"",$node->href)), "link" => $node->href));
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
		$content = $this->getHTTPContent("http://www.40gold.de".$item["link"], $this->searchIndex, $cookiePath);
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

			$this->savelog("Message is : ".$message);

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
						$content = $this->getHTTPContent("http://www.40gold.de/messages.php?action=write&to=".$item['username'], "http://www.40gold.de/profil/".$item['username']."/", $cookiePath);
						$content = substr($content, strpos($content, '<form action="/nachrichten/senden/" method="POST" name="newmsg" accept-charset="UTF-8">'));
						$content = substr($content, 0, strpos($content, '</form>'));
						
						$param = array();
						
						if($html = str_get_html($content)){		
							
							$nodes = $html->find("input[type=hidden]");										
							foreach ($nodes as $node) {
								$param[$node->name] = $node->value;
							}

							$nodes = $html->find("input[type=text]");										
							foreach ($nodes as $node) {
								$param[$node->name] = utf8_decode($subject);
							}

							$nodes = $html->find("#inhalt");										
							foreach ($nodes as $node) {
								$param[$node->name] = utf8_decode($message);
							}

						}
						
						$param["kommst"] = "auf";						
						$param["submit"] = "Senden";						

						$headers = array("Content-Type:application/x-www-form-urlencoded");
						$content = $this->getHTTPContent("http://www.40gold.de/nachrichten/senden/", "http://www.40gold.de/messages.php?action=write&to=".$item['username'], $cookiePath, $param, $headers);

						$url_log = "URL => http://www.40gold.de/nachrichten/senden/ \nREFERER => http://www.40gold.de/messages.php?action=write&to=".$item['username']."\n";
						file_put_contents("sending/pm-".$username."-".$item["username"].".html",$url_log.$content);

						if(strpos($content, 'Nachricht wurde gesendet') !== false)
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