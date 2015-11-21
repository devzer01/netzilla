<!-- {$smarty.template} -->
<div class="">
{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	{if $for_old_design}
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
	{/if}
{else}

	<div id="container-top-content-sub-l">         
	{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}   
	
	{else}   
	{******************************** login *****************************************}
		{include file="left-notlogged.tpl"}
	{******************************** End login *************************************} 
	{/if}
	</div>
	
	
	<div id="container-profile-online" class="container-online">
		<div class="title">
        	<div class="title-left"></div><h1>Online</h1><div class="title-right"></div>
        </div>
		{include file="online.tpl" total="10"}
	</div>
	<br class="clear" />
		
	<!-- <div class="container-banner">
        <a href="#"><img src="images/cm-theme/banner-whatsapp.png" width="249" height="396"></a>
    </div> -->
        
	<div class="container-content-full">
		<div class="title">
        	<div class="title-left"></div><h1>{#Newest_main#}</h1><div class="title-right"></div>
        </div>
		{include file="newest_members_box.tpl" total="14"}	
	</div>
{/if}
</div>

{if ($smarty.session.sess_username!="")}
    <!--start banner verify -->
	<!--
	{include file="banner-verify-mobile.tpl"}
	-->
    <!--end banner verify -->

	{if (($bonusid != '') && ($bonusid > 0))}
		<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
		</span>
	{/if}

    <div class="container-online">
    	<div class="title">
        	<div class="title-left"></div><h1>Online</h1><div class="title-right"></div>
        </div>
        {include file="online.tpl" total="10"}
    </div>
    
    {include file="left-logged.tpl"}
    
    <br class="clear" />
    
    {if $smarty.const.COIN_VERIFY_MOBILE gt 0}
		{if !$mobile_verified}
		<div class="container-banner">
	        <a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/banner-whatsapp.png" width="249" height="396"></a>
	    </div>
		{/if}
	{/if}
    
    {if $smarty.const.COIN_VERIFY_MOBILE gt 0}
		{if !$mobile_verified}
        <div class="container-newest">
    	{else}
    	<div class="container-content-full">
        {/if}
    {else}
    <div class="container-content-full">
    {/if}
   		{assign var="total" value="14"}
    	<div class="title"><div class="title-left"></div><h1>{#Newest_main#}</h1><div class="title-right"></div></div>
        {assign var="total" value="10"}
        {if $smarty.const.COIN_VERIFY_MOBILE gt 0}
		{if !$mobile_verified}
        	{assign var="total" value="10"}
        {/if}
        {/if}
        {include file="newest_members_box.tpl" total="$total"}
    </div>
{include file="my_favorite.tpl"}
{/if}