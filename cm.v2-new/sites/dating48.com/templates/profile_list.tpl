<ul id="container-profile-list">
	{foreach from=$result item="item"}
	{if $item.username}
    <li>
	{include file=profile_item.tpl nofavorite=$nofavorite style=$style}
    </li>
	{/if}
	{/foreach}
</ul>
{if $paginate eq 'true'}
<br class="clear"/>
<div class="page">{paginate_prev onclick="return page(this)" class="pre-pager"} {paginate_middle onclick="return page(this)" class="num-pager"} {paginate_next onclick="return page(this)" class="next-pager"}</div>
{/if}