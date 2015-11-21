<ul id="list" class="portfolio_list da-thumbs">
	{foreach from=$result item="item"}
		{if $item.username}
			{include file=profile_item.tpl nofavorite=$nofavorite style=$style}
		{/if}
	{/foreach}
</ul>
{if $paginate eq 'true'}
	<br class="clear"/>
	<div class="page">
		{paginate_prev onclick="return page(this)" class="pre-pager"} {paginate_middle onclick="return page(this)" class="num-pager"} {paginate_next onclick="return page(this)" class="next-pager"}
	</div>
{/if}

<script type="text/javascript">
{literal}
	jQuery(function() {
		jQuery('ul.da-thumbs > li').hoverdir();
	});
{/literal}
</script>