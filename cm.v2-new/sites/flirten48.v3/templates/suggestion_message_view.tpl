<!-- {$smarty.template} -->
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td align="left">
		<table border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
			  <td valign="top"><b>{#Subject#}:</b></td>
				<td>{$message.subject|wordwrap:80:"<br />":true}</td>
			</tr>
			<tr>
			  <td valign="top"><b>{#Message#}:</b></td>
				<td>{$message.message|wordwrap:80:"<br />":true}</td>
			</tr>
			<tr>
			  <td valign="top"><b>{#Datetime#}:</b></td>
				<td>{$message.datetime}</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<form id="view_message_form" name="view_message_form" method="post" action="">
			<tr>
				<td><input type="button" name="back_button" onclick="location = '?action=suggestion_box&do=sugg4&type=outbox'; return false;" value="{#BACK#}" class="button"/></td>
				<td align="right"> 
				<input type="hidden" id="messageid" name="messageid[]" value="{$message.id}" />
				<input id="delete_button" name="delete_button" type="button" value="{#Delete#}" onClick="return confirm('Are you sure to delete selected message?')" class="button"> 
				
				</td>
			</tr>
			</form>
		</table>	
		</td>
	</tr>
</table>