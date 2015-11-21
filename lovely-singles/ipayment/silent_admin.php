<?
  // PLEASE ENTER YOUR IPAYMENT ACCOUNT DATA HERE
  $account_id   = <Account_ID>;               // Your ipayment Account-ID
  $trxuser_id   = <User_ID>;                  // Your ipayment User-ID
  $trxpassword  = <Transactionpassword>;      // Your transaction password
  $adminactionpassword= <AdminactionPassword> // Your admin action password

  // the admin action password is needed for some special transaction types i.e.
  // refunds.
  // Since this is a form which is normally not displayed to your customers
  // we put the admin action password into the hidden fields of the form.

  // the amount to be processed, will be transmitted  in the parameter trx_amount
  // (can be done via method GET or POST), we assume that the currency is always EUR here.
  if (count($_GET) > 0)
    $params= $_GET;
  elseif (count($_POST) > 0)
    $params= $_POST;
  else
    $params= array();

  $trx_currency= "EUR";

?>

<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Pragma" content="no-cache"> 
   <meta http-equiv="Cache-Control" content="no-cache, must-revalidate"> 
   <meta http-equiv="Expires" content="0"> 
   <meta name="Author" content="ipayment team">
   <title>ipayment Testformular für Kreditkartenzahlungen (Silent Modus)</title>
</head>
<body bgcolor="#ffffff" link="#0000ff" vlink="#800080" text="#000000">

  <h2>ipayment test form for administrative payments (silent mode)</h2>

  <form method="post" action="https://ipayment.de/merchant/<? echo $account_id; ?>/processor.php">
    <input type="hidden" name="trxuser_id" value="<? echo $trxuser_id; ?>">
    <input type="hidden" name="trxpassword" value="<? echo $trxpassword; ?>">
    <input type="hidden" name="adminactionpassword" value="<? echo $adminactionpassword; ?>">
    <input type="hidden" name="silent" value="1">
    <input type="hidden" name="trx_amount" value="<? echo $trx_amount; ?>">
    <input type="hidden" name="trx_currency" value="<? echo $trx_currency; ?>">

    <input type="hidden" name="silent_error_url" value="http://your_domain/back_to_shop.php">
    <input type="hidden" name="hidden_trigger_url" value="http://your_domain/hidden_trigger.php">
    <input type="hidden" name="redirect_url" value="http://your_domain/back_to_shop.php">
    <input type="hidden" name="noparams_on_redirect_url" value="1">

    <table cellpadding="0" cellspacing="0" width="450" border="1">
      <tr>
        <td>
          <table border="0">
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Number of transaction to be used<br/>
                  (payment data are retrieved from the original transaction)
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="orig_trx_number" size="12" maxlength="12" value="">
              </td>
            </tr>
            <tr>
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
              <td valign="top">
                <font face="Arial,Helvetica">
                  action to be processed
                </font>  
              </td>
              <td colspan="2">
                <select name="trx_typ">
                  <option value="capture">Capture preautorization</option>
                  <option value="re_preauth">Do another preauthorization</option>
                  <option value="re_auth">Do another autorization with capture</option>
                  <option value="reverse">Reverse</option>
                  <option value="refund_cap">Refund</option>
                  <option value="refund">Refund without capture</option>
                </select>
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
                <a href="{--backlink--}">Back</a>
              </td>
              <td>
                <input type="submit" name="ccform_submit" value="Process action">
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