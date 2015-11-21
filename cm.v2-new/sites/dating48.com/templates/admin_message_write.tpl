<!-- {$smarty.template} -->
<form id="message_write_form" name="message_write_form" method="post" action="">
<div style="text-align:center;">
<table align="left" border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr>
		<td align="left" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td align="left">
		<table align="left" border="0" cellpadding="4" cellspacing="0" width="500">
			<tr height="28">
				<td valign="top"><b>{#To#}:</b></td>
				<td width="250">
				{if $username.0 neq ""}			
					{section name="username" loop=$username}
					{if $smarty.section.username.index > 0}
					, 
					{/if}
					<input id="to" name="to[]" type="hidden" value="{$username[username]}" class="input-style01">
					{$username[username]}					
					{/section}
				{elseif $smarty.get.username neq ""}
					{$smarty.get.username}
					<input id="to" name="to" type="hidden" value="{$smarty.get.username}" class="input-style01">
				{else}
				<input id="to" name="to" type="text" value="{$save.to}" class="input-style01">
				{/if}
				{if $messageid.0 neq ""}
					{section name="messageid" loop=$messageid}
					<input id="messageid" name="messageid[]" type="hidden" value="{$messageid[messageid]}" class="input-style01">
					{/section}
				{/if}
				</td>
			</tr>
			<tr height="28">
				<td><b>{#Subject#}:</b></td>
				<td><input id="subject" name="subject" type="text" value="{$save.subject}" class="input-style01"></td>
			</tr>
			<tr height="28">
				<td valign="top"><b>{#Message#}:</b></td>
				<td><textarea id="message" name="message" class="input-style01" style="height:250px;">{$save.message}</textarea></td>
			</tr>
			<tr height="28">
				<td></td>
				<td><input type="submit" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}" class="button"> <input type="button" id="back_button" name="back_button" onclick="history.go(-1);" value="Back" class="button"></td>
			</tr>
		</table>
		</td>
	</tr>			
</table>
</div>
</form>