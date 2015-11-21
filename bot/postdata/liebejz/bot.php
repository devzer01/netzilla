<?php

// error_reporting(0);

if(isset($_GET['command']) && ($_GET['command']=="STOP"))
{
	file_put_contents("logs/".$_GET['id']."_command.log","STOP");
	file_put_contents("logs/".$_GET['id']."_run_count.log","-1");
	exit;
}

require_once("XMLParser.php");
require_once('simple_html_dom.php');
require_once("botutils.php");

class bot
{
	protected $_session_id;
	public $loginArr = array();
	public $logged = FALSE;
	protected $currentUser = -1;
	protected $usernameField = "name";
	protected $indexURL = "";
	protected $indexURLLoggedInKeyword = "Ausloggen";
	protected $loginURL = "";
	protected $loginActionURL;
	protected $loginRefererURL = "";
	protected $loginRetry = "3";
	protected $logoutURL = "";
	protected $searchURL = "";
	protected $searchRefererURL = "";
	protected $searchResultsPerPage = 0;
	protected $profileURL = "";
	protected $sendMessageURL = "";
	public $command = array();
	protected $commandID = "";
	protected $runCount = 0;
	protected $siteID = 0;
	protected $botID = 1;
	protected $currentSubject = 0;
	protected $currentMessage = 0;
	protected $proxy_ip = "127.0.0.1";
	protected $proxy_port = "9050";
	protected $proxy_control_port = "9051";
	protected $proxy_type;
	protected $userAgent = "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.168 Safari/535.19";
	protected $lastSentTime = 0;
	protected $messageSendingInterval = 0;
	protected $receiverProfiles = array();

	public function bot()
	{
		$this->receiverProfiles = $this->getRecieverProfile();
		$this->userAgent = botutil::getAgentString();
	}

