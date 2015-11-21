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
	define("RUN_COUNT",0);
	$command = array(
						"profiles" => array(
												array(
														"username" => "Rockybella", 	
														"password" => "aon1201"
													),
												array(
														"username" => "Maxmovie007",
														"password" => "thtl9999"
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
						"Gender" => 'Female',
						"Gender_Look" => 'Male',
						"age_from" => '18',
						"age_to" => '50',
						"country[]" => 'GB',
						"search_zip" => '',
						"search_miles" => '500',
						"search_name" => '',
						"Submit" => 'Submit',
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
	$loginURL				= "http://www.datenz.com/login.asp";
	$loginRefererURL		= "http://www.datenz.com/";
	$searchURL				= "http://www.datenz.com/member/searching.asp";
	$searchReferer			= "http://www.datenz.com/member/search_quick.asp";
	$searchResultsPerPage	= 10;
	$sendMessageURL			= "http://www.datenz.com/member/message_save.asp";
	$sendMessageReferer		= "http://www.datenz.com/member/message_form.asp";
	$receiverInboxURL		= "http://www.datenz.com/member/message_inbox.asp";

	if(is_numeric($command['messages_per_hour']))
	{
		$messages_per_hour = $command['messages_per_hour'];
	}

	//RECEIVER PROFILE
	if($command['Gender_Look']=="Male")
	{
		$receiverId			= "6637";
		$receiverUsername	= "Maxmovie007";
		$receiverPassword	= "thtl9999";
	}
	else
	{
		$receiverId			= "6636";
		$receiverUsername	= "Rockybella";
		$receiverPassword	= "aon1201";
	}
}

//COMMAND ACTION
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
		$receiverId			= "6637";
		$receiverUsername	= "Maxmovie007";
		$receiverPassword	= "thtl9999";
		$receiverInboxURL	= "http://www.datenz.com/member/message_inbox.asp";
	}
	else
	{	//female
		$receiverId			= "6636";
		$receiverUsername	= "Rockybella";
		$receiverPassword	= "aon1201";
		$receiverInboxURL	= "http://www.datenz.com/member/message_inbox.asp";
	}

	if(funcs::memberlogin($receiverUsername, $receiverPassword, $loginURL, $loginRefererURL))
	{
		echo funcs::checkInboxPage($receiverUsername, $receiverInboxURL, '');
	}
}
elseif(isset($command['action']) && ($command['action']=='search'))
{
	//funcs::getMembersFromSearchResult('','','');
	if(funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL))
	{
		$post = $command;
		unset($post['profiles']);
		unset($post['messages']);
		unset($post['timer']);
		unset($post['country']);
		/*echo "<pre>";
		print_r($command['country']);
		echo "</pre>";*/
		if(is_array($command['country']))
		{
			foreach($command['country'] as $val)
			{
				funcs::savelog("Search for country: ".$val);
				$page = 1;
				$post['country'] = $val;
				
				$searchData = http_build_query($post);
				do
				{
					funcs::savelog("Search page: ".$page);
					list($list, $searchData) = funcs::getSearchResult($command['profiles'][$current_profile]['username'], $searchURL, $searchReferer, $searchData, $page);
					funcs::savelog("Found ".count($list)." members.");
					funcs::savelog("Saving to database");
					funcs::saveMembers($list, $post);
					funcs::savelog("Saving done");

					$page++;
				}
				while(count($list)>=$searchResultsPerPage);//while(funcs::getCountExistingUser()>=122142);//($page<5);// /**/
			}
		}
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
			funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL);
			//SENDING TEST MESSAGE
			funcs::savelog("Sending test message to ".$receiverUsername." with subject: ".$current_subject." and message: ".$current_message);
			funcs::sendMessage($command['profiles'][$current_profile]['username'], $receiverUsername, $receiverId, $command['messages'][$current_subject]['subject'], $command['messages'][$current_subject]['message'], $sendMessageURL, $sendMessageReferer);

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
		}

		if($i%$messages_per_hours==0)
		{
			while(!funcs::memberlogin($command['profiles'][$current_profile]['username'], $command['profiles'][$current_profile]['password'], $loginURL, $loginRefererURL))
			{
				funcs::savelog("Random new profile");
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

		$arr_sec = range(30,3600/$messages_per_hours,30);
		shuffle($arr_sec);
		$sleep_time = $arr_sec[0];
		funcs::savelog("Sleep for ".$sleep_time." seconds");
		funcs::sleep($sleep_time);

		funcs::savelog("Sending message to ".$member['username']." with subject: ".$current_subject." and message: ".$current_message);
		funcs::sendMessage($command['profiles'][$current_profile]['username'], $member['username'], $member['userid'], $randomSubject, $randomMessage, $sendMessageURL, $sendMessageReferer);
		
		$sleep_time = (3600/$messages_per_hours) - $arr_sec[0];
		funcs::savelog("Sleep for ".$sleep_time." seconds");
		funcs::sleep($sleep_time);
		$i++;
	}
	funcs::savelog("FINISHED");
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