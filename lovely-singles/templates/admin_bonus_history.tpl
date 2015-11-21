<div class="result-box">
	<h1>BONUS HISTORY</h1>
	<div class="result-box-inside-nobg">
		{if (count($userrec)>0)}
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td align="center" width="160" class="text-title">Date</td>
				
				<td align="center" class="text-title">Username</td>
				
				<td align="center" width="120" class="text-title">Coin(s)</td>
				
				<td align="center" width="120" class="text-title">Status</td>

				<td align="center" width="160" class="text-title">Verified Date</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#663333,#996666"}">
				<td align="center">{$userdata.vcode_insert_time}</td>

				<td align="center">{$userdata.username}</td>

				<td align="center">{$userdata.coin_plus|number_format:0:".":","}</td>	

				<td align="center">{$userdata.status_text}</td>

				<td align="center">{$userdata.verify_time}</td>
			</tr>
			{/foreach}
		</table>
		{else}
			No record for this bonus
		{/if}
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>