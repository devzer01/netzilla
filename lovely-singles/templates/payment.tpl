{if $smarty.session.sess_id}
	{literal}
	<script language="javascript">
		function changeOption(level){    
			var pay_obj = $("payment");
			var via = pay_obj.options[pay_obj.selectedIndex].value;        
			var gp_price = "GiroPay-Preis:<br><br>3 Monate: {/literal}{$gold_price.7}{literal},00 €<br>1 Jahr: {/literal}{$gold_price.8}{literal},00 €";
		
			if (level == 3){
				$("silver_level").style.display = "block";
				$("gold_level").style.display = "none";
				
				document.getElementById("price").innerHTML = "";
			}
			else {
				$("silver_level").style.display = "none";
				$("gold_level").style.display = "block";
				
				document.getElementById("price").innerHTML = gp_price;
			}
		}
		
		function changePaymentVia(via) {
			if (via == 4 || via == 6) {
				$("paid_via_3").style.display = "none";
				$("real_name").value = "";
				$("real_street").value = "";
				$("real_city").value = "";
				$("real_plz").value = "";
			}
			else {
				$("paid_via_3").style.display = "block";
			}
			
			var level_obj = $("mitglied");
			var level = level_obj.options[level_obj.selectedIndex].value;
			
			changeOption(level);
		}
		
		function check_info(){
			var obj = $("payment");
			var selected = obj.options[obj.selectedIndex].value;
			if ((selected == 2) || (selected == 3) || (selected == 5)) {
				if ($("real_name").value == "") {
					alert("Please enter your name!");
					return false;
				} else if ($("real_street").value == "") {
					alert("Please enter your address!");
					return false;
				} else if ($("real_city").value == "") {
					alert("Please enter your city!");
					return false;
				} else if ($("real_plz").value == "") {
					alert("Please enter your zip code!");
					return false;
				} else return true;
			}
		}
		
		function ValidateInput(form_name, err_msg){
			var error = 0;
			var form = form_name;
			for (var i = 0; i < form.elements.length; i++) {
				if (form.elements[i].className == "require") {
					if (form.elements[i].value == "") {
						error = 1;
						break;
					}
				}
			}
			if (error == 1) {
				alert(err_msg);
				return false;
			}
			else {
				return true;
			}
		}
	</script>
	{/literal}
