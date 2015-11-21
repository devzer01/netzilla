<?php
 
$mysql_server = 'localhost';
$mysql_username = 'bot';
$mysql_password = 'bot';
$mysql_db = 'bot';

mysql_connect($mysql_server, $mysql_username, $mysql_password) or die("can not connect database.".mysql_error());
mysql_select_db($mysql_db) or die("can not select database.");
mysql_query("SET NAMES UTF8");
?>