<!-- {$smarty.template} -->
<form id="message_inbox_form" name="message_inbox_form" method="post" action="">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="border:solid 1px;">			
<tr bgcolor="#2d2d2d" height="28px">
	<td width="5%" class="text-title">&nbsp;</td>
	<td width="19%" class="text-title"><a href="#">{#From#}:</a></td>
	<td width="28%" class="text-title"><a href="#">{#Subject#}:</a></td>
	<td width="17%" class="text-title"><a href="#">{#Date#}:</a></td>
	<td width="15%" class="text-title"><a href="#">&nbsp;</a></td>
	<td width="10%" class="text-title"><a href="#">{#Reply#}:</a></td>
	<td width="16%"><a href="javascript:selectAll('message_inbox_form','messageid')">{#All#}:</a></td>	
</tr>
</table>
<table width="100%" cellpadding="2" cellspacing="1" border="0">
	{section name="record" loop="$message"} 
	{assign var="msg" value=$message[record].message|truncate:5:""}
	{assign var="id" value=$message[record].message|replace:"#HPB#":""} 
{if $message[record].status eq 0}
	<tr bgcolor="{cycle values="#ccb691,#fde6be"}" height="25px">
		<td width="5%" align="center">
			<img src="images/icon/i_new.gif" height="16" width="16">
		</td>
		<td width="19%" style="padding-left:10px;"><span id ="from"><a href="?action=viewprofile&id={$message[record].id}&username={$message[record].username}&from=admin" class="admin-link">{$message[record].username}</a></span></td>
		<td width="28%" style="padding-left:10px;"><a href="./?action=admin_viewmessage&type=inbox&id={$message[record].id}" class="admin-link">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="17%" align="center" >{$message[record].datetime|date_format:"%D %T"}</td>
		<td width="15%" align="center" valign="middle">
			{if $msg == "#HPB#"}
				<a href="./?action=viewcard&id={$id}&m_id={$message[record].id}&type=inbox" class="admin-link">Read now</a>
			{else}
				<a href="./?action=admin_viewmessage&type=inbox&id={$message[record].id}&from=message" class="admin-link">Read now</a>
			{/if}
		</td>
		<td width="10%" align="center">
			{if $message[record].reply eq 1}
				{#Yes#}
			{else}
				{#No#}
			{/if}
		</td>
		<td  width="16%" align="center"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>		
	</tr>
{else}
	<tr bgcolor="{cycle values="#ccb691,#fde6be"}">
		<td width="5%" align="center">
			<img src="images/icon/i_read.gif" height="16" width="16">
		</td>
		<td width="19%" style="padding-left:10px;"><span id ="from"><a href="?action=viewprofile&id={$message[record].id}&username={$message[record].username}&from=admin" class="admin-link">{$message[record].username}</a></span></td>
		<td width="28%" style="padding-left:10px;"><a href="?action=admin_viewmessage&type=inbox&id={$message[record].id}" class="admin-link">{$message[record].subject|truncate:45:"..."}</a></td>
		<td width="17%" align="center">{$message[record].datetime|date_format:"%D %T"}</td>
		<td width="15%" align="center" valign="middle">
			{if $msg == "#HPB#"}
				<a href="?action=viewcard&id={$id}&m_id={$message[record].id}&type=inbox" class="admin-link">
					Read now
				</a>
			{else}
				<a href="?action=admin_viewmessage&type=inbox&id={$message[record].id}&from=message" class="admin-link">
					Read now
				</a>
			{/if}				
		</td>
		<td width="10%" align="center">
			{if $message[record].reply eq 1}
				{#Yes#}
			{else}
				{#No#}
			{/if}
		</td>
		<td width="316%" class="text-title" align="center"><input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}"></td>		
	</tr>
{/if}
	{/section}

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
	
	<input id="reply_button" name="reply_button" type="submit" value="Reply" onClick="adminReplyMessage(this.form.id)" class="button" >
	<input id="delete_button" name="delete_button" onclick="return confirm('{#delete_comfirm_msg#}')" type="submit"  class="button" value="Delete">
	<input id="back_button" name="back_button" onclick="history.go(-1)" type="button" class="button" value="Back">
	</td>
</tr>			
</table>
</form>