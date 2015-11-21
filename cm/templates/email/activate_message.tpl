{include file="email/email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px;  font-weight:bold; font-size:14px;">
						Herzlich Willkommen auf Flirt48.net, deinem Flirtportal! <br/><br/> 
						Bitte bestätige noch schnell deine Emailadresse, damit du gleich los flirten kannst! <br/>
						Drücke dafür bitte den nachfolgenden Link:<br /><br />
						<a href="{$smarty.const.MOBILE_WEB}/verify/activate/{$username}/{$password}/{$code}" style="color:#d20000; text-decoration:underline; font-size:14px;">Bitte hier klicken !</a><br />
						<br/>
						Oder <br/><br/>
						Falls der Link nicht funktioniert, gebe bitte diesen Verifizierungs-Code ein: <br/> <br/>
						<strong>{$code}</strong>
						<br/><br/>
						<br />Weiterhin senden wir dir hier nochmal deinen Login-Namen sowie dein Passwort. Bitte speicher diese Email an einem sicheren Ort oder schreibe dir die Daten auf!<br />
						<br /><b>Benutzername:</b>&nbsp;&nbsp;{$username}
						<br /><b>Passwort:</b>&nbsp;&nbsp;{$password}<br />
						<br />Viel Spaß & Flirterfolg auf Flirt48.net!<br /> 
						
						<br/><br/>
						
						Wurdest du bei der Anmeldung unterbrochen? <br/>

						Falls du bei der Anmeldung unterbrochen wurdest und noch nicht verifiziert bist, dann gehe bitte wie folgt vor: <br/>

						1) Logge dich bitte mit deinem Username und Passwort ein. <br/>

						2) Gib bitte deinen Verifizierungs-Code ein: <strong>{$code}</strong> <br/>
					</td>
				</tr>
				</table>
{include file="email/email_footer.tpl"}