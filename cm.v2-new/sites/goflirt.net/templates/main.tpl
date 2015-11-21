{if !$smarty.session.sess_id}
{include file="online.tpl" total="12"}
{else}
{include file="online.tpl" total="12"}
{/if}
{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    <div style="float:right; margin-top:40px;">
<!--box profile -->
	<div class="container-box-content-02">
    	<div class="box-content-02-top"></div>
        
        <div class="box-content-02-middle">
            <!--Profile -->
            <h1>Hallo <strong style="color:#ffff00; text-transform:none;">{$smarty.session.sess_username}</strong></h1>
            <ul class="container-profile-icon" style="height:120px !important;">
                <li>
                    <a href="?action=profile" class="profile-icon{if $profile.approval} approval{/if}"></a>
                    <img src="thumbnails.php?file={$MyPicture}&w=110&h=92" width="110" height="92" class="profile-img"/>
                </li>
            </ul>
            
            <!-- new gif box -->
            <div class="container-your-coins">
            	<h2>Sie haben!<br /><strong style="color:#ff0000;">{$coin}</strong> coins</h2>
            </div>
            
            <div class="container-gif">
            	 <strong>Erste Kontaktanzeige: </strong>
                 <p>Einfach mal Leute kennen lernen. Und nicht so einen Facebook Kinderkram :) Wer weiss? Vielleicht finde ich hier den richtigen</p>
				 {if $user_gifts}
                 <strong> Deine Geschenke:</strong>
                 <ul class="container-gif-item">
                 	{foreach from=$user_gifts item=gift}
                 		<li><a href="#"><img src="{$gift.image_path}" width="30" height="30" /></a></li>
                    {/foreach}
                     <br class="clear" />
                 </ul>
				 {/if}
                
				{if $recent_contacts}
                 <strong> Letzte Nachrichten:</strong>
                 <ul class="container-gif-item">
					{foreach from=$recent_contacts item="item"}
                 	<li><a href="?action=chat&username={$item.username}"><img src="thumbnails.php?file={$item.picturepath}&w=30&h=30" width="30" height="30" /></a></li>
					{/foreach}
                     <br class="clear" />
                 </ul>
				 {/if}
                 </div>
                 <!--end gif box -->
                 
            <br class="clear" />
            <!--end Profile -->
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
	<div style="float:right; margin-top:0; margin-bottom:10px;">
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
				<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="fb-register" style="margin-bottom:10px;"><span class="hidden">facebook</span></a>
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