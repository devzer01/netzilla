#!/usr/bin/php
<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Asia/Bangkok");
require_once "dbconnect.php";
require_once "System/Daemon.php";                 // Include the Class

$sleep_time = 30;
$timeout = 7*60;
$time_adjustment = 7*60*60;
$already_stop = false;

if(!isCli())
{
	include("status.php");
}
else
{
	$options = array(
		'appName' => "bot-daemon",
		'appDir' => dirname(__FILE__),
		'appDescription' => 'Monitor the BOTs status',
		'authorName' => 'Natchanan Sawaengwit',
		'authorEmail' => 'zerocoolz@gmail.com',
		'sysMaxExecutionTime' => '0',
		'sysMaxInputTime' => '0',
		'sysMemoryLimit' => '256M',
		'appRunAsGID' => 0,
		'appRunAsUID' => 0,
	);

	System_Daemon::setOptions($options);
	System_Daemon::start();

	while(1)
	{
		//checkLoginFailForFirstRun();
		checkStopRunningBotsTime();
		checkStop();
		checkSchedule();
		checkRunning($timeout);

		logging("Sleeping for $sleep_time seconds.");
		sleep($sleep_time);
	}
}

function checkLoginFailForFirstRun()
{
	// Get list of running commands
	$list = DBConnect::assoc_query_2D("SELECT c.*,si.name as site_name, se.ip FROM commands c LEFT JOIN sites si ON c.site=si.id LEFT JOIN servers se ON c.server=se.id WHERE c.status='false' AND c.finished_datetime>(NOW()-INTERVAL 5 MINUTE) AND site!=74 AND c.run_count>0");
	if(is_array($list) && count($list)>0)
	{
		logging("Checking if login failed for first run.");
		foreach($list as $command)
		{
			$command_url = "http://".$command['ip']."/postdata/".$command['site_name']."/send-message.php";
			$log_url = "http://".$command['ip']."/postdata/".$command['site_name']."/logs/".$command['id'].".log";

			$data = get_data($log_url);
			if((strpos($data,"Logged in")===false) && (strpos($data,"Force stop")===false) && (strpos($data,"Log in failed")!==false) && (strpos($data,"Get all profiles to check if they are still usable")===false))
			{
				logging("Login failed for first time, command id: ".$command['id']);

				//get next profile
				$sql = "SELECT id, username, password FROM user_profiles WHERE site_id=".$command['site']." AND status='true' AND in_use='false' AND sex='".$command['sex']."' ORDER BY used ASC, id DESC LIMIT 1";
				$profile = DBConnect::assoc_query_1D($sql);

				if(is_array($profile))
				{
					$sql = "UPDATE user_profiles SET in_use='true', used='true' WHERE id=".$profile['id']." LIMIT 1";
					DBConnect::execute_q($sql);

					logging("Get new profile: ".$profile['username']);

					$command_arr = mb_unserialize($command['command']);
					$command_arr['profiles'] = array(array(
													"username"=>$profile['username'],
													"password"=>$profile['password']
													));
					$command_serialize = serialize($command_arr);
					$command['command'] = $command_serialize;

					//put in command
					DBConnect::execute_q("UPDATE commands SET command = '".addslashes($command_serialize)."', status='true', finished_datetime='0000-00-00 00:00:00' WHERE id=".$command['id']);

					//RUN IT
					logging("Running command ID: ".$command['id']);
					logging(print_r($command,true));
					curl_post_async($command_url,$command);
					DBConnect::execute_q("UPDATE commands SET run_count=run_count+1 WHERE id=".$command['id']);
				}
				else
				{
					logging("No profile.");
				}
			}
		}
	}
}

