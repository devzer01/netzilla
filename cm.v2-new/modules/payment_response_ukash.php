<?php
require_once('classes/top.class.php');
require(SITE."config-ukash.php");

if($_POST['UTID'] != '')
{
	//Query the Gateway with the received UTID Value
	$ukash_transaction_detail = QueryTransaction($SecurityToken, $BrandID, $_POST['UTID']);

	//if payment completed
	if($ukash_transaction_detail['TransactionCode']=='0')
	{
		//get transaction detail from contact market
		$package = DBConnect::assoc_query_1D("SELECT * FROM purchases_log WHERE id=".$ukash_transaction_detail['MerchantTransactionID']." AND purchase_finished=0");

		if($package)
		{
			//if transaction detail match transaction from Ukash. (price, currency)
			if((round($ukash_transaction_detail['SettleAmount'])==round($package['price'])) && ($ukash_transaction_detail['MerchantCurrency']==$package['currency']))
			{
				file_put_contents(SITE."payments/ukash/callback-".date("YmdHis")."txt", print_r($_POST, true));
				file_put_contents(SITE."payments/ukash/detail-".$_POST['UTID']."-".date("YmdHis").".txt", print_r($ukash_transaction_detail,true));

				$saleID = $_POST['UTID'];
				$payment_method = "Ukash";
				$payment_type = "VoucherCard";
				$transaction_id = $ukash_transaction_detail['MerchantTransactionID'];
				$postdata = $ukash_transaction_detail;

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
			else
			{
				//Wrong payment info, maybe user pay less than the price.
				echo "ERROR: SettleAmount";
			}
		}
		else
		{
			//Wrong package
			echo "ERROR: MerchantTransactionID";
		}
	}
	else
	{
			//Wrong TransactionCode
			echo "ERROR: TransactionCode";
	}
}
else
{
	//Wrong UTID
	echo "ERROR: UTID";
}

function DoHttpPost($URL,$ArrayOfPostData)
{
	$postData = http_build_query($ArrayOfPostData);

	$headA = array();
	$headA[] = "Content-Type: application/x-www-form-urlencoded";
	$headA[] = "Content-Length: ".mb_strlen($postData);

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$URL);
	curl_setopt($ch,CURLOPT_POST, TRUE);
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postData); 
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_HEADER, false);
	curl_setopt($ch,CURLOPT_HTTPHEADER,$headA);
	$responseA = curl_exec($ch);
	curl_close ($ch);
		
	return $responseA;
}

//This function will call the gateway to query the status, of the transaction.
//The function just returns the values as text, this should be stored / used
//by merchant to change the transaction status on their side.
function QueryTransaction($SecurityToken, $BrandID, $UTID)
{
	//build up an array of the data to post to GetTransactionStatus
	$DataToPost = array(
		'SecurityToken' => $SecurityToken,
		'BrandID' => $BrandID,
		'UTID' => ''.$UTID.'');

	//Post call to the RPP Gateway, an soap request will be returned.
	$XmlResult =  (DoHttpPost('https://processing.ukash.com/RPPGateway/Process.asmx/GetTransactionStatus', $DataToPost));
	
	//Convert the string value to XML
	$xml = new SimpleXmlElement($XmlResult);
	
	//Decode the xml strings value
	$decodedstring = utf8_decode(urldecode($xml));
	
	//Reloaded the decoded string, as XML.
	$xml = new SimpleXmlElement($decodedstring);
	
	//Write out all XML
	//echo $decodedstring.'<br />';
	
	//Extract data
	$nodes = $xml->xpath('/UKashRPP/UTID');
	$detail['UTID'] = (string)$nodes[0];

	$nodes = $xml->xpath('/UKashRPP/TransactionCode');
	$detail['TransactionCode'] = (string)$nodes[0];

	$nodes = $xml->xpath('/UKashRPP/MerchantTransactionID');
	$detail['MerchantTransactionID'] = (string)$nodes[0];

	$nodes = $xml->xpath('/UKashRPP/UkashTransactionID');
	$detail['UkashTransactionID'] = (string)$nodes[0];

	$nodes = $xml->xpath('/UKashRPP/SettleAmount');
	$detail['SettleAmount'] = (string)$nodes[0];

	$nodes = $xml->xpath('/UKashRPP/MerchantCurrency');
	$detail['MerchantCurrency'] = (string)$nodes[0];

	//Return the UTID value to the calling function.
	return $detail;
}
exit;
?>