<?php 

$app->get('/', function () use ($app, $smarty) {

	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$member = $dbo_member->getBasicMemberInfo($dbo_member->getUsername($_SESSION['temp_member_id']));

	$smarty->assign('mailbox', $member['email']);
	$smarty->assign('username', $member['username']);
	$smarty->assign('password', $member['password']);

	$smarty->display('public/verify.tpl');

});

$app->post('/check', function () use ($app, $smarty) {
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	$ok = 0;
	if ($dbo_member->isCode($_POST['username'], $_POST['code'])) {
		$ok = 1;	
	}
	
	$app->contentType('application/json');
	echo json_encode(array('ok' => $ok));
});

$app->get('/activate/:username/:password/:code', function ($username, $password, $code) use ($app, $smarty) {
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	if ($dbo_member->isActive($username)) {
		$member = $dbo_member->loginSite($username, $password);
		$app->redirect(APP_PATH . "/");
		return;
	}
	
	if (!activate($username, $password, $code)) {
		//error page
	}
	
	$member = $dbo_member->loginSite($username, $password);
	setLoginSession($member);
	
	$app->redirect(APP_PATH . '/profile/edit');
});


function activate($username, $password, $code)
{
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	if (!$dbo_member->activateMember($username, $password, $code)) return false;
	
	$dbo_member->setCoin($username, FREECOINS);
	$dbo_member->addCoinLog(1, $dbo_member->getId($username), 'Activate Member', FREECOINS, FREECOINS);
	$dbo_member->setMemberIp($username, $_SERVER['REMOTE_ADDR']);
	
	$msg = "Hallo und herzlich Willkommen bei Flirt48.net, dem Flirtportal zum Kennenlernen, Flirten und Verlieben.";
	$sub = "Willkommen bei Flirt48.net";
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	
	$dbo_message->addToInbox($dbo_member->getId($username), 2, $sub, $msg, 0, 0);
	
	return true;
}