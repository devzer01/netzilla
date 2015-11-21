<!-- {$smarty.template} -->
<h1 class="admin-title">ADMIN MESSAGE</h1>

<table cellpadding="5" cellspacing="0" width="450" style="margin-top:10px; background:#CCC">
<tr height="28px">
<td align="center" class="tab-admin-btn" style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'inbox'} background: #718993{/if}" width="33%">
{if $smarty.request.type eq "inbox"}
<a href="./?action=admin_message&type=inbox" class="admin-link"><u>{#Inbox#}</u></a>
{else}
<a href="./?action=admin_message&type=inbox" class="admin-link">{#Inbox#}</a>
{/if}
</td>
<td align="center"  style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'outbox'}background: #718993{/if}" width="33%">
{if $smarty.request.type eq "outbox"}
					<a href="?action=admin_message&type=outbox" class="admin-link"><u>{#Outbox#}</u></a>
				{else}
					<a href="?action=admin_message&type=outbox" class="admin-link">{#Outbox#}</a>
{/if}
</td>

<td align="center"  style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'writemessage'}background: #718993{/if}" width="33%">
{if $smarty.request.type eq "writemessage"}
					<a href="?action=admin_message&type=writemessage" class="admin-link"><u>{#Write#} {#Message#}</u></a>
				{else}
					<a href="?action=admin_message&type=writemessage" class="admin-link">{#Write#} {#Message#}</a>
				{/if}
</td>
</tr>
</table>





                
<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td>
		{if $smarty.get.type eq "complete"}
			{include file="message_complete.tpl"}
		{elseif $smarty.get.type eq "outbox"}
			{include file="admin_message_outbox.tpl"}
		{elseif $smarty.get.type eq "reply"}
			{include file="admin_message_write.tpl"}
		{elseif $smarty.get.type eq "writemessage"}
			{include file="admin_message_write.tpl"}
		{else}
			{include file="admin_message_inbox.tpl"}
		{/if}
	</td>
	<td width="10px"></td>
</tr>
</table>
