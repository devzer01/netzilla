{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px;  font-weight:bold; font-size:14px;">
						Herzlich Willkommen auf Goflirt.net, deinem Flirtportal! <br/><br/> 
						Bitte bestätige noch schnell deine Emailadresse, damit du gleich los flirten kannst! <br/>
						Drücke dafür bitte den nachfolgenden Link:<br /><br />
						<a href="{$url_web}?action=activate&username={$username}&password={$password}&code={$code}" style="color:#d20000; text-decoration:underline; font-size:14px;">Bitte hier klicken !</a><br />
						<br />Weiterhin senden wir dir hier nochmal deinen Login-Namen sowie dein Passwort. Bitte speicher diese Email an einem sicheren Ort oder schreibe dir die Daten auf!<br />
						<br /><b>{#USERNAME#}:</b>&nbsp;&nbsp;{$username}
						<br /><b>{#PASSWORD#}:</b>&nbsp;&nbsp;{$password}<br />
						<br />Viel Spaß & Flirterfolg auf Goflirt.net!<br /> 
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}