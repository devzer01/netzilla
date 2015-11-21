<h1 class="favoriten-title">NEUESTE MITGLIEDER</h1>
            <ul class="container-profile-list">
{foreach from=$result item=contact name=newest}
<li>
                <div>
                	<img src="thumbnails.php?file={$contact.picturepath}&amp;w=141&amp;h=170" width="141" height="170" />
    				<a id="p{$contact.username}" class="settings-button"><img src="images/cm-theme/profile-list-bg.png" width="145" height="145" /></a>
                </div>
                <p>{$contact.username}</p>
				 <script type="text/javascript">
				 
	                jQuery(document).ready(function($) {ldelim}
	                    $('#p{$contact.username}').toolbar({ldelim}content: '#user-options{$contact.username}', position: 'left'{rdelim});
	                    jQuery('#p{$contact.username}').on('toolbarItemClick', function (event, elm) {ldelim}
	 	    				window.location.href = elm;
	 	    			{rdelim});
                 {rdelim});
                 
                </script>
               	<div id="user-options{$contact.username}" class="toolbar-icons" style="display: none;">
                    <a href="?action=viewprofile&username={$contact.username}" title="Profile"><i class="icon-user"></i></a>
                    {if $smarty.session.sess_username neq ""}
						{if !in_array($contact.username, $favorites_list)}
							<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$contact.username}','favorite-list-container');" title="Favoriten"><i class="icon-star"></i></a>
						{/if}
					{/if}
					{if $contact.username ne $smarty.session.sess_username}
						<a href="?action=chat&username={$contact.username}" title="Nachrichten"><i class="icon-comment"></i></a>
					{/if}
            	</div>
</li>
{/foreach}
</ul>
 <br class="clear" />