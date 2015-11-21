<?php
session_start();
$_SESSION['password'] = "1234";
require_once '_include/dbconnect.php';
require_once 'funcs.php';
if($_POST)
{
	if((isset($_POST['email'])) && (isset($_POST['password'])) && ($_POST['email']!=""))
	{

		$sql = "SELECT user FROM email WHERE user = '" . mysql_real_escape_string(trim($_POST['email'])) . "'";
		
		$rs = mysql_query($sql);
		
		if (mysql_num_rows($rs) == 0) {
			$sql = "INSERT INTO email (user, password, created) VALUES ('".trim($_POST['email'])."', '".trim($_POST['password'])."', NOW())";
		 
			if(mysql_query($sql)){
				echo "Email Added completed.<br><br>";
			}
		} else {
			echo "Email account already exists";
		}
	}
}

if (isset($_GET['action']) && $_GET['action'] == 'del') {
	
	$sql = "DELETE FROM email WHERE id = " . $_GET['id'];
	mysql_query($sql);
}

$sql = "SELECT id, user, password FROM email ORDER BY created DESC ";

$rs = mysql_query($sql);


?>
<html>
<head>
<title>BOT - Users</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript" type="text/javascript" src="_include/jquery-1.7.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body class="manage">
	<form name="form_insert" method="post" action="">
		<div class="boxcontainner">
			<span class="label">Email Address:</span>
			<span class="field">
				<input type="text" name="email" id="email" class="box">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">Password:</span>
			<span class="field">
				<input type="text" name="password" id="password" class="box">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label">&nbsp;</span>
				<input type="submit" name="submit" value="Insert" class="button">
			<span class="field">
			</span>
		</div>
	</form>
	
	<table>
		<tr>
			<td>Email</td>
			<td>Password</td>
			<td>Delete</td>
		</tr>
		
		<?php while ($row = mysql_fetch_assoc($rs)) { ?>
		
			<tr>
				<td><?php echo $row['user']; ?></td>
				<td><?php echo $row['password']; ?></td>
				<td><a href="?action=del&id=<?php echo $row['id'];?>">Delete</a></td>
			</tr>
		
		<?php } ?>
	</table>
	
</body>
</html>