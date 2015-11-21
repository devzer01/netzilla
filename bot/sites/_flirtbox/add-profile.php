<?php
require_once 'include/dbconnect.php';
if($_POST)
{
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
// 	funcs::db_insert_profile($username, $password);
 $sql = "insert into user_profile (id, username, password) values ('', '$username', '$password')";
 
 if(mysql_query($sql)){
 	echo "Add user completed.<br><br>";
 }else {
 	echo "Couldn't add user please try again later.";
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