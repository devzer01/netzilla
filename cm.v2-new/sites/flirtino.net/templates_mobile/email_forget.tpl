{include file="email_header.tpl"}
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top" style="font-family:tahoma, Helvetica, sans-serif; padding:10px; text-align:left; line-height:20px; font-weight:bold; font-size:14px;">
						{if $reason eq 'forget'}
						{#Info#}
						{else}
						{#InfoFacebook#}
						{/if}
						<br /><b>{#USERNAME#}:</b>&nbsp;&nbsp;{$username}
						<br /><b>{#PASSWORD#}:</b>&nbsp;&nbsp;{$password} 
					</td>
				</tr>
				</table>
{include file="email_footer.tpl"}