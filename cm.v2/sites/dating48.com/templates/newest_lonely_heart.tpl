<h1 class="title">{#newest_lonely_heart#}</h1>
<ul id="container-profile-list">
	{foreach item="item" from=$FLonelyHeart}
	<li>
	{include file=profile_item.tpl}
	</li>
    {/foreach}
    
    {foreach item="item" from=$MLonelyHeart}
	<li>
	{include file=profile_item.tpl}
	</li>
    {/foreach}
</ul>