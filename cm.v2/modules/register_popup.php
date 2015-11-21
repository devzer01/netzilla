<?php
if($_SESSION['registerred_email'])
{
	$smarty->assign('text1', funcs::getText($_SESSION['lang'], '$register_testmembership_complete1'));
	$smarty->assign('text2', funcs::getText($_SESSION['lang'], '$register_testmembership_complete2'));
	$smarty->assign('mailbox', $_SESSION['registerred_email']);
	$smarty->assign('text3', funcs::getText($_SESSION['lang'], '$register_testmembership_complete3'));
	$smarty->assign('section', 'regis-step1-result');
	//unset($_SESSION['registerred_email']);
}
else
{
	$smarty->assign('gender', funcs::getChoice($_SESSION['lang'],'','$gender'));
	$smarty->assign('date', funcs::getRangeAge(1,31));
	$smarty->assign('month', funcs::getChoice($_SESSION['lang'],'','$month'));
	$smarty->assign('year_range', funcs::getYear());
	$smarty->assign('country', funcs::getChoiceCountry());
	$smarty->assign('age', funcs::getRangeAge());//Singh
}
$smarty->display('register_popup.tpl');
?>