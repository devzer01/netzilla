<!-- {$smarty.template} -->
<div class="result-box">
<h1>Already copied profiles ({if $countMember}{$countMember}{else}0{/if})</h1>
<div class="result-box-inside-nobg">

{if $userrec|count}
<table width="100%"  border="0">
<tr bgcolor="#2d2d2d" height="28px">
	<td width="50" align="center">
		<a href="?action={$smarty.get.action}&order=&type={if $smarty.get.type eq "asc"}desc{else}asc{/if}&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">
			#
			{if $smarty.get.order eq ""}
			<img src="images/{if $smarty.get.type eq "asc"}s_desc.png{else}s_asc.png{/if}" border="0"/>
			{/if}
		</a>
	</td>
	<td align="center" width="100">
		<a href="?action={$smarty.get.action}&order=name&type={if $smarty.get.type eq "asc"}desc{else}asc{/if}&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">
			Username
			{if $smarty.get.order eq "name"}
			<img src="images/{if $smarty.get.type eq "asc"}s_desc.png{else}s_asc.png{/if}" border="0"/>
			{/if}
		</a>
	</td>

	<td align="center" width="100">
		<a href="?action={$smarty.get.action}&order=registred&type={if $smarty.get.type eq "asc"}desc{else}asc{/if}&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">
			Registered
			{if $smarty.get.order eq "registred"}
			<img src="images/{if $smarty.get.type eq "asc"}s_desc.png{else}s_asc.png{/if}" border="0"/>
			{/if}
		</a>
	</td>

	<td align="center" width="90">
		<a href="?action={$smarty.get.action}&order=city&type={if $smarty.get.type eq "asc"}desc{else}asc{/if}&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">
			City
			{if $smarty.get.order eq "city"}
			<img src="images/{if $smarty.get.type eq "asc"}s_desc.png{else}s_asc.png{/if}" border="0"/>
			{/if}
		</a>
	</td>
	<td align="center" width="100">
		<a href="?action={$smarty.get.action}&order=country&type={if $smarty.get.type eq "asc"}desc{else}asc{/if}&g={$smarty.get.g}&lg={$smarty.get.lg}&f={$smarty.get.f}&co={$smarty.get.co}&s={$smarty.get.s}&ci={$smarty.get.ci}&u={$smarty.get.u}" class="sitelink">
			Country
			{if $smarty.get.order eq "country"}
			<img src="images/{if $smarty.get.type eq "asc"}s_desc.png{else}s_asc.png{/if}" border="0"/>
			{/if}
		</a>
	</td>
	{if $smarty.session.sess_permission eq 1}
	<td align="center" width="45"><a href="#">Action</a></td>
	{/if}
</tr>
{foreach key=key from=$userrec item=userdata}
<tr  bgcolor="{cycle values="#ccb691,#fde6be"}">
	<td  align="center">{if $userdata.picturepath ne ""}<img src="thumbnails.php?file=../thumbs_temp/{$userdata.picturepath}&w=50&h=50">{/if}</td>
	<td width="100" align="center">{$userdata.username}{*if ($userdata.agent) and ($userdata.fake)}<br/><font color="yellow">[{$userdata.agent}]{/if*}</font></td>
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
		{else}
		{/if}
	</td>
	{if $smarty.session.sess_permission eq 1}
	<td width="45">
		<div align="center">
			{if !$userdata.copied}
			<a href="?action={$smarty.get.action}&user={$userdata.username}&proc=copy" onclick="return confirm('Copy?')">
				<img src="images/icon/checked.png" width="16" height="16" border="0" title="Copy">
			</a>
			{/if}
			<a href="?action={$smarty.get.action}&user={$userdata.username}&proc=del" onclick="return confirm(confirm_delete_box)">
				<img src="images/icon/b_drop.png" width="16" height="16" border="0" title="Delete">
			</a>
		</div>
	</td>
	{/if}
</tr>
{/foreach}
</table>
<div class="page">{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}</div>
<br class="clear"/>
<a href="?action={$smarty.get.action}&proc=delete-unconnected" class="btn-admin" onclick="return confirm('Delete all unconnected profiles?')">Delete unconnected</a>
<a href="?action={$smarty.get.action}&proc=copyall" class="btn-admin" onclick="return confirm('Copy all?')">Copy all profiles</a>
{/if}
</div>
</div>
