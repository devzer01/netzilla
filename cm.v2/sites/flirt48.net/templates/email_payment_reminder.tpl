{include file="email_header.tpl"}
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px;  font-weight:bold; font-size:14px;">
		{#email_payment_reminder_subject#}<br/><br/>
		{#email_payment_reminder_text1#}<br /><br />
		{#email_payment_reminder_text2#}
		<a href="{$url_web}?action=chat&username=SUPPORT" style="color:#d20000; text-decoration:underline; font-size:14px;">Support!</a><br /><br/>
		{#email_payment_reminder_text3#}<br /><br />
		{#email_payment_reminder_text4#}<br /><br />
		{#email_payment_reminder_text5#}<br /><br />
		{#email_payment_reminder_text6#}<br />
		{#email_payment_reminder_text7#}<br /><br />
		{#email_payment_reminder_text8#}<br />
		{#email_payment_reminder_text9#}<br />
	</td>
</tr>
</table>
{include file="email_footer.tpl"}