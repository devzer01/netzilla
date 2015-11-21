<?

/* configuration file */
/* please configure either for linux or for windows system */

/* (example) path for linux: */
// $config="/usr/local/paysafecard/etc/merchant_direct.properties

/* (example) path for windows: */
$config="/var/www/payment/paysafecard/etc/merchant_direct.properties";



/* ok url:                              */
/*   address the customer comes back to */
/*   if payment finished successfully:  */

// okurl=http://www.mysite.com/psc_ok.php
$okurl="http://localhost/libcurl/psc_ok.php";



/* nok url:                            */
/* address the customer comes back to  */
/* if payment is cancelled:            */

// nokurl=http://www.mysite.com/psc_nok.html
$nokurl="http://localhost/libcurl/psc_nok.html";



/* to enable debug output during test phase,               */
/*  set the global debug parameter to 1 (set 0 to disable) */
$globaldebug="0";

?>