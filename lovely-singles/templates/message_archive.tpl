{if ($mymessage_total > 0)}
<form id="message_archive_form" name="message_archive_form" method="post" action="">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">			
		<tr bgcolor="#b6b6b6" height="28">
			<th width="8"></th>
			<th align="left" width="135" class="text-title">{#From#}:</th>
			<th align="left" class="text-title">{#Subject#}:</th>
			<th align="left" width="115" class="text-title">{#Date#}:</th>
			<th align="left" width="30" class="text-title"><a href="javascript:selectAll('message_archive_form','messageid')" class="sitelink">{#All#}:</a></th>
			<th align="left" width="5"></th>
		</tr>
	</table>
	
	<table border="0" cellpadding="0" cellspacing="1" width="100%">
		{section name="record" loop="$message"}
		<tr  bgcolor="{cycle values="#663333,#996666"}">
			<td width="125"  valign="middle" align="left" style="padding-left:10px;" height="25">
				{if ($message[record].username == 'System Admin')}
					{$message[record].username}
				{else}
					<a href="?action=viewprofile&username={$message[record].username}" class="link-inrow">
						{$message[record].username}
					</a>
				{/if}
			</td>
			<td align="left" style="padding-left:10px;">
				<a href="?action=viewmessage&type=archive&id={$message[record].id}" class="link-inrow">
					{$message[record].subject|truncate:45:"..."}
				</a>
			</td>
			<td width="105" align="center">
				{$message[record].datetime|date_format:"%D"}
			</td>
			<td width="50" align="center">
				<input id="messageid" name="messageid[]" type="checkbox" value="{$message[record].id}">
			</td>
		</tr>
		{/section}
	</table>

	<div class="pagein">{paginate_prev} {paginate_middle} {paginate_next}</div>

	<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="text12grey"><b>{#Marked_mails#}:</b></td>
			<td align="right" width="100"><input id="delete_button" name="delete_button" onclick="return confirm('{#delete_comfirm_msg#}')" type="submit" class="button" value="{#Delete#}"></td>
		</tr>			
	</table>	
</form>
{else}
	<p align="center" style="padding-top:10px">{#No_Message_Archiv#}</p>
{/if}