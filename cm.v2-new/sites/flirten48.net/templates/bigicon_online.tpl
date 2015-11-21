<ul class="container-news-box">
	{foreach from=$result item=contact name=online}
                	<li>
                        <img src="thumbnails.php?file={$contact.picturepath}&w=183&h=220" width="183" height="220" />
                        <a id="n{$contact.id}" class="settings-button"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        <script type="text/javascript">
	                        jQuery(document).ready(function($) {ldelim}
		                        $('#n{$contact.id}').toolbar({ldelim} content: '#user-n-options{$contact.id}', position: 'left' {rdelim});
		                        jQuery('#n{$contact.id}').on('toolbarItemClick', function (event, elm) {ldelim}
		        	    			window.location.href = elm;
		        	    		{rdelim});
	                        {rdelim});
                        </script>
                        <div id="user-n-options{$contact.id}" class="toolbar-icons" style="display: none;">
                        <a href="?action=viewprofile&username={$contact.username}" title="Profile"><i class="icon-user"></i></a>
						{if $smarty.session.sess_username neq ""}
	                        {if !in_array($item.username, $favorites_list)}
	                        	<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$contact.username}','favorite-list-container');" title="Favoriten"><i class="icon-star"></i></a>
	                        {/if}
	                    {/if}
                        <a href="?action=chat&username={$contact.username}" title="Nachrichten"><i class="icon-comment"></i></a>
                        </div>
                    </li>
  	{/foreach}
</ul>