<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td><font style="font-weight:bold;font-size:20px">{$data.subject|wordwrap:45:"<br>":true}</font></td>
	</tr>
	<tr>
		<td height="10px"></td>
	</tr>
	<tr>
		<td width="100%"><font style="font-size:12px">{$data.message}</font></td>
	</tr>
	<tr>
		<td height="20px"></td>
	</tr>
	<tr>
		<td align="center"><input type="button" id="back_button" name="back_button" onclick="parent.location='{$smarty.server.HTTP_REFERER}'" value="{#BACK#}" class="button" /></td>
	</tr>
	<tr>
		<td height="30px"></td>
	</tr>
</table>