<div class="result-box">
<h1>SUGGESTION BOX</h1>
<div class="result-box-inside-nobg">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
	<td>
    
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align=""><font style="font-weight:bold;font-size:20px">{$data.subject|wordwrap:50:"<br>":true}</font></td>
	</tr>
	<tr>
		<td height="10px"></td>
	</tr>
	<tr>
		<td width="100%"><font style="font-size:12px">{$data.message}</font></td>
	</tr>
	</table><br />

	</td>
	</tr>
	<tr>
		<td align="center">
		<input id="back_button" name="back_button" type="button" class="button" onclick="parent.location='{$smarty.server.HTTP_REFERER}'" value="Back">
		</td>
	</tr>
</table>
</div>
</div>