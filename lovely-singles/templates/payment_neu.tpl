{literal}
<script language="javascript">
function changeOption(level)
{
	if(level == 3)
	{
		$('silver_level').style.display = 'block';
		$('gold_level').style.display = 'none';
	}
	else
	{
		$('silver_level').style.display = 'none';
		$('gold_level').style.display = 'block';
	}
}

function ValidateInput(form_name,err_msg)
{
	var error = 0;
	var form = form_name;
	for(var i=0; i < form.elements.length; i++)
	{
		if(form.elements[i].className == 'require')
		{
			if(form.elements[i].value == "")
			{
				error=1;
				break;
			}
		}
	}
	if(error == 1)
	{
		alert(err_msg);
		return false;
	}
	else
	{
		return true;
	}
}
</script>
{/literal}
<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<tr>
		<td class="text14grey">
		
		{if $payment_history}
		<table border="0" cellpadding="2" cellspacing="1" width="100%" align="center">
		<tr><td height="20px"></td>	</tr>		
		<tr bgcolor="#0099FF" bordercolor="#FFFFFF" height="20px" style="font-size: medium; color:#FFFFFF" valign="middle">
			<th>Derzeitige Mitgliedschaft</th>
			{if $payment_history.id}<th>Seit</th>
			<th>Gültig bis</th>
			<th>Kündigung</th>{/if}
		</tr>
		<tr bgcolor="#eeeeee">
			<td align="center" style="font-size: medium">{$payment_history.type}</td>
			{if $payment_history.id}<td align="center" style="font-size: medium">{$payment_history.start_date}</td>
			<td align="center" style="font-size: medium">{$payment_history.end_date}</td>
			<td align="center">
				{if $payment_history.cancelled eq 0}
				<span style="color: red; cursor: pointer" onclick="if(confirm('Möchtest du deine Mitgliedschaft tatsächlich beenden?')) location='?action=membership&type=cancel&id={$payment_history.id}'">Mitgliedschaft beenden.</span>
				{else}
				<span style="color: red;">beendet</span>
				{/if}
			</td>{/if}
		</tr>
		<tr>
			<td height="30px"></td>
		</tr>
		</table>
		{/if}			
		
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="90%" background="">
			<tr>
				<td align="center" class="text14grey">
{if !$payment}
	{if $today eq $payment_history.start_date}
	Du hast heute bereits eine erfolgreiche Zahlung durchgeführt!
	{else}

			{if $payment_history} 
				{if $payment_history.type == "Gold"}
					<b>Du möchtest deine Gold Mitgliedschaft verlängern? <br> Deine Mitgliedschaftsdauer verlängert sich automatisch um den von dir gewählten Zeitraum.</b><br><br>		
				{elseif $payment_history.type == "Silber"}
					Du möchtest deine Silber Mitgliedschaft verlängern?  <br> Deine Mitgliedschaftsdauer verlängert sich automatisch um den von dir gewählten Zeitraum.<br><br>		
					Oder möchtest du deine Silber Mitgliedschaft zu Gold aufwerten? <br> Dann zahle nur die Differenz zwischen deinem jetzigen und dem Goldabo!<br><br>
				{elseif $payment_history.type == "Bronze"}
					<b>Bezahlung</b><br><br>
				{/if}
			{/if}
			
			
	<form id="payment_form" name="payment_form" method="POST" action="index.php?action=payportal">
	<table align="center" bgcolor="#eeeeee" bordercolor="#FFFFFF">
	<tr>
		<td height="10px"></td>
	</tr>	
	<tr>
		<td class="text12grey" align="right">Deine gew&uuml;nschte Mitgliedschaft:</td>
		<td align="left" style="padding-left: 10px">
			<select name="mitglied" onchange="changeOption(this.options[this.selectedIndex].value)">
			<option value="3">Silber</option>
			<option value="2" selected="selected">Gold</option>
			</select>
		</td>
	</tr>
	<tr><td height="15"></td><td></td></tr>
	<tr>
		<td class="text12grey" align="right">Deine gew&uuml;nschte Dauer:</td>
		{if $payment_history.type == "Silber"}
		<td align="left" style="padding-left: 10px">
			<span id="silver_level" style="display: none">
			<select name="abo_silver">
			<option value="1">3 Tage [5,00 Euro]</option>
			<option value="2" selected="selected" >1 Monatsabo [30,00 Euro]</option>
			<option value="3">3 Monatsabo [70,00 Euro]</option>
			<!-- <option value="4">Jahres Abo</option> -->
			</select>
			</span>
			<span id="gold_level" style="display: block">
			<select name="abo_gold">
			<option value="5">1 Monatsabo [nur 10,00 Euro]</option>
			<option value="6" selected="selected">3 Monatsabo [nur 20,00 Euro]</option>
			<option value="4">Jahres Abo [210,00 Euro]</option>
			</select>
			</span>
		</td>		
		{else}
		<td align="left" style="padding-left: 10px">
			<span id="silver_level" style="display: none">
			<select name="abo_silver">
			<option value="1">3 Tage [5,00 Euro]</option>
			<option value="2" selected="selected" >1 Monatsabo [30,00 Euro]</option>
			<option value="3">3 Monatsabo [70,00 Euro]</option>
			<!-- <option value="4">Jahres Abo</option> -->
			</select>
			</span>
			<span id="gold_level" style="display: block">
			<select name="abo_gold">
			<option value="2">1 Monatsabo [40,00 Euro]</option>
			<option value="3" selected="selected">3 Monatsabo [90,00 Euro]</option>
			<option value="4">Jahres Abo [210,00 Euro]</option>
			</select>
			</span>
		</td>
		{/if}
		
	</tr>
	<tr><td height="15"></td><td></td></tr>
	<tr>
		<td class="text12grey" align="right">Deine gew&uuml;nschte Bezahlungsweise:</td>
		<td align="left" style="padding-left: 10px">
			<select name="payment">
			<!--<option value="1">Kreditkarte</option>-->
			<option value="2">Paypal</option>
			<option value="3">&Uuml;berweisung</option>
			<option value="4">Elektronisches Lastschriftverfahren (ELV)</option>
			</select>
			<!--<input type="hidden" name="payment" value="1">-->
		</td>
	</tr>
	<tr><td height="15"></td><td></td></tr>
	<tr align="center"><td colspan="4">	<input type="image" src="images/senden_bt.gif" id="senden" name="senden" onclick="submit"></td>
	<tr><td height="10"></td><td></td></tr>
	</form>
	</table>
	{/if}
{else}
	{if $payment==1}
		 <b>Kreditkarte</b><br><br>
		 <table>
