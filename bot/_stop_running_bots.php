<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Asia/Bangkok");
require_once "daemon/dbconnect.php";
$timeout = 60*60*7;
$testing = false;

$time_allow = array("22", "23");

if(in_array(date("H"), $time_allow))
{
	$commands = DBConnect::assoc_query_2D("select c.start_time as start, c.site, c.server, c.id as id, c.sex, c.target, c.command as command, s.name as servername, s.ip as ip, s2.name as site_name from commands c left join servers s on c.server = s.id left join sites s2 on c.site = s2.id where c.status='true' and c.run_count>0 order by site_name asc, c.sex");

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
	}
}
else
{
	logging("Can not run this time. ".date("H:i"));
}

function setInUseStatus($username, $siteid, $status)
{
	$set = "SET in_use = '".$status."'";
	if($status=="true")
		$set = $set.", used='true'";
	$sql = "UPDATE user_profiles ".$set." WHERE username = '".$username."' AND site_id = '".$siteid."'";
	mysql_query($sql);
}

function logging($msg)
{
	$path = "logs_stop/".date("Y")."/".date("m")."/".date("d");

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
	echo $log;
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
		if($httpCode == 403)
		{
			if($repeat_count>0)
			{
				$repeat_count--;
				sleep(5);
			}
			else
			{
				logging("Fail after $repeat times retries.");
				$httpCode=0;
			}
		}
	}while($httpCode==403);

	curl_close($ch);
	return $data;
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

?>