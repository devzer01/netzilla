<div>
	<img src="thumbnails.php?file={$item.picturepath}&amp;w=141&amp;h=170" width="141" height="170" />
    <a id="p{$item.username}" class="settings-button"><img src="images/cm-theme/profile-list-bg.png" width="145" height="145" /></a>
</div>
<p>{$item.username|regex_replace:"/@.*/":""}</p>
<script type="text/javascript">
		jQuery(document).ready(function($) {ldelim}
	    	jQuery('#p{$item.username}').toolbar({ldelim}content: '#user-options{$item.username}', position: 'left' {rdelim});
	    	jQuery('#p{$item.username}').on('toolbarItemClick', function (event, elm) {ldelim}
	    		window.location.href = elm;
	    	{rdelim});
		 {rdelim});
</script>
<div id="user-options{$item.username}" class="toolbar-icons" style="display: none;">
	<a href="?action=viewprofile&username={$item.username}" title="Profile"><i class="icon-user"></i></a>
	{if $smarty.session.sess_username neq ""} 
		{if !in_array($item.username, $favorites_list)}
			<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$item.username}','favorite-list-container');" title="Favoriten"><i class="icon-star"></i></a>
		{else}
			<a href="#" onclick="removeFavorite('{$item.username}','favorite-list-container', 2); $(this).remove(); return false;" title="Favoriten"><i class="icon-trash"></i></a>
		{/if}
	{/if}
	{if $item.username ne $smarty.session.sess_username}
		<a href="?action=chat&username={$item.username}" title="Nachrichten"><i class="icon-comment"></i></a>
	{/if}
</div>