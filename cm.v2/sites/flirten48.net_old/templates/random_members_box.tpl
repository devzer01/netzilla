<h1 class="title">Kontaktvorschläge</h1>
<ul id="container-profile-list">
	{foreach item="item" from=$random_contacts}
    <li>
		{include file=profile_item.tpl}
	</li>
    {/foreach}
</ul>
