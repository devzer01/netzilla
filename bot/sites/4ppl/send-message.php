<?php 
/*echo "<pre>";
print_r($_POST);
echo "</pre>";
die('Post..');

http://localhost/postdata/4ppl/send-message.php
*/

ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");
require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');

$current_profile		= 0;
$current_subject		= 0;
$current_message		= 0;
$messages_per_hour		= "";

define("PROXY_IP", "127.0.0.1");
define("PROXY_PORT", "9050");
define("PROXY_CONTROL_PORT", "9051");

if($_POST)
{
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
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("MSG_INTERVAL",1);
	define("SITE_ID",25);
	define("RUN_COUNT",0);
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);
	$_POST['start_time'] = date('H:i:s', strtotime(date('H:i:s')) + 420);//"18:20:00";//"00:00:00";//"22:00:00";//"10:00:00";
	$_POST['end_time'] = date('H:i:s', strtotime(date('H:i:s')) + 1020);//"18:25:00";//"00:00:00";//"02:00:00";//"12:00:00";
	//die($_POST['start_time'] . ' - '. $_POST['end_time']);
	$command = array(
						"profiles" => array(
												array(
														"username" => "fairy888", 	
														"password" => "1q2w3e4r5t"
													),
												array(
														"username" => "CowboyShelby",
														"password" => "man1234"
													)
											),
						"messages" => array(
												array(
														"subject" => "Hello There!",
														"message" => "Hi handsome! Guess what? Are you as good looking in person as you are on you profile? yourbuddy24 dot com X"
													),
												array(
														"subject" => "Hi guy",
														"message" => "You are stunning! Life's too short to be serious all the time, don't you think? yourbuddy24 dot com, X"
													)
											),
						//"action" => 'search',
						"action" => 'send',
						"Sex" => "1",
						"LookingFor" => "2",
						"DateOfBirth3_f" => "18",
						"DateOfBirth3_t" => "99",
						"Country" => "206", //205 206 38
						"messages_per_hour" => 8,
						"timer" => (6*60)
					);
	/*$db_command = DBConnect::retrieve_value("SELECT command FROM commands WHERE id ='855'");
	$command = funcs::mb_unserialize($db_command);
	echo "<pre>";
	print_r($command);
	echo "</pre>"; 
	die('<br/>command');*/
	file_put_contents("logs/".ID."_post.log",print_r($command,true));
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);
}
//START STANDARD CONFIG
if((is_array($command)) && (count($command)>0))
{
	$headerOpt				= array('Host: www.4ppl.com', 'Origin: http://www.4ppl.com');

	$loginURL				= "http://www.4ppl.com/login/check";
	$loginRefererURL		= "http://www.4ppl.com/";
	
	$searchURL				= "http://www.4ppl.com/profilesearch/search/680313/";
	$searchReferer			= "http://www.4ppl.com/profilesearch/search_submit";
	$searchReferer		   .= "?Sex=".$command['Sex'];
	$searchReferer		   .= "&LookingFor[]=".$command['LookingFor'];
	$searchReferer		   .= "&DateOfBirth3_f=".$command['DateOfBirth3_f'];
	$searchReferer		   .= "&DateOfBirth3_t=".$command['DateOfBirth3_t'];
	$searchReferer		   .= "&Country=".$command['Country'];
	$searchReferer		   .= "&step=3";
	$searchReferer		   .= "&rnd=680313";
	$searchReferer		   .= "&submit=SEARCH";
	
	$searchResultsPerPage	= 20;
	$sendMessageURL			= "http://www.4ppl.com/mail/xml_send_message/";
	$sendMessageReferer		= "http://www.4ppl.com/mail/new/";
	
	$receiverInboxURL		= "http://www.4ppl.com/mail/tag/inbox";

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}

	//RECEIVER PROFILE
	$receiverProfiles = funcs::getRecieverProfile();
	if($command['LookingFor']=="2")
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

	$total_proflies = count($command['profiles'])-1; //for checking if current profile is the last one
}

