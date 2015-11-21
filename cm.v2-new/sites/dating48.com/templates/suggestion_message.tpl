<!-- {$smarty.template} -->
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="left">
<table cellpadding="5" cellspacing="0" width="200">
<tr height="28px">
<td align="center" style="border: solid 1px #FFFFFF;{if $smarty.get.type ne 'outbox'} background: #b6b6b6{/if}" width="50%"><a href="?action=suggestion_box&do=sugg4&type=inbox" class="sitelink">{#Inbox#}</a></td>
<td align="center"  style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'outbox'}background: #b6b6b6{/if}"><a href="?action=suggestion_box&do=sugg4&type=outbox" class="sitelink">{#Outbox#}</a></td>
</tr>
</table>
		</td>
	</tr>
	<tr>
		<td>
		{if $smarty.get.type eq "complete"}
			{include file="message_complete.tpl"}
		{elseif $smarty.get.type eq "outbox"}
			{include file="suggestion_message_outbox.tpl"}
		{else}
			{include file="suggestion_message_inbox.tpl"}
		{/if}
		</td>
	</tr>
</table>