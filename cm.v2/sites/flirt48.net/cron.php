<?php
define('BASE_DIR', '/var/www/cm.v2/');

set_include_path(BASE_DIR . PATH_SEPARATOR . get_include_path());

require_once('classes/cli.top.php');
require_once('cron/queue_runner.php');

while (true) {
	printf("Running Queue\n");
	runQueue();
	sleep(3);
}

//require_once "config.php";
//require_once (BASE_DIR . "modules/common.php");


