<?

  // depending on the server configuration the submited params are in the variables
  // $_POST and $_GET 
  // or $_SERVER['POST'] and $_SERVER['GET']
  // you can display all variables using the PHP function phpinfo();
  //
  // the hidden_trigger_url is usually called via method POST, but we have to check
  // to see, if there are data
  //
  // This script will write a log file. Every time this scrip is called, one line will be
  // added to the log. The line has go the following format:
  // Payment on 09.18.03 at 11:42:17 from name of 129,89 EUR :: payment successful 
  //                                               >>  transaction no 1-10601273
  //

  // Check first if this script was called from the ipayment server. If this is not the case
  // someone was trying to call it from another side, faking a payment. So we terminate
  // the script without doing anything. The ip address below is from our server (the ipayment
  // system). Please be aware that IP addresses might change in future. We will anounce changes
  // on our website.
  $allowed_ips= array(
    "212.227.34.218", 
    "212.227.34.219", 
    "212.227.34.220", 
    "195.20.224.139",
  );
  if (! in_array($_SERVER["REMOTE_ADDR"], $allowed_ips))
    exit();

  // system variables usually always exist and are from type Array,
  // so we only check if the array consists of more than 0 elements.
  if (count($_POST) > 0)
    $params= $_POST;
  elseif (count($_GET) > 0)
    $params= $_GET;
  else
    $params= array();

  // params (if there were submited any) are stored in $params now

  $string="Payment on ".date("m.d.y u\m G:i:s")." from ";

  // the name is stored in cc_name on credit card payments
  // or in addr_name on direct debit payments
  if (isset($params['addr_name']))
    $string.= $params['addr_name'];
  elseif (isset($params['cc_name']))
    $string.= $params['cc_name'];
  else
    $string.= "someone without name";

  // evaluate some other parameters (only a subset - there are more that can be used)
  $string.= " of "
            .preg_replace("/(\d+)?(\d{2})$/", "\\1,\\2", $params['trx_amount'])
            ." ".$params['trx_currency']
            ." :: payment "
            .(($params['ret_errorcode']=="0") ? "successful" : "failed")
            .(($params['ret_booknr']) ? " transaction no ".$params['ret_booknr'] : "")
            ."\n";

  // the previously created string can be written in a file now. It's recomended to use
  // some file locking mechanism here that locks the file if a process is writing into it.
  // There can be several payments from different clients at the same time and the
  // hidden-trigger-script can be called from these processes.
  $log_file= fopen("hidden_trigger.log", "a");
  if ($log_file) fputs ($log_file, $string);

?>