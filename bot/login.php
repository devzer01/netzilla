<?php
session_start();
include("funcs.php");
//unset($_SESSION['password']);
if($_POST['password'])
{
	$_SESSION['password']=$_POST['password'];
	//echo $_SESSION['password'];

	if($_POST['password']==ADMIN_PASSWORD)
		$url = " .";//echo "All";
	elseif($_POST['password']==ADMIN_REPORT_PASSWORD)
		$url = "summary-report.php";//echo "Only Report";
	elseif($_POST['password']==ADMIN_LOGS_PASSWORD)
		$url = "logs.php";//echo "Only Report";
	elseif($_POST['password']==ADMIN_TEST_PASSWORD)
		$url = "bot-test.php";//echo "Only Report";
	
	header("location: $url");
	exit;
}
?>
<form action="login.php" method="post">
Password: <input type="password" name="password"/> <input type="submit" value="Login"/>
</form>