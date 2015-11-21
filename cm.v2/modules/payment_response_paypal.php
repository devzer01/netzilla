<?php
require_once('classes/top.class.php');
require(SITE."config-paypal.php");

//&transaction_id=67&token=EC-73J4243218358174P&PayerID=HV7PB5Z6QD8CN
if($_GET['PayerID'] != '')
{
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$_GET['transaction_id']." AND purchase_finished=0");
	if($package)
	{
		$transaction_id = $_GET["transaction_id"];
		$payerID = $_GET["PayerID"];
		$token = $_GET["token"];

		$paymentType = "Sale";
		$paymentAmount = $package["price"];
		$currencyID = $package["currency"];

		// Add request-specific fields to the request string.
		$nvpStr = "&TOKEN=$token&PAYERID=$payerID&PAYMENTREQUEST_0_PAYMENTACTION=$paymentType&PAYMENTREQUEST_0_AMT=$paymentAmount&PAYMENTREQUEST_0_CURRENCYCODE=$currencyID";

		// Execute the API operation; see the PPHttpPost function above.
		$httpParsedResponseAr = PPHttpPost($API_UserName, $API_Password, $API_Signature, 'DoExpressCheckoutPayment', $nvpStr);

		file_put_contents(SITE."payments/paypal/detail-".$_GET['transaction_id']."-".date("YmdHis").".txt", print_r($httpParsedResponseAr,true));

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
		{
			$saleID = $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"];
			$payment_method = "Paypal";
			$payment_type = $httpParsedResponseAr["PAYMENTINFO_0_PAYMENTTYPE"];
			$arr = $httpParsedResponseAr;

			//update transaction status
			DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, reference_id='".$saleID."', payment_method='".$payment_method."', payment_type='".$payment_type."', purchase_finished_date=NOW(), postdata_serialized='".serialize($arr)."', postdata_text='".print_r($arr,true)."' WHERE id=".$transaction_id);

			//update coin to member
			$sqlUpdateCoin = "UPDATE member SET coin = coin + ".$package['coin_amount']." WHERE id = '".$package['user_id']."'";
			DBconnect::execute($sqlUpdateCoin);

			//get username from user id
			$username = DBConnect::retrieve_value("SELECT username FROM member WHERE id='".$package['user_id']."'");

			//get current coin value
			$coinVal = funcs::checkCoin($username);

			//insert coin log
			$sqlAddCoinLog = "INSERT INTO coin_log (member_id, send_to, coin_field, coin, coin_remain, log_date) VALUES ('0','".$package['user_id']."','payment',".$package['coin_amount'].",".$coinVal.", NOW())";
			DBconnect::execute($sqlAddCoinLog);

			funcs::grantReward($package['user_id'], $username); //check return value to see if granted or not
			
			//reset warning_sms
			$sqlResetWarningSMS = "DELETE FROM warning_sms WHERE userid=".$package['user_id'];
			DBconnect::execute($sqlResetWarningSMS);
			header("location: ?action=payment_success");
		}
		else
		{
			header("location: ?action=payment_failed");
		}
	}
	else
	{
		echo 'NO TRANSACTION';
	}
}
exit;
?>