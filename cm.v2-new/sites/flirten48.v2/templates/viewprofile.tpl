<!-- {$smarty.template} -->
<div class="container-profile-page">
	<div class="title">
    	<div class="title-left"></div><h1>{$profile.username|regex_replace:"/@.*/":""}</h1><div class="title-right"></div>
    </div>
    
    
        <ul id="container-profile-list" class="container-my-profile">
        
        <li>
       		<img src="thumbnails.php?file={$profile.picturepath}&w=102&h=102" width="102" height="102" border="0"> 	
        	<a {if $profile.picturepath && ($smarty.get.part ne 'partial')}href="thumbnails.php?file={$profile.picturepath}"  class='lightview' rel='gallery[profile]'{elseif $smarty.get.part eq 'partial'}href="?action=viewprofile&username={$profile.username}"{/if} title="{$profile.username|regex_replace:'/@.*/':''}"></a>
            {if ($profile.picturepath) && ($smarty.get.from eq "admin")}
                <a href="?action={$smarty.get.action}&username={$smarty.get.username}&from=admin&proc=delete_profile_picture" onclick="if(confirm('{#Delete#}?')) return true; else return false;" style="color: black"><img src="images/icon/b_drop.png"/> {#Delete#}</a>
            {/if}
        </li>
        </ul>
    
	    <div class="container-profile-detail">
			{include file="profile_detail.tpl"}
		    {include file="profile_detail_description.tpl"}
		</div>
    <!--btn-group -->
<div class="container-btn-group">
    {if $smarty.session.sess_mem eq 1}
        {assign var="action" value="?action=chat&amp;username=`$smarty.get.username`"}
        {assign var="onclick" value=""}
    {else}
        {assign var="action" value="#"}
        {assign var="onclick" value="loadPagePopup('?action=register_popup&username=`$smarty.get.username`', '100%'); return false;"}
    {/if}
    <div>
    	<ul class="container-profilebtn-group">
	    <li><a href="{$action}" onclick="{$onclick}" class="btn-user-action">Nachricht schicken<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a></li>
	    <li>
	    {if $smarty.session.sess_id}
		    {if !in_array($profile.username, $favorites_list)}
		    <a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
		    {else}
		    <a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
		    {/if}
	    {/if}
	    </li>
	    </ul>
    </div>
</div>
<!--end btn-group -->
<!-- -->
</div>
{*include file="chat.tpl" mode="instant"*}


{if !$smarty.session.sess_id}
<div class="container-profile-page-content">
    {include file="register.tpl"}
</div>
{else}

	{if $smarty.get.from eq "admin"}
	
	{else}

		{if ($smarty.get.part ne 'partial')}
			{if count($fotoalbum)}
			<div class="container-profile-page-content">
				<div>
					<div class="title">
				    	<div class="title-left"></div><h1>{#Foto_Album#}</h1><div class="title-right"></div>
				    </div>
				
					<ul id="container-profile-list" class="container-photo" style="float:left;">
					{foreach from=$fotoalbum item=item name="fotoalbum"}
					<li>
						<img src="thumbnails.php?file={$item.picturepath}&w=102&h=102" width="102" height="102" />
   						<a href="thumbnails.php?file={$item.picturepath}" class="link-profile lightview" rel='gallery[mygallery]'></a>
					</li>
					{/foreach}
					</ul>
				</div>
			</div>
			{/if}
			
			{if $random_contacts}
			<div class="container-profile-page-content">
				{include file="random_members_box.tpl" total=20}
			</div>
			{/if}
		{/if}
	{/if}
{/if}

<!-- -->