function checkStopRunningBotsTime()
{
	$testing = false;
	global $already_stop;
	global $timeout;

	$time = DBConnect::retrieve_value("SELECT setting_value FROM settings WHERE setting_name='STOP_RUNNING_TIME'");
	logging("All running bots will be stopped at ".$time);

	if(date("H:i") == $time)
	{
		if($already_stop)
		{
			logging("ALREADY STOPPED.");
		}
		else
		{
			$commands = DBConnect::assoc_query_2D("select c.start_time as start, c.site, c.server, c.id as id, c.sex, c.target, c.command as command, s.name as servername, s.ip as ip, s2.name as site_name from commands c left join servers s on c.server = s.id left join sites s2 on c.site = s2.id where c.status='true' and c.run_count>0 and s.enabled_stop_all=1 order by site_name asc, c.sex");

			if(is_array($commands) && count($commands))
			{
				foreach($commands as $command)
				{
					$log_url = "http://".$command['ip']."/postdata/".$command['site_name']."/logs/".$command['id']."_latest.log";

					// check if $command['server'] is running $command['site'];
					logging("ID ".$command["id"].": Checking status: ".$log_url);
					$data = get_data($log_url);
					$data = get_last_modified($data);

					if($data['time'])
						$time = $data['time'];
					else
						$time = 0;
					$diff = time()-$time;
					logging("ID ".$command["id"].": Last modified: ".date("Y-m-d H:i:s",$time));
					logging("ID ".$command["id"].": Different: ".convertToTime($diff));

					if($diff<$timeout)
					{
						logging("ID ".$command["id"].": Sending STOP command.");
						$id = $command['id'];

						if(!$testing)
						{
							$sql = "UPDATE commands SET status='stop', finished_datetime = NOW() WHERE id=".$id;
							$result = DBConnect::execute_q($sql);

							$sql = "UPDATE schedule SET status='false' WHERE id=".$id;
							$result = DBConnect::execute_q($sql);

							$command_post = mb_unserialize($command['command']);
							foreach($command_post['profiles'] as $key => $profilename)
							{
								setInUseStatus($profilename['username'], $command['site'], 'false');
							}
						}
					}
				}

				$already_stop = true;
			}

			/**
			 * REMOVE ALL Reservation
			 */
			logging("Removing Reservation List");
			$listTB = DBConnect::assoc_query_2D("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'bot' AND TABLE_NAME LIKE  '%_reservation'");
			if(!empty($listTB) && is_array($listTB)) {
				foreach($listTB as $TB) {
					if(strpos($TB['TABLE_NAME'],'_reservation')){// For Security before TRUNCATE
						DBConnect::execute_q('TRUNCATE TABLE '.$TB['TABLE_NAME']);
						logging("Removing Reservation for ".$TB['TABLE_NAME']);
					}
				}
			}

			/**
			 * Remove Bots Logs
			 */
			logging("Removing Bot's log & Sleep for Process work 60 sec");
			file_get_contents('http://192.168.1.253/bot/tools/remove_bot_logs.php');
			sleep(60);

			/**
			 * Send Signal to Reboot
			 */
			logging("Check for reboot all machines!");
		 	$enable = DBConnect::retrieve_value("SELECT setting_value FROM settings WHERE setting_name='REBOOT_ENABLED'");
			if($enable == '1'){
				$day = DBConnect::retrieve_value("SELECT setting_value FROM settings WHERE setting_name='REBOOT_DAYS'");
				$days = unserialize($day);
				if(in_array(date('N'),$days)){
					file_get_contents('http://192.168.1.253/bot/tools/rebootvm.php');
					logging("Waiting 60 sec for Reboot ALL VM ");
					sleep(60);
				}
				else
				{
					logging("Not reboot today.");
				}
			}
			else
			{
				logging("Not reboot.");
			}

			/**
			 * Reboot Apache and Tor processes
			 */
			logging("Restarting Apache processes on all machines!");
			file_get_contents('http://192.168.1.253/bot/tools/restart_apache.php');
			logging("Restarting Tor process on all machines!");
			file_get_contents('http://192.168.1.253/bot/tools/restart_tor.php');
		}
	}
	else
	{
		$already_stop = false;
	}
}

