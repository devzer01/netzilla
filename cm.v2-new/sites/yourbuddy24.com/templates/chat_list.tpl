{if $smarty.get.part eq 'part1'}
{literal}

<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
	
	jQuery("#emoticons").click(function (e) {
		e.preventDefault();
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
        
		<span style="color:#000; position:relative; top:-28px; margin-left:95px;">
		<input readonly type="text" id="countdown" name="countdown" size="3" value="{$smarty.const.MAX_CHARACTERS}" style="background:none; border:none; color:#000; font-weight:bold; text-align:right;"> {#SMS_LEFT#}
		<font style="line-height:26px; color:#000;">({#SMS_MAX#} {$smarty.const.MAX_CHARACTERS})</font>
        </span>

		<!--end profile image -->
        
		<div class="container-btn-chat">
		<a href="#" {if ($smarty.const.ATTACHMENTS eq 1) and ($smarty.const.ATTACHMENTS_COIN eq 1) and ($already_topup)}onclick="showAttachmentsList('coins'); return false;"{/if} class="btn-coin-icon"></a>
		<a href="#" id='emoticons' class="btn-emoticon"></a>
		<a href="javascript:void(0)" onclick="sendChatMessage('sms');" id="sms_send_button" class="btn-sms-chat" tabindex="2">{#SMS_SEND#}</a>
		<a href="javascript:void(0)" onclick="sendChatMessage('email');"  id="email_send_button" class="btn-email-chat" tabindex="3"><span>{#Email_SEND#}</span></a>
		</div>
		<div id="attachments-list"></div>
</form>
<br class="clear"/>
{else}
<!--profile image -->
    <ul class="container-profile-icon" style="float:left; margin-top:10px;">
    {if $username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
        <li style="margin-left:0 !important;">
            <a  class="lightview profile-icon"></a>
            <img src="thumbnails.php?username=bigbrother&w=110&h=92" width="110" height="92" class="profile-img"/>
        </li>
     {else}
        <li style="margin-left:0 !important;">
            <a href="?action=viewprofile&username={$username}"  class="lightview profile-icon"></a>
            <img src="thumbnails.php?username={$username}&w=110&h=92" width="110" height="92" class="profile-img"/>
        </li>
      {/if}
    </ul>
<!--End profile image -->
	<div class="container-chat-history">
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
</div>

<script>
totalMessages = {$total};
</script>
{/if}