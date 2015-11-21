<div class="result-box">
	<h1>COIN STATISTICS</h1>
	<div class="result-box-inside-nobg">
		{if (count($userrec)>0)}
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" class="text-title">Username</td>
				
				<td align="center" width="160" class="text-title">Registered</td>
				
				<td align="center" width="120" class="text-title">City</td>
				
				<td align="center" width="70" class="text-title">Country</td>
				
				<td align="center" width="90" class="text-title">Spent</td>

				<td align="center" width="90" class="text-title">Remain</td>
				
				<td align="center" width="80" class="text-title">Action</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>

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
					<a href="?action=admin_coin_statistics_details&user={$userdata.username}&r={$period}" class="link">
						View
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
		{else}
			<p align="center">There are no coin statistics</p>
		{/if}
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>