<div class="result-box">
	<h1>{$user}'s Spending Record</h1>
	<div class="result-box-inside-nobg">
		{if (count($userrec)>0)}
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="160" class="text-title">Date</td>
				
				<td align="center" class="text-title">Item</td>
				
				<td align="center" width="120" class="text-title">Send To</td>
				
				<td align="center" width="90" class="text-title">Spent</td>

				<td align="center" width="90" class="text-title">Remain</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
				<td align="center">{$userdata.log_date}</td>

				<td align="center">{$userdata.coin_field}</td>

				<td align="center">{$userdata.send_to_user} {$userdata.mid}</td>

				<td align="center">{$userdata.coin|number_format:0:".":","}</td>	

				<td align="center">{$userdata.coin_remain|number_format:0:".":","}</td>
			</tr>
			{/foreach}
		</table>
		{else}
			No record for this bonus
		{/if}
		<a class="butregisin" href="?action=admin_coin_statistics&r={$period}">Back</a>
	</div>
	<br clear="all" />
	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>