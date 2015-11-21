<!-- {$smarty.template} -->
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td height="10px"></td>
</tr>
<tr>
	<td>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td align="center" class="text14red"><b>{$text}</b><br><br> </td>
			</tr>

			{if !$payment}
			<tr>
				<td align="center" class="text14grey">		
		
				<b>Mitglied werden:</b><br><br>
				<form id="payment_form" name="payment_form" method="POST" action="index.php?action=payportal">
				<table align="center">
					<tr><td class="text12grey">Deine gew&uuml;nschte Mitgliedschaft:</td><td><select name="mitglied"><option value="1">Silber</option><option value="2" selected="selected">Gold</option></select></td></tr>
					<tr><td height="15"></td><td></td></tr>
					<tr><td class="text12grey">Deine gew&uuml;nschte Dauer:</td><td><select name="abo">
					<option value="1">3 Tage</option>
					<option value="2" selected="selected">1 Monatsabo</option>
					<option value="3">3 Monatsabo</option>
					<option value="4">Jahres Abo</option>
					</select></td></tr>
					<tr><td height="15"></td><td></td></tr>
					
					<tr><td class="text12grey">Deine gew&uuml;nschte Bezahlungsweise:</td><td><select name="payment">
					<!--<option value="1">Kreditkarte</option>-->
					<option value="2">Paypal</option>
					<option value="3">&Uuml;berweisung</option>
					</select></td></tr>
					<!--<input type="hidden" name="payment" value="1">-->
				</table> 
			<br><br>
			<input type="image" src="images/senden_bt.gif" id="senden" name="senden" onclick="submit">
			<br><br><br></form>		
		</td></tr><tr>
		
		
    			<td align="center" class="text14grey">	
				<b>Mitgliedschafts ?bersicht</b><br><br>

				<table border="1" cellpadding="5">				
					<tr>
						<td width="33%" class="text12grey" align="center" >Bronze-Mitglied (Standard)</td>
						<td width="33%" class="text12grey" align="center">Silber-Mitglied</td>
						<td class="text12grey" align="center" width="34%">Gold-Mitglied</td>
					</tr>
					<tr>
						<td align="center">
							<img src="images/bronze.gif" border="0" height="30" width="30">
						</td>
						<td align="center">
							<img src="images/silber.gif" border="0" height="30" width="30">
							<img src="images/silber.gif" border="0" height="30" width="30">
						</td>
						<td align="center">
							<img src="images/gold.gif" border="0" height="30" width="30">
							<img src="images/gold.gif" border="0" height="30" width="30">
							<img src="images/gold.gif" border="0" height="30" width="30">
						</td>
					</tr>
					<tr><td class="text12grey">
					Nachrichten lesen<br><br> 
					Standardantworten verschicken<br><br>
					Ohne Abo den ersten Kontakt kn&uuml;pfen<br><br>
					
					Kontaktanzeigen aufgeben<br>
					Eine oder mehrere Anzeigen aufgeben und Chancen erh&ouml;hen<br><br>
					
					Bilder einstellen<br>
					Profil interessanter machen und Flirtchancen steigern! Lade ein pers?nliches Profilbild hoch
					</td><td class="text12grey">
					Nachrichten lesen<br><br>
					
					Kontaktanzeige aufgeben<br><br>
					
					Standardantwort<br>
					Unbegrenzte Anzahl an Standardantworten an andere User versenden und dich somit kurz melden<br><br>
					
					Auf Nachrichten antworten<br>
					Unbegrenzt Nachrichten schreiben und auf Nachrichten antworten<img src="images/silber.gif" height="15" width="15"><img src="images/silber.gif" height="15" width="15"><br><br>
					
					Goldschnuppertag<br>
					Den ersten Tag die Goldvorteile exklusiv nutzen <img src="images/silber.gif" height="15" width="15"><img src="images/silber.gif" height="15" width="15"><br><br>
					</td><td class="text12grey">	
					Nachrichten lesen<br><br>
					
					Kontaktanzeige aufgeben<br><br>
					
					Standardantwort<br><br>
					
					Auf Nachrichten antworten<br><br>
					
					Kummerkasten <br> Sabine ist f?r dich da<img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"> <br><br>
					
					E-Cards<br>
					Versende unbegrenzt E-Cards an Freunde<img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"> <br> <br>
					
					Fotoalbum<br>
					Lade zus?tzliche Bilder in dein Profil <img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"> <br> <br>
					
					Live Video Cams <img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15"><img src="images/gold.gif" height="15" width="15">
					</td></tr>
				</table> 
		{else}
			{if $payment==1}
		 	<b>Kreditkarte</b><br><br>
		 	<table>
				<tr><td>Name des Karteninhabers:</td><td><input type="text" name="addr_name"></td></tr>
				<tr><td>Stra&szlig;e des Karteninhabers:</td><td><input type="text" name="addr_street"></td></tr>
				<tr><td>PLZ des Karteninhabers:</td><td><input type="text" name="addr_zip"></td></tr>
				<tr><td>Ort des Karteninhabers:</td><td>
					<select name="addr_country">
						<option value="DE">Deutschland</option>
						<option value="CH">Schweiz</option>
						<option value="AT">&Ouml;sterreich</option>
					</select>
					</td></tr>
				<tr><td height="20"></td><td></td></tr>
				<tr><td>Nummer der Kreditkarte:</td><td><input type="text" name="cc_number" size="19" maxlength="19"></td></tr>
				<tr><td>Kartenpr&uuml;fnummer der Kreditkarte:</td><td><input type="text" name="cc_checkcode" size="4" maxlength="4"></td></tr>
				<tr><td>G&uuml;ldigkeitsdatum:</td><td><input type="text" name="cc_expdat_year" size="2" maxlength="2"></td></tr>		
		 	</table>
			<br><br>
			Dieses Abonnement w&auml;re g&uuml;ltig bis zum: <b>{$dauer}</b><br>
			Dein zu zahlender Betrag: <b>{$preis} Euro</b><br><br>
			<input type="submit" value="abschlie?en"></form>
			{elseif $payment==2}
			<tr align="center"><td class="text14grey"><b>Paypal</b></td></tr>
			<tr align="center"><td class="text14grey" height="25"></td></tr>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="jg@loox-consulting.de">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="cancel_return" value="http://www.abccorp.de/korb/">
			<input type="hidden" name="return" value="http://www.lovely-singles.com">
			<input type="hidden" name="item_name" value="{$aboname}">
			<input type="hidden" name="amount" value="{$preis}">
			<tr align="center"><td>	<input type="image" src="images/abschliessen_bt.gif" id="abschlie?en" name="abschlie?en" onclick="submit"> <td>
	
			<tr align="center"><td class="text14grey" height="50"></td></tr>		
			</form>
			<tr align="center"><td class="text12black">Dieses Abonnement w&auml;re g&uuml;ltig bis zum: {$dauer}</td></tr>
			<tr align="center"><td class="text14grey" height="25"></td></tr>
			<tr align="center"><td class="text12black">Dein zu zahlender Betrag: {$preis} Euro</td></tr>
			<tr align="center"><td class="text14grey" height="25"></td></tr>		
			</form>
			{elseif $payment==3}
			<tr align="center"><td class="text14grey"><b>&Uuml;berweisung</b></td></tr>
			<tr align="center"><td class="text14grey" height="25"></td></tr>
			<tr align="center"><td class="text12grey">Bitte ?berweise den unten stehenden Betrag an:</td></tr>
			<tr align="center"><td class="text14grey" height="25"></td></tr>		
		 <table>
			<tr><td width="150"></td><td class="text14black" align="left">Kontoinhaber:</td><td class="text14grey"><b>TMP Callcenter Service-Nord GmbH</b></td><td width="150"></td></tr>
			<tr><td width="150"></td><td class="text14black">Kontonummer:</td><td class="text14grey"><b>303185119</b></td><td width="150"></td></tr>
			<tr><td width="150"></td><td class="text14black">Bankleitzahl:</td><td class="text14grey"><b>85590000</b></td><td width="150"></td></tr>
			<tr><td width="150"></td><td class="text14black">Institut:</td><td class="text14grey"><b>Volksbank Bautzen</b></td width="150"><td></td></tr>
			<tr><td width="150"></td><td class="text14black">Verwendungszweck:</td><td class="text14grey"><b>{$vzweck}</b></td><td width="150"></td></tr>
			<tr><td width="150"></td><td class="text14black">Betrag:</td><td class="text14grey"><b>{$preis} Euro</b></td><td></td width="150"></tr>
		 </table>	 
		{/if}
			<tr><td height="50"></td></tr>
			<tr>
				<td align="center"><input type="image" src="images/zurueck_bt.gif" id="back_button" name="back_button" onclick="location = '?action=membership'; return false;" />
			</tr>
		{/if}
			</td>
			</tr>
		</table>
		</td>
	</tr>
				<tr><td height="20"></td></tr>	
</table>