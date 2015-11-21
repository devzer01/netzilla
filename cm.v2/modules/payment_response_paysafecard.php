<?php
require_once('classes/top.class.php');

$mtid = $_GET['mtid'];

$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$mtid." AND purchase_finished=0");
if($package)
{
	require_once(SITE.'config-paysafecard.php');
}
else
{
	// Wrong transaction id.
	die("Invalid transaction id");
}

/* close transaction after debiting */
$closeflag="1";

if ($globaldebug) print "Before calling get_serial_numbers... <BR>\n";

$readmerchant_errorcode = read_merchant_config($config);
if ($readmerchant_errorcode!=0){
	die ("could not read merchant_direct.properties");
} 

$arr = get_serial_numbers($mid, $mtid, $config);

list ($rc, $errorcode, $errormessage, $amount, $currency, $state) = $arr;
$please_contact_admin_msg = 'Bitte kontaktieren Sie <a href="?action=chat&username='.ADMIN_USERNAME_DISPLAY.'" style="color: black; font-weight: bold">Administrator</a>.';

if ($rc == "0")
{
    if ($state == "S")
    {
    	if(abs($amount - $package['price']) < 0.00001)
    	{
			file_put_contents(SITE."payments/paysafecard/detail-".$mtid."-".date("YmdHis").".txt", print_r($arr,true));
			list ($rc, $errorcode, $errormessage) = execute_debit ($mid, $mtid, $amount, $currency, $closeflag, $config);
			if ($rc == "0") {
				$saleID = $serialNumbers;
				$payment_method = "Paysafecard";
				$payment_type = "VoucherCard";
				$transaction_id = $mtid;
				$postdata = $_GET;

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
				//"The transaction could not be completed. This may have happened due to a temporary connection problem.;
				//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
				$_SESSION['payment_failed_message'] = "TDie Transaktion konnte nicht abgeschlossen werden. ".$please_contact_admin_msg;
				header("location: ?action=payment_failed");
			}
		}
		else
		{
			//Wrong price paid
			//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
			$_SESSION['payment_failed_message'] = "Die Transaktion konnte nicht abgeschlossen werden. Wrong price has been paid. ".$please_contact_admin_msg;
			header("location: ?action=payment_failed");
		}
    }
    elseif ($state == "O")
	{
		//print "Disposition has already been debited!";
		header("location: ?action=payment_success");
	}
	else
	{
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		$_SESSION['payment_failed_message'] = "Die Transaktion konnte nicht abgeschlossen werden. ".$please_contact_admin_msg;
		header("location: ?action=payment_failed");
	}
}
else
{
    // do whatever you want if get_serial_numbers failed
	//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	$_SESSION['payment_failed_message'] = "Die Transaktion konnte nicht abgeschlossen werden. ".$please_contact_admin_msg;
	header("location: ?action=payment_failed");
}
?>