<h1 class="title">Newest members</h1>
<ul id="container-profile-list">
	{foreach item="item" from=$NewestMembers}
    <li>
		{include file=profile_item.tpl}
	</li>
    {/foreach}
</ul>