<form method="post" action="https://ipayment.de/merchant/45519135/processor.php">
    <input type="hidden" name="trxuser_id" value="7173">
    <input type="hidden" name="trxpassword" value="46623301">

    <input type="hidden" name="trx_paymenttyp" value="cc">

    <input type="hidden" name="silent" value="1">
    <input type="hidden" name="silent_error_url" value="http://www.lovely-singles.com/back_to_shop.php">
    <input type="hidden" name="redirect_url" value="http://www.lovely-singles.com/back_to_shop.php?paylog_id={$paylog_id}">
    <input type="hidden" name="noparams_on_redirect_url" value="1">

    <input type="hidden" name="hidden_trigger_url" value="http://www.lovely-singles.com/hidden_trigger.php">
    <input type="hidden" name="send_confirmation_email" value="1">

    <table cellpadding="0" cellspacing="0" width="450" border="1">
      <tr>
        <td>
          <table border="0">
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Name:
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="addr_name" size="34" maxlength="50" value="">
              </td>
            </tr>
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Straße:
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="addr_street" size="34" maxlength="50" value="">
              </td>
            </tr>
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  PLZ, Stadt:
                </font>
              </td>
              <td>
                <input type="text" name="addr_zip" size="6" maxlength="10" value=""> <input type="text" name="addr_city" size="26" maxlength="50" value="">
              </td>
            </tr>
            <tr>   
              <td valign="top">
                <font face="Arial,Helvetica">
                  Land:
                </font>
              </td>
              <td colspan="2">
                <select name="addr_country">
                  <option value="DE">Deutschland</option>
                  <option value="AT">Österreich</option>
                  <option value="CH">Schweiz</option>     
                </select>
              </td>
            </tr>  
            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Email Adresse:
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
                  Summe:
                </font>  
              </td>
              <td colspan="2">
                <font face="Arial,Helvetica">
                  <b>{$preis} EUR</b>
                  <input type="hidden" name="trx_amount" value="{$preis}">
                  <input type="hidden" name="trx_currency" value="EUR">
                </font>
              </td>
            </tr>
			<tr><td class="text12grey">{$payment_text}</td></tr>
            <tr>
              <td height="20" colspan="3"></td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                   Kreditkarten Nummer:
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="cc_number" size="34" maxlength="40" value="">
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                   CVV2 Code der Kreditkarte:
                </font>
              </td>
              <td colspan="2">
                <input type="text" name="cc_checkcode" size="4" maxlength="4" value=""><br>
                (3 Zeichen auf der Rückseite der Karte (Visa,
                Mastercard) oder 4 Zeichen auf der Vorderseite der Karte (American-Express)
              </td>
            </tr>

            <tr>
              <td valign="top">
                <font face="Arial,Helvetica">
                  Gültig bis:
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
                  Gültig seit:
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
                  Ausgabe Nummer:
                </font>  
              </td>
              <td colspan="2">
                <input type="text" name="cc_issuenumber" size="2" maxlength="2" value=""><br>
                (Wird nicht bei allen Kreditkarten genutzt)
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
              <td align="center">
                <input type="submit" name="ccform_submit" value="Zahlungsdaten prüfen">
              </td>
         
            </tr>
            <tr>
              <td>
              </td>
              </tr><tr>
              <td align="center">
                Der Zahlungsvorgang kann einige Sekunden in Anspruche nehmen. Bitte senden Sie das Formular nur 1 mal und haben Sie kurz Geduld.
              </td>
            </tr>
          </table>
        </td>
      </tr>


  </form>		 </table>
			<br><br>

{if $payment==1}
	Dieses Abonnement w&auml;re g&uuml;ltig bis zum: <b>{$dauer}</b><br>
{else}
	Dieses Abonnement w&auml;re g&uuml;ltig bis zum: <b>{$dauer}</b><br>
	Dein zu zahlender Betrag: <b>{$preis} Euro</b><br><br>
	<input type="submit" value="abschließen"></form>
{/if}	
	{elseif $payment==2}
	  <table>
		<tr align="center"><td class="text14grey"><b>Deine Paypal Zahlung:</b></td></tr>
		<tr align="center"><td class="text14grey" height="25"></td></tr>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_notify-synch">
		<input type="hidden" name="business" value="jg@loox-consulting.de">
		<input type="hidden" name="currency_code" value="EUR">
		<input type="hidden" name="cancel_return" value="http://www.lovely-singles.com">
		<input type="hidden" name="return" value="http://www.lovely-singles.com">
		<input type="hidden" name="item_name" value="{$aboname}">
		<input type="hidden" name="item_number" value="{$paylog_id}">		
		<input type="hidden" name="amount" value="{$preis}">
		<tr align="center"><td>	<input type="image" src="images/abschliessen_bt.gif" id="abschließen" name="abschließen" value="PDT" onclick="submit"> <td>

		<tr align="center"><td class="text14grey" height="30"></td></tr>		
		</form>
		<tr align="center"><td class="text14grey">Dieses Abonnement w&auml;re g&uuml;ltig bis zum: {$dauer}</td></tr>
		<tr align="center"><td class="text14grey" height="15"></td></tr>
		<tr align="center"><td class="text14grey">Dein zu zahlender Betrag: {$preis} Euro</td></tr>
		<tr align="center"><td class="text14grey" height="15"></td></tr>
		<tr align="center"><td class="text12grey">{$payment_text}</td></tr>
		<tr align="center"><td class="text14grey" height="15"></td></tr>		
		
	  </table>		
		</form>
	{elseif $payment==3}
	<table>
		<tr align="center"><td class="text14grey"><b>&Uuml;berweisung</b></td></tr>
		<tr align="center"><td class="text14grey" height="25"></td></tr>
		<tr align="center"><td class="text14grey">Bitte überweise den unten stehenden Betrag an:</td></tr>
		<tr align="center"><td class="text14grey" height="25"></td></tr>		
		 <table>
			<tr><td width="150"></td><td class="text14black" align="left">Kontoinhaber:</td><td class="text14grey"><b>Loox GmbH</b></td><td width="150"></td></tr>	
			<tr><td width="150"></td><td class="text14black">Kontonummer:</td><td class="text14grey"><b>17092590</b></td><td width="150"></td></tr>	
			<tr><td width="150"></td><td class="text14black">Bankleitzahl:</td><td class="text14grey"><b>21550050</b></td><td width="150"></td></tr>	
			<tr><td width="150"></td><td class="text14black">Institut:</td><td class="text14grey"><b>Flensburger Sparkasse</b></td width="150"><td></td></tr>		
			<tr><td width="150"></td><td class="text14black">Verwendungszweck:</td><td class="text14grey"><b>{$vzweck}</b></td><td width="150"></td></tr>		
			<tr><td width="150"></td><td class="text14black">Betrag:</td><td class="text14grey"><b>{$preis} Euro</b></td><td></td width="150"></tr>		
			<tr align="center"><td class="text14grey" height="15"></td></tr>
			<tr align="center"><td class="text12grey">{$payment_text}</td></tr>
			<tr align="center"><td class="text14grey" height="15"></td></tr>
	</table>
	
	{elseif $payment==4}
	<table cellpadding=0 cellspacing=0 width=450 border=0 align="center">
	<tr>
        <td class="text14grey" align="center" heigth=20>Elektronisches Lastschriftverfahren</td>
      </tr>     
      <tr>
        <td>
          <font face="arial,helvetica" color="red">
            {$error_message}
          </font>
        </td>
      </tr>
      <tr>
        <td heigth=20>&nbsp;</td>
      </tr>
      </table>
<form id="evn_validation_form" name ="evn_validation_form" action="?action=evnValidation" method="post" onsubmit="return ValidateInput(this, 'Bitte fülle alle Felder vollständig aus.')">
	<table cellpadding=0 cellspacing=0 width=450 border=1 align="center">
      <tr>
        <td>
          <table border=0>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Vorname:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="addr_vorname" size=34 maxlength=50 value="" class="require">
              </td>
            </tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Nachname:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="addr_name" size=34 maxlength=50 value="" class="require">
              </td>
            </tr>
            <tr><td class="text12grey">(Kontoinhaber)</td></tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Straße & Hausnummer:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="addr_street" size=34 maxlength=50 value="" class="require">
              </td>
            </tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Land:
                </font>
              </td>
              <td colspan=2 class="text14grey">
                <select name="addr_country">
                  <option value="DE">Deutschland</option>
                  <option value="AT">Österreich</option>
                  <option value="CH">Schweiz</option>     
                </select>
              </td>
            </tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  PLZ, Wohnort:
                </font>
              </td>
              <td>
                <input type=text name="addr_zip" size=6 maxlength=10 value="" class="require">
              </td>
              <td align=right class="text14grey">
                <input type=text name="addr_city" size=26 maxlength=50 value="" class="require">
              </td>
            </tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  E-Mail Adresse:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="addr_email" size=34 maxlength=50 value="" class="require">
              </td>
            </tr>

            <tr>
              <td height=20></td>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Betrag:
                </font>  
              </td>
              <td colspan=2 class="text14grey">
                <font face="Arial,Helvetica">
                  <b>{$preis} Euro</b>
                </font>
                  <input type="hidden" name="preis" value="{$preis}">
                  <input type="hidden" name="trx_amount" value="{$preis}00">
                  <input type="hidden" name="trx_currency" value="EUR">     
              </td>
            </tr>
			<tr><td class="text12grey">{$payment_text}</td></tr>
            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                   Bankname:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="bank_name" size=34 maxlength=40 value="">
              </td>
            </tr>

            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                   Bankleitzahl:
                </font>
              </td>
              <td colspan=2>
                <input type=text name="bank_code" size=12 maxlength=12 value="" class="require">
              </td>
            </tr>

            <tr>
              <td valign="top" class="text14grey">
                <font face="Arial,Helvetica">
                  Kontonummer:
                </font>  
              </td>
              <td colspan=2>
                <input type=text name="bank_accountnumber" size=12 maxlength=12 value="" class="require">
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
          <table align="center">
            <tr>
              <td heigth=20></td>
            </tr>
            <tr>
              <td>
                <input type="submit" name="ccform_submit" value="Absenden">
              </td>
            </tr>
          </table>
        </td>
      </tr>
   </table>
</form>   
	{/if}
{/if}
<table><tr><td heigt=40>&nbsp;</td></tr></table>
  </tr></table> 
<table align="center">
<tr><td>
{include file=membership_listing.tpl}  
</td></tr>
</table>
<table>
<tr><td height="10"></td></tr>
<tr>
<td align="center" class="text12grey">Wenn das Abo nicht gek&uuml;ndigt wird, verl&auml;ngert sich das 3 Tage Testabo in ein Monatsabo. Das 1 Monatsabo wird automatisch in ein 3 Monatsabo umgewandelt, das 3 Monatsabo wandelt sich zu einem Jahresabo. Das Jahresabo wird um ein weiteres Jahr verlängert.<br><br></td>
</tr>
</table>	
</td>

	</tr>
				<tr><td height="15"></td></tr>
			<tr>
				<td align="center"><input type="image" src="images/zurueck_bt.gif" id="back_button" name="back_button" onclick="location = '?action=payportal'; return false;" />
			</tr>
</table>
<table><tr><td heigt=40>&nbsp;</td></tr></table>