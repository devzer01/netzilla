<!-- {$smarty.template} -->
<h1 class="admin-title">Copy users</h1>
<div class="result-box">
<div class="result-box-inside">
<form>
	Server: 
	<select>
		<option name="server" value="4">Lovely-singles.com</option>
		<option name="server" value="2">Yourbuddy24.com</option>
	</select><br/>
	Age:
	<select>
		{for $from=18 to 50}
		<option name="age_from" value="{$from}">{$from}</option>
		{/for}
	</select>
	To
	<select>
		{for $from=18 to 50}
		<option name="age_to" value="{$from}">{$from}</option>
		{/for}
	</select> To
</form>
<br class="clear" /><br class="clear" />

<table width="100%"  border="0">
<tr bgcolor="#2d2d2d" height="28px">
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
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a> <img src="images/s_desc.png">
	    {else}
		<a href="?action=admin_manageuser&order=name&type=desc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a> <img src="images/s_asc.png">
	    {/if}
	{else}
		<a href="?action=admin_manageuser&order=name&type=asc&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">{#USERNAME#}</a>
	{/if}
	</td>

	<td align="center" width="100">
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

	<td align="center" width="90">
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
	<td align="center" width="100">
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
	<td align="center" width="80">
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


	<td align="center" width="45"><a href="#">{#Edit#}</a></td>
	{if $smarty.session.sess_permission eq 1}
	<td align="center" width="45"><a href="#">{#Delete#}</a></td>
	<td align="center" width="45"><a href="#">Block</a></td>
	<td align="center" width="45"><a href="#">Reset phone</a></td>
	{/if}
</tr>
{foreach key=key from=$userrec item=userdata}
<tr  bgcolor="{cycle values="#ccb691,#fde6be"}">
	<td  align="center">{if $userdata.picturepath ne ""}<img src="images/has_pic.png">{/if}</td>
	
	<td width="100" align="center"><a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a>{if ($userdata.agent) and ($userdata.fake)}<br/><font color="yellow">[{$userdata.agent}]{/if}</font></td>
	<td width="100px"  align="center">{$userdata.registred}</td>
	<td width="90px"  align="center">{$userdata.city}</td>
<!--	<td width="100px">{$userdata.state}</td>  -->
	{if $userdata.country eq Germany}
	<td width="20px"  align="center">DE</td>				
	{elseif $userdata.country eq Switzerland}
	<td width="20px"  align="center">CH</td>
	{elseif $userdata.country eq Austria}
	<td width="20px"  align="center">AT</td>							
	{elseif $userdata.country eq "United Kingdom"}
	<td width="20px"  align="center">UK</td>							
	{elseif $userdata.country eq "Belgium"}
	<td width="20px"  align="center">BE</td>
	{else}
	<td width="20px"  align="center"></td>
	{/if}							
	{if $userdata.flag == 1}
	<td align="center"  align="center">Yes</td>
	{else}
	<td align="center">No</td>
	{/if}							
	<td width="45">
		<div align="center">
		<a href="?action=profile&user={$userdata.username}&proc=edit&from=admin#editprofile">
		<img src="images/icon/b_edit.png" width="16" height="16" border="0"></a> </div>
	</td>
	{if $smarty.session.sess_permission eq 1}
	<td width="45">
		<div align="center">
		{if $userdata.status != 1}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=del&page={$smarty.get.page}" onclick="return confirm(confirm_delete_box)">
		<img src="images/icon/b_drop.png" width="16" height="16" border="0">
		</a>
		{else}
		<img src="images/icon/b_drop_disable.png" width="16" height="16">
		{/if}
		</div>
	</td>
	<td width="45">
		<div align="center">
		{if $userdata.status != 1}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=block&page={$smarty.get.page}" onclick="return confirm('Are you sure to block this member?')">
		<img src="images/icon/b_drop_block.png" width="16" height="16" border="0">
		</a>
		{else}
		<img src="images/icon/b_drop_disable.png" width="16" height="16">
		{/if}
		</div>
	</td>
	<td width="45">
		<div align="center">
		{if $userdata.vcode_mobile_insert_time != 0}
		<a href="?action=admin_manageuser&user={$userdata.username}&proc=resetphone&page={$smarty.get.page}" onclick="return confirm('Are you sure to reset this member mobile phone verification?')">
		<img src="images/icon/reset_icon.png" width="16" height="16" border="0">
		</a>
		{/if}
		</div>
	</td>
	{/if}
</tr>
{/foreach}
</table>
</div>
</div>
<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>