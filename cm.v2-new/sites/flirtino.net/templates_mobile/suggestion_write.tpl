<!-- {$smarty.template} -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="26" background="images/bg_sex.jpg">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="12" height="24"><img src="images/bgl.gif" width="12" height="24" /></td>
					<td align="center" background="images/bgcenter.gif" class="text14whitebold" ><img src="images/xx.gif" />
					::{#Feedback#}::</td>
					<td background="images/bgr.gif" width="12" height="24"></td>
				 </tr>
		</table>
		</td>
	</tr>
</table>
{if $smarty.get.type eq "complete"}
	{include file="message_complete.tpl"}
{else}
<form id="feedback_form" name="feedback_form" method="post" action="">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="50%">			
	<tr>
		<td align="center" height="30px" valign="middle"><font color="#FF0000"><b>{$text}</b></font></td>
	</tr>
	<tr>			
		<td>
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="100%">
			<tr>
				<td colspan="2"><input id="to" name="to" type="hidden" value="admin"></td>
			</tr>
			<tr>
				<td><b>{#Subject#}:</b></td>
				<td><input id="subject" name="subject" type="text" style="width:180px" value="{$save.subject}"></td>
			</tr>
			<tr>
				<td valign="top"><b>{#Message#}:</b></td>
				<td><textarea id="message" name="message" style="width:180px">{$save.message}</textarea></td>
			</tr>
			<tr>
				<td colspan="2" height="10px"></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}"></td>
			</tr>
		</table>
		</td>
	</tr>			
</table>
</form>
{/if}