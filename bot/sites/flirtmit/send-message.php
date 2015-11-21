<?php
ob_start();
require_once 'funcs.php';
set_time_limit(0);

function flush_buffers(){
	echo "<br/><script>window.scrollTo(0, document.body.scrollHeight);</script>";

	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}

if($_POST)
{
	echo "[ ".date("Y-m-d H:i:s")." ] "."Starting...";
	flush_buffers();

	$subject = funcs::replaceWord($_POST['subject']);
	$message = funcs::replaceWord($_POST['message']);
	$start_age = $_POST['start'];
	$end_age = $_POST['end'];
	$timer = $_POST['timer'];
	$count_profile = $_POST['send-amount'];
	//$profile_num = funcs::db_count_profile();
	$profile = funcs::db_get_loginprofile();
	
	foreach ($profile as $dProfile)
	{
		$username = $dProfile['username'];
		$password = $dProfile['password'];
		echo "[ ".date("Y-m-d H:i:s")." ] "."Logging in with profile: ".$dProfile['username'];
		flush_buffers();

		funcs::memberlogin($username, $password);

		$lastpage = funcs::db_get_last_page();
		if(!$lastpage)
		{
			$start_page = 1;
		}
		else
		{
			$start_page = $lastpage['lastpage'] + 1;
		}
	
		$end_page = ($start_page - 1) + ($count_profile / 10);
		
		if ($start_page == $end_page)
		{
			echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
			flush_buffers();

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
			
			foreach($arrRecipient as $result)
			{
				$checkname = funcs::dbCheckSentUser($result['recipient']);

				if($checkname['name'] != $result['recipient'])
				{	
					try
					{
						funcs::send_message($result['recipient'], $subject, $message);
						funcs::insertLog($username, $result['recipient']);
						echo "[ ".date("Y-m-d H:i:s")." ] "."Sent to ". $result['recipient'];
						flush_buffers();						
					}
					catch(Exception $e)
					{
						file_put_contents("textfiles/".$username."age".$start_age.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
						echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$result['recipient'];
						flush_buffers();
					}
					echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
					flush_buffers();
					sleep($timer);
				}
				
			}
			funcs::insertLogPage($username, $start_page);
			unlink($strFileName);
		}
		else
		{
			echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
			flush_buffers();

			for ($page = $start_page; $page <= $end_page; $page++)
			{
				
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
					
				//$i = 1;
				foreach($arrRecipient as $result)
				{
					$checkname = funcs::dbCheckSentUser($result['recipient']);

					if($checkname['name'] != $result['recipient'])
					{	
						try
						{
						funcs::send_message($result['recipient'], $subject, $message);
						funcs::insertLog($username, $result['recipient']);
						}
						catch(Exception $e)
						{
							file_put_contents("textfiles/".$username."age".$start_age.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
							echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$result['recipient'];
						}
						echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
						flush_buffers();
						sleep($timer);
					}
					
				}//foreach
				funcs::insertLogPage($username, $page);
			}
			unlink($strFileName);
		}
		
	}//foreach
}//post


?>
<html>
<head>
<title>flirtmit bot</title>
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