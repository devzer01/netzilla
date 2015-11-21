{if $smarty.get.part eq 'part1'}
{literal}

<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
	
	jQuery("#emoticons").click(function (e) {
		e.preventDefault();
		jQuery("#show_emo").show();
		jQuery("#show_gift").hide();
		jQuery("#iconlist").css("left", jQuery(this).parent().offset().left + 'px');
		jQuery("#iconlist").css("top", (jQuery(this).parent().offset().top + 30) + 'px');
		jQuery("#iconlist").fadeIn();
	});
	
	jQuery("#sendgift").click(function (e) {
		e.preventDefault();
		jQuery("#show_gift").show();
		jQuery("#show_emo").hide();
		jQuery("#iconlist").css("left", jQuery(this).parent().offset().left + 'px');
		jQuery("#iconlist").css("top", (jQuery(this).parent().offset().top + 30) + 'px');
		jQuery("#iconlist").fadeIn();
	});
</script>
{/literal}
<form id="message_write_form" name="message_write_form" method="post" onsubmit="return false;">
<input type="hidden" name="act" value="writemsg" />
<input id="to" name="to" type="hidden" style="width:180px" value="{$username}" style="float: left">
		<h1>{$coin_charge_sms}</h1>
		<textarea id="sms" name="sms" maxlength="{$smarty.const.MAX_CHARACTERS}" onclick="markAsRead('{$username}')" tabindex="1">{$save.message}</textarea>
	  
		<!--profile image -->
        <ul class="container-my-profile" id="container-profile-list">
            <li>
                {if $username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
                <img src="thumbnails.php?username=bigbrother&w=102&h=102" width="102" height="102" />
                <a class="link-profile"></a>
                {else}
                <img src="thumbnails.php?username={$username}&w=102&h=102" width="102" height="102" />
                <a href="?action=viewprofile&username={$username}" class="link-profile"></a>
                {/if}
            </li>
        </ul>
        
        
		<span style="color:#000; position:relative; top:-28px; margin-left:30px; text-shadow: 1px 1px #ccc;">
		<input readonly type="text" id="countdown" name="countdown" size="3" value="{$smarty.const.MAX_CHARACTERS}" style="background:none; border:none; color:#000; font-weight:bold; text-align:right;"> {#SMS_LEFT#}
		<font style="line-height:26px; color:#000;">({#SMS_MAX#})</font>
        </span>

		<!--end profile image -->
		<div class="container-btn-chat">
		<a href="#" {if ($smarty.const.ATTACHMENTS eq 1) and ($smarty.const.ATTACHMENTS_COIN eq 1) and ($already_topup)}onclick="showAttachmentsList('coins'); return false;"{/if} class="btn-coin-icon"></a>
		<a href="#" id='emoticons' class="btn-emoticon"></a>
        <a href="#" id='sendgift' class="btn-gift"></a>
		<a href="javascript:void(0)" onclick="sendChatMessage('sms');" id="sms_send_button" class="btn-sms-chat" tabindex="2">{#SMS_SEND#}</a>
		<a href="javascript:void(0)" onclick="sendChatMessage('email');"  id="email_send_button" class="btn-email-chat" tabindex="3"><span>{#Email_SEND#}</span></a>
		</div>
		<div id="attachments-list"></div>
</form>
<br class="clear"/>
{else}
{foreach from=$messages item="message"}
	<div class="container-history-{if $message.username eq $username}left{else}right{/if}">
    <h1><strong>{$message.username}</strong> [{$message.datetime}] {if ($message.status eq 0) and ($message.username eq $username)}<img src="images/cm-theme/new_icon.gif"/>{/if}</h1>
    <p>		
		{$message.message}
       
        {if $message.attachment_coins>0}
		{include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
		{/if}
    </p>
    </div>
{/foreach}

<script>
totalMessages = {$total};
</script>
{/if}
<div id='iconlist'>
	<div id="show_emo">{include file='emoticons.tpl'}</div>
	<div id="show_gift">{include file='send_gift.tpl'}</div>
</div>