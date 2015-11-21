<?php
require_once 'funcs.php';
if ($_POST) {
	$deleteId = $_POST['user'];

	$sql = "delete from smooch_user_profile where id = $deleteId limit 1";

	if(mysql_query($sql))
	{
		$text = "<font color='green'>delete completed!!!</font>";
	}
	else {
		$text = "<font color='red'>could not delete. please try again later!!!</font>";
	}
}//if post

$arrProfile = funcs::db_get_loginprofile();

?>
<html>
<head>

</head>
<body>

	<div style="margin: 15px auto; width: 800px;">


	<?php
	if (isset($text))
	{
		echo $text;
	}
	?>
	<div> <a href="send-message.php">Back to send message.</a>
		<div>
			<form name="delete-user" action="" method="post">
				<div>
					select delete profile: <select name="user">
					<?php
					foreach ($arrProfile as $profile) {
						echo "<option value='$profile[id]'>$profile[username]</option>";
					}
					?>
					</select>
				</div>
				<div style="width: 200px;">
					<input type="submit" name="submit" value=" submit ">
				</div>
			</form>
		</div>


	</div>
</body>

</html>
