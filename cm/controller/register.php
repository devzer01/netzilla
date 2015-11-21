<?php
$app->get('/', function () use ($app, $smarty) {

	$vars = array_fill_keys(array('username', 'email', 'date', 'month', 'year', 'gender'), '');

	$smarty->assign('year_range', initSmartyOptionsArray(range(date("Y")-90, date("Y") - 18)));
	$smarty->assign('date', initSmartyOptionsArray(range(1, 31)));
	//$smarty->assign('month', array(_("January")));
	$smarty->assign('month', array(1 => _("January"), _("February"), _("March"), _("April"), _("May"), _("June"), _("July"), _("Auguest"), _("September"), _("October"), _("November"), _("December")));
	$smarty->assign('gender', array(_("Male"), _("Female")));
	include_once 'lib/dbo/config.php';
	$dbo_config = new dbo_config();
	$smarty->assign('country', initSmartyOptionsArray($dbo_config->getCountryList(), 'id', 'name'));

	$smarty->assign('save', $vars);
	$smarty->display('public/register.tpl');
});

$app->post('/', function () use ($app) {

	require_once 'lib/Formvalidator.php';

	$validator = new FormValidator();
	$validator->trimPost();

	$errors = array();

	if (!$validator->email($_POST['email'])) {
		$errors[] = array('field' => 'email', 'error' => _("Email Address invalid"));
	}
	
	if (!$validator->username($_POST['username'])) {
		$errors[] = array('field' => 'username', 'error' => _("Username too short or already in use"));
	}

	$app->contentType('application/json');

	if (!empty($errors)) {
		echo json_encode(array('status' => 1, 'errors' => $errors));
		return;
	}

	$dbo_member = new dbo_member();

	$dob = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['date'];

	$member_id = $dbo_member->addMember($_POST['username'], $_POST['password'], $_POST['email'], $dob, $_POST['gender'], $_POST['country']);
	$_SESSION['temp_member_id'] = $member_id;

	require_once 'lib/ui/email.php';
	$ui_email = new ui_email();
	$message = $ui_email->getMembershipEmailHtml($_POST['username']);

	require_once 'lib/dbo/queue.php';
	$dbo_queue = new dbo_queue();
	$dbo_queue->queueEmail($_POST['email'], "Flirt48.net: Dein Benutzername und Passwort", $message);	

	echo json_encode(array('status' => 0));

});