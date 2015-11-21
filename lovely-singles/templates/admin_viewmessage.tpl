<div class="result-box">
<h1>{#View_Message#}</h1>
<div class="result-box-inside-nobg">

<table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td>
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
			  <td valign="top"><b>{#From#}:</b></td>
				<td>{$message.username}</td>
			</tr>
			<tr>
			  <td valign="top"><b>{#Datetime#}:</b></td>
				<td>{$message.datetime}</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td height="30px"></td>
	</tr>
	<tr>
		<td>
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<form id="view_message_form" name="view_message_form" method="post" action="">
			<tr>
				<td>
				<input type="hidden" id="messageid" name="messageid[]" value="{$message.id}" /> 
				<input type="submit" id="delete_button" name="delete_button" value="{#Delete#}" onClick="return confirm('Are you sure to delete selected message?')" class="button"/>
				
				{if $smarty.get.type eq "inbox"}
				<input type="button" id="reply_button" name="reply_button" onClick="adminReplyMessage(this.form.id)" value="{#Reply#}" class="button" />
				<input type="button" id="delete_user_button" name="delete_user_button" onClick="if(confirm('Please confirm to delete?')) location='?action=admin_manageuser&user={$message.username}&proc=del&redirectto=admin_message'" value="Delete user" class="button" />
				<input type="button" id="block_user_button" name="block_user_button" onClick="if(confirm('Please confirm to block?')) location='?action=admin_manageuser&user={$message.username}&proc=block&redirectto=admin_message'" value="Block user" class="button" />
				{/if}
				<input type="button" id="back_button" name="back_button" onclick="parent.location='{$smarty.server.HTTP_REFERER}'" value="{#BACK#}" class="button" />
			</td>
			</tr>
			</form>
		</table>	
		</td>
	</tr>
	<tr>
		<td height="30px"></td>
	</tr>
</table>

</div>
</div>