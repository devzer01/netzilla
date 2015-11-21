<?php
require("classes/config.php");
if(file_exists(SITE.$_GET['q']))
	header("location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/".SITE.$_GET['q']);
?>