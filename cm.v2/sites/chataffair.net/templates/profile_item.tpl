
<li>
	 <a href="?action=viewprofile&username={$item.username}" class="link-profile-img">
		<img src="thumbnails.php?file={$item.picturepath}&amp;w=169&amp;h=168" width="169" height="168" alt="{$item.username}" />
	 </a>
     <article class="da-animate da-slideFromRight" style="display: block;">
         <h3>{$item.username|regex_replace:"/@.*/":""}</h3>
         <em>{$item.age}</em>
		 
		 {if $smarty.session.sess_mem eq 1}
       
			{if $style eq '2'}
            <span class="view"><a href="?action=viewprofile&&username={$item.username}" class="quick-icon-left message-icon" title="View Profile"></a></span>
				<span class="del"><a href="javascript:void(0);" class="quick-icon-right del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$item.username}','favorite-list-container')"></a></span>
			{else}
				{if $item.username ne $smarty.session.sess_username}
                <span class="view"><a href="?action=viewprofile&&username={$item.username}" class="quick-icon-left message-icon" title="View Profile"></a></span>
					<span class="chat"><a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="Message"></a></span>
				{/if}
				
				{if ($nofavorite ne 'true') and ($item.username ne $smarty.session.sess_username)}
					
					{if !in_array($item.username, $favorites_list)}
						<span class="fav"><a href="javascript:void(0);" class="quick-icon-right fav-icon" title="Favorite" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container');"></a></span>
					{else}
					
					{/if}

				{elseif $item.username ne $smarty.session.sess_username}
					<span class="del"><a href="javascript:void(0);" class="quick-icon-right del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$item.username}','favorite-list-container')"></a></span>
				{/if}
			{/if}
            {else}
				<span class="view"><a href="?action=viewprofile&&username={$item.username}" class="quick-icon-left message-icon" title="View Profile"></a></span>
		{/if}
        
      </article>
</li>