<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

function mb_unserialize($serial_str)
{ 
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
	return unserialize($out); 
}

require_once('DBconnect.php');
require_once('myflirt.php');
require_once('config.php');

$bot = new myflirt($_POST);

if($bot->getAction()=='check' || $_GET['action'] == 'check')
{
	$bot->savelog("Get all profiles to check if they are still usable. #".$bot->getSiteID());
	$sql = "SELECT id, username, password FROM user_profiles WHERE site_id='".$bot->getSiteID()."' AND status = 'false' AND in_use = 'false' ORDER BY id DESC";	
	$profiles = DBConnect::assoc_query_2D($sql);
	if(!empty($profiles))
	{
		$bot->savelog("Have ". count($profiles) ." profiles to check.");
		
		// Test Login
		foreach($profiles as $profile) {
			$bot->loginArr = array();
			$bot->savelog("[TEST] : Loggin to ".$profile['username']." for Next Step");
			$bot->addLoginData(array(
				array(
					'username' => $profile['username'],
					'password' => $profile['password']
				)
			));
			$bot->login();
			if($bot->logged == TRUE) {
				break 1;
			}
		}
		
		// Test Profile Check
		foreach($profiles as $profile) {
			if($bot->checkTargetProfile($profile['username']) == TRUE) {
				DBConnect::execute_q("UPDATE user_profiles SET status='true' WHERE id=".$profile['id']);
				$bot->savelog("Test success : ".$profile['username']." still usable.");
			} else {
				DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
				$bot->savelog("Test failed : ".$profile['username']." will be deleted.");
			}
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
			if($bot->work())
			{
				$wait_for_login = 180;

				if($bot->command['logout_after_sent'] == "Y"){
					$wait_for_login = $bot->command['wait_for_login']*60;
				}

				$bot->sleep($wait_for_login);
			}
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