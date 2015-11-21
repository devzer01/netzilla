<a href="?action=viewprofile&username={$item.username}" class="profile-icon"></a>
<img src="thumbnails.php?file={$item.picturepath}&amp;w=110&amp;h=92" width="110" height="92" class="profile-img"/>
<p>{$item.username|regex_replace:"/@.*/":""}</p>
<a href="?action=chat&username={$item.username}" class="quick-icon q-left q-chat"><span>chat</span></a>
{if $smarty.session.sess_mem eq 1}
	{if $style eq '2'}
		<a href="javascript:void(0)" onclick="removeFavorite('{$item.username}','favorite-list-container', {$style}); return false;"></a>
	{else}
		{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
			{if !in_array($item.username, $favorites_list)}
			<a href="javascript:void(0)" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container');" class="quick-icon q-right q-fav"><span>fav</span></a>
			{else}
			<!--ALIGN ICON HERE-->
			<!-- <div class="fav"><img src="images/cm-theme/icon-fav-g.png"/></div> -->
			{/if}
		{elseif $item.username ne $smarty.session.sess_username}
			<a href="javascript:void(0)" class="quick-icon q-right q-del" title="{#Delete#}" onclick="return removeFavorite('{$item.username}','favorite-list-container')"></a>
		{/if}
	{/if}
{/if}
