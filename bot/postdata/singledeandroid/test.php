<?php

require_once "config.php";
require_once "bot.php";
require_once "singledeandroid.php";


$worker = new singledeandroid();

$sql = "SELECT name FROM city";
$con = mysql_connect("192.168.1.202", "bot", "bot");
mysql_select_db("bot", $con);
mysql_query("SET NAMES utf8", $con);

$rs = mysql_query($sql, $con) or die(mysql_error());


while ($row = mysql_fetch_assoc($rs)) {

try {
	$city = preg_replace("/[^A-Za-z\s]/", "", $row['name']);
	$loc = $worker->getLocationFromCityName('Berlin');
	if (!isset($loc['name'])) {
		echo $row['name'] . " Not Found \r\n";
	}
} catch (Exception $e) {
	echo $e->getMessages();
}

}

