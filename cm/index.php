<?php
session_set_cookie_params(0, "/", "flirt48.net");
session_start();
require_once('config.php');
require_once 'vendor/autoload.php';
require_once('db.php');
require_once('smarty3/Smarty.class.php');
require_once('lib/bootstrap.php');
require_once('lib/common.php');


$app = new \Slim\Slim();

$smarty = basesmarty::getInstance();

$app->get('/', function () use ($app, $smarty) {
	if (isLoggedIn()) {
		$smarty->assign('online', getOnlineMembers());
		$smarty->assign('newest', getNewestMembers());
		$smarty->display('private/home.tpl');
	} else {
		$smarty->display('public/index.tpl');
	}
});

$app->group("/ajax", function () use ($app, $smarty) {
	require_once 'controller/ajax.php';
});

$app->group("/verify", function () use ($app, $smarty) {
	require_once 'controller/verify.php';
});
	
$app->group("/login", function () use ($app, $smarty) {
	require_once 'controller/login.php';
});

$app->group("/register", function () use ($app, $smarty) {
	require_once 'controller/register.php';
});

$app->group("/facebook", function () use ($app, $smarty) {
	require_once 'controller/facebook.php';
});

if (isLoggedIn()) { 
$app->group("/search", function () use ($app, $smarty) {
	require_once 'controller/search.php';
});

$app->group("/profile", function () use ($app, $smarty) {
	require_once 'controller/profile.php';
});

$app->group("/chat", function () use ($app, $smarty) {
	require_once 'controller/chat.php';
});

$app->group("/help", function () use ($app, $smarty) {
	require_once 'controller/help.php';
});

$app->get("/coins", function () use ($app, $smarty) {
	$url = URL_WEB . '/?action=pay-for-coins';
	$app->redirect($url, 302);
	//$smarty->display('private/coins.tpl');
});

$app->get("/password", function () use ($app, $smarty) {
	$smarty->display('public/password.tpl');
});

$app->post("/password", function () use ($app, $smarty) {

	require_once "lib/dbo/member.php";
	$dbo_member = new dbo_member();
	$member = $dbo_member->getBasicMemberInfo($_POST['username']);
	
	$app->contentType('application/json');
	
	if ($member === false) {
		echo json_encode(array('status' => 1, 'error' => 'not found'));
		return;
	}
	
	$smarty->assign('member', $member);
	$message = $smarty->fetch('email/forgot.tpl');
	$subject = 'Flirt48.net: Dein Benutzername und Passwort';
	
	require_once 'lib/dbo/queue.php';
	$dbo_queue = new dbo_queue();
	$dbo_queue->queueEmail($member['email'], $subject, $message);
	
	echo json_encode(array('status' => 0));
	return;
	
});

$app->get("/logout", function () use ($app, $smarty) {
	session_destroy();
	$app->redirect(APP_PATH . "/");
});
}

$app->notFound(function () use ($app) {
	$app->redirect(APP_PATH);
});

$app->run();
