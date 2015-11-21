<!-- {$smarty.template} -->
<form id="message_outbox_form" name="message_outbox_form" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:solid 1px">			
	<tr bgcolor="#b6b6b6" height="28px">
		<th width="20px"></th>
		<th align="left" width="130px" class="text-title">{#Subject#}:</th>
		<th align="left" width="125px" class="text-title">{#Date#}:</th>
		<th width="80px"></th>
		<th align="left" width="30px"><a href="javascript:selectAll('message_outbox_form', 'messageid')" class="sitelink">{#All#}:</a>
		  </th>
		<th align="left" width="5px"></th>
	</tr>
</table>
{if ($mymessage_total > 0)}
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	{section name="record" loop="$message"}
	{assign var="msg" value=$message[record].message|truncate:5:""}
	{assign var="id" value=$message[record].message|replace:"#HPB#":""}
	{if $message[record].status eq 0} 
	<tr  bgcolor="{cycle values="#006de0,#003873"}">
		<td width="20px" align="center">
			<img src="images/icon/i_new.gif" height="25px" width="25px">
		</td>
		{if $msg == "#HPB#"}
		<td width="130px" align="left"><a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox&from=sugges" class="link-inrow">{$message[record].subject|truncate:45:"..."}</a></td>
		{else}
		<td width="130px" align="left"><a href="?action=suggestion_box&type=outbox&do=view_message&id={$message[record].id}&from=sugges" class="link-inrow">{$message[record].subject|truncate:45:"..."}</a></td>
		{/if}
		<td width="125px" align="left">{$message[record].datetime|date_format:"%D %T"}</td>
		<td width="80px">
		{if $msg == "#HPB#"}
		<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="link-inrow">
		{else}
		<a href="?action=suggestion_box&type=outbox&id={$message[record].id}&do=view_message" class="link-inrow">
		{/if}
		Read now</a>
		</td>
		<td width="30px"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>
		<td width="5px">
		</td>
	</tr>
	{else}
		<tr  bgcolor="{cycle values="#006de0,#003873"}">
		<td width="20px" align="center">
			<img src="images/icon/i_read.gif" height="25px" width="25px">
		</td>
		<td width="130px" align="left"><a href="?action=suggestion_box&type=outbox&do=view_message&id={$message[record].id}" class="link-inrow">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="125px" align="left">{$message[record].datetime|date_format:"%D %T"}
</td>
		<td width="80" align="left">
		{if $msg == "#HPB#"}
		<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="link-inrow">
		{else}
		<a href="?action=suggestion_box&type=outbox&id={$message[record].id}&do=view_message" class="link-inrow">
		{/if}
		Read now</a>
		</td>
		<td width="30px"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>
		<td width="5px"></td>
	</tr>
	{/if}
	{/section}
</table>
<div class="pagein">{#page#} : {paginate_prev} {paginate_middle} {paginate_next}</div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="right" > 
        <a href="?action=suggestion_box&do=sugg2" class="button">{#BACK#}</a>
			<input id="delete_button" name="delete_button" type="button" value="{#Delete#}" onClick="return confirm('Are you sure to delete selected message?')" class="button">
		</td>
	</tr>	
</table>
</form>
{else}
	<p align="center" style="padding-top:10px">{#Suggestion_Message_None#}</p>
{/if}