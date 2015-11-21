<?php

$app->get('/', function () use ($app, $smarty) {
	$smarty->display('public/login.tpl');
});

$app->post('/', function () use ($app, $smarty) {
	
	$retval = performLogin($_POST['username'], $_POST['password']);
	
	$app->contentType('application/json');
	echo json_encode(array('status' => $retval));
	
});