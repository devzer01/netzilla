<?php
//ob_start();
if($_SERVER['HTTP_HOST'] == 'localhost') {
	// Turn off output buffering
	ini_set('output_buffering', 'off');
	// Turn off PHP output compression
	ini_set('zlib.output_compression', false);
	         
	//Flush (send) the output buffer and turn off output buffering
	while (@ob_end_flush());
	   
	// Implicitly flush the buffer(s)
	ini_set('implicit_flush', true);
	ob_implicit_flush(true);
}
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

function mb_unserialize($serial_str)
{ 
	$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str ); 
	return unserialize($out); 
}

require_once('DBconnect.php');
require_once('firstaffair.php');
require_once('config.php');

$bot = new Firstaffair($_POST);

if($bot->getAction()=='check' || $_GET['action'] == 'check')
{
	$bot->savelog("Get all profiles to check if they are still usable. #".$bot->getSiteID());
	$sql = "SELECT id, username, password FROM user_profiles WHERE site_id='".$bot->getSiteID()."' ORDER BY created_datetime DESC";	
	$profiles = DBConnect::assoc_query_2D($sql);
	if(!empty($profiles))
	{
		$count = count($profiles);
		$bot->savelog("Have ". number_format($count) ." profiles to check.");
		
		// Test Login
		/* foreach($profiles as $profile) {
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
		} */
		
		// Test Profile Check
		$i = 1;
		foreach($profiles as $profile) {
			// if($bot->checkTargetProfile($profile['username']) == TRUE) {
				$bot->savelog('***** '.$i.'/'.$count.' => Test Profile : '.$profile['username'].' and waiting for checking login *****');
				$bot->savelog('==[TEST]==> Testing for Loggin');
				if($bot->testLogin($profile) == TRUE) {
					DBConnect::execute_q("UPDATE user_profiles SET status='true' WHERE id=".$profile['id']);
					$bot->savelog('==[TEST]==> login on profile "'.$profile['username'].'" completed and still usable.');
				} else {
					DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
					$bot->savelog('==[TEST]==> Test login on profile "'.$profile['username'].'" failed and deleted');
				}
				
				// Reset To Tor Connection
				// $bot->command['proxy_type'] = 1;
				// $bot->setProxy();
			// } else {
				// DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
				// $bot->savelog($i.'/'.$count.' => Test failed : '.$profile['username'].' will be deleted.');
			// }
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
			if($bot->work())
			{
				$bot->logout();
				$bot->sleep(180);
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