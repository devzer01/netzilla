<!-- {$smarty.template} {$username}-->
{foreach from=$messages item="message"}

	{if $message.username eq $username}
				<div class="container-chat-history-line">
			<div class="chat-history-profile-img">
				<img src="thumbnails.php?file={$message.picturepath}&w=40&h=40" width="40" height="40" />
			</div>
			<div class="chat-history-name">{$message.username} [{$message.datetime}] {if ($message.status eq 0) and ($message.username eq $username)}<img src="images/cm-theme/new_icon.gif"/>{/if}</div>
			<div style="float:left; padding-top:8px;"><img src="images/cm-theme/chat-area/chat-massage-history-box-b.jpg" width="9" height="18" /></div>
			<div class="chat-bubble">
					{$message.message}
				{if $message.attachment_coins>0}
					{include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
				{/if}
			</div>
		</div>
				
	{else}
		 <div class="container-chat-history-line">
	         <div style="float:right; width:590px; padding-left:10px; margin-bottom:5px; font-weight:bold; text-align:right;">[{$message.datetime}]</div>
	         <div style="float:right; padding-top:8px;"><img src="images/cm-theme/chat-area/chat-massage-history-box-g.jpg" width="9" height="18" /></div>
	        <div class="chat-bubble2">
					{$message.message}
				{if $message.attachment_coins>0}
					{include file="attachments-coins-display2.tpl" coins=$message.attachment_coins}
				{/if}
			</div>
		</div>
	{/if}
	
	
{/foreach}

<script>
totalMessages = {$total};
</script>