<div class="result-box">
<h1>{#MANAGE_SUGGESTION_BOX#}</h1>
<div class="result-box-inside-nobg">
<table border="0" celpadding="5" cellspacing="0" width="60%">
<form id="admin_suggestionbox_write_form" name="admin_suggestionbox_write_form" method="post" action="">
<tr>
<td>Subject: </td>
<td><input type="text" id="subject" name="subject" style="width:300px" value="{$data.subject}" class="input"/></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
<td valign="top">Message: </td>
<td><textarea id="message" name="message" style="height:150px;width:300px">{$data.message}</textarea></td>
</tr>
<tr><td height="5"></td></tr>
<tr>
<td></td>
<td>
<input type="submit" class="button" id="submit_button" name="submit_button" value="Save" onclick="return checkWriteSuggestion()" />
</td>				
</tr>
</form>
</table>

</div>
</div>