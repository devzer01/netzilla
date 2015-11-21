<?php 
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");
ignore_user_abort(true);

require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');

$current_profile = 0;
$current_subject = 0;
$current_message = 0;
$messages_per_hour = 8;

$loginURL				= "http://www.phonefling.com/Default.aspx";
$loginRefererURL		= "http://www.phonefling.com/";
$searchURL				= "http://www.phonefling.com/search";
$searchReferer			= "http://www.phonefling.com/search";
$searchResultsPerPage	= 30;
$sendMessageURL			= "http://www.phonefling.com/compose/";

define("PROXY_IP", "127.0.0.1");
define("PROXY_PORT", "9050");
//END STANDARD CONFIG

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
	define("MSG_INTERVAL",$command['msg_interval']);
	file_put_contents("logs/".ID."_post.log",print_r($_POST,true));
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("MSG_INTERVAL",1);
	define("SITE_ID",0);
	define("RUN_COUNT",0);
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);
	//$db_command = DBConnect::retrieve_value("SELECT command FROM commands WHERE id ='544'");
	//$command = funcs::mb_unserialize($db_command);
	$command = array(
						"profiles" => array(
												array(
														"username" => "leadon", //"Aprilforever",
														"password" => "leadon0515" //"tht007"
													),
												array(
														"username" => "sweetyLindz90",
														"password" => "thtl19"
													)
											),
						"messages" => array(
												array(
														"subject" => "Hello There!",
														"message" => "Hi handsome! Guess what? Are you as good looking in person as you are on you profile? yourbuddy24 dot com"
													),
												array(
														"subject" => "Hi guy",
														"message" => "You are stunning! Life's too short to be serious all the time, don't you think? yourbuddy24 dot com"
													)
											),
						"action" => 'search',
						//"action" => 'send',
						//"action" => 'view',
						"search_sex" => "Male",
						"country" => "840:United States",
						"messages_per_hour" => 8,
						"timer" => (6*60)
					);
	/*echo "<pre>";
	print_r($command);
	echo "</pre>"; die('<br/>command');*/
	file_put_contents("logs/".ID."_post.log",print_r($command,true));
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);
	
}
//RECEIVER PROFILE//START STANDARD CONFIG
if((is_array($command)) && (count($command)>0))
{
	$receiverProfiles = funcs::getRecieverProfile();
	if($command['search_sex']=="Male")
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
	$receiverInboxURL = "http://www.phonefling.com/messages/inbox";
	$total_proflies = count($command['profiles'])-1;
}

if(isset($_GET['command']) && ($_GET['command']=="STOP"))
{
	file_put_contents("logs/".$_GET['id']."_command.log","STOP");
	exit;
}
elseif(isset($command['action']) && ($command['action']=='check'))
{
	funcs::savelog("Get all profiles to check if they are still usable.");
	$profiles = DBConnect::assoc_query_2D("SELECT id, username, password FROM user_profiles WHERE site_id =".SITE_ID);
	if(is_array($profiles) && count($profiles))
	{
		foreach($profiles as $profile)
		{
			funcs::savelog("Checking user: '".$profile['username']."' : '".$profile['password']."'");
			if(funcs::memberlogin($profile['username'], $profile['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies, 2))
			{
				DBConnect::execute_q("UPDATE user_profiles SET status='true' WHERE id=".$profile['id']);
				funcs::savelog($profile['username']." still usable.");
			}
			else
			{
				DBConnect::execute_q("UPDATE user_profiles SET status='false' WHERE id=".$profile['id']);
			}
		}
	}
	else
	{
		funcs::savelog("No profiles to check.");
	}
	funcs::savelog("FINISHED");
	exit;
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies))
	{
		$page = 1; //$p = 1;
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['timer']);
		$searchData = $post;//http_build_query($post);
		do
		{
			funcs::savelog("Search page: ".$page);
			list($list,$searchData) = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $searchData, $page);
			funcs::savelog("Found ".count($list)." members.");
			funcs::savelog("Saving to database");
			funcs::saveMembers($list,$command);
			funcs::savelog("Saving done");

			$page++;
			//$p++;
		}
		while(count($list)>=$searchResultsPerPage);//funcs::getCountExistingUser()>=122142 //while($p<=10);//
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
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'],$receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL);

				//$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				$send_test_nextime = strtotime(date('Y-m-d H:i:s')) + $send_test_period;
				funcs::savelog("Send test message next time: ". date('Y-m-d H:i:s',$send_test_nextime));
			}

			if($i%100==0)
			{
				//RANDOM NEW PROFILE
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies))
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
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage,$sendMessageURL);/**/

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
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'],$receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL);

				//RANDOM NEW PROFILE
				$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
			}

			if($i%$messages_per_hour==0)
			{
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $current_profile, $total_proflies))
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

			$arr_sec = range(30,3600/$messages_per_hour,30);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage,$sendMessageURL);

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
	$list = DBConnect::assoc_query_2D("SELECT * FROM phonefling_member ORDER BY id DESC LIMIT 100");
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