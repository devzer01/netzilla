<!-- {$smarty.template} -->
<div class="result-box">
	<h1>{$user}'s {#Stat_Popup_Title#}</h1>
	<div class="result-box-inside-nobg">
		{if (count($userrec)>0)}
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="160" class="text-title">{#Date_Col#}</td>
				
				<td align="center" class="text-title">{#Item_Col#}</td>
				
				<td align="center" width="120" class="text-title">{#Sendto_Col#}</td>
				
				<td align="center" width="90" class="text-title">{#Spent_Coin#}</td>

				<td align="center" width="90" class="text-title">{#Outstanding_coin#}</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#006de0,#003873"}">
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
		<a class="butregisin" href="?action=admin_coin_statistics&r={$period}" style="float:right">Back</a>
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>