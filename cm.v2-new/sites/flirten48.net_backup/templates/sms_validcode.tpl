<!-- {$smarty.template} -->
<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="center" class="txtbold">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
			<td align="center" background="images/bgcenter.gif" class="text12whitebold" ><img src="images/xx.gif" />:: {#Valid_Code#} ::</td>
			<td background="images/bgr.gif" width="12" height="24"></td>
		</tr>
		<tr><td height="10"></td><tr>
		</table>
		
	</td>
</tr>
<tr>
	<td>
		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
		<form id="sendmessage_form" name="sendmessage_form" action="?action=validCode" method="post">
		{if $msgAlert}
		<tr>
			<td  colspan="2" align="center" valign="middle"><font color="#FF0000">{$msgAlert}</font></td>
		</tr>
		<tr>
			<td  colspan="2" align="center" valign="middle" height="10px"></td>
		</tr>
		{/if}
		<tr>
			<td width="50%">
				<fieldset>
				<legend>Dein Freischaltcode</legend>
					<br><input type="text" id="field_code" name="field_code" class="input" style="width:100px" /><br><br>
					<input type="hidden" name="mobnr" value="{$mobnr}">
					<input type="image" id="sendmessage" name="sendmessage" src="images/senden_bt.gif" value="{#SUBMIT#}" />
				</fieldset>
			</td>
			<td width="50%">
				<fieldset>
				<legend>Freischaltcode erneut senden</legend>                    
                    <div style="float:left;padding-top:2px">+&nbsp;</div><input type="text" id="phone_code" name="phone_code" value="{$save.phone_code}" class="code" maxlength="4" />
					<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="boxcode" /><br><br>
					<input type="image" id="sendmessage" name="sendmessage" src="images/senden_bt.gif" value="{#SUBMIT#}" />
				</fieldset>
			</td>
		</tr>
		</form>
		</table>
	</td>
</tr>
</table>