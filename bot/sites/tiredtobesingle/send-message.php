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

if($_POST)
{
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

	$loginURL = "http://www.tiredtobesingle.com/LoginRequired.aspx?cc=1";
	$loginRefererURL = "http://www.tiredtobesingle.com/LoginRequired.aspx?cc=1";
	$searchURL = "http://www.tiredtobesingle.com/SearchResults.aspx";
	$searchReferer = "http://www.tiredtobesingle.com/search.aspx";
	$sendMessageURL = "http://www.tiredtobesingle.com/members/messagewrite.aspx?to=";
	$sendMessageRefererURL = "http://www.tiredtobesingle.com/members/messagewrite.aspx?to=";
	$searchResultsPerPage = 10;

	//RECEIVER PROFILE
	$receiverUsername = "katiehill374@yahoo.com";
	$receiverUserid = "518266";
	$receiverPassword = "R2021982b";
	$receiverInboxURL = "http://www.tiredtobesingle.com/members/messages.aspx";
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("MSG_INTERVAL",1);
	define("SITE_ID",30);
	define("RUN_COUNT",0);
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);

	$command = array(
						"profiles" => array(
												array(
														"username" => "jeleetawrong@yahoo.com",
														"password" => "thtl19"
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
						"ctl00\$CPHM\$rbGender" => "m",
						"ctl00\$CPHM\$age1" => "18",
						"ctl00\$CPHM\$age2" => "60",
						"ctl00\$CPHM\$country" => "391",
						//"action" => "search"
						"action" => "send"
					);
	$loginURL = "http://www.tiredtobesingle.com/LoginRequired.aspx?cc=1";
	$loginRefererURL = "http://www.tiredtobesingle.com/LoginRequired.aspx?cc=1";
	$searchURL = "http://www.tiredtobesingle.com/SearchResults.aspx";
	$searchReferer = "http://www.tiredtobesingle.com/search.aspx";
	$sendMessageURL = "http://www.tiredtobesingle.com/members/messagewrite.aspx?to=";
	$sendMessageRefererURL = "http://www.tiredtobesingle.com/members/messagewrite.aspx?to=";
	$searchResultsPerPage = 10;

	//RECEIVER PROFILE
	$receiverUsername = "katiehill374@yahoo.com";
	$receiverUserid = "518266";
	$receiverPassword = "R2021982b";
	$receiverInboxURL = "http://www.tiredtobesingle.com/members/messages.aspx";
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
elseif(isset($command['action']) && ($command['action']=='search'))
{
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
	{
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['action']);

		$retries = $max_retries;
		$page = 1;
		do
		{
			funcs::savelog("Search page: ".$page);
			$list = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $post, $page, $searchResultsPerPage);
			funcs::savelog("Found ".count($list)." members.");
			funcs::saveMembers($list,$post);

			// Hack for NO RESPONSE
			if(count($list)>=$searchResultsPerPage)
			{
				$page++;
				$retries=$max_retries;
			}
			elseif($retries>=0)
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
	funcs::savelog("Checking start time");
	funcs::checkRunningTime($_POST['start_time'], $_POST['end_time']);

	funcs::savelog("Getting list of members");
	funcs::savelog("Test profile is '".$receiverUsername."'");

	$i=0;
	while($member=funcs::getNextMember($command))
	{
		funcs::checkRunningTime($_POST['start_time'], $_POST['end_time']);

		if(($i%$messages_per_hour==0) && ($i>0))//profiles per hour //if($i==0)//
		{
			//FORCE LOGIN
			funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL);
			//SENDING TEST MESSAGE
			funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverUserid, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'],$sendMessageURL,$sendMessageRefererURL);
			
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
		funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage,$sendMessageURL,$sendMessageRefererURL);

		$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
		funcs::savelog("Sleep for ".$sleep_time." seconds");
		funcs::sleep($sleep_time);
		$i++;
	}
	funcs::savelog("FINISHED");
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