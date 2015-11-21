{if $smarty.get.part eq 'part1'}

<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
	var already_topup = '{$already_topup}';
	//var already_topup = '';
	{literal}
	jQuery(function () {
		jQuery("#emoticons").click(function (e) {
			e.preventDefault();
			jQuery("#show_emo").show();
			jQuery("#iconlist").css("left", jQuery(this).parent().offset().left + 'px');
			jQuery("#iconlist").css("top", (jQuery(this).parent().offset().top + 30) + 'px');
			jQuery("#iconlist").fadeIn();
			jQuery("show_gifts").hide();
			
			jQuery(document).mouseup(function (e)
			{
						var container = jQuery("#iconlist");

						if (!container.is(e.target) // if the target of the click isn't the container...
							&& container.has(e.target).length === 0) // ... nor a descendant of the container
						{
							container.hide();
							jQuery(document).unbind('mouseup');
						}
			});
		});
		
		jQuery("#sendgift").click(function (e) {
			if (already_topup == 1) {
				showAttachmentsList('gifts');
			} else {
				showVipMessage();	
			}
		});
	});
	
	function sendCoins() {
		if (already_topup == 1) {
			showAttachmentsList('coins');
		} else {
			showVipMessage();
		}
	}
	
	function showVipMessage()
	{
		loadPagePopup('?action=show_vip_message', '100%');
	}
	
</script>
{/literal}
<form id="message_write_form" name="message_write_form" method="post" onsubmit="return false;">
<input type="hidden" name="act" value="writemsg" />
<input id="to" name="to" type="hidden" style="width:180px" value="{$username}" style="float: left">
		<h1>{$coin_charge_sms}</h1>
		<textarea id="sms" name="sms" maxlength="{$smarty.const.MAX_CHARACTERS}" onclick="markAsRead('{$username}')" tabindex="1">{$save.message}</textarea>
        <span class="text-max">
            <input readonly type="text" id="countdown" name="countdown" size="3" value="{$smarty.const.MAX_CHARACTERS}" style="background:none; border:none; color:#ffff00; font-weight:bold; text-align:right;"> 
            {#SMS_LEFT#}<font style="line-height:26px; color:#ffff00;">({#SMS_MAX#}{$smarty.const.MAX_CHARACTERS})</font>
            
        </span>
        <div id="attachments-list"></div>
		<div class="container-btn-chat">
		<a href="#" onclick="sendCoins(); return false;" class="btn-coin-icon"></a>
		<a href="#" id='emoticons' class="btn-emoticon"></a>
		<a href="#" id='sendgift' class="btn-gift"></a>
		<a href="javascript:void(0)" onclick="sendChatMessage('sms');" id="sms_send_button" class="btn-sms-chat" tabindex="2">{#SMS_SEND#}</a>
		<a href="javascript:void(0)" onclick="sendChatMessage('email');"  id="email_send_button" class="btn-email-chat" tabindex="3"><span>{#Email_SEND#}</span></a>
		</div>

		
</form>
<br class="clear"/>
{else}
	<div class="container-chat-history">
{foreach from=$messages item="message"}
	{if $message.gift_id>0}
	<div class="container-gift-history-{if $message.username eq $username}left{else}right{/if}">
    <h1><strong>{$message.username}</strong> [{$message.datetime}] {if ($message.status eq 0) and ($message.username eq $username)}<img src="images/cm-theme/new_icon.gif"/>{/if}</h1>
        	<img src="{$message.gift_path}" height="100"/>
	</div>
	{else}
	<div class="container-history-{if $message.username eq $username}left{else}right{/if}">
    <h1><strong>{$message.username}</strong> [{$message.datetime}] {if ($message.status eq 0) and ($message.username eq $username)}<img src="images/cm-theme/new_icon.gif"/>{/if}</h1>
    <p>		
		{$message.message}
       
        {if $message.attachment_coins>0}
			{include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
		{/if}
    </p>
    </div>
    {/if}
{/foreach}
</div>

<script>
totalMessages = {$total};
</script>
{/if}
<div id='iconlist'>
	<div id="show_emo" style='display: none;'>{include file='emoticons.tpl'}</div>
</div>