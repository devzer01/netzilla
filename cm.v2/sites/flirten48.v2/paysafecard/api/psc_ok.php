<?php

/* Merchant transaction ID: unique ID identifying a transaction, up to
 * 30 characters alpha-numeric */
 
//if you use PHP 5 or higher 
//$mtid = $_GET['mtid'];

//if you use PHP4
$mtid = $HTTP_GET_VARS['mtid'];

include "psc_config.php";
include "psc_functions.php";

/* close transaction after debiting */
$closeflag="1";

if ($globaldebug) print "Before calling get_serial_numbers... <BR>\n";

$readmerchant_errorcode = read_merchant_config($config);
if ($readmerchant_errorcode!=0){
   die ("could not read merchant_direct.properties");
} 

list ($rc, $errorcode, $errormessage, $amount, $currency, $state) = get_serial_numbers($mid, $mtid, $config);

if ($rc == "0") {
    print <<<INFO
      Result from get_serial_numbers:\n\n\n\n
      resultcode=$rc\n\n
      errorcode=$errorcode\n\n
      errormessage=$errormessage\n\n
      amount=$amount\n\n
      currency=$currency\n\n
      state=$state\n\n
INFO;

echo "<br>\n<br>\n";

    if ($state == "S") {
      list ($rc, $errorcode, $errormessage) = execute_debit ($mid, $mtid, $amount, $currency, $closeflag, $config);
      if ($rc == "0") {
         print "MTID=$mtid was successfully debited (amount=$amount, currency=$currency).";
      } else {
        // do whatever you want if execute_debit failed
        print <<<INFO
          Error: execute_debit failed!\n\n\n\n
          resultcode=$rc\n\n
          errorcode=$errorcode\n\n
          errormessage=$errormessage\n\n
INFO;
      }
    } elseif ($state == "O") {
      print "Disposition has already been debited!";
    }
} else {
    // do whatever you want if get_serial_numbers failed
    echo "Error: get_serial_numbers failed!<BR>\n\n";
    print <<<INFO
      resultcode=$rc\n\n
      errorcode=$errorcode\n\n
      errormessage=$errormessage\n\n
INFO;
    echo "The problem might be temporary. Please try to <a href=\"javascript:location.reload()\">reload</a> the page.<BR>";
    echo "If the problem prevails, please contact our support.<BR>";
}

?>