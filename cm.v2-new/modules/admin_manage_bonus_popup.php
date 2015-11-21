<?php 
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$smarty->assign('email_subject', funcs::getText($_SESSION['lang'], '$adminsendemail_subject'));
$smarty->assign('email_body', funcs::getText($_SESSION['lang'], '$adminsendemail_content'));
$smarty->assign('sms_body', funcs::getText($_SESSION['lang'], '$bonus_SMS_message'));
$smarty->display('admin_manage_bonus_popup.tpl');
?>