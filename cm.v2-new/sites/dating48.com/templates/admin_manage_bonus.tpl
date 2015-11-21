<!-- {$smarty.template} -->
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<h1 class="admin-title">{#MANAGE_BONUS#}</h1>

	{if (count($userrec)>0)}
	<h2 style="display:block; background:#999; padding:10px; color:#000; font-weight:bold;">TOP {$limit_number}</h2>
	<div class="result-box-inside">
		<table width="100%"  border="0" cellpadding="10" cellspacing="1">
			<tr bgcolor="#718993" height="28px">
				<td align="center" class="text-title"><a href="#" class="sitelink">Username</a></td>
				<td align="center" width="160" class="text-title"><a href="#" class="sitelink">Registered On</a></td>
				<td align="center" width="120" class="text-title"><a href="#" class="sitelink">City</a></td>
				<td align="center" width="70" class="text-title"><a href="#" class="sitelink">Country</a></td>
				<td align="center" width="90" class="text-title"><a href="#" class="sitelink">Spent Coins</a></td>
				<td align="center" width="90" class="text-title"><a href="#" class="sitelink">Balance</a></td>
				<td align="center" width="80" class="text-title"><a href="#" class="sitelink">Action</a></td>
			</tr>
			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#fafafa,#dad9d9"}">
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>
				<td align="center">{$userdata.registred}</td>
				<td align="center">{$userdata.city}</td>
				<td width="20px"  align="center">
					{if $userdata.country eq "Germany"}
					DE
					{elseif $userdata.country eq "Switzerland"}
					CH
					{elseif $userdata.country eq "Austria"}
					AT
					{elseif $userdata.country eq "United Kingdom"}
					UK
					{elseif $userdata.country eq "Belgium"}
					BE
					{/if}
				</td>
				<td align="center">{$userdata.spent_coin|number_format:0:".":","}</td>
				<td align="center">{$userdata.remain_coin|number_format:0:".":","}</td>
				<td align="center">
					<a href="?action=admin_manage_bonus_popup&user={$userdata.username}" rel="gb_page_center[400, 250]" class="admin-link">
						Add Bonus
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
	</div>
	{/if}

	{if (count($search_result)>0)}
	<h1>Search result</h1>
	<div class="result-box-inside">
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" class="text-title">Username</td>
				<td align="center" width="160" class="text-title">Registered On</td>
				<td align="center" width="120" class="text-title">City</td>
				<td align="center" width="70" class="text-title">Country</td>
				<td align="center" width="90" class="text-title">Spent Coins</td>
				<td align="center" width="90" class="text-title">Balance</td>
				<td align="center" width="80" class="text-title">Action</td>
			</tr>
			{foreach key=key from=$search_result item=userdata}
			<tr  bgcolor="{cycle values="#006de0,#003873"}">
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>
				<td align="center">{$userdata.registred}</td>
				<td align="center">{$userdata.city}</td>
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
				<td align="center">{$userdata.spent_coin|number_format:0:".":","}</td>
				<td align="center">{$userdata.remain_coin|number_format:0:".":","}</td>
				<td align="center">
					<a href="?action=admin_manage_bonus_popup&user={$userdata.username}" rel="gb_page_center[400, 250]" class="link">
						Add Bonus
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
	</div>
	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
	{/if}
