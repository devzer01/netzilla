{include file="email/email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; color:#ffffff; font-weight:bold; font-size:14px;">
						Du erh&auml;ltst diese Email aus folgendem Grund: Passwort vergessen <br/>
						<br /><b>Benutzername:</b>&nbsp;&nbsp;{$member.username}
						<br /><b>Passwort:</b>&nbsp;&nbsp;{$member.password} 
					</td>
				</tr>
				</table>
{include file="email/email_footer.tpl"}