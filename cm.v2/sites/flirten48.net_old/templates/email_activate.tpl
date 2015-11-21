{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px;  font-weight:bold; font-size:14px;">
						{#activate_message_title#}<br /><br />
						<a href="{$url_web}?action=activate&username={$username}&password={$password}&code={$code}" style="color:#d20000; text-decoration:underline; font-size:14px;">{#Activate#}</a><br />
						<br />{#activate_message_title2#}<br />
						<br /><b>{#USERNAME#}:</b>&nbsp;&nbsp;{$username}
						<br /><b>{#PASSWORD#}:</b>&nbsp;&nbsp;{$password}<br />
						<br />{#activate_message_title3#} 
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}