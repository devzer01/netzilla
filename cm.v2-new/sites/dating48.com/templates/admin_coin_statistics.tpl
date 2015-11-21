<!-- {$smarty.template} -->
<h1 class="admin-title">{#COIN_STATISTICS#}</h1>
<div style="margin-top:10px;">
		{if (count($userrec)>0)}
		<table width="100%"  border="0" cellpadding="10" cellspacing="1">
			<tr bgcolor="#718993" height="28px">
				<td align="center" class="text-title"><a href="#" class="sitelink">{#USERNAME#}</a></td>
				
				<td align="center" width="160" class="text-title"><a href="#" class="sitelink">{#Registered#}</a></td>
				
				<td align="center" width="120" class="text-title"><a href="#" class="sitelink">{#City#}</a></td>
				
				<td align="center" width="70" class="text-title"><a href="#" class="sitelink">{#Country#}</a></td>
				
				<td align="center" width="90" class="text-title"><a href="#" class="sitelink">{#Spent_Coin#}</a></td>

				<td align="center" width="90" class="text-title"><a href="#" class="sitelink">{#Outstanding_coin#}</a></td>
				
				<td align="center" width="80" class="text-title"><a href="#" class="sitelink">{#Action_Col#}</a></td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#fafafa,#dad9d9"}">
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="admin-link">{$userdata.username}</a></td>

				<td align="center">{$userdata.registred}</td>

				<td align="center">{$userdata.city}</td>
				<td align="center">
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
					<a href="?action=admin_coin_statistics_details&user={$userdata.username}&r={$period}" class="admin-link">
						{#View_Details#}
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
		{else}
<p align="center">There are no coin statistics</p>
		{/if}


	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
