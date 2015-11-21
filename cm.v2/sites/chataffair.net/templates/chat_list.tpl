{if $smarty.get.part eq 'all'}
{literal}

<script language="javascript" type="text/javascript">
	var to_ok = false;
	var sms_ok = false;
</script>
{/literal}


<br class="clear"/>
{/if}



{foreach from=$messages item="message"}
	<div class="container-chat-history-line"> 
		{if $message.username eq $username}
			<div class="chat-history-profile-img">
            	<a href="?action=viewprofile&username={$message.username}">
            	<img src="thumbnails.php?file={$message.picturepath}&w=40&h=40" width="40" height="40" />
            	</a>
            </div>
            <div class="chat-history-name" style="float:none;">{$message.username} [{$message.datetime}]</div>
            <div style="float:left; padding-top:8px;"><img src="images/cm-theme/chat-area/chat-massage-history-box-b.jpg" width="9" height="18" /></div>
            <div class="chat-bubble">
            	{if $message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
					{$message.message}
				{else}
					{$message.message|strip_tags|replace:"<":"&lt;"|replace:">":"&gt;"|nl2br}
				{/if}
            </div>		               	
		{else}
	    <div class="container-chat-history-line">
		    <div style="float:right; width:580px; padding-left:10px; margin-bottom:5px; font-weight:bold; text-align:right;">[{$message.datetime}]</div>
		    <div style="float:right; padding-top:8px;"><img src="images/cm-theme/chat-area/chat-massage-history-box-g.jpg" width="9" height="18"></div>
		    <div class="chat-bubble2">
		    	{if $message.username eq $smarty.const.ADMIN_USERNAME_DISPLAY}
					{$message.message}
				{else}
					{$message.message|strip_tags|replace:"<":"&lt;"|replace:">":"&gt;"|nl2br}
				{/if}
		    </div>   
	    </div>
	    {/if}
	</div>
{/foreach}


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