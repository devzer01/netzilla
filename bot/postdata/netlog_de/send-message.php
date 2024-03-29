<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

require_once('DBconnect.php');
require_once('netlog_de.php');
require_once('config.php');

$bot = new netlog_de($_POST);

if($bot->getAction()=='check' || $_GET['action'] == 'check')
{
	$bot->savelog("Get all profiles to check if they are still usable. #".$bot->getSiteID());
	$sql = "SELECT id, username, password FROM user_profiles WHERE site_id='".$bot->getSiteID()."' ORDER BY rand()";	
	$profiles = DBConnect::assoc_query_2D($sql);
	if(!empty($profiles))
	{
		$count = count($profiles);
		$bot->savelog("Have ". number_format($count) ." profiles to check.");
		
		// Test Profile Check
		$i = 1;
		foreach($profiles as $profile) {
			if($bot->testLogin($profile) == TRUE) {
				DBConnect::execute_q("UPDATE user_profiles SET status='true' WHERE id=".$profile['id']);
				$bot->savelog($i.'/'.$count.' => Test success : '.$profile['username'].' still usable.');
			} else {
				DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
				$bot->savelog($i.'/'.$count.' => Test failed : '.$profile['username'].' will be deleted.');
			}
			$i++;
		}
		
		// Finished
		$bot->savelog("FINISHED");
		exit();
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

			$bot->logout();
			$bot->sleep(180);
		}

		$bot->sleep(60);
	}
	else
	{
		$bot->savelog("Not in running time period.");
		$bot->sleep($sleep_time);
	}
}

$bot->savelog("All profiles are unable to log in");
$bot->savelog("FINISHED");
?>
