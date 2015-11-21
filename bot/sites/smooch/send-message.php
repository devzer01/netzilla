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
	$send_amount = $_POST['send-amount'];
	$timer = $_POST['timer'];

	$profile = funcs::db_get_loginprofile();

	foreach ($profile as $dProfile)
	{
		echo "[ ".date("Y-m-d H:i:s")." ] "."Logging in with profile: ".$dProfile['username'];
		flush_buffers();
		$userid = $dProfile['userid'];
		$username = $dProfile['username'];
		$password = $dProfile['password'];

		$lastpage = funcs::db_get_last_page();

		if(!$lastpage)
		{
			$start = 1;
		}
		else {
			$start = $lastpage['lastpage'] + 1;
		}
		
		$end = ($start - 1) + ($send_amount / 10);

		if($start == $end)
		{
			echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
			flush_buffers();

			funcs::insertLogPage($username, $start);
			
			$url = "http://www.smooch.com/asp/search.asp?anc=0&pg=$start&search=&ct=D&sortby=3";
			$content =  funcs::get_contents($url, $username, $password);

			// 			create file
			$strFileName = "textfiles/".$username."-page-".$start.".txt";
			$objFopen = fopen($strFileName, 'w');
			fwrite($objFopen, $content);
			fclose($objFopen);

			// 			get content from file
			$data = file_get_contents($strFileName);
			
			$arrIdName = funcs::getIdUsername($data);
			
			foreach($arrIdName as $val)
			{
				echo "[ ".date("Y-m-d H:i:s")." ] "."Sending to: ".$val[1];
				flush_buffers();
				// 				echo '<br>id: '.$val[0].' // username: '.$val[1];
				$checkname = funcs::dbCheckSentUser($val[1]);
				if($checkname['name'] != $val[1])
				{
					//send message
					try
					{
						funcs::sendMessage($subject, $message, $userid, $username, $password, $val[0], $val[1]);
						echo "[ ".date("Y-m-d H:i:s")." ] "."Sent to ".$val[1];
						flush_buffers();
						
						funcs::insertLog($username, $val[1]);
					}
					catch(Exception $e)
					{
						file_put_contents("textfiles/".$username."-page-".$start.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
						echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$val[1];
						flush_buffers();
					}
						echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
						flush_buffers();
						sleep($timer);
					
				}
				else
				{
					echo "[ ".date("Y-m-d H:i:s")." ] "."Already sent to ".$checkname['name'];
					flush_buffers();
				}
			}//foreach
			unlink($strFileName);

		}
		else
		{
			echo "[ ".date("Y-m-d H:i:s")." ] "."Start sending message...";
			flush_buffers();

			for($page = $start; $page <= $end; $page++)
			{
				echo "Getting list of user profiles...";
				flush_buffers();

				funcs::insertLogPage($username, $page);
				$url = "http://www.smooch.com/asp/search.asp?anc=0&pg=$page&search=&ct=D&sortby=3";
				$content =  funcs::get_contents($url, $username, $password);

				// 				create file
				$strFileName = "textfiles/".$username."-page-".$start.".txt";
				$objFopen = fopen($strFileName, 'w');
				fwrite($objFopen, $content);
				fclose($objFopen);

				// 				get content from file
				$data = file_get_contents($strFileName);

				// 				$str = db_get_content($url);
				// 				$data = stripslashes($str['data']);

				$arrIdName = funcs::getIdUsername($data);

				foreach($arrIdName as $val)
				{
					echo "[ ".date("Y-m-d H:i:s")." ] "."Sending to: ".$val[1];
					flush_buffers();
					$checkname = funcs::dbCheckSentUser($val[1]);
					
					if($checkname['name'] != $val[1])
					{
						
						try
						{
							//send message
							funcs::sendMessage($subject, $message, $userid, $username, $password, $val[0], $val[1]);
							echo "[ ".date("Y-m-d H:i:s")." ] "."Sent to ".$arrRecipient[$i]['recipient'];
							flush_buffers();
							
							funcs::insertLog($username, $val[1]);
							//echo "message sent to " . $val[1] . "<br>";
						}
						catch(Exception $e)
						{
							file_put_contents("textfiles/".$username."-page-".$start.".txt","[ ".date("Y-m-d H:i:s")." ] ".$e->getMessage(),FILE_APPEND);
							echo "[ ".date("Y-m-d H:i:s")." ] "."EXCEPTION ".$val[1];
							flush_buffers();
						}
						echo "[ ".date("Y-m-d H:i:s")." ] "."Sleep for ".$timer."s.";
						flush_buffers();
						sleep($timer);
					}
					else
					{
						echo "[ ".date("Y-m-d H:i:s")." ] "."Already sent to ".$checkname['name'];
						flush_buffers();
					}
				}//foreach
				unlink($strFileName);
			}
		}

	}



}


?>
<html>
<head>
<title>smooch.com bot</title>
<style type="text/css">
div {
	line-height: 50px;
}
</style>
<script type="text/javascript">
function listTo(page)
{
	var end = document.getElementById('end');
	
	if(page==25)
	{
		option = '<option value=' + page + '>' + page + '</option>';
		end.innerHTML = option;
	}
	else
	{
		var option;
		for(var i=page; i<=25; i++)
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
		</select> <a href="add-profile.php">Add user</a>  ||  <a href="delete-user.php">delete user.</a>
	</div>
	<?php 
	if(!$_POST) {
	?>
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