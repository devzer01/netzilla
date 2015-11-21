<!-- {$smarty.template} -->
<h1 class="admin-title">Newest Members</h1>
<div class="container-admin-cotent-box">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
	{if $smarty.get.r eq "search"}
	<form name="search_form" action="">
	<tr>
		<td align="center">
			<table width="80%">
			<tr>
				<td align="left">Username:</td>
				<td align="left">
				<script language="JavaScript" src="js/overlib_mini.js"></script>
				<script language="JavaScript" src="js/calendar.js"></script>
				<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
				<input type="hidden" name="action" value="admin_new_members">
				<input type="hidden" name="r" value="search">
				<input type="text" name="u" value="{$smarty.get.u}">
				</td>
				<td align="left">Last name:</td>
				<td align="left">
				<input type="text" name="l" value="{$smarty.get.l}">
				</td>
			</tr>
			<tr>
				<td align="left">Email:</td>
				<td align="left">
				<input type="text" name="e" value="{$smarty.get.e}">
				</td>
				<td align="left">Membership:</td>
				<td align="left">
				<select name="mt" style="width:145px">
				<option value="">-- All --</option>
				{html_options options=$type_box selected=$smarty.get.mt}
				</select>
				</td>
			</tr>
			<tr>
				<td align="left">From:</td>
				<td align="left">
				<input type="text" name="from" value="{$smarty.get.from}" readonly> <a href="javascript:show_calendar('search_form.from');" onMouseOver="window.status='Date Picker'; overlib('Click here to choose a date.'); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="images/calendar_icon.gif" width="20" height="20" border="0"></a>
				</td>
				<td align="left">To:</td>
				<td align="left">
				<input type="text" name="to" value="{$smarty.get.to}" readonly> <a href="javascript:javascript:show_calendar('search_form.to');" onMouseOver="window.status='Date Picker'; overlib('Click here to choose a date.'); return true;" onMouseOut="window.status=''; nd(); return true;"><img src="images/calendar_icon.gif" width="20" height="20" border=0></a>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
				<input type="submit" value="Search" class="button">
				</td>
			</tr>
			</table>
		</td>
	</tr>
	</form>
	{elseif $userrec}
	<tr>
		<td>
			<a href="#" onclick="checkWithChatServer('', 'checkAllWithChatServer')" class="admin-link">Check all with chat server</a>
		</td>
	</tr>
	{/if}
	<tr>
		<td align="center">
		{if $userrec}
			<table width="100%"  border="0" cellspacing="1" cellpadding="3">
			<tr bgcolor="{$table_bg_top}" height="28px">
					<td width="40" align="center">
					{if $smarty.get.order eq ""}
					{if $smarty.get.type eq "asc"}
					<a href="?action=admin_new_members&order=&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}"><img src="images/s_desc.png" border="0"></a>
					{else}
					<a href="?action=admin_new_members&order=&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" ><img src="images/s_asc.png" border="0"></a>
					{/if}
					{else}
					<a href="?action=admin_new_members&order=&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">#</a>
					{/if}
					</td>
					<td align="center" width="120">
					{if $smarty.get.order eq "activated"}
					{if $smarty.get.type eq "desc"}
					<a href="?action=admin_new_members&order=activated&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Activated?</a> <img src="images/s_desc.png">
					{else}
					<a href="?action=admin_new_members&order=activated&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Activated?</a> <img src="images/s_asc.png">
					{/if}
					{else}
					<a href="?action=admin_new_members&order=activated&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Activated?</a>
					{/if}
					</td>
					<td align="center" width="120">
					{if $smarty.get.order eq "name"}
					{if $smarty.get.type eq "desc"}
					<a href="?action=admin_new_members&order=name&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#USERNAME#}</a> <img src="images/s_desc.png">
					{else}
					<a href="?action=admin_new_members&order=name&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#USERNAME#}</a> <img src="images/s_asc.png">
					{/if}
					{else}
					<a href="?action=admin_new_members&order=name&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#USERNAME#}</a>
					{/if}
					</td>
					<td align="center" class="text-title">Password</td>
					<td align="center" width="100">
					{if $smarty.get.order eq "signup"}
					{if $smarty.get.type eq "desc"}
					<a href="?action=admin_new_members&order=signup&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Registered</a> <img src="images/s_desc.png">
					{else}
					<a href="?action=admin_new_members&order=signup&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Registered</a> <img src="images/s_asc.png">
					{/if}
					{else}
					<a href="?action=admin_new_members&order=signup&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Registered</a>
					{/if}
					</td>

					<td align="center" width="100">
					{if $smarty.get.order eq "email"}
					{if $smarty.get.type eq "desc"}
					<a href="?action=admin_new_members&order=email&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Email</a> <img src="images/s_desc.png">
					{else}
					<a href="?action=admin_new_members&order=email&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Email</a> <img src="images/s_asc.png">
					{/if}
					{else}
					<a href="?action=admin_new_members&order=email&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">Email</a>
					{/if}
					</td>

					<td align="center" class="text-title" width="120">Mobile Phone Number</td>

					<td align="center" width="120">
					{if $smarty.get.order eq "country"}
					{if $smarty.get.type eq "desc"}
					<a href="?action=admin_new_members&order=country&type=asc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#Country#}</a> <img src="images/s_desc.png">
					{else}
					<a href="?action=admin_new_members&order=country&type=desc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#Country#}</a> <img src="images/s_asc.png">
					{/if}
					{else}
					<a href="?action=admin_new_members&order=country&tCountryasc&r={$smarty.get.r}&from={$smarty.get.from}&to={$smarty.get.to}&u={$smarty.get.u}&l={$smarty.get.l}&e={$smarty.get.e}" class="sitelink">{#Country#}</a>
					{/if}
					</td>
					<td align="center" width="100" class="text-title">Action</td>
				</tr>
                        
			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="$table_bg_1,$table_bg_2"}" height="28px">
					<td align="center">{if $userdata.picturepath ne ""}<img src="thumbnails.php?file={$userdata.picturepath}&w=50&h=50" height="50">{/if}</td>
					<td align="center">{if $userdata.isactive}Yes{else}{if $userdata.isactive_datetime}Banned{/if}{/if}</td>
					<td align="center"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>
					<td align="center">{$userdata.password}</td>
					<td align="center">{$userdata.registred}</td>
					<td align="center">{$userdata.email}</td>
					<td align="center">{$userdata.mobileno}</td>
					<td align="center">{$userdata.country}</td>
					<td align="center">
						{if $userdata.isactive}
							<a href="?action=admin_view_messages&username={$userdata.username}" class="admin-link">Messages</a>
							<span id='check_{$userdata.user_id}'>|| <a href="#" onclick="checkWithChatServer('{$userdata.user_id}', 'checkWithChatServer'); return false;"  class="admin-link">Check</a></span>
							<span id='sync_{$userdata.user_id}' style="display: none">|| <a href="#" onclick="checkWithChatServer('{$userdata.user_id}', 'syncWithChatServer'); return false;"  class="admin-link">Syncronize</a></span>
							<span id='resend_{$userdata.user_id}' style="display: none">|| <a href="#" onclick="checkWithChatServer('{$userdata.user_id}', 'resendNewRegistrationMessage'); return false;"  class="admin-link">resend</a></span>
						{/if}
					</td>
				</tr>
			{/foreach}
			</table>
			{else}
				There are no new registrations!
			{/if}			
			</td>
		</tr> 
		</table>

{if $countMember > 0}
	<div class="page">{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}</div>
{/if}
	</div>
<script>
{literal}
function checkWithChatServer(id, action)
{
	jQuery.ajax({ type: "POST", dataType: "json", url: "?action=admin_new_members&r={/literal}{$smarty.get.r}{literal}&do="+action, data: {'id': id}, success:(function(result){
		if(result)
		{
			alert(result.message);
			if(result.span_close)
			{
				jQuery('#'+result.span_close+"_"+result.id).hide();
			}
			if(result.span_open)
			{
				jQuery('#'+result.span_open+"_"+result.id).show();
			}
		}
	}) });
}
{/literal}
</script>