function checkStop()
{
	// Get list of commands that may need to stop
	$list = DBConnect::assoc_query_2D("SELECT c.*,si.name as site_name, se.ip FROM commands c LEFT JOIN sites si ON c.site=si.id LEFT JOIN servers se ON c.server=se.id WHERE c.status='stop'");
	if(is_array($list) && count($list)>0)
	{
		foreach($list as $command)
		{
			$command_url = "http://".$command['ip']."/postdata/".$command['site_name']."/send-message.php?command=STOP&id=".$command['id'];
			logging("Sending STOP for command ID: ".$command['id']." to ".$command_url);
			curl_post_async($command_url,array("command"=>"STOP","id"=>$command['id']));
			DBConnect::execute_q("UPDATE commands SET status='false' WHERE id=".$command['id']);
		}
	}
}

function checkSchedule()
{
	// Get scheduled commands
	$list = DBConnect::assoc_query_2D("SELECT * FROM schedule WHERE status='true' AND start_date<DATE(NOW()) AND start_time<=TIME(NOW()+INTERVAL 5 MINUTE) AND end_date>=DATE(NOW()) AND id NOT IN (SELECT command_id FROM schedule_log WHERE DATE(start_datetime)=DATE(NOW()))");
	if(is_array($list) && (count($list)>0))
	{
		logging("Running scheduled commands");
		foreach($list as $item)
		{
			// GET command
			$command = DBConnect::assoc_query_1D("SELECT * FROM commands WHERE id=".$item['id']);
			$site = $command['site'];
			$sex = $command['sex'];

			// Get new profile
			$profile = DBConnect::assoc_query_1D("SELECT id, username, password FROM user_profiles WHERE site_id=".$site." AND sex='".$sex."' AND used='false' LIMIT 1");

			if(is_array($profile))
			{
				// Extract command
				$command = mb_unserialize($command['command']);

				// Put into command
				$command['profiles'] = array();
				array_push($command['profiles'], $profile);

				// Set profile in use
				DBConnect::execute_q("UPDATE user_profiles SET in_use='true', used='true' WHERE id=".$profile['id']);

				// Insert back into database
				DBConnect::execute_q("UPDATE commands SET command='".serialize($command)."', status='true', finished_datetime='0000-00-00 00:00:00' WHERE id=".$item['id']);

				// Run command
				$command = DBConnect::assoc_query_1D("SELECT c.*,si.name as site_name, se.ip FROM commands c LEFT JOIN sites si ON c.site=si.id LEFT JOIN servers se ON c.server=se.id WHERE c.id='".$item['id']."'");
				$command_url = "http://".$command['ip']."/postdata/".$command['site_name']."/send-message.php";
				logging("Running command ID: ".$command['id']);
				logging(print_r($command,true));
				curl_post_async($command_url,$command);
				DBConnect::execute_q("UPDATE commands SET run_count=run_count+1 WHERE id=".$command['id']);

				// Insert schedule log
				DBConnect::execute_q("INSERT INTO schedule_log (command_id, start_datetime) VALUES (".$item['id'].", NOW())");
			}
		}
		logging("END Running scheduled commands, sleep for 10s.");
		sleep(10);
	}
}

