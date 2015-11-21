<?php
require_once('classes/top.class.php');
require_once(SITE.'config-worldpay.php');

if(isset($_POST) && ($_POST['installation']==WORLDPAY_INSTID))
{
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$_POST['cartId']." AND purchase_finished=0");
	if($package)
	{
		if($_POST['transStatus'] == "Y")
		{
			if(floatval($_POST['amount'])==floatval($package['price']))
			{
				$saleID = $_POST['transId'];
				$payment_method = "Worldpay";
				$payment_type = $_POST['cardType'];
				$transaction_id = $_POST['cartId'];
				$postdata = $_POST;
				file_put_contents(SITE."payments/worldpay/callback-".date("YmdHis").".txt", print_r($_POST, true));

				//update transaction status
				DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, reference_id='".$saleID."', payment_method='".$payment_method."', payment_type='".$payment_type."', purchase_finished_date=NOW(), postdata_serialized='".serialize($postdata)."', postdata_text='".print_r($postdata,true)."' WHERE id=".$transaction_id);

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
				echo '<meta http-equiv="refresh" content="0;url=http://'.$domain.'?action=payment_success">';
			}
			else
			{
				// Wrong price
				file_put_contents(SITE."payments/worldpay/callback-error-".date("YmdHis")."txt", print_r($_POST, true));
				echo '<meta http-equiv="refresh" content="0;url=http://'.$domain.'?action=payment_failed">';
			}
		}
		else
		{
			// Cancelled payment
			echo '<meta http-equiv="refresh" content="0;url=http://'.$domain.'?action=payment_failed">';
		}
	}
	else
	{
		// Wrong package ID
		file_put_contents(SITE."payments/worldpay/callback-error-".date("YmdHis").".txt", print_r($_POST, true));
	}
}
else
{
	// Wrong installation ID
	file_put_contents(SITE."payments/worldpay/callback-error-".date("YmdHis").".txt", print_r($_POST, true));
}
exit;
?>