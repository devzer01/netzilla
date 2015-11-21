<script src="js/jquery-ui-1.9.2.custom.js"></script>
<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.9.2.custom.css" />

<div class="result-box">
<h1>{#MANAGE_USER#}</h1>
<div class="result-box-inside-nobg">
<a href="?action=admin_adduser" class="button">Add new user</a>
<br /><br />

<table width="100%"  border="0">
<tr bgcolor="#b6b6b6" height="28px">
	<td width="50" align="center">
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
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Username</a> <img src="images/s_desc.png">
	    {else}
		<a href="?action=admin_manageuser&order=name&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Username</a> <img src="images/s_asc.png">
	    {/if}
	{else}
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Username</a>
	{/if}
	</td>

	<td align="center" width="100">
	{if $smarty.get.order eq "registred"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=registred&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Registered</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=registred&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Registered</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=registred&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Registered</a>
	{/if}
	</td>							

	<td align="center" width="90">
	{if $smarty.get.order eq "city"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=city&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">City</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=city&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">City</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=city&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">City</a>
	{/if}
	</td>
	<td align="center" width="50">
	{if $smarty.get.order eq "country"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=country&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Country</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=country&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Country</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=country&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Country</a>
	{/if}
	</td>
	<td align="center" width="30">
	{if $smarty.get.order eq "flag"}
	{if $smarty.get.type eq "desc"}
	<a href="?action=admin_manageuser&order=flag&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Edit</a> <img src="images/s_desc.png">
	{else}
	<a href="?action=admin_manageuser&order=flag&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Edit</a> <img src="images/s_asc.png">
	{/if}
	{else}
	<a href="?action=admin_manageuser&order=flag&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">Edit</a>
	{/if}
	</td>


	<td align="center" width="80" class="text-title">Action</td>
</tr>
{foreach key=key from=$userrec item=userdata}
<tr  bgcolor="{cycle values="#663333,#996666"}">
	<td  align="center">{if $userdata.picturepath ne ""}<img src="images/has_pic.gif">{/if}</td>
	<td width="100" align="center"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>
	<td width="100px"  align="center">{$userdata.registred}</td>
	<td width="90px"  align="center">{$userdata.city}</td>
				<td width="20px"  align="center">
					{if $userdata.country eq Germany}
					DE
					{elseif $userdata.country eq Switzerland}
					CH
					{elseif $userdata.country eq Austria}
					AT
					{elseif $userdata.country eq "United Kingdom"}
					UK
					{elseif $userdata.country eq "Belgium"}
					BE
					{/if}
				</td>
	<td align="center">{if $userdata.flag == 1}Yes{else}No</td>	
	{/if}							
	<td width="45">
		<div align="center">
		<a href="?action=editprofile&user={$userdata.username}&proc=edit&from=admin"><img src="images/icon/b_edit.png" width="16" height="16" border="0"></a>
	{if $smarty.session.sess_permission eq 1}
		{if $userdata.status != 1}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=del&page={$smarty.get.page}" onclick="return confirm('Please confirm delete?')"><img src="images/icon/b_drop.png" width="16" height="16" border="0"></a>
		{else}
		<img src="images/icon/b_drop_disable.png" width="16" height="16">
		{/if}

		{if $userdata.status != 1}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=block&page={$smarty.get.page}" onclick="return confirm('Are you sure to block this member?')"><img src="images/icon/b_drop_block.png" width="16" height="16" border="0"></a>
		{else}
		<img src="images/icon/b_drop_disable.png" width="16" height="16">
		{/if}

		{if $userdata.vcode_mobile_insert_time != 0}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=resetphone&page={$smarty.get.page}" onclick="return confirm('Are you sure to reset this member mobile phone verification?')"><img src="images/icon/reset_icon.png" width="16" height="16" border="0"></a>
		{/if}
		
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=sendcoins&coins=" onclick="return sendcoins(this);" title="Send coins"><img src="images/coins.png" width="16" height="16" border="0"></a>

		{if $userdata.fake}
		<a href="#" onclick="copyfake('{$userdata.username}'); return false;" title="Copy this profile"><img src="images/icon/copy.png" width="16" height="16" border="0"></a>
		{/if}
		</div>
	{/if}
	</td>
</tr>
{/foreach}
</table>
</div>

<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>
<div id="dialog" title="Copy to"></div>

<script>
{if $admin_manageuser_error}
alert('{$admin_manageuser_error}');
{/if}
{literal}
function sendcoins(obj)
{
	var coins = prompt('How many coins you want to send to {/literal}{$userdata.username}{literal}?');

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

function copyfake(username)
{
	var url = "?action=admin_manageuser&proc=copy_popup&username="+username;
	jQuery("#dialog").load(url).dialog({ width: 200 });
}

function hideCopyButton()
{
	jQuery("#copy_button").hide();
	jQuery("#copy_info").text("Please wait...");
}

function showCopyButton()
{
	jQuery("#copy_button").show();
	jQuery("#copy_info").text("");
}


function submitCopyProfileForm(username)
{
	jQuery.ajax({ type: "GET", url: "?action={/literal}{$smarty.get.action}{literal}&proc=copy&username="+username+"&site="+jQuery('#site').val(
	), success:(function(result){if(result){alert(result);} if(result=="FINISHED") jQuery('#dialog').dialog('close'); else showCopyButton();}) });
}
{/literal}
</script>