<?php

/*

 Title          : paysafecard PHP-API (libcurl)

 Copyright      : paysafecard.com Wertkarten AG 
 Version        : 1.0.5 
 Current Date   : 17.8.2005
 
 Initial Date   : 10.6.2003 
 Initial Version: T.Huemer@paysafecard.com
 
 History        : see doc/changelog.txt

 Paysafecard grants (licenses) the use of this file to third parties.
 Third parties may modify parts of this code.
 Third parties may not relicense or resell this product.

*/

// array of error message strings
global $error_message;
$error_message = array (
    0 => "",
    4001 => "SSL error",
    4002 => "invalid function request",
    4003 => "bad value in function parameter number ",
    4003 => "bad parameter",
    4004 => "invalid proxy request",
    4005 => "connection error",
    4006 => "unexpected response from server",
    4007 => "undefined error - this should not happen",
    4008 => "error reported by backend",
    4010 => "error opening config file: ",
    4011 => "config file is no regular readable file: ",
    4012 => "incorrect syntax in config file: ",
    4013 => "incorrect value in config file: ",
    4014 => "error HTTP response from API proxy: ",
    9002 => "file does not exist: ",
    9003 => "property is not available: ",
    9006 => "no card properties available",
    9007 => "error parsing cards property file",
    9008 => "can not open file: "
);

// the following are default values for the config parameters
$language="en";


////////////////////////////////////////////////////////////////////

function do_https_request($url, $urlparam, $keyringfile, $keyringpw, $cakeyringfile)
{
   global $globaldebug;
/* requrl: the URL executed later on */
   if ($globaldebug) print "<BR>do_https_request: $url<BR>\n\r\n";
   if ($globaldebug) print "do_https_request: $urlparam<BR>\n\r\n";
/* some prerquisites for the connection */
   $ch = curl_init($url);
   curl_setopt($ch, CURLOPT_POST, 1);  // a non-zero parameter tells the library to do a regular HTTP post.
   curl_setopt($ch, CURLOPT_POSTFIELDS, $urlparam);  // add POST fields
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);  // don't allow redirects
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // return into a variable
   curl_setopt($ch, CURLOPT_TIMEOUT, 240);  // maximum time, in seconds, that you'll allow the CURL functions to take
   curl_setopt($ch, CURLOPT_SSLCERT, $keyringfile); // filename of PEM formatted certificate
   curl_setopt($ch, CURLOPT_SSLCERTTYPE, "PEM"); // format of certificate, "PEM" or "DER"
   curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $keyringpw); // password required to use the CURLOPT_SSLCERT certificate
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); // verify the peer's certificate
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // verify the Common name from the peer certificate
   curl_setopt($ch, CURLOPT_CAINFO, $cakeyringfile); // file holding one or more certificates to verify the peer with
/* establish connection */
   $data = curl_exec($ch);
/* determine if there were some problems */
   $errno = curl_errno($ch);
   $errmsg = curl_error($ch);

/* bug fix for PHP 4.1.0/4.1.2 (curl_errno() returns high negative
 * value in case of successful termination) */
   if ($errno < 0) $errno = 0;
/* bug fix for PHP 4.1.0/4.1.2 */

   if ($globaldebug) {
      print_r(curl_getinfo($ch));
      echo "<BR><BR>\n\n\ndo_https_request: cURL error number:" . $errno . "<BR>\n";
      echo "\n\n\ndo_https_request: cURL error:" . $error . "<BR>\n";
   }
/* close connection */
   curl_close($ch);
/* read and return data from paysafecard server */
   if ($globaldebug) print "<BR>\n" . $data;
   return array ($errno,$errmsg,$data);
}

////////////////////////////////////////////////////////////////////

