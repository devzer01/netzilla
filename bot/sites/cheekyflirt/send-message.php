<?php
ob_start();
date_default_timezone_set("Asia/Bangkok");
ignore_user_abort(true);
set_time_limit(0);

require_once 'funcs.php';

function flush_buffers(){
	echo "<br/><script>window.scrollTo(0, document.body.scrollHeight);</script>";

	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}


if($_GET['command']=="STOP")
{
	file_put_contents("logs/".$_GET['id']."_command.log","STOP");
	exit;
}
elseif($_POST)
{
	

/*
	$subject = funcs::replaceWord($_POST['subject']);
	$message = funcs::replaceWord($_POST['message']);
	$start_age = $_POST['start'];
	$end_age = $_POST['end'];
	$timer = $_POST['timer'];
	$count_profile = $_POST['send-amount'];
*/	
//	$profile = funcs::db_get_loginprofile();

	$id = $_POST['id'];
	$command = funcs::mb_unserialize($_POST['command']);

	$profiles = $command['profiles'];
	$messages = $command['messages'];
	$start_age = $command['start'];
	$end_age = $command['end'];
	$send_amount = $command['send-amount'];
	$timer = $command['timer'];

	//$log_file = 'logs/'.$_POST['job_id'].'.log';
	
	$log_data = funcs::logging("Starting...", $id);
	echo $log_data;
	flush_buffers();

	foreach ($profiles as $profile)
	{
		$log_data = funcs::logging("Logging in with profile: ".$profile['username'], $id);
		echo $log_data;
		flush_buffers();

		$username = $profile['username'];
		$password = $profile['password'];

		$rand_message = rand(0,2);
		$subject = funcs::replaceWord($messages[$rand_message]['subject']);
		$message = funcs::replaceWord($messages[$rand_message]['message']);
		
		funcs::memberlogin($username, $password);

		//$lastpage = funcs::db_get_last_page();
		if(!$lastpage)
		{
			$start_page = 1;
		}
		else
		{
			$start_page = $lastpage['lastpage'] + 1;
		}		
		$end_page = ($start_page - 1) + ($send_amount / 10);
		
		if ($start_page == $end_page)
		{
			$log_data = funcs::logging("Start sending message...", $id);
			//echo $log_data;
			//flush_buffers();

			$content =  funcs::get_contents($start_age, $end_age, $end_page);
			
			if(!file_exists("textfiles"))
			{
				if(mkdir('textfiles', 0777))
				{
					echo 'make directory done';
				}else {
					echo 'could not create directory';
				}
			}
			
			$strFileName = "textfiles/".$username."age".$start_age.".txt";
			$objFopen = fopen($strFileName, 'w');
			fwrite($objFopen, $content);
			fclose($objFopen);
	
			$data = file_get_contents($strFileName);
	
			$arrRecipient = funcs::file_get_data($data);
					
			for($i = 0; $i<count($arrRecipient); $i++)
			{
				$txt_command = file_get_contents("logs/".$id."_command.log");
				if($txt_command == "STOP")
				{
					funcs::logging("Force stop", $id);
					exit;
				}

				if($i%2==0)
				{
					$checkname = funcs::dbCheckSentUser($arrRecipient[$i]['recipient']);

					if($checkname['name'] != $arrRecipient[$i]['recipient'])
					{	
						//echo $arrRecipient[$i]['recipient']."<br>";
						try
						{
							funcs::send_message($arrRecipient[$i]['recipient'], $subject, $message);
							funcs::insertLog($username, $arrRecipient[$i]['recipient']);

							$log_data = funcs::logging("Sent to ".$arrRecipient[$i]['recipient'], $id);
							//echo $log_data;
 //flush_buffers();
						}
						catch(Exception $e)
						{
							file_put_contents("textfiles/".$username."age".$start_age.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
							$log_data = funcs::logging("EXCEPTION ".$arrRecipient[$i]['recipient'], $id);
							//echo $log_data;
 //flush_buffers();
						}
						$log_data = funcs::logging("Sleep for ".$timer."s.", $id);
						//echo $log_data;
 //flush_buffers();
						sleep($timer);
					}
					else
					{
						$log_data = funcs::logging("Already sent to ".$checkname['name'], $id);
						//echo $log_data;
 //flush_buffers();
					}
				}
				$i++;
			}//for
			unlink($strFileName);
			funcs::insertLogPage($username, $start_page);
			sleep(1);
		}
		else
		{
			$log_data = funcs::logging("Start sending message...", $id);
			//echo $log_data;
			//flush_buffers();
			for ($page = $start_page; $page <= $end_page; $page++)
			{
				$log_data = funcs::logging("Page: ".$page, $id);
				//echo $log_data;
				//flush_buffers();
				$content =  funcs::get_contents($start_age, $end_age, $page);
					
				if(!file_exists("textfiles"))
				{
					if(mkdir('textfiles', 0777))
					{
						echo 'make directory done';
					}else {
						echo 'could not create directory';
					}
					
				}
					
				$strFileName = "textfiles/".$username."age".$start_age."to$end_age-page-".$page.".txt";
				$objFopen = fopen($strFileName, 'w');
				fwrite($objFopen, $content);
				fclose($objFopen);
				
				$data = file_get_contents($strFileName);
				
				$arrRecipient = funcs::file_get_data($data);		
					
				for($i = 0; $i<count($arrRecipient); $i++)
				{
					if(file_exists("logs/".$id."_command.log"))
					{
						$txt_command = file_get_contents("logs/".$id."_command.log");
						if($txt_command == "STOP")
						{
							funcs::logging("Force stop", $id);
							exit;
						}
					}

					if($i%2==0)
					{
							$log_data = funcs::logging("Sending to: ".$arrRecipient[$i]['recipient'], $id);
							//echo $log_data;
							//flush_buffers();

						$checkname = funcs::dbCheckSentUser($arrRecipient[$i]['recipient']);

						if($checkname['name'] != $arrRecipient[$i]['recipient'])
						{
							try
							{
								//funcs::send_message($arrRecipient[$i]['recipient'], $subject, $message);
								funcs::insertLog($username, $arrRecipient[$i]['recipient']);
								$log_data = funcs::logging("Sent to ".$arrRecipient[$i]['recipient'], $id);
								//echo $log_data;
								//flush_buffers();
							}
							catch(Exception $e)
							{
								file_put_contents("textfiles/".$username."age".$start_age.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
								//$log_data = funcs::logging("EXCEPTION ".$arrRecipient[$i]['recipient'], $id);
								//echo $log_data;
								flush_buffers();
							}

							//echo $arrRecipient[$i]['recipient']."<br>";
							$log_data = funcs::logging("Sleep for ".$timer."s.", $id);
							//echo $log_data;
							//flush_buffers();
							sleep($timer);
						}
						else
						{
							$log_data = funcs::logging("Already sent to ".$checkname['name'], $id);
							//echo $log_data;
							//flush_buffers();
						}
					}
					$i++;
				}//for
				unlink($strFileName);
				funcs::insertLogPage($username, $page);
				sleep(1);
			}//for page
		}//else
		
	}//foreach
}//post


?>
<html>
<head>
<title>cheekyflirt bot</title>
<style type="text/css">
div {
	line-height: 50px;
}
</style>
<script type="text/javascript">
function listTo(age)
{
	var end = document.getElementById('end');
	
	if(age==60)
	{
		option = '<option value=' + age + '>' + age + '</option>';
		end.innerHTML = option;
	}
	else
	{
		var option;
		for(var i=age; i<=60; i++)
		{
			option = option + '<option value=' + i + '>' + i + '</option>';
		}
		end.innerHTML = option;
		
	}
}

function put_message(val)
{
	var ele_text = document.getElementById('message');
	ele_text.value = val;
}

function checkform()
{
	if(document.getElementById('subject').value == "")
	{
		alert ('please enter subject');
		document.getElementById('subject').focus();
		return false;
	}

	if(document.getElementById('message').value == "")
	{
		alert ('please enter message.');
		document.getElementById('message').focus();

		return false;
	}
}
</script>
</head>
<body>

<?php
if($_POST)
{
	if($_POST['subject'] == "")
	{
		$alertText = "please input subject.";
	}
	elseif ($_POST['message'] == "")
	{
		$alertText = "please input message";
	}
	else
	{
		$alertText = "";
	}

	if ($alertText != "")
	{
		echo "<font color='red'>$alertText</font>";
	}
}


if(!$_POST) {
	?>
	<div style="margin: 15px auto; width: 800px;">
		userlist : <select name="user-list" id="user-list">
		<?php
		$arrLoginProfile = funcs::db_get_loginprofile();
		foreach ($arrLoginProfile as $loginData)
		{
			echo "<option value='$loginData[id]'>$loginData[username]</option>";
		}
		//user list
		?>
		</select> <a href="add-profile.php">Add user</a> || <a
			href="delete-user.php">delete user.</a>
	</div>
	<hr>
	<form name="selectpage" id="selectpage" action="" method="post"
		onsubmit="return checkform();">
		<div style="margin: 0 auto; width: 800px;">
			<div>
				waiting every: <select name="timer">
					<option value="120">2 min</option>
					<option value="180">3 min</option>
					<option value="240">4 min</option>
					<option value="300">5 min</option>
					<option value="600">10 min</option>
					<option value="900">15 min</option>
				</select>
			</div>
			<div>
				send amount: <select name="send-amount">
				<?php
				for ($i=10; $i<=500; $i++)
				{
					if(($i % 10) == 0)
					{
						echo "<option value='$i'>$i</option>";
					}
				}
				?>
				</select>
			</div>

			<div>
				<hr>
				search option::

			</div>
			<div>
				Age: 
				<select name="start" onchange="listTo(this.value)">
					<?php 
						for ($i = 18; $i <= 60; $i++) {
							echo "<option value='$i'>$i</option>";							
						}
					?>
				</select>
				to: 
				<select name="end" id="end">
					<?php 
						for ($i = 18; $i <= 60; $i++) {
							echo "<option value='$i'>$i</option>";							
						}
					?>
				</select>
			</div>
			
			<div>
				gender: <select name="gender">
					<option selected="selected" value="M">Male</option>
					<!--<option value="F">Female</option>-->
				</select>
			</div>

			<hr>

			<div>
				subject: <br> <input type="text" name="subject" id="subject"
					value="">
			</div>
			<div>
				select message:
				<select name="message_template" id="message_template" onchange="put_message(this.value)">
					<option value="">::select message::</option>
					<?php
						$result_message = funcs::db_get_message();
						foreach ($result_message as $m_result) {
							echo "<option value=\"$m_result[text_message]\">$m_result[id]</option>";
						}
					?>
				</select>
				
			</div>
			<div>
				message: <br>
				<textarea name="message" id="message" rows="10" cols="80"></textarea>
			</div>
			<div>
				<input name="submit" type="submit" value="submit">
			</div>
		</div>
	</form>
	
	<?php 
	}else {
	?>
	<div><H2><a href="send-message.php">back to send message</a></H2></div>
	<?php 
	}
	?>

</body>
</html>
