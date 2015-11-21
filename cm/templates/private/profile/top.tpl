<!--Menu profile -->
         <div class="container-menu-profile">
            	<ul class="container-view-profile">
                	<li><a href="{$smarty.const.APP_PATH}/chat/history/{$member.username|username}" class="chat"><span>Nachrichten</span></a></li>
                    {if $isfavorite eq "0"}
                    	<li><a href="#" data-username='{$member.username|username}' class="fav"><span>My Favoriten</span></a></li>
                    {else}
                    	<li><a href="#" data-username='{$member.username|username}' class="unfav"><span>My Favoriten</span></a></li>
                    {/if}
                    <li><a href="{$smarty.const.APP_PATH}/profile/viewgift/{$member.username|username}" class="gift"><span>Geschenke</span></a></li>
                </ul>
         </div>
        <!--End Menu profile -->
        <h1><font>{$member.username|username}</font><div class="you-coins"><strong><span id='coins'>...</span> coins</strong></div></h1>
        <!--start profile -->
        <div class="container-profile-content">
            <ul class="container-profile-icon">
                <li>
                    <div class="container-profile-icon">
                        <a href="{$smarty.const.APP_PATH}/profile/view/{$member.username|username}"><div class="border-profile-icon"></div></a>
                        <img src="{$smarty.const.URL_WEB}/thumbnails.php?file={$member.picturepath}&w=112&h=113" width="112" height="113" />
                    </div>
                </li>
            </ul>
            <div class="profile-content">
                <label><strong>Geschlecht:</strong></label><label>{$member.gender_text}</label>
                <label><strong>Geburtstag:</strong></label><label>{$member.age}</label>
                <label><strong>Nationalität:</strong></label><label>{$member.country_name}</label>
                <label><strong>Land/Kanton:</strong></label><label>{$member.state_name}</label>
                <label><strong>Stadt:</strong></label><label>{$member.city_name}</label>
            </div>
            {if $desc eq "Y"}
            	<div class="container-profile-detail">
            	<label><strong>Erste Kontaktanzeige:</strong></label>
            	<p>{$member.description}</p>
            	</div>
            {/if}
        </div>
        <!--end profile -->