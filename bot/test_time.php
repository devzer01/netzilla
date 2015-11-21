<?php
require_once "daemon/dbconnect.php";

function mb_unserialize($serial_str) { 
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
	return unserialize($out); 
}

$list = DBConnect::assoc_query_2D("SELECT * FROM schedule WHERE status='true' AND start_date<DATE(NOW()) AND start_time<=TIME(NOW()) AND id NOT IN (SELECT command_id FROM schedule_log WHERE DATE(start_datetime)=DATE(NOW()))");
if(is_array($list) && (count($list)>0))
{
	foreach($list as $item)
	{
		// GET command
		$command = DBConnect::assoc_query_1D("SELECT * FROM commands WHERE id=".$item['id']);
		$site = $command['site'];
		$sex = $command['sex'];

		// Extract command
		$command = mb_unserialize($command['command']);
		print_r($command);

		// Get new profile
		$profile = DBConnect::assoc_query_1D("SELECT id, username, password FROM user_profiles WHERE site_id=".$site." AND sex='".$sex."' AND used='false' LIMIT 1");
		
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
?>