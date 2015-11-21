<?php
//check permission type//
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

//get email from username//
$userid = funcs::getUserid($_GET['username']);
$email = funcs::getEmail($userid);

if(isset($_POST['send_button']) && !empty($_POST['send_button']))
{
	$email_by = funcs::getEmail($_SESSION['sess_id']);
	funcs::sendMail($email, "Herzoase: ".$_POST['subject'], $_POST['message'], $email_by);
}

$smarty->assign('email', $email);
$smarty->display('index.tpl');
?>