<!-- {$smarty.template} -->
<h2>{#QUESTION_TO_TEAM#}</h2>
<div class="result-box">

<div class="result-box-inside-nobg">

<form id="message_write_form" name="message_write_form" method="post" action="">
<input type="hidden" name="act" value="sendquestion" />
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr>
		<td align="center" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td align="center">
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="350">
			<tr height="28">
				<td style="padding-right:10px;"><b>{#Subject#}:</b></td>
				<td>
					{if $mode eq 'standard_message'}
					{$save.subject}
					{else}
					<input id="subject" name="subject" type="text" style="width:180px" value="{$save.subject}" class="input">
					{/if}
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding-right:10px;"><b>{#Message#}:</b></td>
				<td>
					{if $mode eq 'standard_message'}
					{$save.message|replace:"\n":"<br>"}
					{else}
					<textarea id="message" name="message" style="width:360px; height:120px">{$save.message}</textarea>
					{/if}
				</td>
			</tr>
			<tr>
				<td colspan="2" height="10px"></td>
			</tr>
			<tr>
				<td></td>
 				<td><input type="submit" id="send_button" name="send_button" onclick="return checkQuestionWriteMessage();" value="{#SEND#}" class="button">
 				<input type="button" id="back_button" name="back_button" onclick="location = '.'; return false;" value="{#BACK#}" class="button" /></td>
				<input type="hidden" id="send_button" name="send_button" value="{#SEND#}">
 				</tr>
		</table>
		</td>
	</tr>			
</table>
</form>

</div>
</div>