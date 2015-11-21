<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if(isset($_POST['id']))
{
	$id = $_POST['id'];
	$transaction = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id='".$id."'");
	$error = "";
	if(!$transaction)
	{
		$error .= "Please enter valid transaction id.<br/>";
	}

	if(!$error)
	{
		$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$transaction['user_id']);
		DBConnect::execute_q("UPDATE member SET coin=coin+".$transaction['coin_amount']." WHERE username='".$username."'");

		//get current coin value
		$coinVal = funcs::checkCoin($username);

		//insert coin log
		$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$transaction['user_id']."','payment',".$transaction['coin_amount'].",".$coinVal.", NOW())";
		DBconnect::execute($sqlAddCoinLog);

		//reset warning_sms
		$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$user_id;
		DBconnect::execute($sqlResetWarningSMS);

		$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
		DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, payment_method='Paypal', payment_type='Worldpay Manual', purchase_finished_date=purchase_datetime WHERE id=".$id);

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