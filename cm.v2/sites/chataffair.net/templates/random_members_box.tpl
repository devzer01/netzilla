<h1 class="title">Kontaktvorschläge</h1>
<div id='newest-result-container' class="image_grid portfolio_4col">
<ul id="list" class="portfolio_list da-thumbs">
	{foreach item="item" from=$random_contacts}

		{include file=profile_item.tpl}

    {/foreach}
</ul>
</div>

<script type="text/javascript">
{literal}
	jQuery(function() {
		jQuery('ul.da-thumbs > li').hoverdir();
	});
{/literal}
</script>