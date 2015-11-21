<?php
require_once('classes/top.class.php');
error_reporting(E_ALL);

$target_page = "";
$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$_POST['ORDER_ID']." AND purchase_finished=0");

if($package)
{
	require_once(SITE.'config-realex.php');
}
else
{
	// Wrong transaction id.
	echo "Invalid transaction id";
}

if(!file_exists(SITE."payments/realex"))
	mkdir(SITE."payments/realex");

if (!empty($_POST['TIMESTAMP']) && !empty($_POST['RESULT']) && isset($_POST['ORDER_ID']) && !empty($_POST['MESSAGE']) && !empty($_POST['AUTHCODE']) && !empty($_POST['PASREF']) && !empty($_POST['SHA1HASH']))
{
	$time = date("YmdHis");
	file_put_contents(SITE."payments/realex/".$time."-".$_POST['ORDER_ID'].".txt", print_r($_POST,true));
	$amount = $_POST['AMOUNT'];
	$payment_type = (isset($_POST["PAYMENTMETHOD"]) && ($_POST["PAYMENTMETHOD"]!=""))?$_POST["PAYMENTMETHOD"]:"";

	$timestamp = $_POST['TIMESTAMP'];
	$result = $_POST['RESULT'];
	$orderid = $_POST['ORDER_ID'];
	$message = $_POST['MESSAGE'];
	$authcode = $_POST['AUTHCODE'];
	$pasref = $_POST['PASREF'];
	$realexsha1 = $_POST['SHA1HASH'];
	$merchantid = REALEX_MERCHANT_ID;
	$secret = REALEX_SECRET;

	$tmp = "$timestamp.$merchantid.$orderid.$result.$message.$pasref.$authcode";
	$sha1hash = sha1($tmp);
	$tmp = "$sha1hash.$secret";
	$sha1hash = sha1($tmp);

	//Check to see if hashes match or not
	if ($sha1hash == $realexsha1)
	{
		if($result == "00")
		{
			if($amount == ($package["price"]*100))
			{
				//update transaction status
				if(DBConnect::execute_q("UPDATE purchases_log SET purchase_finished=1, reference_id='".$pasref."', payment_method='Realex', payment_type='', purchase_finished_date=NOW(), postdata_serialized='".serialize($_POST)."', postdata_text='".print_r($_POST,true)."' WHERE id=".$orderid))
				{
					//get transaction detail
					$detail = DBConnect::assoc_query_1D("SELECT * from purchases_log WHERE id=".$orderid);

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
					$target_page = "?action=payment_success";
					echo "Payment succeed.";
				}
				else
				{
					$target_page = "?action=chat&username=SUPPORT";
					echo "Failed to update payment status! Please contact SUPPORT";
				}
			}
			else
			{
				$target_page = "?action=chat&username=SUPPORT";
				echo "Wrong price!  Please contact SUPPORT";
			}
		}
		elseif ($result == "101")
		{
			echo "Your transaction has not been successful, please try another payment method.";
		}
		elseif ($result == "102")
		{
			echo "This card has been reported lost or stolen, please contact your bank.";
		}
		elseif ($result == "103")
		{
			echo "This card has been reported lost or stolen, please contact your bank.";
		}
		elseif ($result == "205")
		{
			echo "There has been a communications error, please try again later.";
		}
		else
		{
			echo "There was an error processing your subscription.";
		}
	}
	else
	{
		echo "Hashes don't match - response not authenticated!";
	}
}
else
{
	echo "Missing parameters!";
}

echo "<br/>Click <a href='http://www.".$domain."/".$target_page."'>here</a> to go back to ".$domain;
?>