function checkRunning($timeout)
{
	// Get list of running commands
	$list = DBConnect::assoc_query_2D("SELECT c.*,si.name as site_name, se.ip FROM commands c LEFT JOIN sites si ON c.site=si.id LEFT JOIN servers se ON c.server=se.id WHERE c.status='true'");
	if(is_array($list) && count($list)>0)
	{
		foreach($list as $command)
		{
			if($command['run_count'] > 0)
			{
				$command_url = "http://".$command['ip']."/postdata/".$command['site_name']."/send-message.php";
				$log_url = "http://".$command['ip']."/postdata/".$command['site_name']."/logs/".$command['id']."_latest.log";

				// check if $command['server'] is running $command['site'];
				logging("Checking status: ".$log_url);
				$data = get_data($log_url);
				$data = get_last_modified($data);
			}
			else
			{
				$data = array();
			}

			if($data['message']=='FINISHED')
			{
				logging("Command ID: ".$command['id']." is finished.");
				$site = DBConnect::retrieve_value("SELECT site FROM commands WHERE id=".$command['id']);
				$botcommand = DBConnect::retrieve_value("SELECT command FROM commands WHERE id=".$command['id']);
				$botcommand = mb_unserialize($botcommand);
				$profiles = $botcommand['profiles'];
				$profiles_name = "";
				foreach($profiles as $profile)
				{
					$profiles_name .= "'".$profile['username']."',";
				}
				$profiles_name = substr($profiles_name,0,-1);
				DBConnect::execute_q("UPDATE commands SET status='false', finished_datetime=NOW() WHERE id=".$command['id']);
				DBConnect::execute_q("UPDATE user_profiles SET in_use='false' WHERE username IN (".$profiles_name.") AND site_id=".$site);
			}
			else
			{
				if($data['time'])
					$time = $data['time'];
				else
					$time = 0;
				$diff = time()-$time;
				logging("Last modified: ".date("Y-m-d H:i:s",$time));
				logging("Different: ".convertToTime($diff));

				// if not, run it
				if($diff>$timeout)
				{
					if($command['run_count']>0)
					{
						//$post = mb_unserialize($command['command']);
						//$post['job_id'] = $command['id'];
						logging("Command ID: ".$command['id']." is not running.");
						//RUN IT
						logging("Running command ID: ".$command['id']);
						logging(print_r($command,true));
						if($command['start_on_time']=='0')
						{
							$command_arr = mb_unserialize($command['command']);
							$command_arr['start_h'] = "00";
							$command_arr['start_m'] = "01";
							$command_arr['end_h'] = "00";
							$command_arr['end_m'] = "00";
							$command_arr['run_count'] = $command['run_count']+1;
							$command_serialize = serialize($command_arr);
							$command['command'] = $command_serialize;
						}
						curl_post_async($command_url,$command);
						DBConnect::execute_q("UPDATE commands SET run_count=run_count+1 WHERE id=".$command['id']);
					}
					else
					{
						$start_time = strtotime(date("Y-m-d ").$command['start_time']);
						$current_time = time();
						if((($current_time > $start_time) && (($current_time - $start_time)<60*30) && ($command['start_on_time']=='1')) || ($command['start_on_time']=='0'))
						{
							logging("Command ID: ".$command['id']." is not running.");

							$post = mb_unserialize($command['command']);
							//If no profiles, then get 1 profile
							//if(!isset($post['profiles']) && ($post['action']!=='check'))
							if(!isset($post['profiles']))
							{
								logging("No selected profile. Getting new profile.");

								//get next profile
								$sql = "SELECT id, username, password FROM user_profiles WHERE site_id=".$command['site']." AND status='true' AND in_use='false' AND sex='".$command['sex']."' ORDER BY used DESC, id DESC LIMIT 1";
								$profile = DBConnect::assoc_query_1D($sql);

								if(is_array($profile))
								{
									$sql = "UPDATE user_profiles SET in_use='true', used='true' WHERE id=".$profile['id']." LIMIT 1";
									DBConnect::execute_q($sql);

									logging("Got new profile: ".$profile['username']);

									$command_arr = mb_unserialize($command['command']);
									$command_arr['profiles'] = array(array(
																	"username"=>$profile['username'],
																	"password"=>$profile['password']
																	));
									if($command['start_on_time']=='0')
									{
										$command_arr['start_h'] = "00";
										$command_arr['start_m'] = "01";
										$command_arr['end_h'] = "00";
										$command_arr['end_m'] = "00";
									}
									$command_serialize = serialize($command_arr);
									$command['command'] = $command_serialize;

									//put in command
									DBConnect::execute_q("UPDATE commands SET command = '".addslashes($command_serialize)."' WHERE id=".$command['id']);

									logging("Running command ID: ".$command['id']);
									logging(print_r($command,true));
									curl_post_async($command_url,$command);
									DBConnect::execute_q("UPDATE commands SET run_count=run_count+1 WHERE id=".$command['id']);
								}
								else
								{
									logging("No profile.");
								}
							}
							else
							{
								logging("Command ID: ".$command['id']." is not running.");
								logging("Running command ID: ".$command['id']);
								logging(print_r($command,true));
								curl_post_async($command_url,$command);
								DBConnect::execute_q("UPDATE commands SET run_count=run_count+1 WHERE id=".$command['id']);
							}
						}
						else
						{
							logging("Command ID: ".$command['id']." is not running.");
							logging("Waiting for start time.");
						}
					}
				}
				else
				{
					logging("Command ID: ".$command['id']." is running.");
				}
			}
		}
	}
	else
	{
		logging("No command.");
	}
}

