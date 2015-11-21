<?php
session_start();
require_once('classes/top.class.php');

if($_POST['email'])
{
	if(DBConnect::retrieve_value("SELECT id FROM member WHERE email='".$_POST['email']."'"))
	{
		if($_POST['password']=="yZfuglO6")
		{
			if($_POST['submit']=="Delete this profile")
			{
				DBConnect::execute_q("DELETE FROM member WHERE email='".$_POST['email']."'");
				$_SESSION['secret_message']="Email ".$_POST['email']." deleted.<br/>";
			}
		}
		else
		{
			$_SESSION['secret_message']="Wrong password.";
		}
	}
	else
	{
		$_SESSION['secret_message']="Wrong email.";
	}
	header("location: secret-script2.php");
	exit;
}

if($_SESSION['secret_message'])
{
	echo "<font style='color:red'>".$_SESSION['secret_message']."</font>";
	$_SESSION['secret_message'] = null;
	unset($_SESSION['secret_message']);
}
//echo DBConnect::retrieve_value("SELECT username FROM member WHERE id='1'");
//echo DBConnect::retrieve_value("SELECT username FROM member WHERE email='moo_moo382@hotmail.com'");
?>
<form action="" method="post">
Email <input type="text" name="email"/><br/>
Password <input type="password" name="password"/><br/>
<input type="submit" name="submit" value="Delete this profile"/>
</form>