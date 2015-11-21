<!-- {$smarty.template} -->
<form id="message_outbox_form" name="message_outbox_form" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:solid 1px">			
<tr height="28px" valign="middle" bgcolor="#718993">
	<td width="5%" class="text-title">&nbsp;</td>
	<td width="19%" class="text-title"><a href="#" class="sitelink">{#From#}:</a></td>
	<td width="28%" class="text-title"><a href="#" class="sitelink">{#Subject#}:</a></td>
	<td width="17%" class="text-title"><a href="#" class="sitelink">{#Date#}:</a></td>
	<td width="25%" class="text-title">&nbsp;</td>	
	<td width="16%"><a href="javascript:selectAll('message_inbox_form','messageid')" class="sitelink">{#All#}:</a></td>	
</tr>
</table>
<table border="0" cellpadding="2" cellspacing="1" width="100%">

	{section name="record" loop="$message"}
	{assign var="msg" value=$message[record].message|truncate:5:""}
	{assign var="id" value=$message[record].message|replace:"#HPB#":""} 
	{if $message[record].status eq 0} 
		<tr bgcolor="{cycle values="#fafafa,#dad9d9"}" height="25px">
			<td width="5%"  align="center">
				<img src="images/icon/i_new.gif" height="16" width="16">
			</td>
			<td width="19%" style="padding-left:10px;"><a href="?action=viewprofile&username={$message[record].username}&from=admin" class="admin-link">{$message[record].username}</a></td>
			<td width="28%" style="padding-left:10px;"><a href="?action=admin_viewmessage&type=outbox&id={$message[record].id}" class="admin-link">{$message[record].subject|truncate:45:"..."}</a></td>
			<td width="22%"   align="center">{$message[record].datetime|date_format:"%D %T"}</td>
			<td width="20%" align="center" >
				{if $msg == "#HPB#"}
					<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="admin-link">
				{else}
					<a href="?action=admin_viewmessage&type=outbox&id={$message[record].id}&from=message" class="admin-link">
				{/if}
				Read now</a>
			</td>
			<td width="16%" align="center"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>			
		</tr>
	{else}
		<tr bgcolor="{cycle values="#fafafa,#dad9d9"}" height="25px">
		<td width="5%"  align="center">
			<img src="images/icon/i_read.gif" height="16" width="16">
		</td>
		<td width="19%" style="padding-left:10px;"><a href="?action=viewprofile&username={$message[record].username}&from=admin" class="admin-link">{$message[record].username}</a></td>
		<td width="28%" style="padding-left:10px;"><a href="?action=admin_viewmessage&type=outbox&id={$message[record].id}" class="admin-link">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="22%"  align="center">{$message[record].datetime|date_format:"%D %T"}</td>
		<td width="20%" align="center">
			{if $msg == "#HPB#"}
				<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=outbox" class="admin-link">
			{else}
				<a href="?action=admin_viewmessage&type=outbox&id={$message[record].id}&from=message" class="admin-link">
			{/if}
				Read now</a>
			</td>
			<td width="16%" align="center"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>			
		</tr>
	{/if}
	{/section}
<tr>
	<td colspan="6" height="10px"></td>
</tr>
<tr>
	<td align="left" colspan="6">{paginate_prev} {paginate_middle} {paginate_next}</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td colspan="2" height="20px"></td>
</tr>
<tr>
	<td><b>{#Marked_mails#}  :</b></td>
	<td align="right">
	<input id="delete_button" name="delete_button" onclick="return confirm('Möchtest du die ausgewählten Nachrichten wirklich löschen?')" type="submit" class="button" value="Delete">
	<input id="back_button" name="back_button" onclick="history.go(-1)" type="button" class="button" value="Back">
</tr>			
</table>
</form>