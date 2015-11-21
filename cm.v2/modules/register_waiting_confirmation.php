<?php 

$smarty->assign('text1', funcs::getText($_SESSION['lang'], '$register_testmembership_complete1'));
$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$register_testmembership_complete2'));
$smarty->assign('mailbox', $_SESSION['registerred_email']);
$smarty->assign('username', $_SESSION['register_username']);
$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$register_testmembership_complete3'));
$smarty->assign('section', 'regis-step1-result');

unset($_SESSION['registerred_email']);

$smarty->display('index.tpl');