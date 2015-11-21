<!-- {$smarty.template} -->
{if ($mymessage_total > 0)}
<form id="message_outbox_form" name="message_outbox_form" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%">			
	<tr bgcolor="#b6b6b6" height="28px">	
		<th width="60" align="center"></th>
		<th align="left" width="135" class="text-title">{#To#}:</th>
		<th align="left" class="text-title">{#Subject#}:</th>
		<th align="left" width="85" class="text-title">{#Date#}:</th>
		<th width="80"></th>
		<th align="left" width="30px"><a href="javascript:selectAll('message_outbox_form', 'messageid')" class="sitelink">{#All#}:</a></th>
		
	</tr>
</table>
<table border="0" cellpadding="2" cellspacing="1" width="100%">
	{section name="record" loop="$message"}
	{assign var="msg" value=$message[record].message|truncate:5:""}
	{assign var="id" value=$message[record].message|replace:"#HPB#":""}
	{if $message[record].status eq 0}
	<tr  bgcolor="{cycle values="#006de0,#003873"}">
		<td width="50" height="25px" align="center">
			<img src="images/icon/i_new.gif" height="25" width="25">
		</td> 
	{else}
	<tr  bgcolor="{cycle values="#006de0,#003873"}">
		<td width="50" height="25px" align="center">
			<img src="images/icon/i_read.gif" height="25" width="25">
		</td>
	{/if}
		<td  width="125"  valign="middle" align="left" style="padding-left:10px;">
			{if ($message[record].username == 'System Admin')}
				{$message[record].username}
			{else}
				<a href="?action=viewprofile&id={$message[record].id}&username={$message[record].username}&type=outbox" class="link-inrow">
					{$message[record].username}
				</a>
			{/if}
		</td>
		{if $msg == "#HPB#"}
		<td align="left" style="padding-left:10px;">
			<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="link-inrow">
				{$message[record].subject|truncate:45:"..."}
			</a>
		</td>
		{else}
		<td align="left" style="padding-left:10px;">
			<a href="?action=viewmessage&type=outbox&id={$message[record].id}&from=message" class="link-inrow">
				{$message[record].subject|truncate:45:"..."}
			</a>
		</td>
		{/if}
		<td width="80" align="center">{$message[record].datetime|date_format:"%D"}</td>
		<td width="85" align="center">
		{if $msg == "#HPB#"}
		<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="link-inrow">
		{else}
		<a href="?action=viewmessage&type=outbox&id={$message[record].id}&from=message" class="link-inrow">
		{/if}
		{#Read_more#}</a>
		</td>
		<td  width="30px" align="center"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>
	</tr>
	{/section}
</table>
<div class="pagein">{#page#} : {paginate_prev} {paginate_middle} {paginate_next}</div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="right"><input id="delete_button" name="delete_button" onclick="return confirm('{#delete_comfirm_msg#}')" type="submit" class="button" value="{#Delete#}" /></td>
	</tr>			
</table>
</form>
{else}
	<p align="center" style="padding-top:10px">{#No_Message#}</p>
{/if}