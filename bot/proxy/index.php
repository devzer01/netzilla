<?php

session_start();
require_once('config.php');
require_once 'vendor/autoload.php';
require_once('db.php');

date_default_timezone_set('Asia/Bangkok');

$app = new \Slim\Slim();

$app->get("/country/:country", function ($country) {
	$dbh = getDbHandler();

	$sql = "SELECT id, host, port, type FROM proxy WHERE latency != 0 AND country = :country GROUP BY host, port ORDER BY count ASC, latency ASC LIMIT 1";
	$sth = $dbh->prepare($sql);
	$sth->execute(array(':country' => $country));

	$result = $sth->fetch(PDO::FETCH_ASSOC);

	switch ($result['type']) {
		case 'http':
			$result['type'] = CURLPROXY_HTTP;
			break;
		case '4':
			$result['type'] = CURLPROXY_SOCKS4;
			break;
		case '5':
		default:
			$result['type'] = CURLPROXY_SOCKS5;
			break;
	}

	if ($sth->rowCount() != 0) {
		$sql = "UPDATE proxy SET count = count + 1 WHERE host = :host AND port = :port ";
		$sth = $dbh->prepare($sql);
		$sth->execute(array(':host' => $result['host'], ':port' => $result['port']));
	}

	echo json_encode($result);
});


$app->get("/", function () {
	$dbh = getDbHandler();
	
	$sql = "SELECT id, host, port, type FROM proxy WHERE latency != 0 GROUP BY host, port ORDER BY count ASC, latency ASC LIMIT 1";
	$sth = $dbh->prepare($sql);
	$sth->execute();
	
	$result = $sth->fetch(PDO::FETCH_ASSOC);
	
	switch ($result['type']) {
		case 'http':
			$result['type'] = CURLPROXY_HTTP;
			break;
		case '4':
			$result['type'] = CURLPROXY_SOCKS4;
			break;
		case '5':
		default:
			$result['type'] = CURLPROXY_SOCKS5;
			break;
	}
	
	$sql = "UPDATE proxy SET count = count + 1 WHERE host = :host AND port = :port ";
	$sth = $dbh->prepare($sql);
	$sth->execute(array(':host' => $result['host'], ':port' => $result['port']));
	
	echo json_encode($result);
});
$app->run();
