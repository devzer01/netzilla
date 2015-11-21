<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');

$current_profile = 0;
$current_subject = 0;
$current_message = 0;
$max_retries = 3;
$messages_per_hour = 720;

$loginURL = "http://www.sexycupid.org/welcome";
$loginRefererURL = "http://www.sexycupid.org/welcome";
$searchURL = "http://www.sexycupid.org/matches";
$searchReferer = "http://www.sexycupid.org/matches";
$searchResultsPerPage = 9;
$sendMessageURL = "http://www.sexycupid.org/node/add/pm";
$sendMessageRefererURL = "http://www.sexycupid.org/node/add/pm";

	$receiverInboxURL = "http://www.sexycupid.org/pm/1902";

define("PROXY_IP", "127.0.0.1");
define("PROXY_PORT", "9050");
define("PROXY_CONTROL_PORT", "9051");

if($_POST)
{
	// ENABLE THIS ONLY WHEN COMPLETED BUILDING BOT
	ignore_user_abort(true);
	define("ID",$_POST['id']);
	define("BOT_ID",$_POST['server']);
	define("SITE_ID",$_POST['site']);
	define("RUN_COUNT",$_POST['run_count']);
	$command = funcs::mb_unserialize($_POST['command']);
	$_POST['command'] = $command;
	file_put_contents("logs/".ID."_post.log",print_r($_POST,true));
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}

	$receiverProfiles = funcs::getRecieverProfile();
	if($command['Seeking']=="Male")
	{
		$receiverId			= $receiverProfiles['male_id'];//"6637";
		$receiverUsername	= $receiverProfiles['male_user'];//"robbywalker";
		$receiverPassword	= $receiverProfiles['male_pass'];//"R2021982b";
	}
	else
	{
		$receiverId			= $receiverProfiles['female_id'];//"6636";
		$receiverUsername	= $receiverProfiles['female_user'];//"rosiebell";
		$receiverPassword	= $receiverProfiles['female_pass'];//"R2021982b";
	}
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("SITE_ID",16);
	define("RUN_COUNT",0);
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);

	$_POST = array(		"start_time" => "00:00:00",
						"end_time" => "00:00:00");
	$command = array(
						"profiles" => array(
												array(
														"username" => "Angelica8",
														"password" => "thtl6a"
													)
											),
						"messages" => array(
												array(
														"subject" => "Hello",
														"message" => "Hi handsome! Guess what? Are you as good looking in person as you are on you profile?"
													),
												array(
														"subject" => "Hi there!",
														"message" => "You are stunning! Life's too short to be serious all the time, don't you think?"
													)
											),
						"Seeking" => "Male",
						"Orientation" => "All",
						"Country" => "United Kingdom",
						"State" => "All",
						"Drinking" => "All",
						"Smoking" => "All",
						"Relationship" => "All",
						"Religion" => "All",
						"Willing to Perform" => "All",
						"Willing to Receive" => "All",
						"view_name" => "matches",
						"view_display_id" => "page_1",
						"view_args" => "",
						"view_path" => "matches",
						"view_base_path" => "matches",
						"view_dom_id" => "1",
						"pager_element" => "0",
						"action" => "search"
						//"action" => "send"
					);

	$receiverUsername	= "jackharris11";
	$receiverPassword	= "mmwcs3";
}

