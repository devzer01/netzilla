<?php

$username = $_GET['username'];

if (trim($username) != '') {
	DBConnect::execute("UPDATE member SET email_read = 1 WHERE username = '" . mysql_real_escape_string($username) . "' LIMIT 1");
}

header("Content-Type: image/gif");
echo file_get_contents('clear.gif');