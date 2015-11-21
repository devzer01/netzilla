<div class="container-buttom-bg">
	<div style="background:#00d0ba; float:left; width:100%; margin-top:100px;">
		<div id="bottom-display" class="container-newest">
		{if $smarty.get.action eq 'profile'}
			{include file="my_favorite.tpl"}
		{elseif $smarty.get.action eq 'search'}
			{include file="search_result_box.tpl"}
		{elseif $smarty.get.action eq 'chat'}
			{include file="my_favorite.tpl"}
		{elseif $smarty.get.action eq 'pay-for-coins'}
			{include file="my_favorite.tpl"}
		{elseif $smarty.get.action eq 'payment'}
			{include file="my_favorite.tpl"}
		{else}
			{include file="newest_members_box.tpl" total="12"}
		{/if}
		</div>
	</div>
</div>
<br class="clear" />