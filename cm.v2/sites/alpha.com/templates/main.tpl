{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    <div class="container-search-box">
            <div class="container-profile">
            	
                <ul class="container-profile-list" style="margin:20px 10px 20px 20px;">
                	<li><a href="?action=profile" class="profile-boder"></a>
                		<img src="thumbnails.php?file={$memberProfile.picturepath}" width="108" height="108" class="profile-img"/></li>
            	</ul>
                
                <div style="width:345px; height:150px; float:left; margin-top:10px;">
                <h2>Letzte Nachrichten</h2>
                    <ul class="container-recent">
                    	{foreach from=$recent_contacts item=member}
	                    	<li>
	                        <a href="?action=viewprofile&username={$member.username}" class="profile-boder"></a><img src="thumbnails.php?file={$member.picturepath}" width="70" height="70" class="profile-img"/>
	                        <a href="?action=chat&username={$member.username}" class="q-icon q-right q-chat"><span>fav</span></a>
	                        </li>
	                   	{/foreach}
                    </ul>
                </div>
                
                <div style="width:345px; height:150px; float:left; margin-top:10px; margin-left:20px;">
                <h2>Kontaktvorschläge</h2>
                    <ul class="container-recent">
                    	{foreach from=$random_contacts item=member}
	                    	<li>
	                        <a href="?action=viewprofile&username={$member.username}" class="profile-boder"></a><img src="thumbnails.php?file={$member.picturepath}" width="70" height="70" class="profile-img"/>
	                        <a href="?action=chat&username={$member.username}" class="q-icon q-right q-chat"><span>fav</span></a>
	                        </li>
	                   	{/foreach}
                    </ul>
                </div>
                
                <ul class="container-profile-list" style="margin:20px 20px 20px 10px;">
                	<li><a href="#" class="profile-boder"></a>
                    <div style="width:108px; height:108px; margin:7px 0 0 6px; text-align:center;">
                    	<div style="margin-top:40px;">
                        Sie haben!<br />
						<strong style="margin-top:5px; display:block; font-size:16px;">{if $coin}{$coin}{else}0{/if} coins</strong>
                        </div>
                    </div>
                    </li>
            	</ul>
                
            </div>
        </div>
        <!-- banner-->
	{if $smarty.const.COIN_VERIFY_MOBILE gt 0 && !$mobile_verified}
        <div style="width:1024px; height:120px; float:left; margin-top:5px"><a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/banner-mobile.png" width="1025" height="121" /></a></div>    
	{/if}
{else}

	<div class="container-login-box">
	<!--box login -->
		{include file="form-login.tpl"}
	<!--End box login --> 
	<!--box register -->    
		
		
			<div class="container-register">
            	<h1>{#Register#}</h1>
            	<form id="form_register_small" method="post" action="?action=register">
	                <input name="username" type="text" placeholder="{#USERNAME#}" AUTOCOMPLETE=OFF class="formfield_01" style=" width:215px; margin-right:10px"/>
	                <input name="email" type="text"  class="formfield_01" placeholder="{#Email#}" style=" width:215px;"/><br class="clear" />
	                <div style="float:left; margin-bottom:10px; margin-top:8px;"><input name="" type="radio" value="" />Male</div>
	                <div style="float:left; margin-bottom:10px; margin-top:8px; margin-left:10px;"><input name="" type="radio" value="" />Female</div><br class="clear" />
	                <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb" style="padding-left:32px; width:198px;">SIGN UP THROUGH FACEBOOK</a>
	                <a href="#" onclick="$('#form_register_small').submit(); return false;" class="btn-login">{#Register#}</a>
	          	</form>
            </div>
	<!--End box register --> 
	</div>
{/if}

<div class="container-content">
        	<div class="container-content-box" style="float:left;">
            	<h1>ONLINE</h1>
                <ul class="container-profile-list">
                	{foreach from=$online_members item=member}
                		<li>
                			<a href="?action=viewprofile&username={$member.username}" class="profile-boder"></a><img src="thumbnails.php?file={$member.picturepath}" width="108" height="108" class="profile-img"/><p>{$member.username}</p>
                			{if $smarty.session.sess_username neq ""}
                				{if !in_array($member.username, $favorites_list)}
									<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('{$member.username}','favorite-list-container');"><span>fav</span></a>
								{else}
									<a href="#" class="q-icon del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$member.username}','favorite-list-container')"><span>fav</span></a>			
								{/if}
                				{if $item.username ne $smarty.session.sess_username}
									<a href="?action=chat&username={$member.username}" class="q-icon q-right q-chat" title="Chat"><span>fav</span></a>
								{/if}
                			{/if}
                		</li>
                	{/foreach}
                </ul>
            </div>
            <div class="container-content-box" style="float:right;">
            	<h1>NEWEST</h1>
                <ul class="container-profile-list">
                	{foreach from=$newest_members item=member}
                		<li>
                			<a href="?action=viewprofile&username={$member.username}" class="profile-boder"></a><img src="thumbnails.php?file={$member.picturepath}" width="108" height="108" class="profile-img"/><p>{$member.username}</p>
                			{if $smarty.session.sess_username neq ""}
                				{if !in_array($member.username, $favorites_list)}
									<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('{$member.username}','favorite-list-container');"><span>fav</span></a>
								{else}
									<a href="#" class="q-icon del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$member.username}','favorite-list-container')"><span>fav</span></a>			
								{/if}
                				{if $item.username ne $smarty.session.sess_username}
									<a href="?action=chat&username={$member.username}" class="q-icon q-right q-chat" title="Chat"><span>fav</span></a>
								{/if}
                			{/if}
                		</li>
                	{/foreach}
                </ul>
            </div>
        </div>
        
        {if $smarty.session.sess_username neq "" && count($favorites) > 0} 
        
        	<div class="container-content-02">
            	<h1>Favoriten</h1>
                <ul class="container-profile-list">
                	{foreach from=$favorites item=member}
                	<li>
                    <a href="?action=viewprofile&username={$member.username}" class="profile-boder"></a><img src="thumbnails.php?file={$member.picturepath}" width="108" height="108" class="profile-img"/><p>{$member.username}</p>
					{if !in_array($member.username, $favorites_list)}
						<a href="#" class="q-icon q-fav" title="Favorite" onclick="$(this).remove(); return addFavorite('{$member.username}','favorite-list-container');"><span>fav</span></a>
					{else}
						<a href="#" class="q-icon del-icon-g" title="{#Delete#}" onclick="return removeFavorite('{$member.username}','favorite-list-container')"><span>fav</span></a>			
					{/if}
                    <a href="#" class="q-icon q-right q-chat"><span>fav</span></a>
                    </li>
                    {/foreach}
                </ul>
            </div>
   		{/if}
        
        