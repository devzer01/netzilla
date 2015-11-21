<!--{if $save.total}
{literal}
<script language="javascript">

function change_message(id){
	var total = {/literal}{$save.total}{literal};
	for(var i=1; i<=total; i++)
	{
		if(i == id)
			$('message_div'+i).style.display = 'block';
		else
			$('message_div'+i).style.display = 'none';
	}
}

/*function switch_subject(obj)
{
	if(obj.checked)
	{
		$('tr_subject').style.display='none';
	}
	else
	{
		$('tr_subject').style.display='block';
	}
}*/

</script>

{/literal}
{/if}-->
{literal}
<script language="javascript">
function switch_subject(obj)
{
	if(obj.checked)
	{
		$('tr_subject').style.display='none';
		$('sendemail').hide();
		$('sendsms').show();
		$('coin_charge_msg').hide();
		$('coin_charge_sms').show();
	}
	else
	{
		$('tr_subject').style.display='';
		$('sendsms').hide();
		$('sendemail').show();
		$('coin_charge_sms').hide();
		$('coin_charge_msg').show();
	}
}
</script>
{/literal}
{literal}
<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
</script>
{/literal}
<form id="message_write_form" name="message_write_form" method="post">
<input type="hidden" name="act" value="writemsg" />
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr>
		<td align="center" height="30px" valign="middle">
			<font style="color: orange;"><b>{$text}</b><!--<br/><span id="coin_charge_msg">{$coin_charge_msg}--><br/>{$coin_charge_sms}</span></font>
		</td>
	</tr>
	<tr>			
		<td align="center">
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="480">
			<tr align="left">
				<td style="padding:10px;"><b>{#To_Message#}:</b></td>
				<td style="padding:10px;">
				{if $username.0 neq ""}			
					{section name="username" loop=$username}
					{if $smarty.section.username.index > 0}
					, 
					{/if}
					<input id="to" name="to" type="hidden" style="width:180px" value="{$username[username]}" style="float: left">
					{$username[username]}					
					{/section}
				{elseif $smarty.get.username neq ""}
					{$smarty.get.username}
					<input id="to" name="to" type="hidden" style="width:180px" value="{$smarty.get.username}">
				{else}
					<input id="to" name="to" type="text" style="width:180px" value="{$save.to}" class="input" placeholder="{#USERNAME#}">
				{/if}

				{if $messageid.0 neq ""}
					{section name="messageid" loop=$messageid}
					<input id="messageid" name="messageid[]" type="hidden" value="{$messageid[messageid]}">
					{/section}
				{/if}
				<br clear="all"/>
				<div id="to_info" style="float: left; margin-top: 5px; color: orange;"></div>
				</td>
			</tr>
			<tr align="left" id="tr_subject">
				<td style="padding:10px;"><b>{#Subject#}:</b></td>
				<td style="padding:10px;">
					{if $mode eq 'standard_message' || $mode eq 'standard_answer'}
					{$save.subject}
					{else}
					<input id="subject" name="subject" type="text" style="width:360px" value="{$save.subject}" class="input">
					<br clear="all"/>
					<div id="subject_info" style="float: left; margin-top: 5px; color: orange;"></div>
					{/if}
				</td>
			</tr> <!---->
			<tr align="left">
				<td valign="top" style="padding:10px;"><b>{#Message#}:</b></td>
				<!--<td style="padding:10px;" id="sendemail">
					<textarea id="message" name="message" style="width:360px; height:120px">{$save.message}</textarea>
					
				</td>-->
				<td style="padding:10px;" id="sendsms">
					<textarea id="sms" name="sms" style="width:360px; height:120px" onKeyDown="limitText(this.form.sms,this.form.countdown,140);"
onKeyUp="limitText(this.form.sms,this.form.countdown,140);">{$save.message}</textarea>
					<br/>
					
					<font style="line-height:26px;">({#SMS_MAX#})</font>
					<br>
					<input readonly type="text" name="countdown" size="3" value="140"> {#SMS_LEFT#}
					<br clear="all"/>
					<div id="sms_info" style="float: left; margin-top: 5px; color: orange;"></div>
				</td>
			</tr>
			<!--<tr align="left">
				<td valign="top" style="padding:10px;"></td>
				<td style="padding:10px;">
					<input type="checkbox" id="send_via_sms" name="send_via_sms" value="1" onclick="switch_subject(this)"/> {#SMS_SEND#}
				</td>
			</tr>-->
			<tr>
				<td colspan="2" height="10px"></td>
			</tr>
			<!--<tr align="left">
				<td></td>
				<!--<td><input type="image" src="images/senden_bt.gif" id="send_button" name="send_button" onclick="return checkWriteMessage();" value="{#SEND#}"></td>
 				<td>
				<input type="button" id="send_button" name="send_button" onclick="if(checkWriteMessage()) $('message_write_form').submit();" value="{#Message_Send_Buttton#}" class="button" />
 				<input type="button" id="back_button" name="back_button" onclick="location = '?action=mymessage&type=inbox'; return false;" value="{#BACK#}" class="button" /></td>
				<input type="hidden" id="send_button" name="send_button" value="{#Message_Send_Buttton#}" />
			</tr>-->
		</table>
		</td>
	</tr>			
</table>
<div class="sms-or-message">
<a href="javascript:void(0)" onclick="switch_submit('sms');" class="sms">{#SMS_SEND#}</a>
<a href="javascript:void(0)" onclick="switch_submit('email');" class="email">{#Email_SEND#}</a>
<a href="javascript:void(0)" onclick="history.go(-1);" class="back">{#BACK#}</a><!-- href="?action=mymessage&type=inbox"-->
</div>
</form>
<!--{literal}
<script language="javascript">
$('coin_charge_sms').hide();
</script>
{/literal}-->