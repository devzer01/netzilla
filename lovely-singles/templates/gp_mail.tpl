<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<table cellpadding='0' cellspacing='0' width="610" style="border: solid 1px #777777; padding: 10px">
<tr>
	<td>
		<table cellpadding='0' cellspacing='0' width="610" style="font-family: verdana; font-size: 12px">
		<tr>
			<td><img src="{$url_web}/images/tmp.jpg"></td>
		</tr>
		<tr>
			<td>
				<br>
				Ihre Zahlung bei {$domain} vom {$payday|date_format:"%d.%m.%Y"} - Buchungsnummer: {$marktId}-{$referenz}-{$pay_id}<br>
				<br>
				<br>
				<b>
				{if $gender eq '1'}
				Sehr geehrter Herr
				{else}
				Sehr geehrte Frau
				{/if}
				{$real_name}...</b><br>
				<br>
				Ihre Zahlung wurde erfolgreich abgewickelt!<br>
				<br>
            Bitte speichern Sie diese E-Mail als Referenz bis Sie die bestellten Waren oder Dienstleistungen erhalten haben.<br>
            <br>
         </td>
       </tr>
       <tr>
          <td>
            <table>
               <tr><td>Händlername</td><td>TMP Callcenter Service-Nord GmbH</td></tr>
               <tr><td>Datum</td><td>{$payday}</td></tr>
               <tr><td>Betrag</td><td>{$sum_paid} EUR</td></tr>
               <tr><td>Buchungsnummer</td><td>{$marktId}-{$referenz}-{$pay_id}</td></tr>
            </table>
          </td>
       </tr>
       <tr>
          <td>
            <br>
            Bei Problemen mit dieser Zahlung schreiben Sie uns bitte eine eMail an info@{$domain}.

				<br>
				<br>
				Mit freundlichen Grüßen<br>
				<br>
				Martin Müller (Forderungsmanagement)				
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		<tr>
			<td><img src="{$url_web}/images/tmp2.jpg"></td>
		</tr>
		<tr>
		<td align="center">Bankverbindung:     Volksbank Bautzen     BLZ: 855 900 00     Kto.: 303185119</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</body>
</html>