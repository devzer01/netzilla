{include file="online.tpl" total="16"}
{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    <div style="float:right; margin-top:50px;">
<!--box profile -->
	<div class="container-box-content-02">
    	<div class="box-content-02-top"></div>
        
        <div class="box-content-02-middle">
        	<!--Profile -->
        	<h1 style="margin-bottom:10px; text-align:center;">Hello <strong style="color:#ffff00; text-transform:none;">{$smarty.session.sess_username}</strong></h1>
            <ul class="container-profile-icon" style=" margin-left:50px;">
                <li>
                    <a href="?action=profile" class="profile-icon{if $profile.approval} approval{/if}"></a>
                    <img src="thumbnails.php?file={$MyPicture}&w=110&h=92" width="110" height="92" class="profile-img"/>
                </li>
            </ul>
            <br class="clear" />
         	<!--end Profile -->
            <!--Recent --> 
			{if $recent_contacts}
            <div class="container-recent">
            <h2>Recent Contact</h2>
                <ul class="container-recent-icon" style="margin-left:2px;">
					{foreach from=$recent_contacts item="item"}
                    <li>
                        <a href="?action=viewprofile&username={$item.username}" class="profile-icon"></a>
                        <img src="thumbnails.php?file={$item.picturepath}&w=78&h=66" width="78" height="66" class="recent-img"/>
                        <a href="?action=chat&username={$item.username}" class="quick-icon q-recent q-chat"><span>{#Message#}</span></a>
                    </li>
					{/foreach}
                </ul>
                <br class="clear" />
            </div>
			{/if}
            <!--end Recent -->
            <!--Random contact --> 
            <div class="container-random">
            <h2>Random Contact</h2>
                <ul class="container-recent-icon" style="margin-left:2px;">
					{foreach from=$random_contacts item="item"}
                    <li>
                        <a href="?action=viewprofile&username={$item.username}" class="profile-icon"></a>
                        <img src="thumbnails.php?file={$item.picturepath}&w=78&h=66" width="78" height="66" class="recent-img"/>
                        <a href="?action=chat&username={$item.username}" class="quick-icon q-recent q-chat"><span>{#Message#}</span></a>
                    </li>
					{/foreach}
                </ul>
                <br class="clear" />
            </div>
            <!--end Random contact -->
            </div>       
        <div class="box-content-02-buttom"></div>
    </div>    
<!--End box profile --> 
    </div>

	<!--start banner verify -->
	{include file="banner-verify-mobile.tpl"}
	<!--end banner verify -->

	{if (($bonusid != '') && ($bonusid > 0))}
	<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
	</span>
	{/if}
	<!--end -->
{else}
	<div style="float:right; margin-top:50px;">
	<!--box login -->
		{include file="form-login.tpl"}
	<!--End box login --> 
	<!--box register -->    
		<div class="container-box-content-01">
			<div class="box-content-01-t-l"></div>
			<div class="box-content-01-t-m"></div>
			<div class="box-content-01-t-r"></div>
			<div class="box-content-01-m-l register-height"></div>
			<div class="box-content-01-m-m register-height">
				<h1 style="margin-bottom:10px;">{#Register#}</h1>
				<form id="form_register_small" method="post" action="?action=register">
				<input name="username" type="text" class="formfield_01" placeholder="{#USERNAME#}" AUTOCOMPLETE=OFF style="width:209px; margin-bottom:10px;"/>
				<input name="email" type="text" class="formfield_01" placeholder="{#Email#}" autocomplete='off' style="width:209px; margin-bottom:10px;"/>
				<a href="#" class="btn-register" style="margin-bottom:10px;" onclick="document.getElementById('form_register_small').submit(); return false;">{#Register#}</a>
				<a href="#" class="fb-register" style="margin-bottom:10px;"><span class="hidden">facebook</span></a>
				<input name="submitbutton" type="submit" value="submit" style="display: none"/>
				</form>
			</div>
			
			<div class="box-content-01-m-r register-height"></div>
			
			<div class="box-content-01-b-l"></div>
			<div class="box-content-01-b-m"></div>
			<div class="box-content-01-b-r"></div>
		</div>
	<!--End box register --> 
	</div>
{/if}