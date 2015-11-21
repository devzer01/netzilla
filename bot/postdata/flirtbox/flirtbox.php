<?php
require_once('bot.php');
require_once 'simple_html_dom.php';

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

class flirtbox extends bot
{
	private $target = 'Male';
	public $subject = '';
	public $message = '';
	public $newMessage = true;
	public $messagesPart = array();
	public $messagesPartTemp = array();
	public $postMessageTemp = array();
	public $preMessageTemp = array();
	public $name = array();
	
	public function flirtbox($post)
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
						// "username" => "annissamarie",
						// "password" => "loveTotext15"
						'username' => 'GertieGB02',
                    	'password' => '0stirNbNd2'
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
				"age_group" => 1,
				//"gender" => "m",
				"gender" => 1,
				"targettedGender" => 2,
				"country" => 70,
				//"action" => "check"
				"action" => "send",
				'online' => 0
			);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 10;
		}

		$this->usernameField = "uname";
		$this->loginURL = "http://www.flirtbox.co.uk/modules.php?name=Your_Account";
		$this->loginRefererURL = "http://www.flirtbox.co.uk/login.php";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.flirtbox.co.uk/logout.php";
		$this->indexURL = "http://www.flirtbox.co.uk/";
		$this->indexURLLoggedInKeyword = "LOGOUT";
		$this->searchURL = "http://www.flirtbox.co.uk/quickSearch.php";
		$this->searchRefererURL = "http://www.flirtbox.co.uk/";
		$this->searchResultsPerPage = 35;
		$this->profileURL = "http://www.flirtbox.co.uk/";
		$this->sendMessagePageURL = "http://www.flirtbox.co.uk/reply.php?send=1&uname=";
		$this->sendMessageURL = "http://www.flirtbox.co.uk/reply.php";
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
		
		if($this->command['gender'] == '2') {
			$this->target = 'Female';
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
		$this->loginArr = array();
		foreach($users as $user)
		{
			$login_arr = array(	
				"uname" => $user['username'],
				"pass" => $user['password'],
				"op" => "login"
			);
			
			array_push($this->loginArr, $login_arr);
		}
	}
	
	public function sendMessage($item, $username, $cookiePath) {
		///reserve this user, so no other bot can send msg to
		$this->savelog("Reserving profile to send message: ".$item['username']);
		if($this->reserveUser($item['username']))
		{
			// Go to profile page
			$this->savelog("Go to profile page: ".$item['username']);
			$content = $this->getHTTPContent($this->profileURL.$item['username'], $this->searchURL, $cookiePath);

			$this->sleep(5);

			/***********************************/
			/***** Go to send message page *****/
			/***********************************/
			$this->savelog("Go to send message page: ".$item['username']);
			$content = $this->getHTTPContent($this->sendMessagePageURL.$item['username'], $this->profileURL.$item['username'], $cookiePath);
			$this->sleep(5);

			/************************/
			/***** Send message *****/
			/************************/
			//RANDOM SUBJECT AND MESSAGE
			$text = botutil::getMessageText($this, $this->target, 'EN');
            $subject = $text['subject'];
            $message = $text['message'];
			$this->savelog("Message is : ".$message);
			
			$message_arr = array(
				"to_user" => $item['username'],
				"subject" => $subject,
				"message" => $message,
				"image" => "icon2.gif",
				"smile" => "on",
				"msg_id" => "",
				"submit" => "submit"
			);
			
			if(time() < ($this->lastSentTime + $this->messageSendingInterval))
				$this->sleep(($this->lastSentTime + $this->messageSendingInterval)-time());
			$this->savelog("Sending message to ".$item['username']);
			$random = "0.".str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT).str_pad(rand(0,9999999), 7, "0", STR_PAD_LEFT);
			
			if(!$this->isAlreadySent($item['username']))
			{
				$content = $this->getHTTPContent($this->sendMessageURL, $this->sendMessagePageURL.$item['username'], $cookiePath, $message_arr);
				// file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
				
				// Message has been sent! 
				if(strpos($content, "Message has been sent"))
				{
					DBConnect::execute_q("INSERT INTO flirtbox_sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".addslashes($username)."','".addslashes($subject)."','".addslashes($message)."',NOW())");
					$this->savelog("Sending message completed.");
					$this->lastSentTime = time();
				}
				else
				{
					$this->savelog("Sending message failed.");
				}
			}
			else
			{
				$this->cancelReservedUser($item['username']);
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
	
	public function getOnline() {
		$this->savelog("Job criterias => [Search Online Users] gender: ".$this->command['gender']);
		$this->savelog("Job started.");
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$page=1;
		$list=array();
		$first_username = '';
		$gender = (($this->command['gender'] == '2') ? 'f' : 'm');
		do
		{
			$content = $this->getHTTPContent('http://www.flirtbox.co.uk/whoison.php?resultpage='.$page.'&gender='.$gender, $this->indexURL, $cookiePath);
			$list = $this->getMembersFromSearchResult($username, $page, $content);
			if(is_array($list))
			{
				$this->savelog("Found ".count($list)." member(s)");
				if(count($list))
				{
					if($list[0]['username'] == $first_username)
					{
						$list = array();
						break;
					}
					if($page == 1)
					{
						$first_username = $list[0]['username'];
					}
					$this->sleep(5);
					foreach($list as $item)
					{
						$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
						//If in running time period
						if($sleep_time==0)
						{
							// If not already sent
							if(!$this->isAlreadySent($item['username']))
							{
								$this->sendMessage($item, $username, $cookiePath);
							}
							else
							{
								$this->savelog("Already send message to profile: ".$item['username']);
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
			$page++;
		}
		while(count($list)>=$this->searchResultsPerPage);
		return true;
	}
	
	public function work()
	{
		
		if($this->command['online'] == 1) {
			$this->getOnline();
		} else {
			$this->savelog("Job criterias => gender: ".$this->command['gender'].", country: ".$this->command['country'].", age_group: ".$this->command['age_group'].", start time ".$this->command['start_h'].":".$this->command['start_m'].", end time ".$this->command['end_h'].":".$this->command['end_m']);
			$this->savelog("Job started.");
			$username = $this->loginArr[$this->currentUser][$this->usernameField];
			$cookiePath = $this->getCookiePath($username);
	
			/*******************************/
			/****** Go to search page ******/
			/*******************************/
			$this->savelog("Go to SEARCH page.");
			$content = $this->getHTTPContent($this->searchRefererURL, $this->loginRefererURL, $cookiePath);
			$this->sleep(5);
	
			$page=1;
			$list=array();
			$first_username = '';
			do
			{
				/******************/
				/***** search *****/
				/******************/
				$search_arr = array(
					"age_group" => $this->command['age_group'],
					"gender" => $this->command['gender'],
					"targettedGender" => (($this->command['gender'] == 2 ) ? 1 : 2),
					"country" => $this->command['country'],
					"location" => "",
					"occupation" => "",
					"username" => "",
					"x" => rand(10,20),
					"y" => rand(20,30),
					"op" => "list",
					"resultpage" => $page
				);
	
				$searchData = http_build_query($search_arr);
	
				$this->savelog("Search for gender: ".$this->command['gender'].", country: ".$this->command['country'].", age_group: ".$this->command['age_group'].", page ".$page);
				$content = $this->getHTTPContent($this->searchURL."?".$searchData, $this->searchRefererURL, $cookiePath);
				file_put_contents("search/".$username."-search-".$page.".html",$content);
	
				/***********************************************/
				/***** Extract profiles from search result *****/
				/***********************************************/
				$list = $this->getMembersFromSearchResult($username, $page, $content);
	
				if(is_array($list))
				{
					$this->savelog("Found ".count($list)." member(s)");
					if(count($list))
					{
						if($list[0]['username'] == $first_username)
						{
							$list = array();
							break;
						}
						if($page == 1)
						{
							$first_username = $list[0]['username'];
						}
						$this->sleep(5);
						foreach($list as $item)
						{
							$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
							//If in running time period
							if($sleep_time==0)
							{
								// If not already sent
								if(!$this->isAlreadySent($item['username']))
								{
									$this->sendMessage($item, $username, $cookiePath);
								}
								else
								{
									$this->savelog("Already send message to profile: ".$item['username']);
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
	
			$this->savelog("Job completed.");
			return true;
		}
	}

	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		
		if($this->command['online'] == 1) {
			if(!empty($content)) {
				
				$html = str_get_html($content);
				if(!empty($html->find('div.with-popup',0))){
					foreach($html->find('div.with-popup') as $div) {
						$list[] = array(
							'username' => $div->find('img.flag',0)->alt,
							'pic' => $div->find('img.flag',0)->src,
							// 'age' => ((!empty($div->find('span.male',0))) ? $div->find('span.male',0)->plaintext : $div->find('span.female',0)->plaintext )
						);
					}	
				}
			}
		} else {
			$content = substr($content,strpos($content,'<div class="holder with-popup">'));
			$content = substr($content,0,strrpos($content,'<span class="bottom-bg">'));
			$content = substr($content,0,strrpos($content,'</div>'));
			$content = substr($content,0,strrpos($content,'</div>'));
			$content = str_replace("’","'",$content);
			$content = str_replace("“","\"",$content);
			$content = str_replace("´","\"",$content);
	
			// Make it to XML object
			$parser = $this->convertToXML($username, $page, $content);
	
			// Check if it's correct result
			if(isset($parser->document->div))
			{
				foreach($parser->document->div as $member)
				{
					if(isset($member->div[0]->div[0]->a[0]->tagData))
					{
						array_push($list, 
							array(	
								"username"=>$member->div[0]->div[0]->a[0]->tagData,
								"pic"=>isset($member->div[0]->a[0])?str_replace("userpics/small/","userpics/profile/",$member->div[0]->a[0]->img[0]->tagAttrs['src']):"",
								"age"=>$member->div[1]->span[0]->tagData
							)
						);
					}
				}
			}
		}
		var_dump($list);
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

	private function isAlreadySent($username)
	{
		$sent = DBConnect::retrieve_value("SELECT count(id) FROM flirtbox_sent_messages WHERE to_username='".$username."'");

		if($sent)
			return true;
		else
			return false;
	}

	private function reserveUser($username)
	{
		$server = DBConnect::retrieve_value("SELECT server FROM flirtbox_reservation WHERE username='".$username."'");

		if(!$server)
		{
			$sql = "INSERT INTO flirtbox_reservation (username, server, created_datetime) VALUES ('".addslashes($username)."',".$this->botID.",NOW())";
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
		DBConnect::execute_q("DELETE FROM flirtbox_reservation WHERE username='".$username."' AND server=".$this->botID);
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
	
	public function checkTargetProfile($profile = '') {
		
		// $username = $this->loginArr[$this->currentUser][$this->usernameField];
		// $cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.flirtbox.co.uk/'.$profile, $this->indexURL);
			// Ich konnte leider keine passenden Einträge finden!
			if(!strpos($content,'Error 404')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}
?>