if(isset($_GET['command']) && ($_GET['command']=="STOP"))
{
	file_put_contents("logs/".$_GET['id']."_command.log","STOP");
	exit;
}
elseif(isset($_GET['command']) && ($_GET['command']=="BAND"))
{
	$profiles = funcs::getUnLogInUser();
	foreach($profiles as $profile)
	{
		//echo "<br/>-----------------<br/>".$profile['username']." ".$profile['password']."<br/>";
		funcs::savelog("Checking Band User for: '".$profile['username']."' : '".$profile['password']."'");
		if(funcs::memberlogin($profile['username'], $profile['password'], $loginURL, $loginRefererURL))
		{
			funcs::setUserToReuse($profile['username'], $profile['password']);
		}
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
	{
		$page = 1;
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['search']);
		$retries = $max_retries ;
		do
		{
			funcs::savelog("Search page: ".$page);
			$list = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $post, $page);
			funcs::savelog("Found ".count($list)." members.");
			funcs::saveMembers($list,$command);

			// Hack for NO RESPONSE
			if(count($list)>=$searchResultsPerPage)
			{
				$page++;
				$retries=$max_retries ;
			}
			elseif($retries>0)
			{
				$retries--;
				funcs::savelog("Retry to get members from page ".$page."[".$retries." retires left]");
				$list=range(1,$searchResultsPerPage);
				$viewstate = "";
			}
		}
		while(count($list)>=$searchResultsPerPage);
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='send'))
{
	//$_POST['start_time'] = "11:07:00";//"22:00:00";//"10:00:00";
	//$_POST['end_time'] = "10:55:00";//"02:00:00";//"12:00:00";

	if(($_POST['start_time']!="00:00:00") && ($_POST['end_time']!="00:00:00"))
	{
		funcs::savelog("Running Time: ".$_POST['start_time']." - ".$_POST['end_time']);
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$running_bot_period = 60*60*24; // every day

		funcs::savelog("Checking start time");
		if(strtotime($start_time)>strtotime($end_time))
		{
			$start_time = strtotime(date('Y-m-d').$start_time);
			$end_time = strtotime(date('Y-m-d').$end_time)+$running_bot_period;
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
		funcs::checkRunningTime($start_time, $end_time);
		$send_test_period = 60*60*2; // every 2 hours 
		$send_test_nextime = strtotime(date('Y-m-d H:i:s')); //die();
		funcs::savelog("Getting list of members");
		funcs::savelog("Test profile is '".$receiverUsername."'");

		$i=0;
		while($member=funcs::getNextMember($command))
		{
			if($end_time<=strtotime(date('Y-m-d H:i:s')))
			{
				$start_time += $running_bot_period;
				$end_time += $running_bot_period;
			}
			funcs::checkRunningTime($start_time, $end_time);

			if(strtotime(date('Y-m-d H:i:s'))>=$send_test_nextime)
			{
				funcs::savelog("Send test message");
				/**
				 * SENDING MESSAGE TO TEST PROFILE
				 **/
				//FORCE LOGIN
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'],$receiverUsername, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageRefererURL);

				//$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				$send_test_nextime = strtotime(date('Y-m-d H:i:s')) + $send_test_period;
				funcs::savelog("Send test message next time: ". date('Y-m-d H:i:s',$send_test_nextime));
			}

			if($i%100==0)
			{
				//RANDOM NEW PROFILE
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
			}

			//funcs::savelog("Send message " . date('Y-m-d H:i:s',$send_test_nextime));
			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			$arr_sec = range(5,3600/$messages_per_hour,5);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $randomSubject, $randomMessage,$sendMessageURL, $sendMessageRefererURL);/**/

			$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);
			$i++;

		}
		funcs::savelog("No Member in database");
		funcs::savelog("FINISHED");

	}
	else
	{
		funcs::savelog("Getting list of members");
		funcs::savelog("Test profile is '".$receiverUsername."'");

		$i=0;
		while($member=funcs::getNextMember($command))
		{
			if(($i%$messages_per_hour==0) && ($i>0))//profiles per hour //if($i==0)//
			{
				//FORCE LOGIN
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'],$receiverUsername, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageRefererURL);

				//RANDOM NEW PROFILE
				$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
			}

			if($i%$messages_per_hour==0)
			{
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
				{
					//RANDOM NEW PROFILE
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
			}

			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			$arr_sec = range(5,3600/$messages_per_hour,5);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $randomSubject, $randomMessage,$sendMessageURL, $sendMessageRefererURL);

			$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);
			$i++;
		}
		funcs::savelog("No Member in database");
		funcs::savelog("FINISHED");
	}
}
else
{
	$list = DBConnect::assoc_query_2D("SELECT * FROM sexycupid_member LIMIT 100");
	if(count($list))
	{
		echo "<table border='1'>";
		$i=0;
		foreach($list as $item)
		{
			if($i==0)
			{
				echo "<tr>";
				foreach($item as $key=>$val)
				{
					echo "<th>".$key."</th>";
				}
				echo "</tr>";
			}
			echo "<tr>";
			foreach($item as $key=>$val)
			{
				if($key=='id')
				{
					$profile_id = $val;
				}
				if($key=="pic")
					echo "<td><img src='".$val."'/></td>";
				else
					echo "<td>".$val."</td>";
			}
			echo "</tr>";
			$i++;
		}
		echo "</table><br/><br/>";
	}
}
?>