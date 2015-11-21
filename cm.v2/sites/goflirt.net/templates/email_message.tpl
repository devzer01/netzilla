{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; font-weight:bold; font-size:14px;">
						{#email_message_Text2#} <strong>{$user}</strong> {#email_message_Text3#}
						<br /><br />
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="110" valign="top"><img src="{$url_web}{$smarty.const.SITE}thumbs/{$picturepath}" width="100" />
							</td><td width="20">&nbsp;</td>
							<td valign="top">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="50%" valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px;  font-size:14px;"><b>{#Name#}:</b>&nbsp;&nbsp;{$user}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>{#Age#}:</b>&nbsp;&nbsp;{$age}</td>
								</tr>
								<tr>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>{#Gender#}:</b>&nbsp;&nbsp;{$gender}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>{#City#}:</b>&nbsp;&nbsp;{$city}</td>
								</tr>
								<tr>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>{#Subject#}:</b>&nbsp;&nbsp;{$subj}</td>
									<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; text-align:left; line-height:20px; font-size:14px;"><b>{#Message#}:</b>&nbsp;&nbsp;{$mess}</td>
								</tr>
								</table>
							</td>
						</tr>
						</table>
						<br />
						{#email_message_Text4#} <a href="{$url_web}" style="color:#d20000; text-decoration:underline; font-size:14px;">{#email_message_Text5#}</a> {#email_message_Text6#}
						<br /><br />
						{#email_message_Text7#}
						<br /><br />
						{#email_message_Text8#}
						<br />{#email_message_Text9#}
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}