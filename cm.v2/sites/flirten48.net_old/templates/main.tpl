<!-- {$smarty.template} -->
<div id="container-top-content-area">

{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
<div id="container-top-content-sub-l">               
</div>
<h2 class="title" style="margin:0;">Hallo <strong style="color:#fdbe00;">{$smarty.session.sess_username}</strong></h2>
<div id="container-content-profile-home">
    <ul id="container-profile-list" style=" float:left;">
    <li>
        <a href="?action=profile">
        <div class="profile-list">
            <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
            <div class="img-profile"><img src="thumbnails.php?file={$MyPicture}&w=112&h=113" width="112" height="113" /></div>
        </div>
        </a>
    </li>
    </ul>
    
    <div style="float:left; width:878px; height:120px; margin-top:10px;">
		<div id="container-recent">
			{if $recent_contacts}
			<fieldset>
				<legend>Letzte Nachrichten</legend>
				<!--Recent -->
				<ul id="container-profile-list-most">
					{foreach from=$recent_contacts item="item"}
					<li>
						<a href="?action=viewprofile&username={$item.username}">
						<div class="profile-list-most">
							<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
							<div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=82&h=83" width="82" height="83" /></div>
						</div>
						</a>
						<div class="container-quick-icon" style="top: -40px">
							<a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="{#Message#}"></a>
						</div>
					</li>
					{/foreach}
				</ul>
				<!--end Recent -->
			</fieldset>
			{/if}
			{if $random_contacts}
			<fieldset>
				<legend>Kontaktvorschläge</legend>
				<!--Recent -->
				<ul id="container-profile-list-most">
					{foreach from=$random_contacts item="item"}
					<li>
						<a href="?action=viewprofile&username={$item.username}">
						<div class="profile-list-most">
							<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
							<div class="img-profile-most"><img src="thumbnails.php?file={$item.picturepath}&w=82&h=83" width="82" height="83" /></div>
						</div>
						</a>
						<div class="container-quick-icon" style="top: -40px">
							<a href="?action=chat&username={$item.username}" class="quick-icon-left message-icon" title="{#Message#}"></a>
						</div>
					</li>
					{/foreach}
				</ul>
				<!--end Recent -->
			</fieldset>
			{/if}
		</div>

</div>
{else}
<div id="container-top-content-sub-l">               
{******************************** login *****************************************}
{include file="left-notlogged.tpl"}
<div id="container-register-box">
    <div class="container-boder-register">
	<form id="form_register_small" method="post" action="?action=register">
        <h3 class="title">Herzlich Willkommen</h3>
        <label class="text-regis">Nickname:</label>
        <input name="username" type="text" class="input-regis-box" placeholder="Nickname" AUTOCOMPLETE=OFF/>
        <label class="text-regis">E-Mail:</label>
        <input name="email" type="text" class="input-regis-box" placeholder="E-Mail" autocomplete='off'/>
       <!-- <label class="text-regis"></label> -->
		
        <a href="#" class="btn-red btn-register" onclick="document.getElementById('form_register_small').submit(); return false;"><input name="submitbutton" type="submit" value="submit" style="display: none"/>KOSTENLOS ANMELDEN</a>
        
        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="register-facebook"><span>register-facebook</span></a>
	</form>
    </div>
</div>
{******************************** End login *****************************************}  
</div>

<div id="container-profile-online">
<h1 class="title">Online</h1>
{include file="online.tpl" total="15"}
</div>
<div id="container-content">
{include file="newest_members_box.tpl" total="16"}
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
    <div style="background:url(images/cm-theme/bg-online-index-page.png) no-repeat; width:481px; height:351px; float:left; margin-top:10px;">
        <div id="container-profile-online-login" style="margin:44px 20px 20px 20px;  -webkit-border-radius: 20px; -moz-border-radius: 20px; border-radius: 20px; width:392px; float:right;">
        <!--<h1 class="title">Online</h1> -->
        {include file="online.tpl" total="6"}
        </div>
    </div>
    
    <div id="container-content" style="float:right; width:520px;">
    {include file="newest_members_box.tpl" total="8"}
    </div>
	<!--<div id="container-content" style="width:644px;">
	{include file="online.tpl" total="5"}
	</div>
    <div id="container-content" style="width:352px; height:200px; margin-left:20px;"></div> -->
{/if}




{include file="my_favorite.tpl"}