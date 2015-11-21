<!-- {$smarty.template} -->
<!--<div id="container-top-content-area"> -->
<div class="container-login-profile">
{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}

<!--start -->
<div class="bg-box-viewprofile" style="width:215px !important;">
<h5 class="title">Hallo <strong>{$smarty.session.sess_username}</strong></h5>
<!--profile image -->
    <ul id="container-profile-list" style=" float:left; margin-left:45px;">
    <li>
        <a href="?action=profile">
        <div class="profile-list">
            <div class="boder-profile-img">
				{if $profile.approval}
				<!-- watermark -->
				<img src="images/cm-theme/wait.png" width="120" height="121" />
				{else}
				<img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
				{/if}
			</div>
            <div class="img-profile" style=" top:-118px !important"><img src="thumbnails.php?file={$MyPicture}&w=102&h=103" width="97" height="98" /></div>
        </div>
        </a>
    </li>
    </ul>
<!--end profile image -->
<br class="clear" />
<!--Letzte Nachrichten-->
{if $recent_contacts}
    <!--Recent -->
    <div class="container-list-most">
    <h1>Letzte Nachrichten</h1>
    <ul id="container-profile-list-most">
        {foreach from=$recent_contacts item="item"}
        <li>
            <a href="?action=viewprofile&username={$item.username}">
            <div class="profile-list-most">
                <div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
                <div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=72&h=73" width="72" height="73" /></div>
            </div>
            </a>
            <div class="container-quick-icon">
                <a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="{#Message#}"></a>
            </div>
        </li>
        {/foreach}
    </ul>
    </div>
    <!--end Recent -->
{/if}
<!--End Letzte Nachrichten-->

<!-- Kontaktvorschläge-->			
{if $random_contacts}
    <!--Recent -->
    <div class="container-list-most">
    <h1>Kontaktvorschläge</h1>
    <ul id="container-profile-list-most">
        {foreach from=$random_contacts item="item"}
        <li>
            <a href="?action=viewprofile&username={$item.username}">
            <div class="profile-list-most">
                <div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
                <div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=82&h=83" width="72" height="73" /></div>
            </div>
            </a>
            <div class="container-quick-icon">
                <a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="{#Message#}"></a>
            </div>
        </li>
        {/foreach}
    </ul>
    </div>
    <!--end Recent -->
{/if}
<!--end Kontaktvorschläge-->

</div>
<!--end -->


{else}

<div id="container-top-content-sub-l">               
{******************************** login *****************************************}
{include file="left-notlogged.tpl"}
{******************************** End login *************************************} 

{******************************** register-box *****************************************} 
<div id="container-register-box">
    <div class="container-boder-register">
	<form id="form_register_small" method="post" action="?action=register">
        <h3 class="title">Herzlich Willkommen</h3>
        
        <input name="username" type="text" class="formfield_01" placeholder="Nickname" AUTOCOMPLETE=OFF style="width:190px !important; margin-left:14px; margin-top:2px;"/> 
        <input name="email" type="text" class="formfield_01" placeholder="E-Mail" autocomplete='off' style="width:190px !important; margin-top:5px; margin-left:14px;"/>
        <a href="#" class="btn-red btn-register" onclick="document.getElementById('form_register_small').submit(); return false;"><input name="submitbutton" type="submit" value="submit" style="display: none"/>KOSTENLOS ANMELDEN</a>
        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="register-facebook"><span>register-facebook</span></a>
	</form>
    </div>
</div>
{******************************** end register-box *****************************************} 
</div>

<div id="container-profile-online">
<h1 class="title">Online</h1>
{include file="online.tpl" total="18"}
</div>

<div id="container-content">
<h1 class="title">{#Newest_main#}</h1>
{include file="newest_members_box.tpl" total="8"}
</div>
{/if}
</div>

{if ($smarty.session.sess_username!="")}
    <!--start banner verify -->
	{include file="banner-verify-mobile.tpl"}
    <!--end banner verify -->

	{if (($bonusid != '') && ($bonusid > 0))}
		<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
		</span>
	{/if}

    <div style="float:left; width:770px; margin-left:10px;">
        <h1 class="title">Online</h1>
        {include file="online.tpl" total="12"}
    </div>
    
    <div style="float:right; width:770px; margin-left:10px; margin-right:5px;">
        <h1 class="title">{#Newest_main#}</h1>
        {assign var="total" value="12"}
        {if $smarty.const.COIN_VERIFY_MOBILE gt 0}
		{if !$mobile_verified}
        	{assign var="total" value="6"}
        {/if}
        {/if}
        
        {include file="newest_members_box.tpl" total="$total"}
    </div>
{include file="my_favorite.tpl"}
{/if}