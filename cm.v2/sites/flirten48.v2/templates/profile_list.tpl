<ul id="container-profile-list" class="container-profile">
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
	<ul class="container-next-page">
		<li>{paginate_prev onclick="return page(this)" class="pre-pager"}</li> 
		{paginate_middle onclick="return page(this)" class="num-pager" link_prefix="<li>" link_suffix="</li>" current_prefix="<li><a href='javascript:;' class='select'>" current_suffix="</a></li>"} 
		<li>{paginate_next onclick="return page(this)" class="next-pager"}</li>
	</ul>
	<br class="clear" />
{/if}