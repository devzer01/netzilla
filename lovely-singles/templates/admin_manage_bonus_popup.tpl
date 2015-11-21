<table>
<tr>
	<td>Amount</td>
	<td><input name="coin" type="number" id="coin" value="10" min="10" step="10" maxlength="30" class="box" /> Coin(s)</td>
</tr>
<tr>
	<td>Email subject</td>
	<td><textarea rows="2" cols="70" id="email_subject_text">{$email_subject}</textarea></td>
</tr>
<tr>
	<td>Email body</td>
	<td><textarea rows="6" cols="70" id="email_body_text">{$email_body|replace:"<br/>":"\r\n"}</textarea></td>
</tr>
<tr>
	<td>Send SMS?</td>
	<td><input type="checkbox" name="sms" value="1" checked="checked" onclick="{literal}if(this.checked){jQuery('#smsBody').show(); jQuery('#send_via_sms').val('1');} else {jQuery('#smsBody').hide(); jQuery('#send_via_sms').val('0');}{/literal}"/></td>
</tr>
<tr id="smsBody">
	<td>SMS body</td>
	<td><textarea rows="6" cols="70" id="sms_body_text">{$sms_body|replace:"<br/>":"\r\n"}</textarea></td>
</tr>
<tr>
	<td colspan="2" align="center"><input type="button" id="add_bonus_button" value="&nbsp;Add Bonus&nbsp;" onclick="hideAddBonusButton(); submitAddBonusForm(jQuery('#coin').val(),jQuery('#email_subject_text').val(),jQuery('#email_body_text').val(),jQuery('#sms_body_text').val());"><span id="add_bonus_info"></span>
</td>
</tr>
</table>