<img border="0" src="thumbnails.php?file={$item.picturepath}&amp;w=102&amp;h=102" width="102" height="102" alt="{$item.username}"/>
<p>{$item.username|regex_replace:"/@.*/":""}</p>
<a href="?action=viewprofile&username={$item.username}" class="link-profile"></a>

{if $smarty.session.sess_mem eq 1}
	{if $style eq '2'}
		<a href="javascript:void(0)" class="q-left q-del" title="{#Delete#}" onclick="removeFavorite('{$item.username}','favorite-list-container', {$style}); return false;"></a>
	{else}
		{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
			{if !in_array($item.username, $favorites_list)}
				<a href="javascript:void(0)" class="q-left q-fav" title="Favorite" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container');"></a>
			{/if}
		{elseif $item.username ne $smarty.session.sess_username}
			<a href="javascript:void(0)" class="q-left q-del" title="{#Delete#}" onclick="return removeFavorite('{$item.username}','favorite-list-container')"></a>
		{/if}
		{if $item.username ne $smarty.session.sess_username}
			<a href="?action=chat&username={$item.username}" class="q-right q-chat" title="Message"></a>
		{/if}
	{/if}
{/if}