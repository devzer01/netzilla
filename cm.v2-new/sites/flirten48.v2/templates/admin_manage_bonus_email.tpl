{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; color:#ffffff; font-weight:bold; font-size:14px;">
					{$intro}<br /><br />
					{$mess}<br /><br />
					{$footer1}
					<br />{$footer2}
					<br />{$footer3}
					</td>
					<td width="95" align="right" valign="bottom"><img src="{$url_web}images/mail-right.jpg" width="85" height="120" /></td>
				</tr>
				</table>
{include file="email_footer.tpl"}