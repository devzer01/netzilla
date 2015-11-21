<a href="?action=viewprofile&username={$item.username}" class="profile-boder"></a>
<img src="thumbnails.php?file={$item.picturepath}&amp;w=108&amp;h=108" width="108" height="108" class="profile-img"/><p>{$item.username|regex_replace:"/@.*/":""}</p>
	{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
		{if !in_array($item.username, $favorites_list)}
			<a href="javascript:void(0)" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container', '{$style}');" class="q-icon q-fav"><span>fav</span></a>
		{/if}
	{elseif $item.username ne $smarty.session.sess_username}
		<a href="javascript:void(0)" onclick="removeFavorite('{$item.username}','favorite-list-container', '{$style}'); return false;" class="q-icon q-del-left"><span>fav</span></a>
	{/if}
	<a href="?action=chat&username={$item.username}" class="q-icon q-right q-chat"><span>fav</span></a>