function mb_unserialize($serial_str) {
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
	return unserialize($out);
}

function convertToTime($time)
{
	if(is_numeric($time))
	{
		$msg="";
		if($time >= 86400){
			$msg .= floor($time/86400)." Day".(floor($time/86400)>1?"s":"")." ";
			$time = ($time%86400);
		}
		if($time >= 3600){
			$msg .= floor($time/3600)." Hour".(floor($time/3600)>1?"s":"")." ";
			$time = ($time%3600);
		}
		if($time >= 60){
			$msg .= floor($time/60)." Minute".(floor($time/60)>1?"s":"")." ";
			$time = ($time%60);
		}
		$msg .= floor($time)." Second".(floor($time)>1?"s":"")." ";
		return $msg;
	}
	else
		return false;
}

function isCli() {

     if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
          return true;
     } else {
          return false;
     }
}

function logging($msg)
{
	$path = "logs/".date("Y")."/".date("m")."/".date("d");

	$init_dir = "";

	foreach(explode("/",$path) as $dir)
	{
		if(!is_dir($init_dir.$dir))
		{
			mkdir($init_dir.$dir);
			chmod($init_dir.$dir,0777);
		}
		$init_dir .= $dir."/";
	}

	$log = "[".date("Y-m-d H:i:s")."] ".$msg."\n";
	//echo $log;
	file_put_contents($path."/".date("H").".txt", $log,FILE_APPEND);
}

function get_data($url,$post=array())
{
	$repeat = 2;
	$repeat_count=$repeat;
	$ch = curl_init();
	$timeout = 10;
	$httpCode = 0;
	curl_setopt($ch,CURLOPT_FRESH_CONNECT,true);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	do
	{
		$data = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		logging("Returned http code => ".$httpCode);

		if(($httpCode != 200) && ($httpCode != 404))
		{
			if($repeat_count>0)
			{
				logging("Getting data failed, ".$repeat_count." more repeats left.");
				$repeat_count--;
				logging("Sleep for 5 seconds");
				sleep(5);
			}
			else
			{
				logging("Fail after $repeat times retries.");
				$httpCode=404;
			}
		}
	}while(($httpCode != 200) && ($httpCode != 404));

	curl_close($ch);
	return $data;
}

function curl_post_async($url, $params)
{
    foreach ($params as $key => &$val) {
      if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    $out = "POST ".$parts['path']."?".$parts['query']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}

function get_last_modified($data)
{
	$temp = explode("\n",$data);
	$last = "";
	foreach($temp as $line)
	{
		if((strpos($line,"[")==0) && (strpos($line,"]")>0))
		{
			$last=substr($line,1,strpos($line,"]")-1);
			$dt = new DateTime($last);
			$last=((int)$dt->getTimestamp());

			$msg=substr($line,strpos($line,"]")+1);
		}
	}
	return array('time'=>$last, 'message'=>trim($msg));
}

function setInUseStatus($username, $siteid, $status)
{
	$set = "SET in_use = '".$status."'";
	if($status=="true")
		$set = $set.", used='true'";
	$sql = "UPDATE user_profiles ".$set." WHERE username = '".$username."' AND site_id = '".$siteid."'";
	DBConnect::execute_q($sql);
}
?>