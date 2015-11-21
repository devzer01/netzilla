<a href="?action=viewprofile&username={$item.username}" class="profile-border"></a>
<img src="thumbnails.php?file={$item.picturepath}&amp;w=106&amp;h=108" width="105" height="105" class="profile-img"/><span>{$item.username|regex_replace:"/@.*/":""}</span>
{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
	{if !in_array($item.username, $favorites_list)}
		<a href="javascript:void(0)" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container', '{$style}');" class="q-icon-l fav"><span>Fav</span></a>
	{/if}
{elseif $item.username ne $smarty.session.sess_username}
	<a href="javascript:void(0)" onclick="removeFavorite('{$item.username}','favorite-list-container', '{$style}'); return false;" class="q-icon-l del-l"><span>Fav</span></a>
{/if}
<a href="?action=chat&username={$item.username}" class="q-icon-r chat"><span>Chat</span></a>
