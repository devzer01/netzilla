<div class="listbox">
	<h1>{#Bonus_step2_title#}</h1>
	<div class="listboxin">
		<form id="bonusverify" name="bonusverify" method="post" action="?action=bonusverify">
			<div align="center"><b style="color: red">{$text}</b></div><br>
			<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>
				<td colspan="3" align="center">{#Bonus_verify_Txt#}</td>
			</tr>
			<tr>
				<td colspan="3" height="10px"></td>
			</tr>
			<tr>
				<td align="right" width="180px">{#Fill_Verify#}:</td>
				<td width="10"></td>
				<td align="left"><input id="bonus_ver_code" name="bonus_ver_code" type="text" style="width:200px;" class="input" /></td>
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
							<input type="hidden" name="act" value="bonusverify" />
							<input type="button" class="button" name="b_bonusverify" onclick="document.getElementById('bonusverify').submit(); return false;" value="{#SUBMIT#}" />
							<input type="hidden" name="submit_hidden" value="1" />
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" height="10px"></td>
			</tr>
			</table>
		</form>

	</div>
</div>