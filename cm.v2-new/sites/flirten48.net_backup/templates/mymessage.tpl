<!-- {$smarty.template} -->
<h2>
{if $smarty.get.type eq "inbox"}
{#Message_Inbox#}
{elseif $smarty.get.type eq "outbox"}
{#Message_Outbox#}
{elseif $smarty.get.type eq "writemessage" or $smarty.get.type eq "complete" or $smarty.get.type eq "reply"}
{#Message_Send#}
{elseif $smarty.get.type eq "archive"}
{#Message_Archive#}
{/if}
</h2>
<div class="result-box">
	<div class="result-box-inside-nobg">

		<table align="center" width="100%" cellspacing="0" cellpadding="0" border="0">
			<tr>			
				<td>
					{if $smarty.get.type eq "archive"}
						{include file="message_archive.tpl"}
					{elseif $smarty.get.type eq "complete"}
						{include file="message_complete.tpl"}
					{elseif $smarty.get.type eq "incomplete"}
						{include file="message_incomplete.tpl"}
					{elseif $smarty.get.type eq "complete_question"}
						{include file="message_complete_question.tpl"}			
					{elseif $smarty.get.type eq "outbox"}
						{include file="message_outbox.tpl"}
					{elseif $smarty.get.type eq "reply"}
						{include file="message_write.tpl"}
					{elseif $smarty.get.type eq "writemessage"}
						{include file="message_write.tpl"}
					{else}
						{include file="message_inbox.tpl"}
					{/if}
				</td>			
			</tr>
		</table>
	</div>
</div>