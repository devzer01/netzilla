{include file="email/email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; font-weight:bold; font-size:14px;">
						Hallo <strong>{$user}</strong>,
						<br /><br />
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="110" valign="top"><img src="{$smarty.const.URL_WEB}{$smarty.const.SITE}thumbs/{$picturepath}" width="100" />
							</td><td width="20">&nbsp;</td>
							<td valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="50%" valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px;  font-size:14px;"><b>Name:</b>&nbsp;&nbsp;{$user}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>Alter:</b>&nbsp;&nbsp;{$age}</td>
								</tr>
								<tr>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>Geschlecht:</b>&nbsp;&nbsp;{$gender}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>Stadt:</b>&nbsp;&nbsp;{$city}</td>
								</tr>
								<tr>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>Betreff:</b>&nbsp;&nbsp;{$subj}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>Nachricht:</b>&nbsp;&nbsp;{$mess|truncate:160:"..."}</td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
						<br />
						Möchtest du die komplette Nachricht jetzt lesen? Dann klicke bitte <a href="{$smarty.const.MOBILE_WEB}" style="color:#d20000; text-decoration:underline; font-size:14px;">[HERE]</a> um in dein Postfach zu gelangen.
						<br /><br />
						Das Team von Flirt48.net wünscht dir viel Spaß beim Lesen! 
						<br /><br />
						Mitteilung:
						<br />Du erhältst diese Nachricht, weil du dich bei Flirt48.net registriert hast.
						<br />Diese Nachricht wurde automatisch erstellt, bitte nicht beantworten.
					</td>
				</tr>
				</table>
{include file="email/email_footer.tpl"}