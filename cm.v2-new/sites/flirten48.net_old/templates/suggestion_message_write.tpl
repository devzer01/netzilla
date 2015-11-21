<!-- {$smarty.template} -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<form id="feedback_form" name="feedback_form" method="post" action="">
	<tr>
		<td class="text12grey"><b>{#Subject#}:</b></td>
		<td>
			<input id="to" name="to" type="hidden" value="admin">
			<input id="subject" name="subject" type="text" style="width:360px" value="{$save.subject}" class="input">
		</td>
	</tr>
	<tr><td height="5"></td></tr>
	<tr>
		<td valign="top" class="text12grey"><b>{#Message#}:</b></td>
		<td><textarea id="message" name="message" style="width:360px; height:120px"">{$save.message}</textarea></td>
	</tr>
	<tr>
		<td colspan="2" height="10px"></td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" id="send_button" name="send_button" class="button" onclick="return checkWriteMessage();" value="{#SEND#}"></td>
	</tr>
    </form>
</table>
