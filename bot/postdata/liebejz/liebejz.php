<?php
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

class liebejz extends bot 
{
	private $_table_prefix = 'liebejz_';
	private $_searchResultId = 0;
	public $rootDomain = 'http://www.liebejz.de';
	public $sendMessageActionURL = 'http://www.mv-spion.de/messages/messenger/send';
	private $nextSearchPage = '';
	
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
													'username' => 'devzer01',
													'password' => 'x2c4eva'
													),
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
									"start_h" => 8,
									"start_m" => 00,
									"end_h" => 23,
									"end_m" => 00,
									"messages_per_hour" => 30,
									"country_code" => '14',
									"plz" => '53225',
									"action" => "send"
								);
			$commandID = 1;
			$runCount = 1;
			$botID = 1;
			$siteID = 100;
		}
		
		$this->usernameField = 'username';
		$this->loginURL = "http://www.liebejz.de/default.aspx";
		$this->loginActionURL = "http://www.liebejz.de/default.aspx";
		$this->loginRefererURL = "http://www.liebejz.de/default.aspx";
		$this->loginRetry = 3;
		$this->logoutURL = "http://www.liebejz.de/PageTemplates/logout.aspx";
		$this->indexURL = "http://www.liebejz.de/PageTemplates/Start.aspx";
		$this->indexURLLoggedInKeyword = 'Logout';
		$this->searchURL = "http://www.fischkopf.de/index.php?page=suchen&sres=1";
		$this->searchActionURL = 'http://www.fischkopf.de/index.php?page=suchen';
		$this->searchRefererURL = "http://www.fischkopf.de/index.php?page=suchen";
		$this->searchResultsPerPage = 10;
		$this->profileURL = "http://www.pof.de/de_viewprofile.aspx?profile_id=";
		$this->sendMessagePageURL = "";
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
		$this->count_msg = 0;
		
		
		$this->zipcodes = array(
				"14" => array(
						"short" => array(
								"01067", "02625", "04315", "08525", "12621", "18069", "18437", "20253", "23566", "24837", "28213", "30179", "50937", "52066", "60528", "69126", "81829", "85051", "88212", "99089"
						),
						"long" => array(
								"01067", "01587", "02625", "02906", "02977", "03044", "03238", "04288", "04315", "06886", "07545", "08525", "09119", "12621", "15236", "16278", "16909", "17034", "17291", "17358", "17489", "18069", "18437", "19053", "19322", "20253", "23566", "23758", "23966", "24534", "24782", "24837", "25524", "25746", "25813", "25899", "27474", "28213", "30179", "33098", "33332", "34121", "35039", "36100", "36251", "39108", "39539", "41239", "44147", "47906", "48151", "49076", "50937", "52066", "52525", "53518", "53937", "54292", "55246", "55487", "56075", "57076", "60528", "63743", "66121", "69126", "70188", "74076", "76187", "77654", "78628", "79104", "81829", "82362", "83024", "84453", "85051", "87437", "88212", "89077", "90408", "90425", "92637", "93053", "94469", "95326", "96450", "97074", "97421", "98529", "99089"
						)
				),
				"47" => array(
						"short" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						),
						"long" => array(
								"1010", "4040", "5020", "6020", "7000", "8010", "9020"
						)
				),
				"56" => array(
						"short" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						),
						"long" => array(
								"8045", "6300", "9000", "3150", "8200", "6023", "9217"
						)
				)
		);
		
		//=== Set Proxy ===
		if(empty($this->command['proxy_type'])) {
			$this->command['proxy_type'] = 1;
		}
		$this->setProxy();
		//=== End of Set Proxy ===

		$target = "Male";
		if($this->command['gender'] == 'w'){
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
				"username" => $user['username'],
				"password" => $user['password'],
			);
			array_push($this->loginArr, $login_arr);
		}
	}
	
	public function getTodaySentMessageCount()
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$sql = "SELECT COUNT(*) FROM " . $this->_table_prefix . "sent_messages WHERE from_username = '" . $username . "' AND DATE(sent_datetime) = DATE(NOW()) ";
		return DBConnect::retrieve_value($sql);
	}

	public function work()
	{
		
		$plz = $this->zipcodes[$this->command['country_code']]['long'];
		if($key = array_search($this->command['start_plz'],$plz))
		{
			$plz = array_slice($plz, $key);
		}
		
	
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$count = $this->getTodaySentMessageCount();
			
		if ($count === 20) {
			$this->savelog("Bot has sent more than 20 messages today, selecting next one");
			$this->logout();
			$this->getNewProfile();
			$this->savelog("Loading new profile");
			$this->login();
		}
		
		foreach ($plz as $splz) {
		
			$this->savelog("Going to user profile and changing plz " . $splz);
			
			if (!$this->changePlz($this->command['country_code'], $splz)) {
				$this->savelog("invalid plz provided, please correct and re-run");
				return false;
			}
			
			$this->savelog("Going to flirt list and send message to 20 users.");
			
			$content = $this->getHTTPContent('http://www.liebejz.de/PageTemplates/Start.aspx', 'http://www.liebejz.de/PageTemplates/Start.aspx', $cookiePath);
			$html = str_get_html($content);
			$aspattr = $this->getAspAttr($html);
			
			$attr['layout$ctl_newmessages$ctl00$txt_Nick'] = '';
			$attr['layout$ctrl_QuickSearch$ddl_Country'] = $this->command['country_code'];
			$attr['layout$ctrl_QuickSearch$hid_Distance'] = 200;
			$attr['layout$ctrl_QuickSearch$ddl_Status'] = 0;
			$attr['layout$ctrl_QuickSearch$ddl_Age']  = $this->command['age_group'];
			$attr['sessid'] = '';
			$attr['LastFocusedElement'] = '';
			$attr['LastFocusedElementTemp'] = '';
			
			$post = array_merge($aspattr, $attr);
			
			$content = $this->getHTTPContent('http://www.liebejz.de/PageTemplates/Start.aspx', 'http://www.liebejz.de/PageTemplates/Start.aspx', $cookiePath, $post);
			
			$html_list = str_get_html($content);
			
			$next_page = true;
			
			do {
				$users = array();
				
				if (trim($this->command['test_username']) != '') {
					$searchUser = 'http://www.liebejz.de/PageTemplates/NickSearchResult.aspx?nick=' . $this->command['test_username'];
					$content = $this->getHTTPContent($searchUser, 'http://www.liebejz.de/PageTemplates/Start.aspx', $cookiePath);
					$html_list = str_get_html($content);
				}
				
				foreach ($html_list->find("div.userdisplay") as $human) {
					$link = $human->find("div", 0)->find("a", 0)->href;
					$link = preg_replace("/javascript:open_vksearch/", "", $link);
					$link = preg_replace("/[()]/", "", $link);
					list($user_id, $rest) = explode(",", $link, 2);
					$user_id = preg_replace("/'/", "", $user_id);
						
					$username = $human->find("div", 1)->find("a.linkbig", 0)->plaintext;
					$link = 'http://www.liebejz.de/PageTemplates/VCard.aspx?user_vk=' . $user_id . '&age=&sex=&pictureprofile=';
						
					$users[] = array('link' => $link, 'userid' => $user_id, 'username' => $username);
				}
				
				foreach ($users as $item) {
					
					$count = $this->getTodaySentMessageCount();
					
					if ($count >= 20) {
						$this->logout();
						$this->getNewProfile();
						$this->savelog("Loading new profile");
						$this->login();
					}
						
					$sleep_time = $this->checkRunningTime($this->command['start_h'],$this->command['start_m'],$this->command['end_h'],$this->command['end_m']);
						
					//If in runnig time period
					if($sleep_time==0)
					{
						// If not already sent
						if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
						{
							///reserve this user, so no other bot can send msg to
							$this->savelog("Reserving profile to send message: ".$item['username']);
							if($this->reserveUser($item['username']))
							{
								// Go to profile page
								$this->savelog("Go to profile page: ".$item['username'] . " -- " . $item['link']);
								$content = $this->getHTTPContent($item['link'], 'http://www.liebejz.de/PageTemplates/gallery.aspx', $cookiePath);
				
								$html = str_get_html($content);
								
								$aspattr = $this->getAspAttr($html);
								$header_attr = $this->getHeaderAttr();
								
								$attr['layout$ctl02$uc_ShowUserContact$btn_VCardSendMessage.x'] = 110;
								$attr['layout$ctl02$uc_ShowUserContact$btn_VCardSendMessage.y'] = 14;
								$attr['layout$ctl02$hid_PPMSUser'] = 'False';
								
								$post = array_merge($aspattr, $header_attr, $attr);
								
								$interval = rand(1,4);
								$this->savelog("Waiting for " . $interval . " seconds before clicking send message");
								sleep($interval);
								
								$content = $this->getHTTPContent($item['link'], $item['link'], $cookiePath, $post);
									
								$html = str_get_html($content);
								
								$aspattr = $this->getAspAttr($html);
								
								
								//RANDOM SUBJECT AND MESSAGE
								$this->savelog("Random new subject and message");
								$this->currentSubject = rand(0,count($this->command['messages'])-1);
								$this->currentMessage = rand(0,count($this->_message)-1);
									
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
									
								if(time() < ($this->lastSentTime + $this->messageSendingInterval)) {
									$sleep_time = ($this->lastSentTime + $this->messageSendingInterval)-time();
									$this->savelog("Sleeping for [" . $sleep_time . "] second(s)");
									$this->sleep($sleeping_time);
								}
				
								$this->savelog("Sending message to ".$item['username']);
									
								if(!$this->isAlreadySent($item['username']) || $enableMoreThanOneMessage)
								{
				
									$attr['hidLastIcon'] = '';
									$attr['layout$ctl02$uc_VCardSendMessage$txa_Message'] = $message;
									$attr['countAnzeige'] = 1000 - strlen($message);
									$attr['layout$ctl02$uc_VCardSendMessage$MessageSend.x'] = '-59';
									$attr['layout$ctl02$uc_VCardSendMessage$MessageSend.y'] = '9';
									$attr['layout$ctl02$hid_PPMSUser'] = 'False';
	
									$post = array_merge($aspattr, $this->getHeaderAttr(), $attr);
									$post['LastFocusedElementTemp'] = 'layout$ctl02$uc_VCardSendMessage$txa_Message';
				
									$content = $this->getHTTPContent($item['link'], $item['link'], $cookiePath, $post);
				
									file_put_contents("sending/pm-".$username."-".$item['username']."-".$item['username'].".html",$content);
				
									if(preg_match("/Die Nachricht wurde erfolgreich/", $content))
									{
										$chat_disabled = FALSE;
										
										$this->count_msg++;
										
										DBConnect::execute_q("INSERT INTO ".$this->_table_prefix."sent_messages (to_username,from_username,subject,message,sent_datetime) VALUES ('".addslashes($item['username'])."','".$username."','".addslashes($subject)."','".addslashes($message)."',NOW())");
										$this->savelog("Sending message completed.");
										$this->lastSentTime = time();
										
										$this->savelog("Sleeping for 5 minutes before sending next message");
										$this->sleep(60 * 5);
									}
									else
									{
										$this->savelog("<textarea>" . urlencode($content) . "</textarea>");
										$this->savelog("Sending message failed.");
									}
								}
								else
								{
									$this->savelog("Sending message failed. This profile reserved by other bot: ".$item['username']);
								}
								$this->cancelReservedUser($item['username']);
								$this->sleep(2);
								
								if($this->command['logout_after_sent'] == "Y"){
									if($this->count_msg >= $this->command['messages_logout']){
										$this->logout();
										$this->getNewProfile();
										$this->savelog("Loading new profile");
										$this->login();
									}
								}
								
							}
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
					}
				} //end foreach
				
				$aspattr = $this->getAspAttr($html_list);
				$header = $this->getHeaderAttr();
				
				$attr = array();
				
				$post_attr = array_merge($aspattr, $header, $attr);
				$post_attr['__EVENTTARGET'] = 'layout$ctl02$btn_NextTop';
				
				$content = $this->getHTTPContent('http://www.liebejz.de/PageTemplates/gallery.aspx', 'http://www.liebejz.de/PageTemplates/gallery.aspx', $post_attr);
	
				if ($content == '') {
					$next_page = false;
				} else {
					$html_list = str_get_html($content);
				}
				
			} while ($next_page != false);
			
		} // end plz loop
						
		$this->savelog("Job completed.");
		return true;
	}
	
	public function getHeaderAttr()
	{
		$attr['layout$ctl_newmessages$ctl00$txt_Nick'] = '';
		$attr['layout$ctrl_QuickSearch$ddl_Country'] = 14;
		$attr['layout$ctrl_QuickSearch$hid_Distance'] = 200;
		$attr['layout$ctrl_QuickSearch$ddl_Status'] = 0;
		$attr['layout$ctrl_QuickSearch$ddl_Age'] = '22_30';
		$attr['sessid'] = '';
		$attr['LastFocusedElement'] = '';
		$attr['LastFocusedElementTemp'] = '';
		
		return $attr;
	}

	public function changePlz($country_code, $plz)
	{
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		$content = $this->getHTTPContent('http://www.liebejz.de/PageTemplates/RegisterUpdate.aspx', 'http://www.liebejz.de/PageTemplates/Start.aspx', $cookiePath);
		
		$html = str_get_html($content);
		$aspattr = $this->getAspAttr($html);
		
		$action = 'RegisterUpdate.aspx';
		$attr['layout$ctl02$email'] = $html->find('#layout_ctl02_email', 0)->value;
		$attr['layout$ctl02$day'] = $html->find('#layout_ctl02_day', 0)->find('option[selected=selected]', 0)->value;
		$attr['layout$ctl02$month'] = $html->find('#layout_ctl02_month', 0)->find('option[selected=selected]', 0)->value;
		$attr['layout$ctl02$year'] = $html->find('#layout_ctl02_year', 0)->find('option[selected=selected]', 0)->value;		
		
		$headers = array(
			'X-Prototype-Version:1.6.1',
			'X-Requested-With:XMLHttpRequest'
		);
		
		$rand = 0 + mt_rand() / mt_getrandmax() * (1 - 0);
		
		$geo_attr = array('zip' => $plz, 'test' => $rand, '_' => '');
		
		$ajax_lookup_url = "http://www.liebejz.de/Ajax/Region.aspx";
		$this->savelog("Geo Attr " . var_dump($geo_attr));
		list($header, $content) = $this->getHTTPContent($ajax_lookup_url, 'http://www.liebejz.de/PageTemplates/RegisterUpdate.aspx', $cookiePath, $geo_attr, $headers, true);
		
		$this->savelog("Header Received " . print_r($header, true));
		
		$json = preg_replace("/'/", '"', $header['X-JSON']);
		
		$geo = json_decode($json);
		
		if (trim($geo->city) == '') return false;
		
		$attr['layout$ctl02$plz'] = $plz;
		$attr['layout$ctl02$ort'] = $geo->city;
		$attr['layout$ctl02$land_id'] = $geo->landID;
		$attr['layout$ctl02$region_id'] = $geo->regionID;
		
		
		$attr['layout$ctl02$btn_registerupdate.x'] = 123;
		$attr['layout$ctl02$btn_registerupdate.y'] = 37;
		$attr['layout$ctl02$rbl_messageemail'] = 1;
		$attr['layout$ctl02$rbl_email'] = 'false';
		$attr['layout$ctl02$coordinates'] = 'undefined';
		$attr['layout$ctl02$rbl_access'] = 'true';
		$attr['layout$ctl02$sternzeichen_id'] = 8;
		
		$header_attr = $this->getHeaderAttr();
		
		$post = array_merge($aspattr, $attr, $header_attr);
		
		$this->getHTTPContent("http://www.liebejz.de/PageTemplates/" . $action, "http://www.liebejz.de/PageTemplates/" . $action, $cookiePath, $post);
		
		return true;
	}
	
	/**
		getMembersFromSearchResult
	**/
	private function getMembersFromSearchResult($username, $page, $content)
	{
		$list = array();
		$html = str_get_html($content);
		if(!empty($html)) {
			foreach($html->find('div.kategoriediv') as $div) {
				$list[] = array(
					'username' => $div->find("a.black",0)->href,
					'uid' => $div->find("a.black",0)->href,
					'link' => $div->find("a.black",0)->href
				);
			}
		}
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

	public function getNewProfile() {
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$this->loginArr = array();
		

		$this->savelog("Site ID : ". $this->siteID);
		$fetch[0] = botutil::getNewProfile($this->siteID, $username, $this->command);
		foreach ($fetch as $f) {
			$this->addLoginData(array(
					array(
							'username' => $f['username'],
							'password' => $f['password']
					)
			));
		}
		
		
		$this->savelog('New profile account is '. $fetch[0]['username']);
		$this->currentUser=0;
	}

	private function json_validate($json, $assoc_array = FALSE)
	{
	    // decode the JSON data
	    $result = json_decode($json, $assoc_array);

	    // switch and check possible JSON errors
	    switch (json_last_error()) {
	        case JSON_ERROR_NONE:
	            $error = ''; // JSON is valid
	            break;
	        case JSON_ERROR_DEPTH:
	            $error = 'Maximum stack depth exceeded.';
	            break;
	        case JSON_ERROR_STATE_MISMATCH:
	            $error = 'Underflow or the modes mismatch.';
	            break;
	        case JSON_ERROR_CTRL_CHAR:
	            $error = 'Unexpected control character found.';
	            break;
	        case JSON_ERROR_SYNTAX:
	            $error = 'Syntax error, malformed JSON.';
	            break;
	        // only PHP 5.3+
	        case JSON_ERROR_UTF8:
	            $error = 'Malformed UTF-8 characters, possibly incorrectly encoded.';
	            break;
	        default:
	            $error = 'Unknown JSON error occured.';
	            break;
	    }

	    if($error !== '') {
	    	$object = new stdClass();
	    	$object->error = $error;
	        return $object;
	    } else {
	    	return $result;
	    }
	}
	
	public function resetPLZ()
	{
		$this->command['start_plz'] = "00000";
	}
	
	public function checkTargetProfile($profile = '') {
		
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);
		
		if($profile != '') {
			$content = $this->getHTTPContent('http://www.liebejz.de/PageTemplates/NickSearchResult.aspx?nick='.$profile, $this->rootDomain, $cookiePath);
			if(strpos($content,$profile.'</a>')) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
	
}