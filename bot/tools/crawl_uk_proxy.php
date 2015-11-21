<?php

/* REQUIRE FILE */
require_once 'simple_html_dom.php';

/* DATABASE CONNECTION */
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
$pdo = new PDO("mysql:host=192.168.1.203;dbname=bot", "bot", "bot", $options);
$i = 0;

if(empty($_GET['flag'])) {
	$html = file_get_html('http://free-proxy-list.net/uk-proxy.html');
} else {
	$html = file_get_html('http://www.us-proxy.org/');
}

if(!empty($html->find('tbody',0))) {
	
	$sql = 'SELECT MIN(count) as max_count FROM proxy WHERE country = :country LIMIT 1';
	$sth = $pdo->prepare($sql);
	$sth->execute(array(':country' => 'uk'));
	$row = $sth->fetch(PDO::FETCH_ASSOC);
	$max_count = $row['max_count'];
	$data = array();
	foreach($html->find('tbody',0)->find('tr') as $tr) {
		
		$host = trim($tr->find('td',0)->plaintext);
		$port = trim($tr->find('td',1)->plaintext);
		$type = 'http';
		
	
		// Checking ?
		$sql = 'SELECT COUNT(*) as total_rows FROM proxy WHERE host = :host AND port = :port AND country = :country';
		$sth = $pdo->prepare($sql);
		$sth->execute(array(':host' => $host, ':port' => $port, ':country' => 'uk'));
		$row = $sth->fetch(PDO::FETCH_ASSOC);
		
		if($row['total_rows'] == 0) {
			$sql = 'INSERT INTO `proxy` (`host`, `port`, `type`, `count`, `latency`, `country`) VALUES (:host, :port, :type, :count, :latency, :country)';
			$sth = $pdo->prepare($sql);
			$sth->execute(array(
				':host' => $host,
				':port' => $port,
				':count' => $max_count,
				':type' => 'http',
				':latency' => 1,
				':country' => 'uk'
			));
			$i++;
		}
	}
	
	die('Added '.$i.' UK Proxy into Databases');
} else {
	die('NOT_FOUND_ELEMENT');
}
