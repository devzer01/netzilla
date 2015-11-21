<?php
$permission_lv = array(1, 4, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
require(SITE."config-worldpay.php");

$columns = DBConnect::row_retrieve_2D_conv_1D("SHOW COLUMNS FROM coin_package");
if(in_array("from_signup_date", $columns))
{
	$signup_date = DBConnect::retrieve_value("SELECT signup_datetime FROM member WHERE id=".$_SESSION['sess_id']);
	$package_date = DBConnect::assoc_query_2D("SELECT from_signup_date FROM coin_package WHERE from_signup_date<'".$signup_date."' GROUP BY from_signup_date ORDER BY from_signup_date DESC");

	$paypal_package = DBConnect::assoc_query_1D("SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' and paypal=1 LIMIT 1");

	$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' and paypal=0 ORDER BY currency_price ASC";
}
else
{
	$paypal_package = DBConnect::assoc_query_1D("SELECT * FROM coin_package WHERE paypal=1 LIMIT 1");
	$sql = "SELECT * FROM coin_package ORDER BY currency_price ASC";
}
$rePackage = DBconnect::assoc_query_2D($sql);

$purchases_log = DBConnect::retrieve_value("SELECT COUNT(id) FROM purchases_log WHERE user_id = '". $_SESSION['sess_id'] ."' AND purchase_finished = '1'");
if(($purchases_log>0) && ($_GET['package_id']==$rePackage[0]["id"]))
{
	header("location: ?action=pay-for-coins");
	exit;
}
elseif(isset($_GET['package_id']))
{
	if(in_array("from_signup_date", $columns))
	{
		$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' AND id=".$_GET['package_id'];
	}
	else
	{
		$sql = "SELECT * FROM coin_package WHERE id=".$_GET['package_id'];
	}

	$user_id=$_SESSION['sess_id'];
	if(isset($_SESSION['payment_admin']) && ($_GET['package_id'] == 0))
	{
		$package = array(
							"currency_price"	=> $_GET['price'],
							"coin"				=> $_GET['coins']
						);

		if(isset($_GET['username']) && ($temp = DBConnect::retrieve_value("SELECT id FROM member WHERE username='".$_GET['username']."' AND isactive=1")))
		{
			$user_id=$temp;
		}
	}
	else
	{
		$package = DBConnect::assoc_query_1D($sql);
	}

	if($package)
	{
		$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
		//Put into purchases_log
		DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip) VALUES (".$user_id.",".$_GET['package_id'].",".$package['currency_price'].",".$package['coin'].",'".$currency."',NOW(),'".$_SERVER['REMOTE_ADDR']."')");
		header("location: ?action=".$_GET['action']."&id=".mysql_insert_id());
	}
	else
	{
		header("location: ?action=pay-for-coins");
	}
}
elseif(isset($_GET['id']))
{
	if(in_array("from_signup_date", $columns))
	{
		$sql = "SELECT * FROM coin_package WHERE from_signup_date='".$package_date[0]['from_signup_date']."' AND id=".$_GET['id'];
	}
	else
	{
		$sql = "SELECT * FROM coin_package WHERE id=".$_GET['id'];
	}
	$package = DBConnect::assoc_query_1D($sql);

	if($_POST['paymentProvider']=="Paypal")
	{
		if($package['paypal'])
		{
			$currency = DBConnect::retrieve_value("SELECT value FROM config WHERE name='CURRENCY'");
			DBConnect::execute_q("INSERT INTO purchases_log (user_id,package_id,price,coin_amount,currency,purchase_datetime, ip) VALUES (".$_SESSION['sess_id'].",".$_GET['id'].",".$paypal_package['currency_price'].",".$paypal_package['coin'].",'".$currency."',NOW(),'".$_SERVER['REMOTE_ADDR']."')");
			$_GET['id'] = mysql_insert_id();
		}
		else
		{
			exit;
		}
	}
	
	if(!isset($_SESSION['payment_admin']))
		$sql_user = " AND user_id=".$_SESSION['sess_id'];
	$package = DBConnect::assoc_query_1D("SELECT p.*, m.email FROM purchases_log p LEFT JOIN member m ON m.id=p.user_id WHERE p.id=".$_GET['id']." AND p.purchase_finished=0".$sql_user);

	if($package)
	{
		if(isset($_POST['paymentProvider']))
		{
			if(ENABLE_PAYMENT)
			{
				switch($_POST['paymentProvider'])
				{
					case "Worldpay":
						$return = array(
										"instId" => WORLDPAY_INSTID,
										"cartId" => $_GET['id'],
										"currency" => CURRENCY,
										"amount" => $package['price'],
										"desc" => $package['price']." ".CURRENCY." for ".$package['coin_amount']." coins",
										);
						echo json_encode($return);
						break;
					case "Ukash":
						require(SITE."config-ukash.php");
						$UTID = getNewUkashTransactionID($SecurityToken, $BrandID, $_GET['id'], $package["user_id"], ceil($package["price"]), CURRENCY);
						echo $UTID;
						break;
					case "CCBill":
						require(SITE."config-ccbill.php");
						$url = getCCBillDirectPayURL($package);
						echo $url;
						break;
					case "CCBillCredit":
						require(SITE."config-ccbill.php");
						$url = getCCBillURL($package);
						echo $url;
						break;
					case "CCBillEUDebit":
						require(SITE."config-ccbill.php");
						$url = getCCBillEUDebitURL($package);
						echo $url;
						break;
					case "Paysafecard":
						require(SITE."config-paysafecard.php");
						//$mtid = date("Ymd-His")."-".$_GET['id'];
						$mtid = $_GET['id'];
						$merchantClientId = DBConnect::retrieve_value("SELECT username FROM member WHERE id=".$package['user_id']);
						$amount=number_format($package['price'],2,".",",");
						$readmerchant_errorcode = read_merchant_config($config);
						if ($readmerchant_errorcode!=0){
							die ("could not read merchant_direct.properties");
						} 

						$okurl=$okurl."&mtid=".$mtid;
						$nokurl=$nokurl."&mtid=".$mtid;
						$pnurl=$pnurl;
						$okurl=rawurlencode($okurl);	
						$nokurl=rawurlencode($nokurl);
						$pnurl=rawurlencode($pnurl);

						if ($globaldebug) print "Before calling create_disposition... <BR>\n";

						list ($rc, $errorcode, $errormessage) = 
							create_disposition($mid, $mtid, $merchantClientId, $amount, $currency, $okurl, $nokurl, $pnurl, $config, $businesstype, $reportingcriteria);

						$language = "de";
						if ($rc == "0") {
						   $url = "$paymenturl?currency=$currency&mid=$mid&mtid=$mtid&merchantClientId=$merchantClientId&amount=$amount&language=$language";
						}
						elseif ($errorcode == "2001") {
						   $url = "$paymenturl?currency=$currency&mid=$mid&mtid=$mtid&merchantClientId=$merchantClientId&amount=$amount&language=$language";
						}
						else
						{
							if($errorcode=='4003')
								$error="Paysafecard: Errorcode: ".$errorcode." => ".$errormessage;
							else
								$error="Paysafecard: Errorcode: ".$errorcode." => Transaction could not be initiated due to connection problems.\r\nIf the problem persists, please contact our support.";
							$url = "index.php?transaction_id=".$_GET['transaction_id'];
						}

						echo json_encode(array("url"=>$url, "error"=>$error));
						break;
					case "Paypal":
						require(SITE."config-paypal.php");
						$paymentAmount = $package['price'];
						$currencyID = $package['currency'];
						$paymentType = 'Sale';
						$payment_site='http://www.'.$domain.'/';
						//$payment_site='http://netzilla.no-ip.org/cm.v2/';

						$returnURL = urlencode($payment_site.'?action=payment_response_paypal&transaction_id='.$package["id"]);
						$cancelURL = urlencode($payment_site.'?action=payment_failed');

						// Add request-specific fields to the request string.
						$nvpStr = "&PAYMENTREQUEST_0_DESC='PayPal Special' ".$package['price']." EUR for ".$package['coin_amount']." coins&PAYMENTREQUEST_0_AMT=$paymentAmount&RETURNURL=$returnURL&CANCELURL=$cancelURL&PAYMENTREQUEST_0_PAYMENTACTION=$paymentType&PAYMENTREQUEST_0_CURRENCYCODE=$currencyID&NOSHIPPING=1&LOCALECODE=DE";

						// Execute the API operation; see the PPHttpPost function above.
						$httpParsedResponseAr = PPHttpPost($API_UserName, $API_Password, $API_Signature, 'SetExpressCheckout', $nvpStr);

						if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"]))
						{
							// Redirect to paypal.com.
							$token = urldecode($httpParsedResponseAr["TOKEN"]);
							$payPalURL = "https://www.paypal.com/webscr&cmd=_express-checkout&token=$token&useraction=commit";
							if("sandbox" === $environment || "beta-sandbox" === $environment) {
								$payPalURL = "https://www.$environment.paypal.com/webscr&cmd=_express-checkout&token=$token&useraction=commit";
							}
							echo $payPalURL;
							exit;
						} else  {
							//print_r($httpParsedResponseAr);
							exit;
						}
						break;
					case "Verotel":
						require(SITE."config-verotel.php");
						$url = getVerotelURL($package, $signaturekey, $shopID);
						echo $url;
						break;
					case "Paymentwall":
						require(SITE."config-paymentwall.php");
						$url = getPaymentwallURL($package);
						echo $url;
						break;
					case "Realex":
						require(SITE."config-realex.php");
						$timestamp = strftime("%Y%m%d%H%M%S");

						$tmp = $timestamp.".".REALEX_MERCHANT_ID.".".$_GET['id'].".".($package['price']*100).".".CURRENCY;
						$sha1hash = sha1($tmp);
						$tmp = $sha1hash.".".REALEX_SECRET;
						$sha1hash = sha1($tmp);

						$return = array(
										"MERCHANT_ID" => REALEX_MERCHANT_ID,
										"ACCOUNT" => REALEX_MERCHANT_ACCOUNT,
										"ORDER_ID" => $_GET['id'],
										"CURRENCY" => CURRENCY,
										"AMOUNT" => $package['price']*100,
										"TIMESTAMP" => $timestamp,
										"SHA1HASH" => $sha1hash
										);
						echo json_encode($return);
						break;
				}
			}
		}
		else
		{
			$smarty->assign('package', $package);
			$smarty->display('index.tpl');
		}
	}
	else
	{
		header("location: ?action=pay-for-coins");
	}
}
else
{
	header("location: ?action=pay-for-coins");
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

function getNewUkashTransactionID($SecurityToken,$BrandID,$MerchantTransactionID,$ConsumerID,$TransactionValue, $currency)
{
	global $domain;
	$payment_site='http://www.'.$domain.'/';
	//build up an array of the data to post to get TransactionID
	$DataToPost = array(
		'SecurityToken' => $SecurityToken,
		'BrandID' => $BrandID,
		'LanguageCode'=> 'DE',
		'MerchantTransactionID'=> ''.$MerchantTransactionID.'',
		'MerchantCurrency'=> ''.$currency.'',
		'ConsumerID'=> ''.$ConsumerID.'',
		'URL_Success'=> $payment_site.'?action=payment_success',
		'URL_Fail'=> $payment_site.'?action=payment_failed',
		'URL_Notification'=> $payment_site.'?action=payment_response_ukash&transaction_id='.$MerchantTransactionID,
		'TransactionValue'=> ''.$TransactionValue.'');

	//Post call to the RPP Gateway, an soap request will be returned.
	$XmlResult =  (DoHttpPost('https://processing.ukash.com/RPPGateway/process.asmx/GetUniqueTransactionID',$DataToPost));

	//Convert the string value to XML
	$xml = new SimpleXmlElement($XmlResult);

	//Decode the xml strings value
	$decodedstring = utf8_decode(urldecode($xml));

	//Reloaded the decoded string, as XML.
	$xml = new SimpleXmlElement($decodedstring);

	//Extract the UTID from the XML object
	$nodes = $xml->xpath('/UKashRPP/UTID');
	$UTID = (string)$nodes[0];

	//Return the UTID value to the calling function.
	return $UTID;
}

function getCCBillURL($details)
{
	global $clientAccnum;
	global $clientSubacc;
	global $cascadeID_arr;
	$language = $_SESSION['lang']=="eng"?"English":"German";
	return "https://bill.ccbill.com/jpost/billingCascade.cgi?clientAccnum=".$clientAccnum."&clientSubacc=".$clientSubacc."&cascadeId=".$cascadeID_arr[$details['price']]."&referenceID=".$details['id']."&language=".$language;
}

function getCCBillDirectPayURL($details)
{
	global $clientAccnum;
	global $clientSubacc;
	global $cascadeIDDirectPay_arr;
	$language = $_SESSION['lang']=="eng"?"English":"German";
	return "https://bill.ccbill.com/jpost/billingCascade.cgi?clientAccnum=".$clientAccnum."&clientSubacc=".$clientSubacc."&cascadeId=".$cascadeIDDirectPay_arr[$details['price']]."&referenceID=".$details['id']."&language=".$language;
}

function getCCBillEUDebitURL($details)
{
	global $clientAccnum;
	global $clientSubacc;
	global $cascadeIDEUDebit_arr;
	$language = $_SESSION['lang']=="eng"?"English":"German";
	return "https://bill.ccbill.com/jpost/billingCascade.cgi?clientAccnum=".$clientAccnum."&clientSubacc=".$clientSubacc."&cascadeId=".$cascadeIDEUDebit_arr[$details['price']]."&referenceID=".$details['id']."&language=".$language;
}

function getVerotelURL($details,$signaturekey,$shopID)
{
	$signature = sha1(
		$signaturekey
		//.":custom1=".$details['site_id']
		.":description=".$details['id']
		.":priceAmount=".$details['price']
		.":priceCurrency=".$details['currency']
		.":referenceID=".$details['id']
		.":shopID=".$shopID
		.":version=1");

	$verotelURL = "https://secure.verotel.com/order/purchase?"
		//."&custom1=".$details['site_id']
		."&description=".$details['id']
		."&priceAmount=".$details['price']
		."&priceCurrency=".$details['currency']
		."&referenceID=".$details['id']
		."&shopID=".$shopID
		."&signature=".$signature
		."&version=1";

	return $verotelURL;
}

function getVerotelSignature($signaturekey,$arr)
{
	$arrString = $signaturekey;
	foreach($arr as $key=>$item)
	{
		if($key!="signature")
		{
			$str=":".$key."=".$item;
			$arrString.=$str;
		}
	}
	return sha1($arrString);
}

function getPaymentwallURL($details)
{
	global $paymenywall_secret;
	global $paymenywall_key;
	global $domain;

	$params = array();
    $params['key'] = $paymenywall_key;
    $params['widget'] = 'p1_1';
    //$params['ts'] = time();
    $params['sign_version'] = 2;
    $params['ag_type'] = 'fixed';
	$params['success_url'] = URL_WEB.'?action=payment_success';
	$params['uid'] = $details['user_id'];
	$params['email'] = $details['email'];
	$params['amount'] = $details['price'];
	$params['currencyCode'] = $details['currency'];
	$params['ag_name'] = $details['coin_amount']." Coins";
	$params['ag_external_id'] = $details['id'];
	$params['sign'] = calculateWidgetSignature($params, $paymenywall_secret);
	$url = 'http://wallapi.com/api/subscription';
	return $url . '?' . http_build_query($params);
}
?>