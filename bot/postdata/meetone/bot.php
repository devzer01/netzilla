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
	protected $loginArr = array();
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
	protected $user_id = null;
	
	protected $session_id = null;

	public function bot()
	{
		$this->receiverProfiles = $this->getRecieverProfile();
		$this->userAgent = botutil::getAgentString();
	}
	
	public function hasEmailToCreateAccount()
	{
		$sql = "SELECT COUNT(*) AS cnt FROM email WHERE user NOT IN (SELECT username FROM user_profiles WHERE site_id = " . $this->siteID . ")";
		$result = DBConnect::assoc_query_1D($sql);
		return $result['cnt'];
	}
	
	public function getNextEmailAccount()
	{
		$sql = "SELECT user, password FROM email WHERE user NOT IN (SELECT username FROM user_profiles WHERE site_id = " . $this->siteID . ") LIMIT 1 ";
		return DBConnect::assoc_query_1D($sql);
	}

	private function getRecieverProfile()
	{
		$sql = "SELECT `male_id`, `male_user`, `male_pass`, `female_id`, `female_user`, `female_pass` FROM `sites` WHERE `id`=".$this->siteID;
		return DBConnect::assoc_query_1D($sql);
	}

	public function login()
	{
		$this->setNextLoginIndex();
		$username = $this->loginArr[$this->currentUser][$this->usernameField];
		$password = $this->loginArr[$this->currentUser]['password'];
		$cookiePath = $this->getCookiePath($username);

		$this->savelog("Trying to login with ".$username.".");
		
		$json = json_decode(file_get_contents("http://api.meetone.com/api/phoneapi.php?format=json&service=base&action=apiAuthorization&username=" . $username . "&password=" . $password . "&lang=en"), true);

		if (isset($json['authorization']) && $json['authorization']['result'] == 'true') {
			botutil::profileCount($this->getSiteID(), $username);
			$this->session_id = $json['authorization']['sessionId'];
			$this->savelog("Logged in with profile: ".$username);
			return true;
		} else
		{
			$this->savelog("login failure, please see debug" . print_r($json, true));
			DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE site_id=".$this->siteID." AND username='".$this->loginArr[$this->currentUser][$this->usernameField]."'");
			$this->command['profile_banned'] = TRUE;
			return false;
		}
		
		return false;
	}

	public function logout()
	{
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

	public function savelog($msg)
	{
		$time=date("Y-m-d H:i:s");
		$scrollScript = "<script>window.scrollTo(0, document.body.scrollHeight);</script>";

		echo "[$time] $msg                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          <br/>\r\n".$scrollScript;
		ob_end_flush();
		ob_flush();
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
				echo "Exiting Stop Command";
				exit;
			}
		}

		if(file_exists("logs/".$this->commandID."_run_count.log"))
		{
			$txt_count = file_get_contents("logs/".$this->commandID."_run_count.log");
			if($txt_count != $this->runCount)
			{
				echo "Exiting Run Count";
				exit;
			}
		}
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