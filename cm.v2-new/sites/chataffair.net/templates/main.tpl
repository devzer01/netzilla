
        {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
<div class="container-box-content profile-page-main">
<!--Profile -->
    <div class="profile-img">
        <a href="?action=profile">
        	<img src="thumbnails.php?file={$MyPicture}&w=169&h=168" width="169" height="168" />
        </a>
        <span style="margin-top:10px !important;">
            <h1>Hallo! <strong>{$smarty.session.sess_username}</strong></h1>
            <p>Sie haben! <strong style="color:#ff9c00;">{if $coin}{$coin}{else}0{/if} coins</strong></p>   
        </span>
    </div>
<!--end profile -->
<!--<div style=" width:715px; height:50px; float:left; margin-left:20px; margin-top:20px;"></div> -->

<div style="float:left; width:720px; height:100px; margin-left:10px; margin-top:16px;/* background:#03F;*/">
    
        {if $recent_contacts}
            <fieldset class="container-recent">
                <legend>Letzte Nachrichten</legend>
                <!--Recent -->
                <ul id="list" class="portfolio_list da-thumbs" style="margin:0; padding:0;">
                    {foreach from=$recent_contacts item="item"}
                    <li style="float:left;margin:0 4px 0 0;">
                        <div>
                            <a href="?action=viewprofile&username={$item.username}">
                                <img src="thumbnails.php?file={$item.picturepath}&w=42&h=43" width="42" height="43" />
                            </a>
                        </div>
                        <article class="da-animate da-slideFromRight" style="display: block; padding-top:5px;">
                        	<span class="chat"><a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="Message"></a></span>
                        </article>							
                    </li>
                    {/foreach}
                </ul>
                <!--end Recent -->
            </fieldset>
        {/if}

        {if $random_contacts}
            <fieldset class="container-recent">
                <legend>Kontaktvorschläge</legend>
                <!--Recent -->
                <ul id="list" class="portfolio_list da-thumbs">
                    {foreach from=$random_contacts item="item"}
                    <li style="float:left;margin:0 4px 0 0;">
                        <div>
                            <a href="?action=viewprofile&username={$item.username}">
                                <img src="thumbnails.php?file={$item.picturepath}&w=42&h=43" width="42" height="43" />											
                            </a>
                        </div>
                    <article class="da-animate da-slideFromRight" style="display: block; padding-top:5px;">
                    	<span class="chat"><a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="Message"></a></span>
                    </article>
                    </li>
                    {/foreach}
                </ul>
                <!--end Recent -->
            </fieldset>
            
        {/if}
</div>

        {if ($smarty.session.sess_username!="")}
            <div style="float:left;">
                <!--start banner verify -->
                {include file="banner-verify-mobile.tpl"}
                <!--end banner verify -->
        
                {if (($bonusid != '') && ($bonusid > 0))}
                    <span id="bonusverify_box">
                    {include file="bonusverify_step1.tpl"}
                    </span>
                {/if}
            </div>
        {/if}
            
<br class="clear" />
</div>

			


			<div class="container-profile-list">
            	{include file="newest_members_box.tpl" total="10"}
            </div>

			<div class="container-profile-list">
            	{include file="my_favorite.tpl"}
            </div>

		{else}
			<div class="container-login box-style-01">            
				{*****************login********************}
					{include file="form-login.tpl"}
				{*************end login********************}
			</div>
				
			<div class="container-register box-style-01">
				<form id="form_register_small" method="post" action="?action=register">
					<h1>Herzlich Willkommen</h1>
					<p>Now it's FREE to receive and review your matches! Each Compatible match pre-screened for you across 29 Dimensions. Get started now.</p>
					<input name="username" type="text" placeholder="Nick name" class="formfield_01 register-field"/>
					<input name="email" type="text" placeholder="Email" class="formfield_01 register-field"/>
					<br class="clear" />
					<a href="javascript:void(0);" class="btn-blue btn-register" onclick="document.getElementById('form_register_small').submit(); return false;"><span>KOSTENLOS ANMELDEN</span></a>
					<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="register-facebook"><span>facebook</span></a>
				</form>
			</div>

			<div class="container-profile-list">
            	{include file="newest_members_box.tpl" total="10"}
            </div>
		{/if}   



