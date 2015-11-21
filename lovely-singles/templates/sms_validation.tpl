<form id="sendmessage_form" name="sendmessage_form" action="?action=sendSMS" method="post">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="26" background="images/bg_sex.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					:: Handynummer Validierung ::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
		</table>
		</td>
	</tr>
</table>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="80%">
	<tr>
		<td height="35px"></td>
	</tr>
	{if $msgAlert}
	<tr>
		<td  colspan="2" align="center" valign="middle"><font color="#FF0000">{$msgAlert}</font></td>
	</tr>
	<tr>
		<td  colspan="2" align="center" valign="middle" height="10px"></td>
	</tr>
	{/if}
	<tr>
		<td colspan="2" class="text12grey">Bitte best&auml;tige zuerst deine Handynummer, in dem Du diese in die Felder eintr&auml;gst und versendest.<br> Du erh&auml;ltst danach einen Freischaltcode auf deine Handy, den Du auf der Folgeseite eintr&auml;gst.</td>
	</tr>
	<tr>
		<td height="10px"></td>
	</tr>
	<tr>
		<td class="text12grey">Deine Nummer:</td>
		<td>
        <div style="float:left;padding-top:2px">+&nbsp;</div><input type="text" id="phone_code" name="phone_code" class="code" maxlength="4" />
        <input type="text" name="phone_number" class="boxcode" /></td>
	</tr>
	<tr>
		<td height="10px"></td>
	</tr>
		<tr>
		<td colspan="2" align="center"><input name="versenden" type="image" src="images/senden_bt.gif" value="{#SUBMIT#}" onclick="return check_mobile_number(phone_number)" ></td>
		
	</tr>
	
</table>
</form>