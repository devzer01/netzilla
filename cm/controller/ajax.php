<?php 

$app->get("/state/:id", function ($id) use ($app, $smarty) {
	
	require_once 'lib/dbo/config.php';
	$dbo_config = new dbo_config();
	$states = $dbo_config->getStateList($id);
	
	$app->contentType('application/json');
	echo json_encode(array('status' => 0, 'states' => $states));
	
});

$app->get("/msgcnt", function () use ($app, $smarty) {
	
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$id = $dbo_member->getId($_SESSION['sess_username']);
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$count = $dbo_message->getNewMessageCount($id);
	echo $count;
	//$app->contentType('application/json');
	//echo json_encode(array('status' => 0, 'count' => $count));
});

$app->get("/city/:id", function ($id) use ($app, $smarty) {

	require_once 'lib/dbo/config.php';
	$dbo_config = new dbo_config();
	$cities = $dbo_config->getCityList($id);

	$app->contentType('application/json');
	echo json_encode(array('status' => 0, 'cities' => $cities));

});

$app->get("/markreadall/:username", function ($username) use ($app, $smarty) {
	$username = revealUsername($username);
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$sender_id = $dbo_member->getId($_SESSION['sess_username']);
	$receiver_id = $dbo_member->getId($username);
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$dbo_message->markRead($sender_id, $receiver_id);
	
	$app->contentType('application/json');
	echo json_encode(array('status' => 0));
});

$app->get("/closechat/:username", function ($username) use($app, $smarty) {
	
	$username = revealUsername($username);
	require_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$sender_id = $dbo_member->getId($_SESSION['sess_username']);
	$receiver_id = $dbo_member->getId($username);
	
	require_once 'lib/dbo/message.php';
	$dbo_message = new dbo_message();
	$dbo_message->clearChat($sender_id, $receiver_id);
	
	$app->contentType('application/json');
	echo json_encode(array('status' => 0));
});

$app->get("/removefavorite/:favorite", function ($favorite) use ($app, $smarty) {
	
	$favorite = revealUsername($favorite);
	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';
	
	$dbo_member = new dbo_member();
	$dbo_member->removeFavorite($username, $favorite);
	
	$app->contentType('application/json');
	echo json_encode(array('status' => 0));
	
});

$app->post("/password", function () use ($app, $smarty) {
	
	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';
	
	$dbo_member = new dbo_member();
	$status = 0;
	if ($dbo_member->changePassword($username, $_POST['current'], $_POST['new']) === false) $status = 1;
	
	$app->contentType('application/json');
	echo json_encode(array('status' => $status));
	
});

$app->get("/addfavorite/:favorite", function ($favorite) use ($app, $smarty) {

	$favorite = revealUsername($favorite);
	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';

	$dbo_member = new dbo_member();
	$dbo_member->addFavorite($username, $favorite);

	$app->contentType('application/json');
	echo json_encode(array('status' => 0));
});

$app->get("/isusername/:username", function ($username) use ($app, $smarty) {
	require_once 'lib/Formvalidator.php';

	$validator = new FormValidator();
	$user = 0;
	if (!$validator->username($username)) $user = 1;
	$app->contentType('application/json');
	echo json_encode(array('status' => $user));
});

$app->get("/isemail/:email", function ($email) use ($app, $smarty) {
	require_once 'lib/Formvalidator.php';
	
	$validator = new FormValidator();
	$eml = 0;
	if (!$validator->email($email)) $eml = 1;
	$app->contentType('application/json');
	echo json_encode(array('status' => $eml));
});

$app->get("/coins", function () use ($app, $smarty) {
	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';
	
	$dbo_member = new dbo_member();
	$coins = $dbo_member->getCoins($username);
	if ($coins === false) $coins = 0;
	echo $coins;
});

$app->get("/removefoto/:id", function ($id) use ($app, $smarty) {

	$username = $_SESSION['sess_username'];
	require_once 'lib/dbo/member.php';

	$dbo_member = new dbo_member();
	$retval = $dbo_member->removeFoto($username, $id);
	$status = 1;
	if ($retval === true) $status = 0;
	else {
		$retval = $dbo_member->removeTempFoto($username, $id);
		if ($retval === true) $status = 0;
	}
	

	$app->contentType('application/json');
	echo json_encode(array('status' => $status));

});

	