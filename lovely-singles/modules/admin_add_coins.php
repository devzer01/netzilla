<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_POST['coins']))
{
	$username = $_POST['username'];
	$user_id = 0;
	$error = "";
	if(!$user_id = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$username."'"))
	{
		$error .= "Please enter valid username.<br/>";
	}
	if(!is_numeric($_POST['coins']))
	{
		$error .= "Please enter valid coins.<br/>";
	}
	if(empty($_POST['datetime']))
	{
		$error .= "Please enter valid date/time.<br/>";
	}
	elseif(strtotime($_POST['datetime'])>time())
	{
		$error .= "Check time again.<br/>";
	}

	if(!$error)
	{
		DBConnect::execute_q("UPDATE member SET coin=coin+".$_POST['coins']." WHERE username='".$username."'");

		//get current coin value
		$coinVal = funcs::checkCoin($username);

		//insert coin log
		$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$user_id."','payment',650,".$coinVal.", NOW())";
		DBconnect::execute($sqlAddCoinLog);

		//reset warning_sms
		$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$user_id;
		DBconnect::execute($sqlResetWarningSMS);

		$date = date("Y-m-d H:i:s", strtotime($_POST['datetime']));

		$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
		DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip, purchase_finished, payment_method, payment_type, purchase_finished_date) VALUES (".$user_id.",0,50,650,'EUR','".$date."','".$_SERVER['REMOTE_ADDR']."', 1, 'Paypal', 'Manual', '".$date."')");

		$_SESSION['error'] = "Finished.";
	}
	else
	{
		$_SESSION['error'] = $error;
	}
	header("location: ?action=admin_add_coins");
}
else
{
	if(!empty($_SESSION['error']))
	{
		$smarty->assign('error', $_SESSION['error']);
		$_SESSION['error'] = "";
	}
	$smarty->display('admin.tpl');
}
?>