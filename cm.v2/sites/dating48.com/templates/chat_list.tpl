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
		<td align="center" valign="middle">
			<font style="color: #fdbe00; display:block; padding:5px; background:#03406e; font-weight:bold; -webkit-border-top-right-radius: 10px; -moz-border-radius-topright: 10px; border-top-right-radius: 10px;">{$coin_charge_sms}</font>
		</td>
	</tr>
	<tr>
		<td align="center">
		<table align="center" border="0" cellpadding="4" cellspacing="0" width="480">
			<tr align="left">
				<td style="padding:10px;" id="sendsms">
					<textarea id="sms" name="sms" maxlength="{$smarty.const.MAX_CHARACTERS}" onclick="markAsRead('{$username}')" tabindex="1">{$save.message}</textarea>
					<br/>
					<font style="line-height:26px;">({#SMS_MAX#})</font>
					<span style="float:right; margin-top:5px;">
					<input readonly type="text" id="countdown" name="countdown" size="3" value="{$smarty.const.MAX_CHARACTERS}" style="background:none; border:none; color:#fff; font-weight:bold; text-shadow: #000 1px 1px 2px; text-align:right;"> {#SMS_LEFT#}
                    </span>
					<br clear="all"/>
					<div id="sms_info" style="float: left; margin-top: 5px; color: orange;"></div>
				</td>
			</tr>

		</table>
		</td>
	</tr>
</table>
<div class="container-btn-chat-submit">
<a href="javascript:void(0)" onclick="sendChatMessage('sms');" id="sms_send_button" class="sms" tabindex="2">{#SMS_SEND#}</a>
<a href="javascript:void(0)" onclick="sendChatMessage('email');"  id="email_send_button" class="email" tabindex="3"><span>{#Email_SEND#}</span></a>
</div>
</form>

<br class="clear"/>
{/if}
<div id="messagesContainer">
<ul id="messagesListArea">
{foreach from=$messages item="message"}
	<li class="message_list {if $message.status eq 0}new{/if} {if $message.username eq $username}sender{else}receiver{/if}">
		<div style="float:left;">
        {if ($message.username ne $username) || ($message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY)}
			<img src="thumbnails.php?file={$message.picturepath}&w=30&h=30" width="30" height="30" style="border:1px solid #FFF;"/>
        {else}
			<a href="?action=viewprofile&username={$message.username}"><img src="thumbnails.php?file={$message.picturepath}&w=30&h=30" width="30" height="30" style="border:1px solid #FFF;"/></a>
        {/if}
		<div style="position:relative; right:-33px; top:-14px;"><img src="images/{if $message.username eq $username}gray_03.png{else}green_03.png{/if}" width="19" height="23"/></div>
		</div>

		<div style="float:left; max-width:350px;">
		<span style="display:block; float:left; padding-left:5px; font-size:11px; line-height:13px; padding-top:2px;"><strong>{$message.username}</strong> [{$message.datetime}]</span> {if ($message.status eq 0) and ($message.username eq $username)}!NEW{/if}
		<br class="clear"/>
		<span class="message">

		{if $message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
			{$message.message}
		{else}
			{$message.message|strip_tags|replace:"<":"&lt;"|replace:">":"&gt;"|nl2br}
		{/if}
		</span>
		</div>

	</li><br class="clear"/>
{/foreach}
</ul>

<script>
totalMessages = {$total};
{literal}

jQuery(document).ready(function() {
	jQuery('#sms').keyup(function(){
        var limit = parseInt(jQuery(this).attr('maxlength'));
        var text = jQuery(this).val();
        var chars = text.length;
 
        if(chars > limit){
            var new_text = text.substr(0, limit);
            jQuery(this).val(new_text);
        }

		jQuery('#countdown').val(limit-chars);
    });
});
{/literal}
</script>
</div>