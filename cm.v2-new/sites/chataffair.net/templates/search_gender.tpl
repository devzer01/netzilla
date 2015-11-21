<h1 class="title">{#search#}</h1>
<ul id="container-profile-list">
	{foreach item="item" from=$result}
    <li>
		{include file=profile_item.tpl}
	</li>
    {/foreach}
</ul>
<br class="clear"/>

<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>