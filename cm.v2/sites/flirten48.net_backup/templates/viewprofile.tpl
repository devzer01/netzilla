<!-- {$smarty.template} -->
<section>
    <div class="container-news-bg">
        <div class="container-profile">
        <!-- -->
            <section>
            	<ul class="container-news-box">
                	<li>
                        <img src="thumbnails.php?file={$profile.picturepath}&w=183&h=220" width="183" height="220" />
                        <a id="n1" class="settings-button"><img src="images/cm-theme/bg-news.png" width="183" height="220" /></a>
                        <script type="text/javascript">
							jQuery(document).ready(function($) {ldelim}
						    	jQuery('#n1').toolbar({ldelim}content: '#user-n-options1', position: 'left' {rdelim});
						    	jQuery('#n1').on('toolbarItemClick', function (event, elm) {ldelim}
						    		window.location.href = elm;
						    	{rdelim});
							 {rdelim});
						</script>
					                        
                        <div id="user-n-options1" class="toolbar-icons" style="display: none;">
                        <a href="?action=viewprofile&username={$profile.username}" title="Profile"><i class="icon-user"></i></a>
                        {if $smarty.session.sess_username neq ""} 
							{if !in_array($item.username, $favorites_list)}
								<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$profile.username}','favorite-list-container');" title="Favoriten"><i class="icon-star"></i></a>
							{/if}
						{/if}
                        <a href="?action=chat&username={$profile.username}" title="Nachrichten"><i class="icon-comment"></i></a>
                        </div>
                    </li>
                </ul>
                <div class="profile-content"> 
                    <p><strong>{#USERNAME#}:</strong> {$profile.username|regex_replace:"/@.*/":""}</p>
                    <p><strong>{#Gender#}:</strong>{$profile.gender}</p>
                    <p><strong>{#Country#}:</strong>{$profile.country}</p>
                    <p><strong>{#Birthday#}:</strong>{$profile.age}</p>
                    <p><strong>{#State#}:</strong>{$profile.state}</p>
                    <p><strong>{#City#}:</strong>{$profile.city}</p>
                    <div class="profil-descrition">
                   		<strong>{#Description#}:</strong>
						{$profile.description}
                    </div>
                </div>
                <br class="clear" />
			</section>
        </div>

        </div>
    </section>
    
    {if $profile.username eq $smarty.session.sess_username}
	     <ul class="container-menu-profile">
	        <li><a href="#">EDIT PROFIL</a></li>
	        <li><a href="#">FOTOALBUM</a></li>
	        <li><a href="#">FAVORITEN</a></li>
	        <li><a href="#">Passwort ändern</a></li>
	        <li><a href="#" class="btn-right">Coins</a></li>
	     </ul>
	{/if}
	
	{if $smarty.session.sess_username neq ""}
	     
	     <section>
	    	<div class="container-favoriten">
	        
	        <h1 class="favoriten-title">Fotoalbum</h1>
	        <div class="upload-file-foto">Dein Bild hochladen  
	*Hochgeladene Bilder dürfen nur dich zeigen, Bilder von anderen Personen, von Personen unter 18 Jahren oder mit jedem anderen Inhalt werden entfernt.</div>
	            <ul class="container-profile-list">
	            	
	            	{foreach from=$fotoalbum item=item name="fotoalbum"}
					<li>
	                	<div>
	                		<a href="thumbnails.php?file={$item.picturepath}" data-lightview-type="image" class='lightview' rel='gallery[mygallery]'>
								<img src="thumbnails.php?file={$item.picturepath}" width="141" height="170" />
	                		</a>
	                	</div>
	                </li>
					{/foreach}
				</ul>
	            <!-- -->
	
	            <!-- -->
	            <br class="clear" />
	        </div>
	    </section>
	{else}
		{include file='register.tpl'}
	{/if}