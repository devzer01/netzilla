<?php
session_start();
require_once "funcs.php";

/* if($_POST)
{
	$username = $_POST['login-name'];
	$password = $_POST['login-pass'];
	
// 	echo funcs::correctLogin($username, $password);
	
// 	if($login)
// 	{
		$_SESSION['username'] = $username;
		$_SESSION['password'] = $password;
		
		header("Location: send-message.php");
// 	}
} */
?>
<html>
<head>
<title>::login::</title>
</head>
<body>
	<div style="margin:30 auto; width:300px;">
		
		<form action="send-message.php" name="login" method="post">
			<div>
				USERNAME :: <input type="text" name="login-name" id="login-name">
			</div>
			<div>
				PASSWORD ::	<input type="text" name="login-pass" id="login-pass">
			</div>
			<div>
				<input type="submit" value="SUBMIT">
			</div>
		</form>
		
	</div>
</body>
</html>