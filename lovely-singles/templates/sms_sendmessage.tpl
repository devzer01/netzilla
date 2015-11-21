<form id="sendmessage_form" name="sendmessage_form" action="?action=sendSMS" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
	<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />
	:: FreiSMS senden ::</td>
	<td background="images/bgr.gif" width="12" height="24"></td>
</tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
	<tr>
		<td height="35px"></td>
	</tr>
	<tr>
		<td colspan="2"><b>{$alert}</b></td>	
	</tr>
	<tr>
		<td colspan="2" class="text12grey">Du hast heute noch {$freesms} SMS zur Verf&uuml;gung</td>	
	</tr>
	<tr>
		<td height="35px"></td>
	</tr>	
	<tr>
	{if $freesms !=0 }
	<tr align="left">
		<td class="text12grey">Empf&auml;nger:</td>
		<td>
        <div style="float:left;padding-top:2px">+&nbsp;</div><input type="text" id="phone_code" name="phone_code" class="box" maxlength="4" />
        <input type="text" name="phone_number" class="boxcode" /></td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr align="left">
		<td valign="top" class="text12grey">Nachricht: </td>
		<td><textarea name="message" cols="40" rows="5" onkeypress="sendSMS_checkText()"></textarea></td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr>
		<td height="5px"></td>
	</tr>
	<tr align="left">
		<td></td>
		<td><input name="sendmessage" type="image" src="images/{$smarty.session.lang}/senden_bt.gif" value="{#SUBMIT#}" onclick="return valid_sendSms()"> </td>
	</tr>
	{/if}
</table>
<input type="hidden" name="MAX_SMS" value="{$max_Sms_length}" />
<input type="hidden" name="MIN_SMS" value="{$min_Sms_length}" />
</form>