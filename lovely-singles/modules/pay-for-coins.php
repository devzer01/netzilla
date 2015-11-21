<?php
$permission_lv = array(1,2,3,4,8,9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

$columns = DBConnect::row_retrieve_2D_conv_1D("SHOW COLUMNS FROM coin_package");

if(in_array("from_signup_date", $columns))
{
	$signup_date = DBConnect::retrieve_value("SELECT signup_datetime FROM member WHERE id=".$_SESSION['sess_id']);
	$last_package_date = DBConnect::retrieve_value("SELECT MAX(c.from_signup_date) FROM coin_package c LEFT JOIN purchases_log l ON l.package_id=c.id WHERE user_id=".$_SESSION['sess_id']." AND purchase_finished=1");
	$max = DBConnect::retrieve_value("SELECT MAX(from_signup_date) FROM coin_package");

	if(!$last_package_date)
		$signup_date = $max;
	else
		$signup_date = $last_package_date;

	$package_date = DBConnect::assoc_query_2D("SELECT from_signup_date FROM coin_package WHERE from_signup_date<='".$signup_date."' and enabled=1 GROUP BY from_signup_date ORDER BY from_signup_date DESC");

	$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' and enabled=1 ORDER BY package_order ASC";
}
else
{
	$sql = "SELECT * FROM coin_package WHERE enabled=1 ORDER BY package_order ASC";
}
$rePackage = DBconnect::assoc_query_2D($sql);

$purchases_log = DBConnect::retrieve_value("SELECT COUNT(id) FROM purchases_log WHERE user_id = '". $_SESSION['sess_id'] ."' AND purchase_finished = '1'");
if(($purchases_log<=0) && (isset($rePackage[0])))
{
	//$smarty->assign('trialPackage', $rePackage[0]);
	$smarty->assign('start_package', 1);
	//unset($rePackage[0]);
}
else
{
	$smarty->assign('start_package', 2);
	unset($rePackage[0]);
}

$reCurrency = funcs::getConfCurrency();

$smarty->assign('coinpackage', $rePackage);
$smarty->assign('rcurrency', $reCurrency);

$smarty->display('index.tpl');
?>