<?php

session_start();
require_once('config.php');
require_once 'vendor/autoload.php';
require_once('db.php');
require_once('smarty3/Smarty.class.php');

date_default_timezone_set('Asia/Bangkok');

$app = new \Slim\Slim();

$smarty = new Smarty();
$smarty->setTemplateDir('templates/');
$smarty->setCompileDir('templates_c/');
$smarty->setConfigDir('configs/');
$smarty->setCacheDir('cache/');

$app->get('/ping', function () use ($app, $smarty) {
	echo "pong";
});

$app->get('/', function () use ($app, $smarty) {
	
});

$app->get('/verify/:email', function ($email) use ($app, $smarty) {
	
	$app->contentType('application/json');
	
	$code = validateEmail($email);
	echo json_encode(array('code' => $code));
});

$app->notFound(function () use ($app) {
	$app->redirect("/");
});

$app->run();

function validateEmail($email){
	list($name,$Domain) = explode('@',$email);
	$result=getmxrr($Domain,$POFFS);
	if(!$result){
		$POFFS[0]=$Domain;
	}
	$timeout=30;
	$oldErrorLevel=error_reporting(!E_WARNING);
	$result=false;
	foreach($POFFS as $PO)
	{
		$sock = fsockopen($PO, 25, $errno, $errstr,  $timeout);
		if($sock){
			fwrite($sock, "HELO goflirt.net\n");
			$response= getResponse($sock);
			fwrite($sock,"MAIL FROM: <daemon@goflirt.net>\n");
			$response= getResponse($sock);
			fwrite($sock,"RCPT TO: <".$email.">\n");
			$response= getResponse($sock);
			list($code,$msg)=explode(' ',$response);
			fwrite($sock,"RSET\n");
			$response= getResponse($sock);
			fwrite($sock,"quit\n");
			fclose($sock);
			return $code;
		}
	}
	error_reporting($oldErrorLevel);
	return $result;
}

function getResponse($sock) {
	return fread($sock, 1024);
}
