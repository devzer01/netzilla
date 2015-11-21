<?php
define("PAYMENT_URL", "http://pm.connectforever.net");
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_GET['id']))
{
	//Put into purchases_log
	$package = DBConnect::assoc_query_1D("SELECT * FROM coin_package WHERE id=".$_GET['id']);
	if($package)
	{
		$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
		DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip) VALUES (".$_SESSION['sess_id'].",".$_GET['id'].",".$package['currency_price'].",".$package['coin'].",'".$currency."',NOW(),'".$_SERVER['REMOTE_ADDR']."')");
		header("location: ".PAYMENT_URL."?transaction_id=".SERVER_ID."-".mysql_insert_id());
	}
	else
		header("location: .");
}
else
{
	header("location: .");
}
?>