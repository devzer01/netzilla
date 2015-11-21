<!-- {$smarty.template} -->
<h1 class="admin-title">Newest Members</h1>
<div style="margin-top:10px;">
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
	{/if}
	<tr>
		<td align="center">
		{if $userrec}
			<table width="100%"  border="0" cellspacing="1" cellpadding="3">
			<tr bgcolor="#718993" height="28px">
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
				<td align="center" width="120"><a href="#" class="sitelink">Mobile Phone Number</a></td>
                        
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
				<td align="center" width="45"><a href="#" class="sitelink">{#Edit#}</a></td>
				<!--<td align="center"></td>-->
			</tr>
                        
			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#fafafa,#dad9d9"}" height="28px">
				<td align="center">{if $userdata.picturepath ne ""}<img src="thumbnails.php?file={$userdata.picturepath}&w=100&h=100" width="100" height="100">{/if}</td>
				
				<td align="center"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>
				<td align="center">{$userdata.mobileno}</td>
				<td align="center">{$userdata.country}</td>
				<td align="center">
					<a href="?action=editprofile&user={$userdata.username}&proc=edit&from=admin&tool=new_members">
					<img src="images/icon/b_edit.png" width="16" height="16" border="0"></a>
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
	</div>
{if $countMember > 0}
	<div class="page">{paginate_prev class="linklist"} {paginate_middle format="page" page_limit="5" class="linklist"} {paginate_next class="linklist"}&nbsp;</div>
{/if}
