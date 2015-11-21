<?php
require_once('classes/top.class.php');
require_once(SITE.'config-verotel.php');

if(!file_exists(SITE."payments/verotel"))
	mkdir(SITE."payments/verotel");

if($_GET['shopID']==$shopID)
{
	$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$_GET['referenceID']." AND purchase_finished=0 AND reference_id!='".$_GET['saleID']."' AND price='".$_GET['priceAmount']."'");
	if($package)
	{
		//get transaction detail from contact market
		$verotelSignature = getVerotelSignature($signaturekey,$_GET);

		//If same signature.
		if($_GET['signature']==$verotelSignature)
		{
			//$client = new SoapClient(null, array('location' => $server_arr[$site_id], 'uri' => "urn://kontaktmarkt"));
			//$client->finishPurchase($transaction_id, $_GET['saleID'], "Verotel", "CreditCard", $_GET);

			$saleID = $_GET['saleID'];
			$payment_method = "Verotel";
			$payment_type = "CreditCard";
			$transaction_id = $_GET['referenceID'];
			$postdata = $_GET;
			file_put_contents(SITE."payments/verotel/callback-".date("YmdHis").".txt", print_r($_REQUEST, true));

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
			echo '<meta http-equiv="refresh" content="0;url=http://www.'.$domain.'/?action=payment_success">';
		}
		else
		{
			// Wrong signature
			file_put_contents(SITE."payments/verotel/callback-error-".date("YmdHis").".txt", print_r($_REQUEST, true));
			echo '<meta http-equiv="refresh" content="0;url=http://www.'.$domain.'/?action=payment_failed">';
		}
	}
	else
	{
		// Wrong package ID
		file_put_contents(SITE."payments/verotel/callback-error-".date("YmdHis").".txt", print_r($_REQUEST, true));
		echo '<meta http-equiv="refresh" content="0;url=http://www.'.$domain.'/?action=payment_failed">';
	}
}
else
{
	// Wrong shop ID
	file_put_contents(SITE."payments/verotel/callback-error-".date("YmdHis").".txt", print_r($_REQUEST, true));
	echo '<meta http-equiv="refresh" content="0;url=http://www.'.$domain.'/?action=payment_failed">';
}
exit;

function getVerotelSignature($signaturekey,$arr)
{
	$arrString = $signaturekey;
	$arrString .= ":priceAmount=" . $arr['priceAmount'] . ":priceCurrency=" . $arr['priceCurrency'] . ":referenceID=" . $arr['referenceID'] . ":saleID=" . $arr['saleID'] . ":shopID=" . $arr['shopID'];
	return sha1($arrString);
}