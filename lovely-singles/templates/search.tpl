<table width="552" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
		{if $smarty.get.q_forsearch eq 1}
			{include file="search_profile.tpl"}
		{elseif $smarty.get.q_forsearch eq 2}
			{include file="search_ads.tpl"}
		{/if}
	</td>
</tr>
</table>