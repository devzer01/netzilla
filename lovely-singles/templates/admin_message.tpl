<div class="result-box">

<div class="result-box-inside-nobg">

<table cellpadding="5" cellspacing="0" width="300">
<tr height="28px">
<td align="center" style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'inbox'} background: #b6b6b6;{/if}" width="33%">
{if $smarty.request.type eq "inbox"}
<a href="./?action=admin_message&type=inbox" class="sitelink"  ><u>Inbox</u></a>
{else}
<a href="./?action=admin_message&type=inbox" class="sitelink"  >Inbox</a>
{/if}
</td>
<td align="center"  style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'outbox'}background: #b6b6b6;{/if}" width="33%">
{if $smarty.request.type eq "outbox"}
					<a href="?action=admin_message&type=outbox" class="sitelink"  ><u>Outbox</u></a>
				{else}
					<a href="?action=admin_message&type=outbox" class="sitelink"  >Outbox</a>
{/if}
</td>

<td align="center"  style="border: solid 1px #FFFFFF;{if $smarty.get.type eq 'writemessage'}background: #b6b6b6;{/if}" width="33%">
{if $smarty.request.type eq "writemessage"}
					<a href="?action=admin_message&type=writemessage" class="sitelink"><u>Write Message</u></a>
				{else}
					<a href="?action=admin_message&type=writemessage" class="sitelink">Write Message</a>
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
</div>

</div>