<div class="result-box">
	<h1>{#PASSWORD#} {#FORGOTTEN#}</h1>
	<div class="result-box-inside">
    
    <div class="forgot-password-banner">
<span class="forgot-password-text">{#Banner_Password#}</span>
</div>
		<div align="center"><b style="color: orange">{$text}</b></div><br>
				<form id="forget_form" name="forget_form" method="post" action="">
				<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td align="right" width="250px">{#Your#} {#Email#}:</td>
					<td width="10"></td>
					<td align="left">
                        <input id="f_email" name="f_email" type="text" style="width:200px;" class="input" onkeypress="return isValidCharacterPattern(event,this.value,2)" />
					</td>
				</tr>
				<tr>
					<td colspan="3" height="10px"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td>
						<table width="99" cellspacing="0" cellpadding="0" border="0">
						<tr>
							<td align="center">
                            	<input type="button" class="button" name="b_forgetpwd" onclick="forms['forget_form'].submit()" value="{#SUBMIT#}" />
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				</form>
	</div>
    <br class="clear" />
</div>