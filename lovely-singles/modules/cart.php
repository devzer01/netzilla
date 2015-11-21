<?php
//define("PAYMENT_URL", "http://192.168.1.202/pm.lovely-singles.com");
define("PAYMENT_URL", "http://pm.lovely-singles.com");
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

	$package_date = DBConnect::assoc_query_2D("SELECT from_signup_date FROM coin_package WHERE from_signup_date<='".$signup_date."' GROUP BY from_signup_date ORDER BY from_signup_date DESC");

	$paypal_package = DBConnect::assoc_query_1D("SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' and paypal=1 LIMIT 1");

	$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' and paypal=0 ORDER BY currency_price ASC";
}
else
{
	$paypal_package = DBConnect::assoc_query_1D("SELECT * FROM coin_package WHERE paypal=1 LIMIT 1");
	$sql = "SELECT * FROM coin_package ORDER BY currency_price ASC";
}
$rePackage = DBconnect::assoc_query_2D($sql);

$purchases_log = DBConnect::retrieve_value("SELECT COUNT(id) FROM purchases_log WHERE user_id = '". $_SESSION['sess_id'] ."' AND purchase_finished = '1'");
if(($purchases_log>0) && ($_GET['id']==$rePackage[0]["id"]))
{
	header("location: ?action=pay-for-coins");
	exit;
}
elseif(isset($_GET['id']))
{
	if(in_array("from_signup_date", $columns))
	{
		$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' AND id=".$_GET['id'];
	}
	else
	{
		$sql = "SELECT * FROM coin_package WHERE id=".$_GET['id'];
	}

	if(isset($_SESSION['payment_admin']) && ($_GET['id'] == 0))
	{
		$package = array(
							"currency_price"	=> $_GET['price'],
							"coin"				=> $_GET['coins']
						);
	}
	else
	{
		$package = DBConnect::assoc_query_1D($sql);
	}

	if($package)
	{
		$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
		//Put into purchases_log
		DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip) VALUES (".$_SESSION['sess_id'].",".$_GET['id'].",".$package['currency_price'].",".$package['coin'].",'".$currency."',NOW(),'".$_SERVER['REMOTE_ADDR']."')");
		header("location: ".PAYMENT_URL."?transaction_id=".SERVER_ID."-".mysql_insert_id());
	}
	else
	{
		header("location: ?action=pay-for-coins");
	}
}
else
{
	header("location: .");
}
?>