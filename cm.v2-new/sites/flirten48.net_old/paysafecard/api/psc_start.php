<?php

/* Merchant transaction ID: unique ID identifying a transaction, 
 * up to 30 alpha-numeric characters */
$mtid = date('Ymd-His');

// for test purposes only, definition of an amount
$amount="1.23";
// needs to be replaced, e.g. with (double checking with session variable or the like)
//$amount = $_POST['amount'];

// determine the language in which you would like the payment panel to appear
$language = "en";
// if you carry a language parameter in the URL, this can be replaced with
// $language = $_GET['language'];


include "psc_functions.php";
include "psc_config.php";

$readmerchant_errorcode = read_merchant_config($config);
if ($readmerchant_errorcode!=0){
	die ("could not read merchant_direct.properties");
} 

$okurl=$okurl."?mtid=".$mtid;
$okurl=rawurlencode($okurl);	
$nokurl=rawurlencode($nokurl);

if ($globaldebug) print "Before calling create_disposition... <BR>\n";

list ($rc, $errorcode, $errormessage) = 
	create_disposition($mid, $mtid, $amount, $currency, $okurl, $nokurl, $config, 
		$businesstype, $reportingcriteria);

if ($rc == "0") {
   header( "Location: $paymenturl?currency=$currency&mid=$mid&mtid=$mtid&amount=$amount&language=$language");
} else {
   // do whatever you want if create_diposition failed
   print <<<INFO
   Error: create_disposition failed!\n\n\n\n
   resultcode=$rc\n\n
   errorcode=$errorcode\n\n
   errormessage=$errormessage\n\n
INFO;
}
?>