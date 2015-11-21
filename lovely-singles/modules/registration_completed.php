<?php
if($_SESSION['registration_completed_redirect'])
{
	$next_page=$_SESSION['registration_completed_redirect'];
	unset($_SESSION['registration_completed_redirect']);
}
else
	$next_page=".";
$smarty->assign("next_page", $next_page);
$smarty->display('index.tpl');
?>