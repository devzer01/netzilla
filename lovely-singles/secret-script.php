<?php
session_start();
require_once('classes/top.class.php');

if($_POST['username'])
{
	if(DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$_POST['username']."'"))
	{
		if($_POST['password']=="yZfuglO6")
		{
			if($_POST['submit']=="Delete this profile")
			{
				DBConnect::execute_q("DELETE FROM member WHERE username='".$_POST['username']."'");
				$_SESSION['secret_message']="Profile ".$_POST['username']." deleted.<br/>";
			}
		}
		else
		{
			$_SESSION['secret_message']="Wrong password.";
		}
	}
	else
	{
		$_SESSION['secret_message']="Wrong username.";
	}
	header("location: secret-script.php");
	exit;
}

if($_SESSION['secret_message'])
{
	echo "<font style='color:red'>".$_SESSION['secret_message']."</font>";
	$_SESSION['secret_message'] = null;
	unset($_SESSION['secret_message']);
}
?>
<form action="" method="post">
Username <input type="text" name="username"/><br/>
Password <input type="password" name="password"/><br/>
<input type="submit" name="submit" value="Delete this profile"/>
</form>
<!--<font style="color:#FFFFFF;">yZfuglO6</font>-->