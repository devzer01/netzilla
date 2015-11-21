<?php
$app->get("/", function () use ($app, $smarty) {
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$contacts = $dbo_message->getContacts($dbo_member->getId($_SESSION['sess_username']));
	
	$smarty->assign('contacts', $contacts);
	$smarty->display('private/chat.tpl');
});

$app->post('/write', function () use ($app, $smarty) {
	require_once 'controller/chat/write.php';
});

$app->get("/history/:username", function ($username) use ($app, $smarty) {
	
	$username = revealUsername($username);
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	$smarty->assign('rcpt', $dbo_member->getBasicMemberInfo($username));
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$history = $dbo_message->getMessageHistory($dbo_member->getId($_SESSION['sess_username']), $dbo_member->getId($username));
	if (count($history > 1)) $history = array_reverse($history);
	
	$smarty->assign('history', $history);
	$smarty->display('private/chathistory.tpl');
});


$app->get("/log/:username", function ($username) use ($app, $smarty) {

	$username = revealUsername($username);
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	$smarty->assign('rcpt', $dbo_member->getBasicMemberInfo($username));
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$history = $dbo_message->getMessageHistory($dbo_member->getId($_SESSION['sess_username']), $dbo_member->getId($username));
	if (count($history > 1)) $history = array_reverse($history);
	
	$smarty->assign('history', $history);
	$smarty->display('private/chat/log.tpl');
});