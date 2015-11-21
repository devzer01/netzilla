{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px;  font-weight:bold; font-size:14px;">
	Dein Freund von Google hat dich zu der Online-Community eingeladen {$url_web}. 
	Lerne jetzt nette Leute auch aus deiner Umgebung kennen und treffe {$customer_name} auch bei uns.<br />
	Am Besten du folgst gleich jetzt der Einladung von {$customer_name}, indem du einfach auf den unten stehenden Link klickst. 
			<br />
	<a href="{$url_web}?action=register&token={$token}" style="color:#d20000; text-decoration:underline; font-size:14px;">{#Register#}</a><br />
	Wir feuen uns, auch dich bald bei uns begrüssen zu dürfen.<br/>
Dein Team von {$url_web}
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}