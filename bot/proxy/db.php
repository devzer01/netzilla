<?php

/**
 * PDO handler function 
 */

function getDbHandler()
{
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	return new PDO("mysql:host=" . SMS_DBHOST . ";dbname=" . SMS_DB, SMS_DBUSER, SMS_DBPASS, $options);
}