function read_config($file)
{
   global $globaldebug;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	       $initialize_merchant_test_data_URL;
	
   $errno = 0;

   // check config file for existence and readability
   if ( ! is_readable($file) ) {
      $errno = 4011;
      return $errno;
   }

   // open config file
   if (! ($fp=fopen($file,"r")) ) return 4010;

   // loop lines
   while( ! feof($fp) ) {
      $line=trim(fgets($fp,1024));
      
      // skip comments and empty lines
      if ( (substr($line,0,1) != "#") and (! empty($line)) ) {
			 // if ($globaldebug) print "$line";
			 // if ($globaldebug) { $xxx = substr($line,0,9); print "$xxx"; }
		
			 // '=', '=123' ==> error
		    if (substr($line,0,1) == "=") return 4012;
		
			 if (substr($line,0,11) == "keyringfile") $keyringfile=substr($line,12);
			 if (substr($line,0,9)  == "keyringpw") $keyringpw=substr($line,10);
			 if (substr($line,0,13) == "cakeyringfile") $cakeyringfile=substr($line,14);
			 if (substr($line,0,8)  == "language") $language=substr($line,9);
		
			 if (substr($line,0,22) == "create_disposition_URL") $create_disposition_URL=substr($line,23);
			 if (substr($line,0,25) == "get_disposition_state_URL") $get_disposition_state_URL=substr($line,26);
			 if (substr($line,0,17) == "execute_debit_URL") $execute_debit_URL=substr($line,18);
			 if (substr($line,0,28) == "modify_disposition_value_URL") $modify_disposition_value_URL=substr($line,29);
			 if (substr($line,0,22) == "get_serial_numbers_URL") $get_serial_numbers_URL=substr($line,23);
			 if (substr($line,0,33) == "initialize_merchant_test_data_URL") $initialize_merchant_test_data_URL=substr($line,34);
      }
   }

   // close config file
   fclose($fp);

   // some debug information
   if ($globaldebug) {
   	
      print "read_config: keyringfile=$keyringfile<BR>\n";
      print "read_config: keyringpw=$keyringpw<BR>\n";
      print "read_config: cakeyringfile=$cakeyringfile<BR>\n";
      print "read_config: language=$language<BR>\n";
      print "read_config: create_disposition_URL=$create_disposition_URL<BR>\n";
      print "read_config: get_disposition_state_URL=$get_disposition_state_URL<BR>\n";
      print "read_config: execute_debit_URL=$execute_debit_URL<BR>\n";
      print "read_config: modify_disposition_value_URL=$modify_disposition_value_URL<BR>\n";
      print "read_config: get_serial_numbers_URL=$get_serial_numbers_URL<BR>\n";
      print "read_config: initialize_merchant_test_data_URL=$initialize_merchant_test_data_URL<BR>\n";
   }

   // return
   return $errno;
}

////////////////////////////////////////////////////////////////////

function read_merchant_config($file)
{
   global $globaldebug;
	global $mid, $businesstype, $currency, $reportingcriteria, $paymenturl;
	
   $errno = 0;

   // check config file for existence and readability
   if ( ! is_readable($file) ) {
      $errno = 4011;
      return $errno;
   }

   // open config file
   if (! ($fp=fopen($file,"r")) ) return 4010;

   // loop lines
   while( ! feof($fp) ) {
      $line=trim(fgets($fp,1024));
      
      // skip comments and empty lines
      if ( (substr($line,0,1) != "#") and (! empty($line)) ) {
			 // if ($globaldebug) print "$line";
			 // if ($globaldebug) { $xxx = substr($line,0,9); print "$xxx"; }
		
			 // '=', '=123' ==> error
		    if (substr($line,0,1) == "=") return 4012;
		
          if (substr($line,0,3)  == "mid") $mid = substr($line, 4);
          if (substr($line,0,12) == "businesstype") $businesstype = substr($line, 13);
          if (substr($line,0,8)  == "currency") $currency = substr($line, 9);
          if (substr($line,0,17) == "reportingcriteria") $reportingcriteria = substr($line, 18);
          if (substr($line,0,10) == "paymenturl") $paymenturl = substr($line, 11);
      }
   }

   // close config file
   fclose($fp);

   // some debug information
   if ($globaldebug) {
   	
      print "read_config: mid=$mid<BR>\n";
      print "read_config: businesstype=$businesstype<BR>\n";
      print "read_config: currency=$currency<BR>\n";
      print "read_config: reportingcriteria=$reportingcriteria<BR>\n";
      print "read_config: paymenturl=$paymenturl<BR>\n";
      print "<BR>\n";
	   }

   // return
   return $errno;
}

////////////////////////////////////////////////////////////////////

function create_disposition($mid, $mtid, $amount, $currency, $okurl, $nokurl, $config, $businesstype, $reportingcriteria)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, 
          $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, 
          $get_serial_numbers_URL,
          $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;
   

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);


   // some debug information
   if ($globaldebug) {
      print "create_disposition: keyringfile=$keyringfile<BR>\n";
      print "create_disposition: keyringpw=$keyringpw<BR>\n";
      print "create_disposition: cakeyringfile=$cakeyringfile<BR>\n";
      print "create_disposition: language=$language<BR>\n";
      print "create_disposition: create_disposition_URL=$create_disposition_URL<BR>\n";
      print "create_disposition: get_disposition_state_URL=$get_disposition_state_URL<BR>\n";
      print "create_disposition: execute_debit_URL=$execute_debit_URL<BR>\n";
      print "create_disposition: modify_disposition_value_URL=$modify_disposition_value_URL<BR>\n";
      print "create_disposition: get_serial_numbers_URL=$get_serial_numbers_URL<BR>\n";
      print "create_disposition: initialize_merchant_test_data_URL=$initialize_merchant_test_data_URL<BR>\n";
   }

