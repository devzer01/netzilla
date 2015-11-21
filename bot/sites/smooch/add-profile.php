<?php
require_once 'include/dbconnect.php';
if($_POST)
{
	$userid = $_POST['userid'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
// 	funcs::db_insert_profile($username, $password);
 $sql = "insert into smooch_user_profile (id, userid, username, password) values ('', '$userid', '$username', '$password')";
 
 if(mysql_query($sql)){
 	echo "<font color='green'>Add user completed.</font><br><br>";
 }else {
 	echo "<font color='red'>Couldn't add user please try again later.</font>";
 }
}
?>
<html>
<head>
<style>
body {
	margin:30 auto;
	width:800px;
}
</style>
</head>
<body>
	<form name="addprofile" method="post" action="">
		<div>
			user ID: <input type="text" name="userid" id="userid">
		</div>
		<div>
			username: <input type="text" name="username" id="username">
		</div>
		<div>
			password: <input type="text" name="password" id="password">
		</div>
		<div>
			<input type="submit" name="submit" value="submit">
		</div>
	</form>
	
<a href="send-message.php">back to send message.</a>
</body>
</html>