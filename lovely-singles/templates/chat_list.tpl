{if $smarty.get.part eq 'all'}
{literal}
<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
</script>
{/literal}
<form id="message_write_form" name="message_write_form" method="post" onsubmit="return false;">
<input type="hidden" name="act" value="writemsg" />
<input id="to" name="to" type="hidden" style="width:180px" value="{$username}" style="float: left">
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr>
		<td align="center" valign="middle"  bgcolor="#000099" >
			<font style="color: #f8981d; display:block; padding:5px 5px 0 5px; background:#3f0c06; font-weight:bold;">{$text}{$coin_charge_sms}</font>
		</td>
	</tr>
	<tr>			
		<td align="center">
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="480">
			<tr align="left">
				<td style="padding:0 10px;" id="sendsms">
					<textarea id="sms" name="sms" style="width:500px; height:120px; -moz-border-radius: 10px; -webkit-border-radius: 10px; border-radius: 10px; padding:10px; margin:10px 0;" onKeyDown="limitText(this.form.sms,this.form.countdown,{$smarty.const.MAX_CHARACTERS});" onKeyUp="limitText(this.form.sms,this.form.countdown,{$smarty.const.MAX_CHARACTERS});" onclick="markAsRead('{$username}')" tabindex="1">{$save.message}</textarea>
					<br/>

					{if $already_topup}
					<font style="line-height:26px;"><a href="#" style="color:#FFF; text-decoration:none;" onclick="showAttachmentsList(); return false;"><!-- <img src="images/attachment.png"  width="20" height="20" border="0"/> -->Geschenk senden</a></font>
					{/if}
					<span style="float:right; margin-top:5px;">({#SMS_MAX#} {$smarty.const.MAX_CHARACTERS})
					<input readonly type="text" name="countdown" size="3" value="{$smarty.const.MAX_CHARACTERS}"> {#SMS_LEFT#}
                    </span>
					<br clear="all"/>
                    <div id="attachments-list"></div>
					<div id="sms_info" style="float: left; margin-top: 5px; color: orange;"></div>
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td height="10px"></td>
			</tr>
		</table>
		</td>
	</tr>			
</table>
<div style="width:550px; float:left; margin-bottom:10px;">
<a href="javascript:void(0)" onclick="sendChatMessage('sms');" class="sms" tabindex="2">{#SMS_SEND#}</a>
<a href="javascript:void(0)" onclick="sendChatMessage('email');" class="email" tabindex="3"><span>{#Email_SEND#}</span></a>
</div>
</form>

<br class="clear"/>
{/if}
<div id="messagesContainer">
<ul id="messagesListArea" style="position: relative;">
{foreach from=$messages item="message"}
	<li class="message_list {if $message.status eq 0}new{/if} {if $message.username eq $username}sender{else}receiver{/if}">
		<div style="float:left;">
			<img src="thumbnails.php?file={$message.picturepath}&w=30&h=30" width="30" height="30" style="border:1px solid #FFF;"/>
			<div style="position:relative; right:-33px; top:-14px;"><img src="images/{if $message.username eq $username}gray_03.png{else}green_03.png{/if}" width="19" height="23"/></div>
		</div>
		<div style="float:left; max-width:350px;">
			<span style="display:block; float:left; padding-left:5px; font-size:11px; line-height:13px; padding-top:2px;"><strong>{$message.username}</strong> [{$message.datetime}]</span> {if ($message.status eq 0) and ($message.username eq $username)} !NEW{/if}
			<br />
			<span class="message">
            <p style="float:left;">
            &nbsp;
			{if $message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
				{$message.message}
			{else}
				{$message.message|strip_tags|replace:"<":"&lt;"|replace:">":"&gt;"|nl2br}
			{/if}
			&nbsp;
            </p>
                {if $message.attachment_coins>0}
                {include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
                {/if}
            
			</span>
		</div>
	</li><br class="clear"/>
{/foreach}
</ul>

<script>
totalMessages = {$total}
</script>
</div>