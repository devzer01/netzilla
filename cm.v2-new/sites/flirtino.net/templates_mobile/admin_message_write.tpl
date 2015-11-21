<!-- {$smarty.template} -->
<form id="message_write_form" name="message_write_form" method="post" action="">
<div style="text-align:center;">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr>
		<td align="center" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td align="center">
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="500">
			<tr height="28">
				<td valign="top"><b>{#To#}:</b></td>
				<td width="250">
				{if $username.0 neq ""}			
					{section name="username" loop=$username}
					{if $smarty.section.username.index > 0}
					, 
					{/if}
					<input id="to" name="to[]" type="hidden" style="width:500px" value="{$username[username]}">
					{$username[username]}					
					{/section}
				{elseif $smarty.get.username neq ""}
					{$smarty.get.username}
					<input id="to" name="to" type="hidden" style="width:500px" value="{$smarty.get.username}">
				{else}
				<input id="to" name="to" type="text" style="width:500px" value="{$save.to}" class="input">
				{/if}
				{if $messageid.0 neq ""}
					{section name="messageid" loop=$messageid}
					<input id="messageid" name="messageid[]" type="hidden" value="{$messageid[messageid]}">
					{/section}
				{/if}
				</td>
			</tr>
			<tr height="28">
				<td><b>{#Subject#}:</b></td>
				<td><input id="subject" name="subject" type="text" style="width:500px" value="{$save.subject}" class="input"></td>
			</tr>
			<tr height="28">
				<td valign="top"><b>{#Message#}:</b></td>
				<td><textarea id="message" name="message" style="width:500px; height:200px;">{$save.message}</textarea></td>
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