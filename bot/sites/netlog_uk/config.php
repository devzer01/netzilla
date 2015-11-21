<?php
/*CONFIG DATABASE*/
$mysql_server = '192.168.1.253'; //config mysql host
$mysql_username = 'bot'; //config mysql username
$mysql_password = 'bot'; //config mysql password
$mysql_db = 'bot'; //config mysql database
@mysql_connect($mysql_server, $mysql_username, $mysql_password) or die("Database connection error."); //connect mysql
@mysql_select_db($mysql_db) or die("Error select DB."); //connect database
mysql_query("SET NAMES UTF8");
?>