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
ignore_user_abort(true);
require_once('DBconnect.php');
require_once('funcs.php');
require_once('config.php');

$current_profile		= 0;
$current_subject		= 0;
$current_message		= 0;
$messages_per_hours		= 8;

if($_POST)
{
	define("ID",$_POST['id']);//'475'
	define("BOT_ID",$_POST['server']);//'3'
	define("SITE_ID",$_POST['site']);
	define("RUN_COUNT",$_POST['run_count']);//'0'
	$command = funcs::mb_unserialize($_POST['command']);
	//$db_command = DBConnect::retrieve_value("SELECT command FROM commands WHERE id ='1198'");
	//$command = funcs::mb_unserialize($db_command);
	$_POST['command'] = $command;
	file_put_contents("logs/".ID."_post.log",print_r($_POST,true));
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("SITE_ID",34);
	define("RUN_COUNT",0);
	$_POST['start_time'] = date('H:i:s', strtotime(date('H:i:s')) + 420);//"00:00:00";//
	$_POST['end_time'] = date('H:i:s', strtotime(date('H:i:s')) + 1620);//"00:00:00";//
	$command = array(
						"profiles" => array(
												array(
														"username" => "rosynaughty77",	
														"password" => "7383812"
													),
												array(
														"username" => "mariorangins",
														"password" => "6959476"
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
						//"action" => "search",
						"action" => "send",
						"Seeking_Gender" => "12", //Male = 1, Female = 2
						//"Seeking_Age_From" => "18",
						//"Seeking_Age_To" => "80",
						//"Seeking_Country" => "United States",
						"messages_per_hour" => 8,
						"timer" => (6*60) 
					);
	/*$db_command = DBConnect::retrieve_value("SELECT command FROM commands WHERE id ='1198'");
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
	$headerOpt				= array('Host: www.2busy2date.com', 'Origin: http://www.2busy2date.com');

	$preLoginURL			= "http://www.2busy2date.com/index.php";
	$preLoginRefererURL		= "http://www.2busy2date.com/index.php";

	$loginURL				= "http://www.2busy2date.com/pages/login.php";
	$loginRefererURL		= "http://www.2busy2date.com/pages/login.php";

	$logoutURL				= "http://www.2busy2date.com/pages/logout.php";
	$logoutRefererURL		= "http://www.2busy2date.com/pages/my_page.php";

	$searchURL				= "http://www.2busy2date.com/pages/search/search.php";
	$searchReferer			= "http://www.2busy2date.com/pages/search/search.php";
	$searchResultsPerPage	= 24;

	$sendMessageURL			= "http://www.2busy2date.com/pages/email/newemail.php";
	$sendMessageReferer		= "http://www.2busy2date.com/pages/email/newemail.php";

	$receiverInboxURL		= "http://www.2busy2date.com/pages/my_page.php";

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}

	$receiverProfiles = funcs::getRecieverProfile();

	//RECEIVER PROFILE
	if($command['Seeking_Gender']=="12")
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
		funcs::savelog("Checking Band User for: '".$profile['username']."' : '".$profile['password']."'");
		if(funcs::memberlogin($profile['username'], $profile['password'], $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $current_profile, $total_proflies))
		{
			funcs::setUserToReuse($profile['username'], $profile['password']);
		}
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	//for($i=1; $i<=265; $i++) //In case loop through all country
	//{
		//funcs::savelog("Search for country id:".$i);
		//FORCE LOG OUT 
		funcs::memberLogOut($command['profiles'][$current_profile]['username'], $logoutURL, $logoutRefererURL, $headerOpt);
		if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $current_profile, $total_proflies))
		{
			$post = $command;
			unset($post['profiles']);
			unset($post['messages']);
			unset($post['timer']);
			//$post['ddCountry'] = $i;
			$page = 1;
			$postData = $post;//http_build_query($post);//
			//list($viewstate, $eventValidation) = funcs::getViewStateAndEventValidation($command['profiles'][$current_profile]['username'], $searchURL);
			$returnParams = "";

			do
			{
				funcs::savelog("Search page: ".$page);
				list($list, $returnParams) = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $headerOpt, $postData, $page, $returnParams);
				funcs::savelog("Found ".count($list)." members.");
				funcs::savelog("Saving to database");
				funcs::saveMembers($list, $post);
				funcs::savelog("Saving done");

				$page++;
			}
			while(count($list)>=$searchResultsPerPage);//($page<3);// while(funcs::getCountExistingUser()>=122142);///**/
		}
	//}
	funcs::savelog("FINISHED");
}
elseif(isset($command['action']) && ($command['action']=='send'))
{
	$user_login_period = 60*20; // every half an hour
	$user_login_nextime = strtotime(date('Y-m-d H:i:s')); //die();

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
		//funcs::checkRunningTime($start_time, $end_time);
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
			//funcs::checkRunningTime($start_time, $end_time);

			if(strtotime(date('Y-m-d H:i:s'))>=$user_login_nextime)//($i%100==0)
			{
				//FORCE LOG OUT 
				funcs::memberLogOut($command['profiles'][$current_profile]['username'], $logoutURL, $logoutRefererURL, $headerOpt);
				//RANDOM NEW PROFILE
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
					$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				}
				$user_login_nextime = strtotime(date('Y-m-d H:i:s')) + $user_login_period;
				funcs::savelog("Login next time: ". date('Y-m-d H:i:s',$user_login_nextime));
			}

			if(strtotime(date('Y-m-d H:i:s'))>=$send_test_nextime)
			{
				funcs::savelog("Send test message");
				/**
				 * SENDING MESSAGE TO TEST PROFILE
				 **/
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer, $headerOpt);

				//$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
				$send_test_nextime = strtotime(date('Y-m-d H:i:s')) + $send_test_period;
				funcs::savelog("Send test message next time: ". date('Y-m-d H:i:s',$send_test_nextime));
			}

			//funcs::savelog("Send message " . date('Y-m-d H:i:s',$send_test_nextime));
			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			if($messages_per_hour!="")
			{
				$arr_sec = range(10,3600/$messages_per_hour,10);
				shuffle($arr_sec);
				$sleep_time = $arr_sec[0];
				funcs::savelog("Sleep for ".$sleep_time." seconds");
				funcs::sleep($sleep_time);
			}

			funcs::savelog("Sending message to ".$member." with subject: ".$current_subject." and message: ".$current_message);
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
	else
	{
		funcs::savelog("Getting list of members");
		funcs::savelog("Test profile is '".$receiverUsername."'");

		$i=0;
		while($member=funcs::getNextMember($command))
		{
			if(($i%$messages_per_hour==0) && ($i>0))//profiles per hour //if($i==0)//
			{
				//FORCE LOG OUT 
				funcs::memberLogOut($command['profiles'][$current_profile]['username'], $logoutURL, $logoutRefererURL, $headerOpt);
				//FORCE LOGIN
				funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $current_profile, $total_proflies);
				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer, $headerOpt);

				//RANDOM NEW PROFILE
				$current_profile = funcs::checkCurrentProfile($command['profiles'], $current_profile);
			} //exit;

			if($i%$messages_per_hours==0)
			{
				//FORCE LOG OUT 
				funcs::memberLogOut($command['profiles'][$current_profile]['username'], $logoutURL, $logoutRefererURL, $headerOpt);
				while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL, $preLoginURL, $preLoginRefererURL, $headerOpt, $current_profile, $total_proflies))
				{
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

			$arr_sec = range(30,3600/$messages_per_hours,30);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage, $sendMessageURL, $sendMessageReferer, $headerOpt);

			$sleep_time = (3600/$messages_per_hours) - $arr_sec[0];
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