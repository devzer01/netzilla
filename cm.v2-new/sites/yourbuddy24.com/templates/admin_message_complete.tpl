<!-- {$smarty.template} -->
<div class="message-complete-banner">
<span class="message-complete-text">{#sent_complete_message#|replace:'[PROFILE_NAME]':$smarty.get.username}</span>
</div>
        
<input type="button" id="back_button" name="back_button" onclick="location = '?action=admin_message&type=inbox'; return false;" value="{#BACK#}" class="button" />