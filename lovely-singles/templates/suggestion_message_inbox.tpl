<form id="message_inbox_form" name="message_inbox_form" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:solid 1px">			
	<tr bgcolor="#b6b6b6" height="28px">
		<th width="20px"></th>
		<th align="left" width="130px" class="text-title">{#Subject#}:</th>
		<th align="left" width="140px" class="text-title">{#Date#}:</th>
		<th width="80px"></th>
		<th align="left" width="30px"><a href="javascript:selectAll('message_inbox_form','messageid')" class="sitelink">{#All#}:</a></th>
		<th align="left" width="5px"></th>
	</tr>
</table>
{if ($mymessage_total > 0)}
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td height="5px"></td>
	</tr>
	{section name="record" loop="$message"} 
	{assign var="msg" value=$message[record].message|truncate:5:""}
	{assign var="id" value=$message[record].message|replace:"#HPB#":""} 
	{if $message[record].status eq 0}
	<tr bgcolor="{cycle values="#663333,#996666"}" class="b_font">
		<td width="20px" align="center">
			<img src="images/icon/i_new.gif" height="25px" width="25px">
		</td>
		<td width="130px" align="left"><a href="?action=suggestion_box&type=inbox&do=view_message&id={$message[record].id}&from=sugges" class="link-inrow">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="140px" align="left">{$message[record].datetime|date_format:"%D %T"}
</td>
		<td align="left">
		{if $msg == "#HPB#"}
		<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=inbox" class="link-inrow">
		{else}
		<a href="?action=suggestion_box&type=inbox&do=view_message&id={$message[record].id}&from=sugges" class="link-inrow">
		{/if}
		Read now</a>
		</td>
		<td width="30px"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>
		<td width="5px"></td>
	</tr>
	{else}
	<tr bgcolor="{cycle values="#663333,#996666"}">
		<td width="20px" align="center">
			<img src="images/icon/i_read.gif" height="25px" width="25px">
		</td>
		<td width="130px" align="left"><a href="?action=suggestion_box&type=inbox&do=view_message&id={$message[record].id}" class="link-inrow">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="140px">{$message[record].datetime|date_format:"%D %T"}
</td>
		<td width="80px" align="left">
		{if $msg == "#HPB#"}
		<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=inbox" class="link-inrow">
		{else}
		<a href="?action=suggestion_box&type=inbox&id={$message[record].id}&do=view_message" class="link-inrow">
		{/if}
		Read now</a>
		</td>
		<td width="30px"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>
		<td width="5px"></td>
	</tr>
	{/if}
	{/section}
</table>
{paginate_prev} {paginate_middle} {paginate_next}
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" height="20px"></td>
	</tr>
	<tr>
		<td align="right" style="padding-right:10px;"><b>{#Marked_mails#}  :</b></td>
		<td align="right" width="100">
			<input type="hidden" name="action" value="delete"/>
			<input id="delete_button" name="delete_button" type="button" value="{#Delete#}" onClick="if(confirm('Are you sure to delete selected message?')) $('message_inbox_form').submit();" class="button"></td>
			
	</tr>	
	<tr><td height="15"></td></tr>		
</table>
</form>
{else}
	<p align="center" style="padding-top:10px">{#Suggestion_Message_None#}</p>
{/if}