	private function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID;
		return DBConnect::assoc_query_1D($sql);
	}

	protected function setNextLoginIndex()
	{
		if($this->command["login_by"] != 2){
			$this->currentUser++;
		}
		
		$avalableIndexes = array_keys($this->loginArr);
		sort($avalableIndexes);

		if(count($this->loginArr) >= $this->currentUser)
		{
			return true;
		}
		else
		{
			foreach ($avalableIndexes as $a)
			{
				if ($a >= $this->currentUser)
				{
					$this->currentUser = $a;
					return true;
				}
			}
			$this->currentUser = min($avalableIndexes);
			return true;
		}
	}

	public function login()
	{
		$this->userAgent = botutil::getAgentString();
		$this->setNextLoginIndex();
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		$this->savelog("Trying to login with ".$username.".");
		
		if(!($this->isLoggedIn($username)))
		{
			$this->savelog("This profile: ".$username." is not logged in.");
			// count try to login
			for($count_login=1; $count_login<=$this->loginRetry; $count_login++)
			{
				if($this->command["proxy_type"] == 1){
					
					if($this->tor_new_identity($this->proxy_ip,$this->proxy_control_port,'bot')){
						$this->savelog("New Tor Identity request completed.");
					}else{
						$this->savelog("New Tor Identity request failed.");
					}

				}
				$this->savelog("Logging in.");
				
				
				$this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath);
				$content = $this->getHTTPContent($this->loginURL, $this->loginRefererURL, $cookiePath, NULL);
				
				if (trim($content) == '') {
					$this->savelog("Timeout Occured, no data received");
					return false;	
				}
				
				$html = str_get_html($content);
				
				//TODO: this can be placed under a post hook interface which each bot need to implement, nick
				$aspattr = $this->getAspAttr($html);
				
				$attr['layout$uc_HeaderDefault$uc_Login$nick'] = $this->loginArr[$this->currentUser]['username'];
				$attr['layout$uc_HeaderDefault$uc_Login$password'] = $this->loginArr[$this->currentUser]['password'];
				$attr['layout$uc_HeaderDefault$uc_Login$btn_Login.x'] = 42;
				$attr['layout$uc_HeaderDefault$uc_Login$btn_Login.y'] = 34;
				$attr['layout$ctl_QuickSearchBox$ddl_UserSex'] = 4;
				$attr['layout$ctl_QuickSearchBox$ddl_Age'] = 0;
				$attr['LastFocusedElement'] = '';
				$attr['LastFocusedElementTemp'] = '';
				
				$attr = array_merge($aspattr, $attr);
				
				$content = $this->getHTTPContent($this->loginActionURL, $this->loginRefererURL, $cookiePath, $attr);

				file_put_contents("login/".$username."-".date("YmdHis").".html",$content);

				
				if(empty($content))
				{
					
					$this->savelog("No response from server.");
					$this->loginRetry++;
				}
				elseif(!($this->isLoggedIn($username)))
				{
					if(!empty($error)) {
						$this->savelog('Log in failed message is '.$error);
					}
					$this->savelog("Log in failed with profile: ".$username);
					$this->savelog("Log in failed $count_login times.");

					if($count_login>($this->loginRetry-1))
					{
						$this->savelog("User ".$username." tried to login ".$count_login." times. This username would be deleted.");
						DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE site_id=".$this->siteID." AND username='".$this->loginArr[$this->currentUser][$this->usernameField]."'");
						$this->command['profile_banned'] = TRUE;
						return false;
					}
					else
					{
						$sleep_time = 120; // 2 mins
						if($this->getAction()=='check' || $_GET['action'] == 'check') {
							$sleep_time = 3;
						}
						$this->savelog("Sleep after log in failed for ". $this->secondToTextTime($sleep_time));
						$this->sleep($sleep_time);
					}
				}
				else
				{
					//$this->_session_id = NULL;
					//$this->_session_id = $this->getSessioinId($cookiePath);
					botutil::profileCount($this->getSiteID(), $username);
					$this->savelog("Logged in with profile: ".$username);
					$this->logged = TRUE;
					return true;
				}
			}
		}
		else
		{
			//$this->_session_id = NULL;
			//$this->_session_id = $this->getSessioinId($cookiePath);
			$this->savelog("This profile: ".$username." has been logged in.");
			$this->logged = TRUE;
			return true;
		}
	}

	protected function getAspAttr($html)
	{
		$attr = array();
		$attr['__EVENTTARGET'] = $html->find("#__EVENTTARGET", 0)->value;
		$attr['__EVENTARGUMENT'] = $html->find("#__EVENTARGUMENT", 0)->value;
		$attr['__LASTFOCUS'] = $html->find("#__LASTFOCUS", 0)->value;
		$attr['__VIEWSTATE'] = $html->find("#__VIEWSTATE", 0)->value;
		$attr['__EVENTVALIDATION'] = $html->find("#__EVENTVALIDATION", 0)->value;
		
		return $attr;
	}
	
	public function logout()
	{
		$this->count_msg = 0;
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$cookiePath = $this->getCookiePath($username);

		$this->savelog("Logging out.");
		$content = $this->getHTTPContent($this->logoutURL, $this->loginRefererURL, $cookiePath);
		
		$this->savelog("Logout completed.");
	}

	public function countAvailableUsers()
	{
		return count($this->loginArr);
	}

	protected function getCookiePath($username)
	{
		return dirname($_SERVER['SCRIPT_FILENAME'])."/cookies/".$username.".txt";
	}

	public function returnUsers(){
	
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
			
		if($this->command['login_by'] == 1){
			$result = array();
			$result[0] = botutil::getNewProfile($this->siteID, $username, $this->command);
		}else{
			$sql = "select id, username, password from user_profiles where (site_id='".$this->siteID."') AND (status='true') AND (username='".$username."')";
			$result = DBConnect::assoc_query_2D($sql);
		}
	
		if(count($result) > 0){
			$this->updateCommand($result[0]['username'],$result[0]['password']);
	
			// DBConnect::execute_q("update user_profiles set in_use='true',used='true' where id=".$result[0]['id']);
	
		}else{
			$this->loginArr = null;
		}
	}
	
	public function updateCommand($username, $password){
		$sql = "SELECT * FROM commands WHERE id=".$this->commandID;
		$rs = DBConnect::assoc_query_2D($sql);
	
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $rs[0]['command']);
		$command_post = unserialize($out);
	
		$command_post['profiles'][0]["username"] = $username;
		$command_post['profiles'][0]["password"] = $password;
		$txtserialize = serialize($command_post);
	
		DBConnect::execute_q("update commands set command='".$txtserialize."' where id=".$this->commandID);
	}
	
	protected function isLoggedIn($username)
	{
		$cookiePath = $this->getCookiePath($username);
		$this->savelog("Go to INDEX page to check login status for user: " . $username);
		$content = $this->getHTTPContent($this->indexURL, $this->loginRefererURL, $cookiePath);
		if(empty($content)) {
			$this->savelog("Failed : Tor Connection is slow & CURL Timed out ...");
			$this->setProxy();
			return false;
		} else {
			if(strpos($content, $this->indexURLLoggedInKeyword)!==false)//If logged in
			{
				return true;
			}
			else
			{
				$this->setProxy();
				return false;
			}
		}
	}
	
	public function setProxy(){
		$data = $this->getProxyip();
		if(!empty($data)){
			$this->proxy_ip = $data['proxy_ip'];
			$this->proxy_port = $data['proxy_port'];
			$this->proxy_type = $data['proxy_type'];
			$this->savelog('Using proxy ip '.$data['proxy_ip'].':'.$data['proxy_port']);
		} else {
			$this->savelog('Using bot without Proxy');
		}
	}
	
	public function getProxyip() {
		switch($this->command['proxy_type']){
			case 1:
				return array(
					'proxy_ip' => '127.0.0.1',
					'proxy_port' => 9050,
					'proxy_type' => CURLPROXY_SOCKS5
				);
				break;
			case 2:
				$data = json_decode(file_get_contents('http://192.168.1.253/bot/proxy'));
				return array(
					'proxy_ip' => $data->host,
					'proxy_port' => $data->port,
					'proxy_type' => $data->type
				);
				break;
			default:
				return;
				break;

		}
	}
	