<div class="payment">
	{$text}

	{if $payment_history}
		<h1>Your membership for the time :
		{if $payment_history.id}
		Since<br />
		Valid to<br />
		Cancellation<br />
		{/if}
		{$payment_history.type}<br />
		</h1>
		{if $payment_history.id}
			{$payment_history.start_date}
			{$payment_history.end_date}
			
			{if $payment_history.cancelled eq 0}
			<span style="color: red; cursor: pointer" onclick="if(confirm('Möchtest du deine Mitgliedschaft tatsächlich beenden?')) location='?action=membership&type=cancel&id={$payment_history.id}'">Cacel Membership.</span>
			{else}
			<span style="color: red;">Complete</span>
			{/if}    
		{/if}
	{/if}

	{if !$payment}
		{if $today neq ""}
			{if $today eq $payment_history.start_date}
				You have already conducted a successful payment!
			{else}
				{if $payment_history}
					{if $payment_history.type == "VIP"}
						<b>You want to extend your VIP membership?
						<br>
						Your membership term shall be automatically extended for the period you have chosen.
						</b>
						<br>
						<br>
					{*
					{elseif $payment_history.type == "Premium"} 
						Du möchtest deine Premium Mitgliedschaft verlängern? 
						<br>
						Deine Mitgliedschaftsdauer verlängert sich automatisch um den von dir gewählten Zeitraum.
						<br>
						<br>
						Oder möchtest du deine Premium zur VIP Mitgliedschaft aufwerten? 
						<br>
						Dann zahle nur die Differenz zwischen deinem jetzigen und dem VIP-Abo!
						<br>
						<br>
					*}
					{elseif $payment_history.type == "Standard"}<b>Payment</b>
						<br>
						<br>
					{/if}
				{/if}

				<form id="payment_form" name="payment_form" method="POST" action="index.php?action=payportal" onsubmit="return check_info();">
				<label>Your desired membership:</label>
				<span><select id="mitglied" name="mitglied" onchange="changeOption(this.options[this.selectedIndex].value)" class="box">
				{*
				{if $payment_history.type ne "VIP"}<option value="3">Premium</option>{/if}
				*}
				<option value="2" selected="selected">VIP</option>
				</select></span>
				<br/>
				{*<span id="price">GiroPay-Preis:<br/>
				1 Month: [{$gold_price.2},00 €<br/>
				3 Months: {$gold_price.7},00 €<br/>
				1 Year: {$gold_price.8},00 €
				</span>*}

				<label>Your desired duration::</label>
				{if $payment_history.type == "VIP"}
					<span id="gold_level">
					<select name="abo_gold" class="box">
					<option value="2">1 Month [{$gold_price.2},00 Euro]</option>
					<option value="3" selected="selected">3 Months [{$gold_price.3},00 Euro]</option>
					<option value="4">1 Year [{$gold_price.4},00 Euro]</option>
					</select>
					</span>
					{*
					{elseif $payment_history.type == "Premium"}
					<span id="silver_level" style="display: none">
					<select name="abo_silver">
					<option value="1">3 Tage [{$silver_price.1},00 Euro]</option>
					<option value="2" selected="selected">1 Monatsabo [{$silver_price.2},00 Euro]</option>
					<option value="3">3 Monatsabo [{$silver_price.3},00 Euro]</option>
					<!-- <option value="4">Jahres Abo</option> -->
					</select>
					</span>
					<span id="gold_level" style="display: block">
					<select name="abo_gold">
					{if $remain lte "30"}<option value="5">1 Monatsabo [nur {$gold_price.5},00 Euro]</option>
					{else}<option value="6" selected="selected">3 Monatsabo [nur {$gold_price.6},00 Euro]</option>
					{/if}<option value="4">Jahres Abo [{$gold_price.4},00 EURO]</option>
					</select>
					</span>
					*}
					{else}
					{*<span id="silver_level" style="display: none">
					<select name="abo_silver">
					<option value="1">3 Tage [{$silver_price.1},00 Euro]</option>
					<option value="2" selected="selected">1 Monatsabo [{$silver_price.2},00 Euro]</option>
					<option value="3">3 Monatsabo [{$silver_price.3},00 Euro]</option>
					<!-- <option value="4">Jahres Abo</option> -->
					</select>
					</span>*}
					<span id="gold_level">
					<select name="abo_gold" class="box">
					<option value="2">1 Month [{$gold_price.2},00 Euro]</option>
					<option value="3" selected="selected">3 Months [{$gold_price.3},00 Euro]</option>
					<option value="4">1 Year [{$gold_price.4},00 Euro]</option>
					</select>
					</span>
				{/if}
				<label>Your desired method of payment:</label>
				<span><select name="payment" id="payment" onchange="changePaymentVia(this.options[this.selectedIndex].value)" class="box">
				<option value="2">Paypal</option>
				<option value="3">Transfer</option>
				<option value="4">Electronic debit (ELV)</option>
				<option value="5">Cash payment</option>
				<option value="6" selected>GiroPay online payment</option>
				</select>
                </span>
                
				<span id="paid_via_3" style="display:none; width:100%;">
                
				<span id="paid_via_5" style="display:none">Cash payment is made by sending a letter to the TMP Call Center Service-Nord GmbH</span>
                
				<label>Name:</label><span><input type="text" name="real_name" id="real_name" class="box"></span>
				<label>Address:</label><span><input type="text" name="real_street" id="real_street" class="box"></span>
				<label>{#City#}:</label><span><input type="text" name="real_city" id="real_city" class="box"></span>
				<label>Zip code:</label><span><input type="text" name="real_plz" id="real_plz" class="box"></span>
				</span>
                <br clear="all"/>
				<label></label><span><input type="submit" class="button" value="Send" name="senden" id="senden"></span><br clear="all"/>
				</form>
			{/if}
		{/if}
	{elseif $valid_more_14_days}
		<b>Your membership is still valid for more than 14 days and can not therefore be extended.</b><br/><br/>
	{elseif $payment==1}
		<b>Credit card</b>
		<br><br>
		<form method="post" action="https://ipayment.de/merchant/45519135/processor.php">
		<input type="hidden" name="trxuser_id" value="7173">
		<input type="hidden" name="trxpassword" value="46623301">
		<input type="hidden" name="trx_paymenttyp" value="cc">
		<input type="hidden" name="silent" value="1">
		<input type="hidden" name="silent_error_url" value="{$smarty.const.URL_WEB}back_to_shop.php">
		<input type="hidden" name="redirect_url" value="{$smarty.const.URL_WEB}/back_to_shop.php?paylog_id={$paylog_id}">
		<input type="hidden" name="noparams_on_redirect_url" value="1">
		<input type="hidden" name="hidden_trigger_url" value="{$smarty.const.URL_WEB}/hidden_trigger.php">
		<input type="hidden" name="send_confirmation_email" value="1">
		Name: 			<input type="text" name="addr_name" size="34" maxlength="50" value="">
		Address: 		<input type="text" name="addr_street" size="34" maxlength="50" value="">
		Zip code, {#City#}: 	<input type="text" name="addr_zip" size="6" maxlength="10" value=""><input type="text" name="addr_city" size="26" maxlength="50" value="">
		{#Country#}: 
			<select name="addr_country">
			<option value="DE">Deutschland</option>
			<option value="AT">Österreich</option>
			<option value="CH">Schweiz</option>
			</select>
		{#Email#}:	<input type="text" name="addr_email" size="34" maxlength="50" value="">
		Total:	<br />
		{$preis} EUR	<input type="hidden" name="trx_amount" value="{$preis}"><input type="hidden" name="trx_currency" value="EUR"><br />
		{$payment_text}<br />
		Credit card Number: <input type="text" name="cc_number" size="34" maxlength="40" value=""><br />
		CVV2 code of Credit card: <input type="text" name="cc_checkcode" size="4" maxlength="4" value="">
		<br>
		(3 characters on the back of the card (Visa,
		Mastercard) or 4 characters on the front of the card (American-Express)
		<br />
		<br />
		Valid to: 
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
		<br />
		<br />
		Valid from: 
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
		(Only used by some credit cards)<br />

		Issue Number: 
		<input type="text" name="cc_issuenumber" size="2" maxlength="2" value="">
		<br>
		(Wird nicht bei allen Kreditkarten genutzt)<br />
		<input type="submit" name="ccform_submit" value="Zahlungsdaten prüfen">
		<br />
		<br />
		The payment process may take a few seconds in claims. Please submit the form only 1 time and you just have patience.
		</form>
		<br>
		<br>
		{if $payment==1} 
			This subscription would be valid until: <b>{$dauer}</b><br>
		{else} 
			This subscription would be valid until: <b>{$dauer}</b>
			<br>
			Your payment amount: <b>{$preis} Euro</b>
			<br>
			<br>
			<input type="submit" value="abschließen">
			</form>
		{/if}
	{elseif $payment==2}
		<h1>Your Paypal payment:</h1>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="business" value="jg@loox-consulting.de">
		<input type="hidden" name="currency_code" value="EUR">
		<input type="hidden" name="cancel_return" value="{$smarty.const.URL_WEB}">
		<input type="hidden" name="return" value="{$smarty.const.URL_WEB}back_to_shop.php?paylog_id={$paylog_id}&from='paypal'">
		<input type="hidden" name="item_name" value="{$aboname}">
		<input type="hidden" name="item_number" value="{$paylog_id}">
		<input type="hidden" name="amount" value="{$preis}">
		
		<input type="image" id="abschließen" name="abschließen" value="PDT" onclick="submit" class="button">
		</form>
        <br />
		<strong>This subscription was valid up to: {$dauer}</strong><br />
		<strong>An amount to be paid: {$preis} Euro</strong><br />
		{$payment_text}<br />
		</form>
	{elseif $payment==3}
		<h1>Transfer</h1>
		<u><strong>Please transfer the amount to below:</strong></u><br />
		<u>Account owner:</u> 
		TMP Callcenter Service-Nord GmbH<br />
		<u>Account number:</u> 
		303185119<br />
		<u> Bank code:</u> 
		85590000<br />
		<u>Institute:</u> 
		Volksbank Bautzen<br />
		<u>Purpose:</u> 
		{$vzweck}<br />
		<u>Amount:</u> 
		{$preis} Euro<br />
		{$payment_text}
	{elseif $payment==4}
		<h1>Electronic debit</h1>
		<font face="arial,helvetica" color="red">
		{$error_message}
		</font>
		<form id="evn_validation_form" name ="evn_validation_form" action="?action=evnValidation" method="post" onsubmit="return ValidateInput(this, "Bitte fülle alle Felder vollständig aus.")">
		<label>First name:</label><span><input type=text name="addr_vorname" size=34 maxlength=50 value="" class="box"></span>
		<label>Last name:</label><span><input type=text name="addr_name" size=34 maxlength=50 value="" class="box"></span>
		<label><u>(Account holder)</u></label><span>&nbsp;</span>
		<label>Street and House number:</label><span><input type=text name="addr_street" size=34 maxlength=50 value="" class="box"></span>
		<label>{#Country#}: </label>
		<span>
        <select name="addr_country" class="box">
		<option value="DE">Germany</option>
		<option value="AT">Austria</option>
		<option value="CH">Switzerland</option>
		</select>
		</span>
		<label>Postal code and city:</label><span><input type=text name="addr_zip" size=6 maxlength=10 value="" class="box"></span>
		<label>&nbsp;</label><span><input type=text name="addr_city" size=26 maxlength=50 value="" class="box"></span>
		<label>E-mail Address</label><span><input type=text name="addr_email" size=34 maxlength=50 value="" class="box"></span>
		<label>Amount:</label><span>{$preis} Euro  </span>
		<input type="hidden" name="preis" value="{$preis}">
		<input type="hidden" name="trx_amount" value="{$preis}00">
		<input type="hidden" name="trx_currency" value="EUR">
		
		{$payment_text}
		<label>Bankname:</label><span><input type=text name="bank_name" size=34 maxlength=40 value="" class="box"></span>
		<label>Bank code:</label><span><input type=text name="bank_code" size=12 maxlength=12 value="" class="box"></span>
		<label>Account number:</label><span><input type=text name="bank_accountnumber" size=12 maxlength=12 value="" class="box"></span>
		<br />        
		<label>&nbsp;</label><span><input type="submit" class="button" name="ccform_submit" value="Send"></span>
		</form>
	{elseif $payment==5}
		<h1>Sending cash</h1>
		<u><strong>Please send the below amount to:</strong></u><br />
		<u>Address:</u><br />
		TMP Callcenter Service-Nord GmbH 
		Postfach 4242 
		24041 Kiel<br />
		<u>Purpose:</u><br />
		{$vzweck}<br />
		<strong>Amount: {$preis} Euro</strong><br />
		{$payment_text}
	{elseif $payment==6}
		<h1>GiroPay online payment</h1>
		<font face="arial,helvetica" color="red">
		{$error_message}
		</font>

		<form id="gp_validation_form" name="gp_validation_form" action="?action=giroPay" method="post" onsubmit="return ValidateInput(this, "Bitte fülle alle Felder vollständig aus.")">
        <label>First name:</label><span><input type=text name="addr_vorname" size=34 maxlength=50 value="" class="box"></span>
        <label>Last name:</label><span><input type=text name="addr_name" size=34 maxlength=50 value="" class="box"></span>
        <label><u>(Account holders)</u></label><span>&nbsp;</span>
        <label>Street and house number:</label><span><input type=text name="addr_street" size=34 maxlength=50 value="" class="box"></span>
        <label>{#Country#}:</label>
        <span>
        <select name="addr_country" class="box">
		<option value="DE">Deutschland</option>
		<option value="AT">Österreich</option>
		<option value="CH">Schweiz</option>
		</select>
        </span>
        <label>Postal code and city:</label><span><input type=text name="addr_zip" size=6 maxlength=10 value="" class="box"></span>
        <label>&nbsp;</label><span><input type=text name="addr_city" size=26 maxlength=50 value="" class="box"></span>
        <label>E-Mail Address:</label><span><input type=text name="addr_email" size=34 maxlength=50 value="" class="require"></span>
        <label>Amount:</label><span>{$preis} Euro</span>
        <input type="hidden" name="preis" value="{$preis}"><input type="hidden" name="trx_amount" value="{$preis}00"><input type="hidden" name="trx_currency" value="EUR">
        <label>{$payment_text}</label><span>&nbsp;</span>
        <label>Bankname:</label><span><input type=text name="bank_name" size=34 maxlength=40 value="" class="box"></span>
        <label>Bank code:</label><span><input type=text name="bank_code" size=12 maxlength=12 value="" class="box"></span>
        <label>Account number</label><span><input type=text name="bank_accountnumber" size=12 maxlength=12 value="" class="box"></span>
        <label>&nbsp;</label><span><input type="submit" name="gpform_submit" value="Send" class="button"></span>
		</form>
	{/if}
    </div>
{/if}

{include file=membership_listing.tpl}

{if $today neq ""}
	{if $smarty.session.sess_id}
		If the subscription is terminated, extended for 3 days trial subscription to a monthly subscription VIP. The 1 monthly subscription will be automatically converted into a 3 monthly subscription, which converts into a monthly subscription 3 year subscription. The annual subscription is renewed for another year.
		<br/>
	{/if}
	<br/>
	<input type="button" class="button" value="Back" onclick="window.location = '.'" name="back_button" id="back_button">
{/if}

