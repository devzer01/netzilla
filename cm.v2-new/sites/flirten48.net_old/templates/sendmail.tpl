<!-- {$smarty.template} -->
<form id="message_write_form" name="message_write_form" method="post" action="">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="50%">			
	<tr>
		<td align="center" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td>
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td class="text12grey"><b>{#To#}:</b></td>
				<td>
				{if $username.0 neq ""}			
					{section name="username" loop=$username}
					{if $smarty.section.username.index > 0}
					, 
					{/if}
					<input id="to" name="to" type="hidden" style="width:180px" value="{$username[username]}">
					{$username[username]}					
					{/section}
				{elseif $smarty.get.username neq ""}
					{$smarty.get.username}
					<input id="to" name="to" type="hidden" style="width:180px" value="{$smarty.get.username}">
				{else}
				<input id="to" name="to" type="text" style="width:180px" value="{$save.to}">
				<i><b> (Mitgliedsname)</b> </i>
				{/if}
				{if $messageid.0 neq ""}
					{section name="messageid" loop=$messageid}
					<input id="messageid" name="messageid[]" type="hidden" value="{$messageid[messageid]}">
					{/section}
				{/if}
				</td>
			</tr>
			<tr>
				<td class="text12grey"><b>{#Subject#}:</b></td>
				<td><input id="subject" name="subject" type="text" style="width:180px" value="{$save.subject}"></td>
			</tr>
			<tr>
				<td valign="top" class="text12grey"><b>{#Message#}:</b></td>
				<td><textarea id="message" name="message" style="width:360px; height:120px">{$save.message}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="10px"></td>
			</tr>
			<tr>
				<td></td>
				<!--<td><input type="image" src="images/senden_bt.gif" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}"></td>-->
 				<td><input type="image" src="images/{$smarty.session.lang}/senden_bt.gif"  id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}">
 				<input type="image" src="images/{$smarty.session.lang}/zurueck_bt.gif" id="back_button" name="back_button" onclick="parent.location='?action=viewprofile&username={$smarty.get.username}'" value="{#BACK#}" /></td>
				<input type="hidden" id="send_button" name="send_button" value="{#SEND#}">
 				</tr>
		</table>
		</td>
	</tr>			
</table>
</form>