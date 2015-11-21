<?php

/**
 * PDO handler function 
 */

function getDbHandler()
{
	$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	$dbo = new PDO("mysql:host=10.0.0.2;dbname=flirt48.net", "root", "tyZB[Tp.zsX^u", $options);
	$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbo;
}
