<div class="result-box">
<h1>{#MEMBERSHIP#}</h1>
<div class="result-box-inside">
{include file="head.tpl"}<br />

{if $blacklist}
<div class="payment">
Several payment from your account has been canceled. <br> <br> For this reason, the electronic direct debit is available to you are no longer available!
</div>
{else}
<div class="payment">  
<form method="post" action="https://ipayment.de/merchant/46436939/processor.php">
<input type="hidden" name="trxuser_id" value="7849">
<input type="hidden" name="trxpassword" value="38107612">

<input type="hidden" name="trx_paymenttyp" value="elv">

<input type="hidden" name="silent" value="1">
<input type="hidden" name="silent_error_url" value="http://www.lovely-singles.com/back_to_shop.php?paylog_id={$paylog_id}">
<input type="hidden" name="redirect_url" value="http://www.lovely-singles.com/back_to_shop.php?paylog_id={$paylog_id}">
<input type="hidden" name="noparams_on_redirect_url" value="1">

<input type="hidden" name="hidden_trigger_url" value="http://www.lovely-singles.com/hidden_trigger.php?paylog_id={$paylog_id}">
<input type="hidden" name="send_confirmation_email" value="1">	
  
Please double check your payment information. Everything correctly? Then simply make the Payment button!<br /><br />


<label>Account holder:</label><span><input type=text name="addr_name" size=30 maxlength=50 value="{$val_addr_name}" readonly class="box"> </span>
<label>Street:</label><span><input type=text name="addr_street" size=30 maxlength=50 value="{$val_addr_street}" readonly class="box"></span>
<label>Country</label>
<span>{if $val_addr_country eq 'DE'}
<input type=text name="addr_country" size=12 maxlength=2 value="DE" readonly class="box">                 
{elseif $val_addr_country eq 'AT'}
<input type=text name="addr_country" size=12 maxlength=2 value="AT" readonly class="box"> 
{else}
<input type=text name="addr_country" size=12 maxlength=2 value="CH" readonly class="box"> 
{/if}
</span>
<label>Postal code and City:</label><span><input type=text name="addr_zip" size=5 maxlength=5 value="{$val_addr_zip}" readonly class="box"></span>
<label>&nbsp;</label><span><input type=text name="addr_city" size=30 maxlength=30 value="{$val_addr_city}" readonly  class="box"></span>
<label>E-mail Address:</label><span><input type=text name="addr_email" size=30 maxlength=30 value="{$val_addr_email}" readonly class="box"></span>
<label>Amount</label>
<span>
<input type=text name="preis" size=12 maxlength=12 value="{$val_preis},00 EURO" readonly class="box">                
<input type="hidden" name="trx_amount" value="{$val_preis}00">
<input type="hidden" name="trx_currency" value="EUR">
</span>
<label>{$payment_text}</label><span>&nbsp;</span>
<label>Bankname:</label><span><input type=text name="bank_name" size=30 maxlength=30 value="{$val_bank_name}" readonly class="box"></span>
<label>Bank code:</label><span><input type=text name="bank_code" size=12 maxlength=12 value="{$val_bank_code}" readonly class="box"></span>
<label>Account number:</label><span><input type=text name="bank_accountnumber" size=12 maxlength=12 value="{$val_bank_accountnumber}" readonly class="box"></span>
<label>&nbsp;</label><span><input type="submit" name="ccform_submit" value="Payment" class="button"></span>

The payment process can take several seconds, please be patient and click only once.
</form> 
{/if}
</div>
</div></div>