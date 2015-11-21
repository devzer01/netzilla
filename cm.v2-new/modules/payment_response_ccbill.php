<?php
require_once('classes/top.class.php');
require_once(SITE.'config-ccbill.php');

if(($_POST['clientAccnum']==$clientAccnum) && ($_POST['clientSubacc']==$clientSubacc))
{
	// log
	file_put_contents(SITE."payments/ccbill/".date("YmdHis").".txt", print_r($_POST,true));

	//get transaction detail from contact market
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$_POST['referenceID']." AND purchase_finished=0");

	//IF APPROVED
	if(empty($_POST['denialId']) && empty($_POST['reasonForDeclineCode']))
	{
		if($_POST['externalCascadeFlowId'] == $cascadeIDDirectPay_arr[$package['price']])
		{

			$saleID = $_POST['subscription_id'];
			$payment_method = "CCbill";
			$payment_type = "DirectPay";
			$transaction_id = $_POST['referenceID'];
			$postdata = $_POST;

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
		}
	}
	else
	{
		//NOT APPROVED
	}
}
else
{
	//Wrong account
}
echo "OK";
exit;
?>