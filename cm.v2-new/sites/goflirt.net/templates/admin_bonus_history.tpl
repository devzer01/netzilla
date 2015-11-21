<!-- {$smarty.template} -->
<h1 class="admin-title">{#BONUS_HISTORY#}</h1>
<div style="margin-top:10px;">
		{if (count($userrec)>0)}
		<table width="100%"  border="0">
			<tr bgcolor="{$table_bg_top}" height="28px">
				<td align="center" width="160" class="text-title">{#Date_Col#}</td>
				
				<td align="center" class="text-title">{#USERNAME#}</td>
				
				<td align="center" width="120" class="text-title">{#Coin_Unit#}</td>
				
				<td align="center" width="120" class="text-title">{#Status_Col#}</td>

				<td align="center" width="160" class="text-title">{#Verify_Date_Col#}</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="$table_bg_1,$table_bg_2"}">
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

	<div class="page">{paginate_prev class="pre-pager"} {paginate_middle class="num-pager"} {paginate_next class="next-pager"}</div>