/* requrl: the URL executed later on */
   $requrl = $create_disposition_URL;
   $reqparam = "currency=$currency&mid=$mid&mtid=$mtid&amount=$amount&businesstype=$businesstype&reportingcriteria=$reportingcriteria&okurl=$okurl&nokurl=$nokurl&language=$language";
   if ($globaldebug) print "create_disposition: requrl=$requrl<BR>\n\r\n";
   if ($globaldebug) print "create_disposition: reqparam=$reqparam<BR>\n\r\n";
/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {
/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      return array ($resultcode,$errorcode,$errormessage);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

function get_disposition_state($mid, $mtid, $config)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	  $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);

/* requrl: the URL executed later on */
   $requrl = $get_disposition_state_URL;
   $reqparam = "mid=$mid&mtid=$mtid&language=$language";
   if ($globaldebug) print "get_disposition_state: $requrl\r\n";
   if ($globaldebug) print "get_disposition_state: $reqparam\r\n";

/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {

/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      $amount=trim($dataarray[3]);
      $currency=trim($dataarray[4]);
      $state=trim($dataarray[5]);
      return array ($resultcode,$errorcode,$errormessage,$amount,$currency,$state);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

function execute_debit($mid, $mtid, $amount, $currency, $closeflag, $config)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	  $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);

/* requrl: the URL executed later on */
   $requrl = $execute_debit_URL;
   $reqparam = "currency=$currency&mid=$mid&mtid=$mtid&amount=$amount&close=$closeflag&language=$language";
   if ($globaldebug) print "execute_debit: $requrl\r\n";
   if ($globaldebug) print "execute_debit: $reqparam\r\n";
/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {
/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      return array ($resultcode,$errorcode,$errormessage);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

function modify_disposition_value($mid, $mtid, $amount, $currency, $config)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	  $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);

/* requrl: the URL executed later on */
   $requrl = $modify_disposition_value_URL;
   $reqparam = "currency=$currency&mid=$mid&mtid=$mtid&amount=$amount&language=$language";
   if ($globaldebug) print "modify_disposition_value: $requrl\r\n";
   if ($globaldebug) print "modify_disposition_value: $reqparam\r\n";
/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {
/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      return array ($resultcode,$errorcode,$errormessage);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

function initialize_merchant_test_data($mid, $config)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	  $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);

/* requrl: the URL executed later on */
   $requrl = $initialize_merchant_test_data_URL;
   $reqparam = "mid=$mid&language=$language";
   if ($globaldebug) print "initialize_merchant_test_data: $requrl\r\n";
   if ($globaldebug) print "initialize_merchant_test_data: $reqparam\r\n";
/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {
/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      return array ($resultcode,$errorcode,$errormessage);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

function get_serial_numbers($mid, $mtid, $config)
{
   global $globaldebug;
   global $error_message;
   global $create_disposition_URL, $get_disposition_state_URL, $execute_debit_URL,
          $modify_disposition_value_URL, $get_serial_numbers_URL,
	  $initialize_merchant_test_data_URL;
   global $keyringfile, $keyringpw, $cakeyringfile, $language;

/* read config file */
   $errorcode = read_config($config);
   if ( $errorcode != 0) return array (2,$errorcode,$error_message[$errorcode].$config);

/* requrl: the URL executed later on */
   $requrl = $get_serial_numbers_URL;
   $reqparam = "mid=$mid&mtid=$mtid&language=$language";
   if ($globaldebug) print "get_serial_numbers: $requrl\r\n";
   if ($globaldebug) print "get_serial_numbers: $reqparam\r\n";
/* https connection */
   list ($rc, $msg, $data) = do_https_request($requrl, $reqparam, $keyringfile, $keyringpw, $cakeyringfile);
   if ($rc == 0) {
/* read and return data from paysafecard server */
      if ($globaldebug) print $data;
      $dataarray=explode("\n", $data,7);
      $resultcode=trim($dataarray[0]);
      $errorcode=trim($dataarray[1]);
      $errormessage=trim($dataarray[2]);
      $amount=trim($dataarray[3]);
      $currency=trim($dataarray[4]);
      $state=trim($dataarray[5]);
      $snamount=trim($dataarray[6]);
      return array ($resultcode,$errorcode,$errormessage,$amount,$currency,$state,$snamount);
   } else {
      $resultcode = "9001";
      $errorcode = "$rc";
      $errormessage = "libcurl error: $msg";
      return array ($resultcode,$errorcode,$errormessage);
   }
}

////////////////////////////////////////////////////////////////////

?>
