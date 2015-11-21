<!-- {$smarty.template} -->
{if ($smarty.get.sentby == 'sms')}
		<div class="sms-complete-banner">
        <span class="sms-complete-text">{#sent_complete_sms1#|replace:'[PROFILE_NAME]':$smarty.get.username}</span>
        </div>
        <div style="margin-bottom:10px; font-size:14px;">{#sent_complete_sms2#}</div>
			
{else}
        <div class="message-complete-banner">
        <span class="message-complete-text">{#sent_complete_message#|replace:'[PROFILE_NAME]':$smarty.get.username}</span>
        </div>
			
{/if}
        
        <input type="button" id="back_button" name="back_button" onclick="location = '?action=mymessage&type=inbox'; return false;" value="{#BACK#}" class="button" />