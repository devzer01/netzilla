<!-- {$smarty.template} -->
<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />
<h2>{#MANAGE_BONUS#}</h2>
<div class="result-box">
	
	<div class="result-box-inside">
		<table width="100%"  border="0">
			<tr bgcolor="#b6b6b6" height="28px">
				<td  align="center">Type</td>
				
				<td align="center" width="100" class="text-title">{#USERNAME#}</td>
				
				<td align="center" width="150" class="text-title">{#Registered#}</td>
				
				<td align="center" width="150" class="text-title">{#City#}</td>
				
				<td align="center" width="80" class="text-title">{#Country#}</td>
				
				<td align="center" width="100" class="text-title">{#Total_coin#}</td>
				
				<td align="center" width="90" class="text-title">{#Edit#}</td>
			</tr>

			{foreach key=key from=$userrec item=userdata}
			<tr  bgcolor="{cycle values="#006de0,#003873"}">
				<td align="center">
					{if $userdata.type eq 2}
						<img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}><img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}><img src="images/gold.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}>
					{elseif $userdata.type eq 3}
						<img src="images/silber.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}><img src="images/silber.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}>
					{elseif $userdata.type eq 4}
						<img src="images/bronze.gif" border="0" width="15"{if $userdata.end_date} alt="Gltig bis: {$userdata.end_date}"{/if}>
					{else}
						<strong>A</strong>
					{/if}
				</td>

				<td align="center">&nbsp;&nbsp;&nbsp;<a href="?action=viewprofile&username={$userdata.username}&from=admin" class="link">{$userdata.username}</a></td>

				<td align="center">{$userdata.registred}</td>

				<td align="center">{$userdata.city}</td>

				{if $userdata.country eq Deutschland}
					<td align="center">DE</td>				
				{elseif $userdata.country eq Schweiz}
					<td align="center">CH</td>
				{else}
					<td align="center">AT</td>							
				{/if}
				
				<td align="center">{$userdata.spend_coin}</td>	
				
				<td align="center">
					<a href="?action=admin_manage_bonus_popup&user={$userdata.username}&proc=add_bonus&from=admin" class="link">
						Add Bonus
					</a>
				</td>
			</tr>
			{/foreach}
		</table>
	</div>

	<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
</div>