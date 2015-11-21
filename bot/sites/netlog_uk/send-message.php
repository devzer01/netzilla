<?php
ob_start();
set_time_limit(0);
date_default_timezone_set("Asia/Bangkok");

require_once('DBconnect.php');
require_once('netlog_uk.php');
require_once('config.php');

$bot = new netlog_uk($_POST);

while($bot->countAvailableUsers())
{
	if($bot->login())
	{
		// Do all stuffs here
		$bot->work();

		$bot->logout();
		$bot->sleep(600);
	}
}
?>