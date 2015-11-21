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
$messages_per_hour = 1;
$max_retries = 3;

if($_POST)
{
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

	$loginURL = "http://www.singlesaroundme.com/login.aspx";
	$loginRefererURL = "http://www.singlesaroundme.com/login.aspx";
	$searchURL = "http://www.singlesaroundme.com/Search2.aspx";
	$searchReferer = "http://www.singlesaroundme.com/Search2.aspx";
	$searchResultsPerPage = 20;
	$sendMessageURL = "http://www.singlesaroundme.com/SendMessage.aspx";

	//RECEIVER PROFILE
	$receiver = array(
		array('username'=>'spiderman5555','id'=>'0','password'=>'man1234')
		);
	/*
	$receiverUsername = "poppy999";
	$receiverId = "662536";
	$receiverPassword = "thtl";*/
	$receiverInboxURL = "http://www.singlesaroundme.com/Mailbox.aspx";
	
}
else
{
	define("ID",1);
	define("BOT_ID",1);
	define("SITE_ID",13);
	define("RUN_COUNT",0);
	file_put_contents("logs/".ID."_run_count.log",RUN_COUNT);

	$command = array(
						"profiles" => array(
												/*array(
														"username" => "HelenaR",
														"password" => "helena88"
													),*/
												array(
														"username" => "LeahLethal",
														"password" => "crazylover"
													),
												/*array(),rosiebell 	R2021982b
														LeahLethal 	crazylover
														monalisa69 	thtl19
														Nikkigreen69 	1q2w3e4r5t
														fairy888 	1q2w3e4r5t
														Murrrka 	thtl19
														sweetyLindz90 	1a2s3d4f5g
														Judith699 	thtl19
														angeljojo88 	thtl19
														Jessieli 	thtl19
														KateJ87 	kate1987*/
											),
						"messages" => array(
												array(
														"subject" => "Hello",
														"message" => "Hi handsome! Guess what? Are you as good looking in person as you are on you profile? yourbuddy24 dot com xx"
													),
												array(
														"subject" => "Hi there!",
														"message" => "You are stunning! Life's too short to be serious all the time, don't you think? yourbuddy24 dot com xx"
													)
											),
						"Search" => 0,
						"ctl00\$cphContent\$dropGender" => 1,
						"ctl00\$cphContent\$dropInterestedIn" => 2,
						"ctl00\$cphContent\$txtAgeFrom" => 16,
						"ctl00\$cphContent\$txtAgeTo" => 70,
						"ctl00\$cphContent\$dropCountry" => "United Kingdom",
						"timer" => (6*60)
					);
	$loginURL = "http://www.singlesaroundme.com/login.aspx";
	$loginRefererURL = "http://www.singlesaroundme.com/login.aspx";
	$searchURL = "http://www.singlesaroundme.com/Search2.aspx";
	$searchReferer = "http://www.singlesaroundme.com/Search2.aspx";
	$searchResultsPerPage = 20;
	$sendMessageURL = "http://www.singlesaroundme.com/SendMessage.aspx";

	//RECEIVER PROFILE
	$receiver = array(
		array('username'=>'spiderman5555','id'=>'0','password'=>'man1234')
		);
	/*
	//RECEIVER PROFILE
	$receiverUsername = "poppy999";
	$receiverId = "662536";
	$receiverPassword = "thtl";*/
	$receiverInboxURL = "http://www.singlesaroundme.com/Mailbox.aspx";
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
		$rcv = 0;
	}
	else
	{	//female
		$rcv = 1;
	}

	if(funcs::memberlogin($receiver[$rcv]['username'], $receiver[$rcv]['password'], $loginURL, $loginRefererURL))
	{
		echo funcs::checkInboxPage($receiver[$rcv]['username'], $receiverInboxURL, '');
	}
}
elseif(isset($_GET['command']) && ($_GET['command']=="BAND"))
{
	$profiles = funcs::checkBandUser();
	foreach($profiles as $profile)
	{
		//echo "<br/>-----------------<br/>".$profile['username']." ".$profile['password']."<br/>";
		funcs::savelog("Checking Band User for: '".$profile['username']."' : '".$profile['password']."'");
		if(funcs::memberlogin($profile['username'], $profile['password'], $loginURL, $loginRefererURL))
		{
			funcs::reuseUser($profile['username'], $profile['password']);
		}
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['Search']) && ($command['Search']=='1'))
{
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'],$loginURL,$loginRefererURL))
	{
		$page = 1;
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['timer']);
		$viewstate = "";
		$retries = $max_retries ;
		do
		{
			funcs::savelog("Search page: ".$page);
			if($page == 1)
			{
				$viewstate = funcs::getViewState($command['profiles'][$current_profile]['username'],$searchReferer);
			}
			elseif($viewstate=="")
			{
				$viewstate = file_get_contents("viewstates/".$command['profiles'][$current_profile]['username']."-".($page-1).".txt");
			}

			list($list, $viewstate) = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $post, $page, $viewstate);
			funcs::savelog("Found ".count($list)." members.");
			funcs::saveMembers($list,$command);

			// Hack for NO RESPONSE
			if(count($list)>=20)
			{
				$page++;
				$retries=$max_retries ;
			}
			elseif($retries>0)
			{
				$retries--;
				funcs::savelog("Retry to get members from page ".$page."[".$retries." retires left]");
				$list=range(1,20);
				$viewstate = "";
			}
		}
		while(count($list)>=$searchResultsPerPage);
	}
	funcs::savelog("FINISHED");
}
elseif(isset($command['Search']) && ($command['Search']=='0'))
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
			$k = 0;
			//FORCE LOG IN
			funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL);

			if(funcs::checkStatus($command['profiles'][$current_profile]['username'], $receiver[$k]['username'])){

				//SENDING TEST MESSAGE
				funcs::savelog("Sending test message to ".$receiver[$k]['username']." with subject: ".$current_subject." and message: ".$current_message);
				funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiver[$k]['username'], $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL);
			}
			else
			{
				if(!(in_array($command['profiles'][$current_profile]['username'], $unsend_profile)))
					array_push($unsend_profile, $command['profiles'][$current_profile]['username']);
				
				if(count($unsend_profile)>=count($command['profiles']))
				{
					funcs::savelog("All profiles are unable to send message");
					funcs::savelog("FINISHED");
					exit;
				}
			}

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
		} //exit;

		if($i%$messages_per_hour==0)
		{
			while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL))
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
				}
				else
				{
					funcs::savelog("All profiles are unable to log in");
					funcs::savelog("FINISHED");
					exit;
				}
			}
		}

		if(funcs::checkStatus($command['profiles'][$current_profile]['username'], $member['username'])){
			$arr_sec = range(30,3600/$messages_per_hour,30);
			shuffle($arr_sec);
			$sleep_time = $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);


			//RANDOM SUBJECT AND MESSAGE
			funcs::savelog("Random new subject and message");
			$current_subject = rand(0,count($command['messages'])-1);
			$current_message = rand(0,count($command['messages'])-1);

			//RANDOM WORDS WITHIN THE SUBJECT AND MESSAGE
			$randomSubject = funcs::randomText($command['messages'][$current_subject]['subject']);
			$randomMessage = funcs::randomText($command['messages'][$current_subject]['message']);

			funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $randomSubject, $randomMessage, $sendMessageURL);
			
			$sleep_time = (3600/$messages_per_hour) - $arr_sec[0];
			funcs::savelog("Sleep for ".$sleep_time." seconds");
			funcs::sleep($sleep_time);
			$i++;
		}
		else
		{
			if(!(in_array($command['profiles'][$current_profile]['username'], $unsend_profile)))
				array_push($unsend_profile, $command['profiles'][$current_profile]['username']);
			
			if(count($unsend_profile)>=count($command['profiles']))
			{
				funcs::savelog("All profiles are unable to send message");
				funcs::savelog("FINISHED");
				exit;
			}
		}
	}
	funcs::savelog("FINISHED");
}
else
{
	$list = DBConnect::assoc_query_2D("SELECT * FROM singlesaroundme_member LIMIT 100");
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