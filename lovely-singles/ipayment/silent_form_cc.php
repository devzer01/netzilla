<?
  // PLEASE ENTER YOUR IPAYMENT ACCOUNT DATA HERE
  $account_id   = <Account_ID>;               // Your ipayment Account-ID
  $trxuser_id   = <User_ID>;                  // Your ipayment User-ID
  $trxpassword  = <Transactionpassword>;      // Your transaction password

  // the amount to be processed, will be transmitted  in the parameter trx_amount
  // (can be done via method GET or POST), we assume that the currency is always EUR here.
  if (count($_GET) > 0)
    $params= $_GET;
  elseif (count($_POST) > 0)
    $params= $_POST;
  else
    $params= array();

  $trx_amount= $params['trx_amount'];
  $trx_currency= "EUR";

  // to be more save of manipulation of the transmitted account data via the HTML
  // form, you can use the parameter trx_securityhash. If you don't want to use
  // this parameter, comment out or remove the following 2 lines. Make sure that you
  // also remove the hidden field of trx_securityhash in the HTML form.
  // The $security_key here has to be the same like the one in the field
  // Transaction-Security-Key that can be found in the details menu of an "Anwendung"
  // inside the ipayment administration interface.
  // It is recommended to use this option in the normal and silent mode of ipayment.
  $security_key= "qundhft67dnft";
  $trx_securityhash= md5($trxuser_id.$trx_amount.$trx_currency.$trxpassword.$security_key);

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Pragma" content="no-cache"> 
   <meta http-equiv="Cache-Control" content="no-cache, must-revalidate"> 
   <meta http-equiv="Expires" content="0"> 
   <meta name="Author" content="ipayment team">
   <title>ipayment test form for Credit card payments (silent mode)</title>
</head>
<body bgcolor="#ffffff" link="#0000ff" vlink="#800080" text="#000000">

  <h2>ipayment test form for Credit card payments (silent mode)</h2>

  <form method="post" action="https://ipayment.de/merchant/<? echo $account_id; ?>/processor.php">
    <input type="hidden" name="trxuser_id" value="<? echo $trxuser_id; ?>">
    <input type="hidden" name="trxpassword" value="<? echo $trxpassword; ?>">

    <input type="hidden" name="trx_paymenttyp" value="cc">

    <input type="hidden" name="silent" value="1">

    <input type="hidden" name="trx_amount" value="<? echo $trx_amount; ?>">
    <input type="hidden" name="trx_currency" value="<? echo $trx_currency; ?>">
    <input type="hidden" name="trx_securityhash" value="<? echo $trx_securityhash; ?>">

    <input type="hidden" name="silent_error_url" value="http://your_domain/back_from_silent.php">
    <input type="hidden" name="hidden_trigger_url" value="http://your_domain/hidden_trigger.php">
    <input type="hidden" name="redirect_url" value="http://your_domain/back_from_silent.php">
    <input type="hidden" name="noparams_on_redirect_url" value="1">
    <input type="hidden" name="send_confirmation_email" value="1">

    <table cellpadding="0" cellspacing="0" width="450" border="1">
      <tr>
        <td>
          <table border="0">
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Card holders name
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="addr_name" size="34" maxlength="50" value="">
              </td>
            </tr>
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Street
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="addr_street" size="34" maxlength="50" value="">
              </td>
            </tr>
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Zip, City
                </font>
              </td>
              <td>
                <input type="text" name="addr_zip" size="6" maxlength="10" value="">
              </td>
              <td align=right>
                <input type="text" name="addr_city" size="26" maxlength="50" value="">
              </td>
            </tr>
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Email address
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="addr_email" size="34" maxlength="50" value="">
              </td>
            </tr>

            <tr>
              <td height="20" colspan="3"></td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Amount
                </font>  
              </td>
              <td colspan="2">
                <font face="Arial,Helvetica">
                  <b>
                    <? 
                     echo number_format(sprintf("%01.2f", $trx_amount / 100), "2", ",", ".")." ".$trx_currency;
                    ?>
                  </b> (usually filled in automatically)
                </font>
              </td>
            </tr>

            <tr>
              <td height="20" colspan="3"></td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                   Credit card no.
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="cc_number" size="34" maxlength="40" value="">
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                   CVV2 code of the credit card
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="cc_checkcode" size="4" maxlength="4" value=""><br>
                (3 digits in the signature field on the back side of the card (Visa,
                Mastercard) or 4 digits on the front side of the card (American-Express)
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Card expires at:
                </font>  
              </td>
              <td colspan="2">
                <select name="cc_expdate_month">
                  <option>01</option>
                  <option>02</option>
                  <option>03</option>
                  <option>04</option>
                  <option>05</option>
                  <option>06</option>
                  <option>07</option>
                  <option>08</option>
                  <option>09</option>
                  <option>10</option>
                  <option>11</option>
                  <option>12</option>
                </select>
                &nbsp;/&nbsp;
                <select name="cc_expdate_year">
                  <option>2003</option>
                  <option>2004</option>
                  <option>2005</option>
                  <option>2006</option>
                  <option>2007</option>
                  <option>2008</option>
                </select>
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Card valid from:
                </font>  
              </td>
              <td colspan="2">
                <select name="cc_startdate_month">
                  <option>01</option>
                  <option>02</option>
                  <option>03</option>
                  <option>04</option>
                  <option>05</option>
                  <option>06</option>
                  <option>07</option>
                  <option>08</option>
                  <option>09</option>
                  <option>10</option>
                  <option>11</option>
                  <option>12</option>
                </select>
                &nbsp;/&nbsp;
                <select name="cc_startdate_year">
                  <option>2003</option>
                  <option>2004</option>
                  <option>2005</option>
                  <option>2006</option>
                  <option>2007</option>
                  <option>2008</option>
                </select>
                (Only used by some credit cards)
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Issue-No. of the credit card:
                </font>  
              </td>
              <td colspan="2">
                <input type="text" name="cc_issuenumber" size="2" maxlength="2" value=""><br>
                (Only used by some credit cards)
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table>
            <tr>
              <td heigth="20"></td>
            </tr>
            <tr>
              <td>
                <a href="{--backlink--}">Go back</a>
              </td>
              <td>
                <input type="submit" name="ccform_submit" value="Process payment">
              </td>
            </tr>
            <tr>
              <td>
              </td>
              <td>
                The processing of the payment may take some seconds. Please submit the form
                once only and wait for the response.
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  {--custom_values--}
  </form>

</body>
</html>