//COMMAND ACTION
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
		funcs::savelog("Checking Banded User for: '".$profile['username']."' : '".$profile['password']."'");
		if(funcs::memberlogin($profile['username'], $profile['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
		{
			funcs::setUserToReuse($profile['username'], $profile['password']);
		}
		$arr_sec = range(300,600,30);
		shuffle($arr_sec);
		$sleep_time = $arr_sec[0];
		funcs::savelog("Sleep for ". funcs::secondToTextTime($sleep_time));
		funcs::sleep($sleep_time);
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	for($i=48;$i<=128;$i++)
	{
		funcs::savelog("Search for country id:".$i);
		//funcs::getMembersFromSearchResult('','','');
		if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
		{
			$page = 1;
			$post = $command;
			unset($post['profiles']);
			unset($post['messages']);
			unset($post['timer']);
			$post['Country'] = $i;
			//funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer, $headerOpt);
			
			$searchData = $post;//http_build_query($post);
			do
			{
				funcs::savelog("Search page: ".$page);
				list($list, $searchData) = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $searchData, $page);
				funcs::savelog("Found ".count($list)." members.");
				funcs::savelog("Saving to database");
				funcs::saveMembers($list, $command);
				funcs::savelog("Saving done");

				$page++;
			}
			while(count($list)>=$searchResultsPerPage);//while(funcs::getCountExistingUser()>=122142);// /**/
		}
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='send'))
{
	/**
	 * Summary
	 * There is 2 options for this bot
	 * 1. Range Time Running.  
	 *		1.1 Check if it is sending message time
	 *		1.2 Log in every 20 minutes
	 *		1.3 Send message to test profile every 2 hours
	 *		1.4 Send message to member in our database
	 * 2. Run until out of member in database
	 *		2.1 Log in every 20 minutes
	 *		2.2 Send message to test profile every 8 message or any number of message that has been set to $messages_per_hour variable
	 *		2.3 Send message to member in our database
	 **/

	$user_login_period = 60*20; // every half an hour
	$user_login_nextime = strtotime(date('Y-m-d H:i:s')); //die();

	if(($_POST['start_time']!="00:00:00") && ($_POST['end_time']!="00:00:00"))
	{
		//1.1 Check if it is sending message time
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

			//1.2 Log in every 20 minutes
			if(strtotime(date('Y-m-d H:i:s'))>=$user_login_nextime)//($i%100==0)
			{
				//RANDOM NEW PROFILE
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
				$user_login_nextime = strtotime(date('Y-m-d H:i:s')) + $user_login_period;
				funcs::savelog("Login next time: ". date('Y-m-d H:i:s',$user_login_nextime));
			}

			//1.3 Send message to test profile every 2 hours
			if(strtotime(date('Y-m-d H:i:s'))>=$send_test_nextime)
			{
				/*if(!(funcs::isLoggedIn($command['profiles'][$current_profile]['username'], $headerOpt)))//Check log in status before sending message every time
				{
					funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
					while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
					{
						$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
					}
				}*/

				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer, $headerOpt);

				//$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				$send_test_nextime = strtotime(date('Y-m-d H:i:s')) + $send_test_period;
				funcs::savelog("Send test message next time: ". date('Y-m-d H:i:s',$send_test_nextime));
			}

			//1.4 Send message to member in our database
			funcs::savelog("Get next member");
			if($messages_per_hour!="")
			{
				$arr_sec = range(30,3600/$messages_per_hour,30);
				shuffle($arr_sec);
				$sleep_time = $arr_sec[0];
				funcs::savelog("Sleep for ". funcs::secondToTextTime($sleep_time));
				funcs::sleep($sleep_time);
			}

			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			/*if(!(funcs::isLoggedIn($command['profiles'][$current_profile]['username'], $headerOpt)))//Check log in status before sending message every time
			{
				funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
			}*/

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage, $sendMessageURL, $sendMessageReferer, $headerOpt);

			if($messages_per_hour!="")
			{
				$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
				funcs::savelog("Sleep for ". funcs::secondToTextTime($sleep_time));
				funcs::sleep($sleep_time);
			}
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
			//2.1 Log in every 20 minutes
			if(strtotime(date('Y-m-d H:i:s'))>=$user_login_nextime)//($i%100==0)
			{
				//RANDOM NEW PROFILE
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
				$user_login_nextime = strtotime(date('Y-m-d H:i:s')) + $user_login_period;
				funcs::savelog("Login next time: ". date('Y-m-d H:i:s',$user_login_nextime));
			}

			//2.2 Send message to test profile every 8 message or any number of message that has been set to $messages_per_hour variable
			if(($i%$messages_per_hour==0) && ($i>0))//profiles per hour //if($i==0)//
			{
				/*//SENDING TEST MESSAGE
				if(!(funcs::isLoggedIn($command['profiles'][$current_profile]['username'], $headerOpt)))//Check log in status before sending message every time
				{
					funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
					while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
					{
						$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
					}
				}*/

				//RANDOM NEW PROFILE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer, $headerOpt);

			} //die('<br/>Sending test message');

			//2.3 Send message to member in our database
			if($messages_per_hour!="")
			{
				$arr_sec = range(30,3600/$messages_per_hour,30);
				shuffle($arr_sec);
				$sleep_time = $arr_sec[0];
				funcs::savelog("Sleep for ".$sleep_time." seconds");
				funcs::sleep($sleep_time);
			}

			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			/*if(!(funcs::isLoggedIn($command['profiles'][$current_profile]['username'], $headerOpt)))//Check log in status before sending message every time
			{
				funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
			}*/

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage, $sendMessageURL, $sendMessageReferer, $headerOpt);

			if($messages_per_hour!="")
			{
				$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
				funcs::savelog("Sleep for ".$sleep_time." seconds");
				funcs::sleep($sleep_time);
			}
			$i++;
		}
		funcs::savelog("No Member in database");
		funcs::savelog("FINISHED");
	}
}
else
{
	$list = DBConnect::assoc_query_2D("SELECT * FROM okfreedating_member LIMIT 100");
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