protected function getHTTPContent($url, $referer, $cookiePath=null, $postContent=null, $headers = array(), $return_header = false)
{
		$ch = curl_init();
		if($this->command['proxy_type'] != 3 && !empty($this->proxy_ip) && !empty($this->proxy_port) && !empty($this->proxy_type)){
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy_port);
			curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy_type);
		}
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_REFERER, $referer);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		if ($return_header) {
			curl_setopt($ch, CURLOPT_VERBOSE, 1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
		}
		

		if (!empty($headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}
		
		// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
		if($cookiePath !== null)
		{
			$i = TRUE;
			curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiePath);
			curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiePath);
		}

		if($postContent !== null)
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			
			if (is_array($postContent)) {
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postContent));
			} else {
				curl_setopt($ch, CURLOPT_POSTFIELDS, $postContent);
			}
		}
		
		$content = curl_exec($ch);
		
		if ($return_header) {
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($content, 0, $header_size);
			$body = substr($content, $header_size);
			
			$headers = explode("\n", $header);
			
			$head = array();
			
			foreach ($headers as $header) {
				//if (preg_match("/^HTTP|^Date|^$/", $header)) continue;
				if (strpos($header, ':') === false) continue;
				list($key, $name) = explode(":", $header, 2);
				$head[$key] = $name;
			}
			
			curl_close($ch);
			
			return array($head, $body);
		}
		
		curl_close($ch);

		if(empty($content)) {
			$this->savelog('No Response from url : '.$url.' / Proxy : '.$this->proxy_ip.':'.$this->proxy_port); botutil::setNoResponse($this->commandID, TRUE, $this);
		} else {
			botutil::setNoResponse($this->commandID, FALSE, $this);
		}
		
		return $content;
	}

	public function savelog($msg)
	{
		$time=date("Y-m-d H:i:s");
		$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

		echo "[$time] $msg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <br/>\r\n".$scrollScript;
		ob_end_flush();
		// ob_flush();
		flush();
		ob_start();

		file_put_contents("logs/".$this->commandID."_latest.log","[$time] $msg");
		file_put_contents("logs/".$this->commandID.".log","[$time] $msg\r\n",FILE_APPEND);

		if(file_exists("logs/".$this->commandID."_command.log"))
		{
			$txt_command = file_get_contents("logs/".$this->commandID."_command.log");
			if($txt_command == "STOP")
			{
				file_put_contents("logs/".$this->commandID."_latest.log","[$time] Force stop");
				file_put_contents("logs/".$this->commandID.".log","[$time] Force stop\r\n",FILE_APPEND);
				unlink("logs/".$this->commandID."_command.log");
				exit;
			}
		}

		if(file_exists("logs/".$this->commandID."_run_count.log"))
		{
			$txt_count = file_get_contents("logs/".$this->commandID."_run_count.log");
			if($txt_count != $this->runCount)
			{
				exit;
			}
		}
	}

	protected function tor_new_identity($tor_ip='127.0.0.1', $control_port='9051', $auth_code='bot')
	{
		$fp = fsockopen($tor_ip, $control_port, $errno, $errstr, 30);
		if (!$fp) return false; //can't connect to the control port
		 
		fputs($fp, "AUTHENTICATE \"$auth_code\"\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; //authentication failed

		//send the request to for new identity
		fputs($fp, "signal NEWNYM\r\n");
		$response = fread($fp, 1024);
		list($code, $text) = explode(' ', $response, 2);
		if ($code != '250') return false; //signal failed
		 
		fclose($fp);
		return true;
	}

	public function sleep($time)
	{
		$this->savelog("Sleep for ".$time." second(s)");
		$sleep_time = $time;
		while($sleep_time>=60)
		{
			if($sleep_time!=$time)
			{
				$txt_time = $this->secondToTextTime($sleep_time);
				$this->savelog("Still sleeping [".$txt_time." left]");
			}
			sleep(60);
			$sleep_time-=60;

		}
		if($sleep_time!=$time)
		{
			$txt_time = $this->secondToTextTime($sleep_time);
			$this->savelog("Still sleeping [".$txt_time." left]");
		}
		sleep($sleep_time);
	}

	protected function secondToTextTime($seconds)
	{
		$h = (int)($seconds / 3600);
		$m = (int)(($seconds - $h*3600) / 60);
		$s = (int)($seconds - $h*3600 - $m*60);
		return (($h)?(($h<10)?("0".$h):$h):"00").":".(($m)?(($m<10)?("0".$m):$m):"00").":".(($s)?(($s<10)?("0".$s):$s):"00");
	}

	protected function mb_unserialize($serial_str)
	{ 
		$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
		return unserialize($out); 
	}

	protected function convertToXML($username, $page, $content)
	{
		$search = '/\<!--(.*?)--\>/is';
		$replace = '';
		$content = preg_replace( $search, $replace, $content );
		$tidy_config = array(	'clean' => true,
								'output-xhtml' => true,
								'show-body-only' => true,
								'wrap' => 0,
								'indent' => true,
								'indent-spaces' => 4
                     );
		$content = tidy_parse_string($content, $tidy_config, 'UTF8');
		$content->cleanRepair( );
		$content = str_replace("&nbsp;"," ",$content);

		$xml="<?xml version='1.0' standalone='yes' ?><members>".$content."</members>";
		file_put_contents("xml/xml-".$username."-".$page.".xml",$xml);

		$parser = new XMLParser($xml);
		$parser->Parse();
		return $parser;
	}

	protected function randomText($message)
	{
		$list = array(
						" your "	=> array(" ur "),
						"I'm "		=> array("Im ","im ", "I am ","i'm "),
						" for "		=> array(" 4 "),
						" to "		=> array(" 2 "),
						"."			=> array("..","...","!"),
						"..."		=> array("..","."),
						"you "		=> array("u "),
						"are "		=> array("r "),
						"?"			=> array("?!?"),
						" "			=> array("  ","   "),
						" you're "	=> array(" u're "),
						"!"			=> array(".","..","..."),
						" be "		=> array(" b ")
					);
		if(rand(0,1))
		{
			foreach($list as $key => $words)
			{
				if(rand(0,1))
				{
					$message=str_replace($key,$words[rand(0,count($words)-1)],$message);
				}
			}
		}
		return $message;
	}

	protected function getInputValue($name, $content)
	{
		$content=substr($content,strpos($content,"name=\"".$name."\""));
		$content=str_replace("name=\"".$name."\"","",$content);
		$content=substr($content,strpos($content,"value=\"")+7);
		$content=substr($content,0,strpos($content,"\""));
		return $content;
	}

	public function checkRunningTime($h1,$m1,$h2,$m2)
	{
		if(($h1=="00") && ($m1=="00") && ($h2=="00") && ($m2=="00"))
		{
			return 0;
		}
		else
		{
			$start_time = $h1.":".$m1.":00";
			$end_time = $h2.":".$m2.":00";
			$running_bot_period = 60*60*24; // every day

			if(strtotime($start_time)>strtotime($end_time))
			{
				if((time()<strtotime($start_time)) && (time()<strtotime($end_time)))
				{
					$start_time = strtotime(date('Y-m-d').$start_time)-$running_bot_period;
					$end_time = strtotime(date('Y-m-d').$end_time);
				}
				else
				{
					$start_time = strtotime(date('Y-m-d').$start_time);
					$end_time = strtotime(date('Y-m-d').$end_time)+$running_bot_period;
				}
			}
			else
			{
				$start_time = strtotime(date('Y-m-d').$start_time);
				$end_time = strtotime(date('Y-m-d').$end_time);
			}
			if($end_time<=strtotime(date('Y-m-d H:i:s')))
			{
				$start_time += $running_bot_period;
				$end_time += $running_bot_period;
			}

			$unx_current_time = strtotime(date('Y-m-d H:i:s'));
			$unx_start_day_time = strtotime(date('Y-m-d').'00:00:00');
			$unx_end_day_time = strtotime(date('Y-m-d').'24:00:00');
			$unx_start_time = $start_time; // strtotime();
			$unx_end_time = $end_time; //strtotime();
			
			if($unx_end_time>=$unx_start_time)
			{	//Check if current time is not in start and end time then do sleep time below otherwise follow while loop structure in send-message.php
				//Ex. Sending Time 10:00:00 - 17:00:00 AND Sleeping Time 17:00:01 - 09:59:59
				//Ex. Sending Time 22:00:00 - 04:00:00 AND Sleeping Time 04:00:01 - 21:59:59
				if(!(($unx_start_time<=$unx_current_time) && ($unx_end_time>=$unx_current_time)))
				{
					$sleep_time = ($unx_start_time-$unx_current_time);
					if($sleep_time>0)
					{
						$this->savelog("Start time is : ".date('Y-m-d H:i:s',$start_time));
					}
					elseif($unx_end_time<$unx_current_time)
					{
						$this->savelog("End time at : ".date('Y-m-d H:i:s',$unx_end_time));
						$sleep_time = ($unx_end_day_time-$unx_end_time)+($unx_start_time-$unx_start_day_time);
					}
					return $sleep_time;
				}
			}
		}
	}

	private function getSessioinId($cookiePath) {
		if(!empty($this->_session_id)) {
			return $this->_session_id;
		} else {
			$content = $this->getHTTPContent($this->indexURL, $this->indexURL, $cookiePath, NULL);
			$a = explode('"session_id":"', $content);
			if(empty($a[1])) {
				die("Unable to get Session ID");
			} else {
				$b = explode('"', $a[1]);
				$this->_session_id = trim($b[0]);
			}
			unset($a);
			unset($b);
			return $this->_session_id;
		}
	}
}