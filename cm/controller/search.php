<?php

$app->get("/", function () use ($app, $smarty) {
	
	include_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	$member = $dbo_member->getBasicMemberInfo($_SESSION['sess_username']);
	
	include_once 'lib/dbo/config.php';
	$dbo_config = new dbo_config();
	$smarty->assign('countries', initSmartyOptionsArray($dbo_config->getCountryList(), 'id', 'name'));
	$smarty->assign('states', initSmartyOptionsArray($dbo_config->getStateList($member['country']), 'id', 'name'));
	if ($member['state'] == 0) {
		$smarty->assign('cities', initSmartyOptionsArray($dbo_config->getAllCities(), 'id', 'name'));
	} else {
		$smarty->assign('cities', initSmartyOptionsArray($dbo_config->getCityList($member['state']), 'id', 'name'));
	}
	
	$smarty->assign('yesno', array('0' => _("No"), '1' => _("Yes")));
	$smarty->assign('gender', array('1' => _("Male"), '2' => _("Female")));
	$smarty->assign('member', $member);
	$smarty->assign('age', initSmartyOptionsArray(range(18, 90)));
	
	$smarty->display('private/search.tpl');
});

$app->post("/", function () use ($app, $smarty) {
	include_once 'lib/dbo/member.php';
	$dbo_member = new dbo_member();
	
	$minbirth = date("Y") - $_POST['minage'] . '-01-01';
	$maxbirth = date("Y") - $_POST['maxage'] . '-01-01';
	
	$members = $dbo_member->advanceSearch($_POST['gender'], $minbirth, $maxbirth, $_POST['country'], $_POST['state'], $_POST['city']);
	if ($members === false) $members = [];
	else {
		shuffle($members);
		$members = array_slice($members, 0, 12);
	}
	
	$smarty->assign('members', $members);
	$smarty->display('private/searchresults.tpl');
});

$app->get("/newest", function () use ($app, $smarty) {
	require_once 'controller/search/newest.php';
});

$app->get("/online", function () use ($app, $smarty) {
	require_once 'controller/search/online.php';
});

$app->get("/spec/:spec", function ($spec) use ($app, $smarty) {
	require_once 'controller/search/spec.php';
});
	
$app->get("/username/:username", function ($username) use ($app, $smarty) {
	require_once 'controller/search/username.php';
});