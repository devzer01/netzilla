<?php
if($_SESSION['payment_failed_message'])
{
	$smarty->assign("payment_failed_message", $_SESSION['payment_failed_message']);
	unset($_SESSION['payment_failed_message']);
}
$smarty->display('index.tpl');
?>