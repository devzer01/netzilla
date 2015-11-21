<form id="message_write_form" name="message_write_form" method="post" action="">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="50%">			
	<tr>
		<td align="center" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td>
		<table align="center" border="0" cellpadding="2" cellspacing="0" width="100%">
			<tr>
				<td valign="top"><b>To:</b></td>
				<td width="400px">
				{if $username.0 neq ""}			
					{section name="username" loop=$username}
					{if $smarty.section.username.index > 0}
					, 
					{/if}
					<input id="to" name="to[]" type="hidden" value="{$username[username]}">
					{$username[username]}					
					{/section}
				{elseif $smarty.get.username neq ""}
					{$smarty.get.username}
					<input id="to" name="to" type="hidden" value="{$smarty.get.username}">
				{else}
				<input type="text" id="to2" name="to2" value="{$save.to}" style="width:280px;" class="input"/>
				{/if}
				{if $messageid.0 neq ""}
					{section name="messageid" loop=$messageid}
					<input id="messageid" name="messageid[]" type="hidden" value="{$messageid[messageid]}">
					{/section}
				{/if}				</td>
			</tr>
			<tr>
				<td><b>Subject:</b></td>
				<td><input id="subject" name="subject" style="width:280px;" type="text" value="{$save.subject}" class="input"></td>
			</tr>
			<tr>
				<td valign="top"><b>Message:</b></td>
				<td><textarea id="message" name="message" style="width:280px;height:80px">{$save.message}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="10px"></td>
			</tr>
			<tr>
				<td></td>
				<td>
					{*<input type="submit" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="SEND">*}
					<input type="submit" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="SEND">
				</td>
			</tr>
		</table>
		</td>
	</tr>			
</table>
</form>