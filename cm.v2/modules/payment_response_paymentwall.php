<?php
require_once('classes/top.class.php');
error_reporting(E_ALL);

define('IP_WHITELIST_CHECK_ACTIVE', true);
define('CREDIT_TYPE_CHARGEBACK', 2);

$goodsid = $_GET['goodsid'];

if ($_GET['type'] == CREDIT_TYPE_CHARGEBACK)
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$goodsid." AND purchase_finished=1 and reference_id='".$_GET['ref']."'");
else
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$goodsid." AND purchase_finished=0");

if($package)
{
	require_once(SITE.'config-paymentwall.php');
}
else
{
	// Wrong transaction id.
	die("Invalid transaction id");
}

$ipsWhitelist = array(
    '174.36.92.186',
    '174.36.96.66',
    '174.36.92.187',
    '174.36.92.192',
    '174.37.14.28'
);

if(!file_exists(SITE."payments/paymentwall"))
	mkdir(SITE."payments/paymentwall");

if (!IP_WHITELIST_CHECK_ACTIVE || in_array($_SERVER['REMOTE_ADDR'], $ipsWhitelist))
{
	if (!empty($_GET['uid']) && !empty($_GET['goodsid']) && isset($_GET['type']) && !empty($_GET['ref']) && !empty($_GET['sig']))
	{
		$time = date("YmdHis");
		file_put_contents(SITE."payments/paymentwall/".$time."-".$_GET['ref'].".txt", print_r($_GET,true));

		$data = $_GET;
		unset($data['sig']);
		$sig = calculateWidgetSignature($data, $paymenywall_secret);

		//if payment completed
		if($_GET['sig']==$sig)
		{
			$transaction_id = $_GET['goodsid'];
			if ($_GET['type'] == CREDIT_TYPE_CHARGEBACK)
			{
				if(DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=0 WHERE id=".$transaction_id." AND reference_id='".$_GET['ref']."'"))
				{
					//get transaction detail
					$detail = DBConnect::assoc_query_1D("SELECT * from purchases_log WHERE id=".$transaction_id);

					//update coin to member
					$sqlUpdateCoin = "UPDATE member SET coin = coin - ".$detail['coin_amount']." WHERE id = '".$detail['user_id']."'";
					DBconnect::execute($sqlUpdateCoin);

					//get username from user id
					$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id='".$detail['user_id']."'");

					//get current coin value
					$coinVal = funcs::checkCoin($username);

					//insert coin log
					$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$detail['user_id']."','chargeback',".$detail['coin_amount'].",".$coinVal.", NOW())";
					DBconnect::execute($sqlAddCoinLog);
					echo "OK";
				}
				else
				{
					echo "Failed to update payment status!";
				}
			}
			else
			{
				//update transaction status
				if(DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, reference_id='".$_GET['ref']."', payment_method='Paymentwall', payment_type='', purchase_finished_date=NOW(), postdata_serialized='".serialize($_GET)."', postdata_text='".print_r($_GET,true)."' WHERE id=".$transaction_id))
				{
					//get transaction detail
					$detail = DBConnect::assoc_query_1D("SELECT * from purchases_log WHERE id=".$transaction_id);

					//update coin to member
					$sqlUpdateCoin = "UPDATE member SET coin = coin + ".$detail['coin_amount']." WHERE id = '".$detail['user_id']."'";
					DBconnect::execute($sqlUpdateCoin);

					//get username from user id
					$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id='".$detail['user_id']."'");

					//get current coin value
					$coinVal = funcs::checkCoin($username);

					//insert coin log
					$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$detail['user_id']."','payment',".$detail['coin_amount'].",".$coinVal.", NOW())";
					DBconnect::execute($sqlAddCoinLog);

					//reset warning_sms
					$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$detail['user_id'];
					DBconnect::execute($sqlResetWarningSMS);
					echo "OK";
				}
				else
				{
					echo "Failed to update payment status!";
				}
			}
		}
		else
		{
			echo "Signature is not valid!";
		}
	}
	else
	{
		echo "Missing parameters!";
	}
}
else
{
	echo "IP not in whitelist!";
}
?>