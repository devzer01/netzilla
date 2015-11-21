<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

// ENABLE THIS ONLY WHEN COMPLETED BUILDING BOT
ignore_user_abort(true);

require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');

$current_profile = 0;
$current_subject = 0;
$current_message = 0;
$max_retries = 3;
$messages_per_hour = 8;

define("PROXY_IP", "127.0.0.1");//"62.75.229.94"
define("PROXY_PORT", "9050");//"3128"
define("PROXY_CONTROL_PORT", "9051");

if($_POST)
{
	define("ID",$_POST['id']);
	define("BOT_ID",$_POST['server']);
	define("SITE_ID",$_POST['site']);
	$command = funcs::mb_unserialize($_POST['command']);
	$_POST['command'] = $command;
	define("MSG_INTERVAL",$command['msg_interval']);
	file_put_contents("logs/".ID."_post.log",print_r($_POST,true));

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}

	$loginURL = "http://www.collarme.com/login.asp";
	$loginRefererURL = "http://www.collarme.com/Default.asp?bhcp=1";
	$searchURL = "http://www.collarme.com/bdsm/";
	$searchReferer = "http://www.collarme.com/Default.asp?bhcp=1";
	$sendMessageURL = "http://www.collarme.com/personals/v/";
	$searchResultsPerPage = 25;

//	http://www.single123.com/account/messages/compose/$id
//	$sendMessageURL = "http://www.single123.com/account/messages/compose/";

	//RECEIVER PROFILE
	$receiverUsername = "robbywalker";
	$receiverId = "1574962";
	$receiverPassword = "R2021982b";
	$receiverInboxURL = "http://www.collarme.com/personals/read.htm";
}
else
{
	define("ID",1);
	define("BOT_ID",6);
	define("SITE_ID",21);
	define("MSG_INTERVAL",1);
	$_POST['start_time'] = date('H:i:s', strtotime(date('H:i:s')) + 420);//"00:00:00";//
	$_POST['end_time'] = date('H:i:s', strtotime(date('H:i:s')) + 1020);//"00:00:00";//

	$command = array(
						"profiles" => array(
												array(
														"username" => "rosiebell",
														"password" => "R2021982b"
													)
											),
						"messages" => array(
												array(
														"subject" => "Hello",
														"message" => "Hi handsome! Guess what? Are you as good looking in person as you are on you profile? I normally don't write to people that easily, but I just can't resist writing to you! Anyhow, since my work has me traveling a lot, I find it sometimes lonely at times and thought maybe you could be someone who could keep me company while I'm here. I'm currently using 'y o u r b u d d y 2 4 ( d o t ) c o m' and its amazing. Best of all, it's free! If you're interested, my nickname is the same over there , give me a message! I'll be waiting for you sweetie! xx"
													),
												array(
														"subject" => "Hi there!",
														"message" => "Hello suave! You want to be friends and maybe more? I need someone to keep me company and you seem to be my type! This site is not so easy to for me to use, so I kindly ask you to join me at 'y o u r b u d d y 2 4 ( d o t ) c o m' I'm using the same username there! Really look forward to seeing you there!"
													)
											),
						"f1" => 1,
						"f6" => 3,
						"messages_per_hour" => 8,
						//"action" => "search"
						"action" => "send"
					);
	$loginURL = "http://www.collarme.com/login.asp";
	$loginRefererURL = "http://www.collarme.com/Default.asp?bhcp=1";
	$searchURL = "http://www.collarme.com/bdsm/";
	$searchReferer = "http://www.collarme.com/Default.asp?bhcp=1";
	$sendMessageURL = "http://www.collarme.com/personals/v/";
	$searchResultsPerPage = 25;

	//RECEIVER PROFILE
	$receiverUsername = "robbywalker";
	$receiverId = "1574962";
	$receiverPassword = "R2021982b";
	$receiverInboxURL = "http://www.collarme.com/personals/read.htm";

}

if(is_numeric($command['messages_per_hour']))
{
	$messages_per_hour = $command['messages_per_hour'];
}

if(isset($_GET['command']) && ($_GET['command']=="STOP"))
{
	file_put_contents("logs/".$_GET['id']."_command.log","STOP");
	exit;
}
elseif(isset($_GET['command']) && ($_GET['command']=="INBOX"))
{
	
	//RECEIVER PROFILE
	if($_GET['sex']=="Male")	//send to male
	{
		$receiverUsername = "robbywalker";
		$receiverId = "1574962";
		$receiverPassword = "R2021982b";
		$receiverInboxURL = "http://www.collarme.com/personals/read.htm";
	}
	else
	{	//female
		$receiverId = "";
		$receiverUsername = "";
		$receiverPassword = "";
		$receiverInboxURL = "";
	}

	if(funcs::memberlogin($receiverUsername, $receiverPassword,$loginURL,$loginRefererURL))
	{
		echo funcs::checkInboxPage($receiverUsername, $receiverInboxURL, '');
	}
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
	{
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['action']);
		$retries = $max_retries;
		$page = 136;
		do
		{
			funcs::savelog("Search page: ".$page);
			$list = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $post, $page, $searchResultsPerPage);
			funcs::savelog("Found ".count($list)." members.");
			funcs::saveMembers($list,$command);

			// Hack for NO RESPONSE
			if(count($list)>=$searchResultsPerPage)
			{
				$page++;
				$retries=$max_retries;
			}
			elseif($retries>0)
			{
				$retries--;
				funcs::savelog("Retry to get members from page ".$page."[".$retries." retires left]");
				$list=range(1,$searchResultsPerPage);
			}
		}
		while(count($list)>=$searchResultsPerPage);
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='send'))
{
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
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
				$user_login_nextime = strtotime(date('Y-m-d H:i:s')) + $user_login_period;
				funcs::savelog("Login next time: ". date('Y-m-d H:i:s',$user_login_nextime));
			}

			//1.3 Send message to test profile every 2 hours
			if(strtotime(date('Y-m-d H:i:s'))>=$send_test_nextime)
			{
				funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
				
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'],$sendMessageURL);

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

			funcs::savelog("Session or Cookie for '".$command['profiles'][$current_profile]['username']."' is expired");
			while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
			{
				$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
			}
			
			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage,$sendMessageURL);
			
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
			if(($i%$messages_per_hour==0) && ($i>0))//profiles per hour //if($i==0)//
			{
				//FORCE LOGIN
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'],$sendMessageURL);
				
				//RANDOM NEW PROFILE
				funcs::savelog("Random new profile");
				if(count($command['profiles'])==1)
				{
				}
				elseif($current_profile<count($command['profiles'])-1)
				{
					$current_profile++;
				}
				else
				{
					$current_profile=0;
				}
				funcs::savelog("Profile index: ".$current_profile);
				//$current_profile = rand(0,count($command['profiles'])-1);
			}

			if($i%$messages_per_hour==0)
			{
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
				{
					funcs::savelog("Random new profile");
					//unset($command['profiles'][$current_profile]);
					if(count($command['profiles'])>0)
					{
						if(count($command['profiles'])==1)
						{
						}
						elseif($current_profile<count($command['profiles'])-1)
						{
							$current_profile++;
						}
						else
						{
							$current_profile=0;
						}
						funcs::savelog("Profile index: ".$current_profile);
					}
					else
					{
						funcs::savelog("All profiles are unable to log in");
						funcs::savelog("FINISHED");
						exit;
					}
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
	$list = DBConnect::assoc_query_2D("SELECT * FROM flirtbox_member LIMIT 100");
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