<!-- {$smarty.template} -->
<div class="container-box-content profile-page-top">
<div class="profile-img">
<!--<a {if $profile.picturepath && ($smarty.get.part ne 'partial')}href="thumbnails.php?file={$profile.picturepath}"  class='lightview' rel='gallery[profile]'{elseif $smarty.get.part eq 'partial'}href="?action=viewprofile&username={$profile.username}"{/if} title="{$profile.username|regex_replace:'/@.*/':''}"> -->
	<img src="thumbnails.php?file={$profile.picturepath}&w=169&h=168" width="169" height="168" />
<!--</a> -->
    <span>
        <h1>Hallo! <strong>{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
        <p>{#Birthday#}: <strong>{$profile.age}</strong></p>
    </span>
    <ul class="container-menu-profile">
        <li><a href="{if $action}{$action}{else}?action=chat&username={$profile.username|regex_replace:"/@.*/":""}{/if}"  title="Nachricht schicken" onclick="{$onclick}"><img src="images/cm-theme/profile-menu-icon-chat.png" width="53" height="53" /></a></li>
    {if $smarty.session.sess_id}
        {if !in_array($profile.username, $favorites_list)}
        <li><a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" title="Add to Favorite"><img src="images/cm-theme/profile-menu-icon-03.png" width="53" height="53" /></a></li>
        	{else}
        <li><a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" title="Remove From Favorite"><img src="images/cm-theme/profile-menu-icon-del-fav.png" width="53" height="53" /></a></li>
        {/if}
    {/if}
        
    </ul>
    
    <!--btn right -->
    <!--<div style="background:#F00; float:right; width:400px;">
            <div style="float:left; margin-bottom:10px; margin-top:10px; width:330px;">
            <a href="{$action}" onclick="{$onclick}" class="btn-user-action">Nachricht schicken<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>
            {if $smarty.session.sess_id}
            {if !in_array($profile.username, $favorites_list)}
            <a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
            {else}
            <a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
            {/if}
            {/if}
            </div>
    </div> -->
    <!--End btn right -->
    
</div>
<br class="clear" />
<div class="container-profile-detail">
<h2>Profile Detail</h2>
    {include file="profile_detail.tpl"}
</div>

{if ($smarty.get.part ne 'partial')}
<div class="container-foto-gallery">
	<h2>Foto GALLERY</h2>
	
	{if count($fotoalbum)}
	<ul>
	{foreach from=$fotoalbum item=item name="fotoalbum"}
	<li>
	    <a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]'>
			<img src="thumbnails.php?file={$item.picturepath}&w=112&h=113" width="135" height="121" />
	    </a>
	</li>
	{/foreach}
	</ul>
	{else}
	<div style="text-align:center; padding:20px;"><strong>No Photo</strong></div>
	{/if}
</div>
{/if}
<br class="clear" />
</div>


<div id="container-content-profile-home" {if $smarty.get.part eq 'partial'}style="width: 680px"{/if}>


{if ($smarty.get.part ne 'partial')}
	{if $smarty.session.sess_mem eq 1}
		{assign var="action" value="?action=chat&amp;username=`$smarty.get.username`"}
		{assign var="onclick" value=""}
	{else}
		{assign var="action" value="#"}
		{assign var="onclick" value="loadPagePopup('?action=register_popup&username=`$smarty.get.username`', '100%'); return false;"}
	{/if}



</div>
{*include file="chat.tpl" mode="instant"*}
{if !$smarty.session.sess_id}
    {include file="register.tpl"}
{else}
	{if $smarty.get.from eq "admin"}
	{else}
		{if $random_contacts}
		
		{include file="random_members_box.tpl"}
		
		{/if}
	{/if}
{/if}
{/if}
