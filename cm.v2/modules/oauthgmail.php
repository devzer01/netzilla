<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('libs/google-api-php-client/src/Google_Client.php');
require_once('libs/google-api-php-client/src/io/Google_REST.php');
require_once('libs/google-api-php-client/src/io/Google_HttpRequest.php');

$client = new Google_Client();
$client->setApplicationName(GOOGLE_APPNAME);
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(OAUTH_GMAIL_REDIRECT);
$client->setDeveloperKey(GOOGLE_DEVELOPER_KEY);

if (isset($_GET['code'])) {
	$client->authenticate();
	$_SESSION['token'] = $client->getAccessToken();
	$redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
}

if (isset($_SESSION['token'])) {
	$client->setAccessToken($_SESSION['token']);
}

if ($client->getAccessToken()) {
	
	
	if (!isset($_GET['subaction'])) {
		$html="
		<HTML>
		<BODY>
		<SCRIPT type='text/javascript'>
		window.close()
		</SCRIPT>
		</BODY>
		</HTML>";
		echo $html;
		exit;
	}
	
	// 	$activities = $plus->activities->listActivities('me', 'public');
	// 	print 'Your Activities: <pre>' . print_r($activities, true) . '</pre>';

	// We're not done yet. Remember to update the cached access token.
	// Remember to replace $_SESSION with a real database or memcached.
	$_SESSION['token'] = $client->getAccessToken();
	
	$request = new Google_HttpRequest("https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=1000");
	$oauth = Google_Client::$auth;
	$oauth->sign($request);
	$io = Google_Client::$io;
	
	try {
		$response = $io->makeRequest($request)->getResponseBody();
		$json = json_decode($response, true);
		$emails = array();
		foreach ($json['feed']['entry'] as $entry) {
			if (isset($entry['gd$email'])) {
				$emails[] = array('email' => $entry['gd$email'][0]['address'], 'title' => $entry['title']['$t']);
			}
		}
		
		//$emails = array('nayana@corp-gems.com'); //TODO: REMOVE AFTER TEST
		
		$smarty->assign('emails', $emails);
		$smarty->display('social_email.tpl');
		
	} catch (Exception $e) {
		echo $e->getMessage();
	}
	
} else {
	$client->setScopes(array('https://www.googleapis.com/auth/contacts'));
	$authUrl = $client->createAuthUrl();
	header("Location: " . $authUrl);
}