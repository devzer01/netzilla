<?php 

$app->get('/', function () use ($app, $smarty) {
	$smarty->display('private/help.tpl');
});