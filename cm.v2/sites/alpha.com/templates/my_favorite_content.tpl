<ul id="container-profile-list">
	{foreach from=$result item="item"}
    <li>
	{include file=profile_item.tpl nofavorite="true" style=$style}
    </li>
	{/foreach}
</ul>