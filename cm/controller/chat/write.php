<?php 

$isfree = false;
$_POST['to'] = revealUsername($_POST['to']);
if(isset($_SESSION['sess_smalladmin']) && $_SESSION['sess_smalladmin']) $isfree = true;

$total_coins = 0;

require_once 'lib/dbo/config.php';
$dbo_config = new dbo_config();
$coin_email = $dbo_config->getConfig('COIN_EMAIL');

require_once 'lib/dbo/member.php';
$dbo_member = new dbo_member();
$coins = $dbo_member->getCoins($_SESSION['sess_username']);

if(($coins < $coin_email) && $isfree === false) {
	$app->contentType('application/json');
	echo json_encode(array('status' => ERROR_NO_COIN));	
	return;
}

$from = $dbo_member->getId($_SESSION['sess_username']);
sendMessage($from, $_POST['to'], '', strip_tags($_POST['msg']), 0, '', $isfree);
	
$app->contentType('application/json');
echo json_encode(array('status' => ERROR_OK));
return;
	