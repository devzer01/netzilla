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
			<td><img src="http://www.lovely-singles.com/images/tmp.jpg"></td>
		</tr>
		<tr>
			<td>
				<br>
				Unsere Forderung vom {$entry.payday|date_format:"%d.%m.%Y"} - Forderungsnummer: {$entry.booking_number}<br>
				<br>
				<br>
				<b>
				{if $profile.gender eq '1'}
				Sehr geehrter Herr
				{else}
				Sehr geehrte Frau
				{/if}
				{$entry.real_name}...</b><br>
				<br>
				Sie haben sich auf der Internetseite <a href="{$entry.site_url}">{$entry.site}</a> angemeldet, Leistungen in Anspruch genommen und damit rechtsverbindlich einen Vertrag mit der TMP Callcenter Service-Nord GmbH geschlossen. Entsprechende Rechnungen und Belehrungen haben Sie bereits erhalten.<br>
				<br>
				Dennoch konnten wir bislang noch keinen Zahlungseingang bei uns feststellen.<br>
				<br>
				Wir fordern Sie hiermit letztmalig auf, die Zahlung des vollständigen Rechnungsbetrages in Höhe von {$entry.sum_paid} zuzüglich 7,50 EUR Mahngebühr und einer Schreibgebühr in Höhe von 7,50 EUR insgesamt demnach<br>
				<br>
			</td>
		</tr>
		<tr>
			<td align="center">
				{$entry.total} EUR,<br>
				<br>
			</td>
		</tr>
		<tr>
			<td align="left">
				bis spätestens zum<br>
				<br>
			</td>
		</tr>
		<tr>
			<td align="center">
				{$entry.until}<br>
				<br>
			</td>
		</tr>
		<tr>
			<td align="left">
				vorzunehmen.<br>
				<br>
				Hierbei bitten wir, unbedingt die Forderungsnummer {$entry.booking_number} anzugeben, damit die Zahlung korrekt zugeordnet werden kann.<br>
				<br>
				Wir weisen Sie bereits an dieser Stelle darauf hin, dass nach erfolglosem Verstreichen der Zahlungsfrist, die Angelegenheit unverzüglich einer Rechtsanwaltskanzlei übergeben werden wird. Unsere Forderung würde dann erforderlichenfalls auch gerichtlich gegen Sie geltend gemacht werden. Die dadurch zusätzlich entstehenden, nicht unerheblichen Kosten würden dann auch zu Ihren Lasten gehen.<br>
				<br>
				Sollten Sie zwischenzeitlich den offenen Rechnungsbetrag bereits zum Ausgleich gebracht haben, ist dieses Schreiben als gegenstandslos zu betrachten.<br>
				<br>
				<br>
				<br>
				Mit freundlichen Grüßen<br>
				<br>
				Martin Müller (Forderungsmanagement)				
			</td>
		</tr>
		<tr><td height="10"></td></tr>
		<tr>
			<td><img src="http://www.lovely-singles.com/images/tmp2.jpg"></td>
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