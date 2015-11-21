<?php
//ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

function mb_unserialize($serial_str)
{ 
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
	return unserialize($out); 
}

require_once('DBconnect.php');
require_once('mollylove.php');
require_once('config.php');

$bot = new Mollylove($_POST);

if($bot->getAction()=='check')
{
	$bot->savelog("Get all profiles to check if they are still usable.");
	
	$sql = "SELECT id, username, password FROM user_profiles WHERE site_id='".$bot->getSiteID()."'";	
	$profiles = DBConnect::assoc_query_2D($sql);

	if(is_array($profiles) && count($profiles))
	{
		$bot->savelog(count($profiles)." profiles to check.");
		foreach($profiles as $profile)
		{
			$bot->savelog("Checking user: '".$profile['username']."' : '".$profile['password']."'");
			if($bot->checkLogin($profile['username'],$profile['password']))
			{
				DBConnect::execute_q("UPDATE user_profiles SET status='true' WHERE id=".$profile['id']);
				$bot->savelog($profile['username']." still usable.");
			}
			else
			{
				DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
				$bot->savelog($profile['username']." will be deleted.");
			}
		}
	}
	else
	{
		$bot->savelog("No profiles to check.");
	}
	$bot->savelog("FINISHED");
	exit;
}

while($bot->countAvailableUsers())
{	
	$sleep_time = $bot->checkRunningTime($bot->command['start_h'],$bot->command['start_m'],$bot->command['end_h'],$bot->command['end_m']);
	
	//If in runnig time period
	if($sleep_time==0)
	{
		if($bot->login())
		{			
			// Do all stuffs here
			$bot->work();
			
			$wait_for_login = $bot->command['wait_for_login']*60;
			$bot->sleep($wait_for_login);			
		}

		$bot->sleep(60);
	}
	else
	{
		$bot->savelog("Not in running time period.");
		$bot->sleep($sleep_time);
	}

	if($bot->command['logout_after_sent'] == "Y"){				
		$bot->returnUsers();
		$bot->logout();
	}
}

$bot->savelog("All profiles are unable to log in");
$bot->savelog("FINISHED");
?>
