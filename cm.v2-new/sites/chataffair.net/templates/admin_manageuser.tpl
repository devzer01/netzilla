<!-- {$smarty.template} -->
<h1 class="admin-title">{#MANAGE_USER#}</h1>
<div class="result-box">
<div class="result-box-inside">
<a href="?action=admin_adduser" class="btn-admin">{#Add_New_User#}</a>
<br class="clear" /><br class="clear" />

{if $userrec}
<table width="100%"  border="0">
<tr bgcolor="#2d2d2d" height="28px">
	<td width="20" align="center" valign="middle">
	{if $smarty.get.order eq ""}
	{if $smarty.get.type eq "asc"}
	<a href="?action=admin_manageuser&order=&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink"><img src="images/s_desc.png" border="0"></a>
	{else}
	<a href="?action=admin_manageuser&order=&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink"><img src="images/s_asc.png" border="0"></a>
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">#</a>
	{/if}
	</td>
	<!-- /////<td  align="center">Type</td>-->
	<td align="center" width="100">
	{if $smarty.get.order eq "name"}
	    {if $smarty.get.type eq "desc"}
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a> <img src="images/s_desc.png">
	    {else}
		<a href="?action=admin_manageuser&order=name&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a> <img src="images/s_asc.png">
	    {/if}
	{else}
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a>
	{/if}
	</td>

	<td align="center" valign="middle" width="100">
	{if $smarty.get.order eq "registred"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=registred&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Registered#}</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=registred&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Registered#}</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=registred&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Registered#}</a>
	{/if}
	</td>							

	<td align="center" valign="middle" width="90">
	{if $smarty.get.order eq "city"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=city&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#City#}</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=city&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#City#}</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=city&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#City#}</a>
	{/if}
	</td>
	<td align="center" valign="middle" width="100">
	{if $smarty.get.order eq "country"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=country&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Country#}</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=country&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Country#}</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=country&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Country#}</a>
	{/if}
	</td>
	<td align="center" valign="middle" width="80">
	{if $smarty.get.order eq "flag"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=flag&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Edit#}</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=flag&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Edit#}</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=flag&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#Edit#}</a>
	{/if}
	</td>

	<td align="center" valign="middle" width="45"><a href="#" class="sitelink">Action</a></td>
</tr>
{foreach key=key from=$userrec item=userdata}
<tr  bgcolor="{cycle values="#dbdfe2,#fff"}">
	<td  align="center" valign="middle">{if $userdata.picturepath ne ""}<img src="images/has_pic.png">{/if}</td>
	
	<td width="100" align="center" valign="middle"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a>{if ($userdata.agent) and ($userdata.fake)}<br/><font color="yellow">[{$userdata.agent}]{/if}</font></td>
	<td width="100px"  align="center" valign="middle">{$userdata.registred}</td>
	<td width="90px"  align="center" valign="middle">{$userdata.city}</td>
<!--	<td width="100px">{$userdata.state}</td>  -->
	{if $userdata.country eq Germany}
	<td width="20px"  align="center" valign="middle">DE</td>				
	{elseif $userdata.country eq Switzerland}
	<td width="20px"  align="center" valign="middle">CH</td>
	{elseif $userdata.country eq Austria}
	<td width="20px"  align="center" valign="middle">AT</td>							
	{elseif $userdata.country eq "United Kingdom"}
	<td width="20px"  align="center" valign="middle">UK</td>							
	{elseif $userdata.country eq "Belgium"}
	<td width="20px"  align="center" valign="middle">BE</td>
	{else}
	<td width="20px"  align="center" valign="middle"></td>
	{/if}							
	{if $userdata.flag == 1}
	<td align="center"  align="center">Yes</td>
	{else}
	<td align="center">No</td>
	{/if}							
	<td width="45">
		<div align="center">
		<a href="?action=viewprofile&username={$userdata.username}&proc=edit&from=admin#editprofile" onclick="showEditProfile('{$userdata.username}'); return false;" title="Edit">
		<img src="images/icon/b_edit.png" width="16" height="16" border="0"></a>
		{if $smarty.session.sess_permission eq 1}
			{if $userdata.status != 1}
			<a href="?action=admin_manageuser&user={$userdata.username}&proc=del" onclick="return confirm(confirm_delete_box)" title="Delete">
			<img src="images/icon/b_drop.png" width="16" height="16" border="0">
			</a>
			{else}
			<img src="images/icon/b_drop_disable.png" width="16" height="16">
			{/if}

			{if $userdata.status != 1}
			<a href="?action=admin_manageuser&user={$userdata.username}&proc=block" onclick="return confirm('Are you sure to block this member?')" title="Block">
			<img src="images/icon/b_drop_block.png" width="16" height="16" border="0">
			</a>
			{else}
			<img src="images/icon/b_drop_disable.png" width="16" height="16">
			{/if}

			{if $userdata.vcode_mobile_insert_time != 0}
			<a href="?action=admin_manageuser&user={$userdata.username}&proc=resetphone" onclick="return confirm('Are you sure to reset this member mobile phone verification?')" title="Reset phone">
			<img src="images/icon/reset_icon.png" width="16" height="16" border="0">
			</a>
			{/if}

			<a href="?action=admin_manageuser&user={$userdata.username}&proc=sendcoins&coins=" onclick="return sendcoins(this, '{$userdata.username}');" title="Send coins">
            <img src="images/icon/coins.png" width="16" height="16" border="0">
            </a>
		{/if}
		</div>
	</td>
</tr>
{/foreach}
</table>
</div>
</div>
<div class="page">{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}</div>
{/if}

<script>
{if $admin_manageuser_error}
alert('{$admin_manageuser_error}');
{/if}
{literal}
function sendcoins(obj, username)
{
	var coins = prompt('How many coins you want to send to '+username+'?');

	if (coins!=null && coins!="")
	{
		var url = jQuery(obj).attr('href')
		jQuery(obj).attr('href', url+coins);
		return true;
	}
	else
	{
		return false;
	}
}

function showEditProfile(username)
{
	var url = "?action={/literal}{$smarty.get.action}{literal}&proc=getProfile&username="+username;
	loadPagePopup(url, '100%');
}
{/literal}
</script>