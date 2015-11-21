{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center">
						<br/>
						<img src="{$url_web}{$smarty.const.SITE}thumbs/{$pic}" width="200"/>
						<br/>
					</td>
				</tr>
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; font-weight:bold; font-size:14px;">
						Leider wurde bei der Prüfung dein Bild abgelehnt. Dies kann verschieden Gründe haben. Entweder entspricht es nicht unseren Richtlinien oder das Bildformat war falsch ( z.B. Seitenverkehrt )<br/>
						Hochgeladene Bilder dürfen nur dich zeigen, Bilder von anderen Personen, von Personen unter 18 Jahren oder mit jedem anderen Inhalt werden nicht zugelassen.
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}