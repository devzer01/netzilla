<!-- {$smarty.template} -->
 <div class="bg-box-viewprofile">
    <!--{#USERNAME#}: -->
    <h5 class="title">{$profile.username|regex_replace:"/@.*/":""}</h5>
        <ul id="container-profile-list" style="float:left; margin-bottom:5px;">
        
        <li>
        <a {if $profile.picturepath && ($smarty.get.part ne 'partial')}href="thumbnails.php?file={$profile.picturepath}"  class='lightview' rel='gallery[profile]'{elseif $smarty.get.part eq 'partial'}href="?action=viewprofile&username={$profile.username}"{/if} title="{$profile.username|regex_replace:'/@.*/':''}">
        <div class="profile-list">
           <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121"/></div>
           <div class="img-profile">
              <img src="thumbnails.php?file={$profile.picturepath}&w=112&h=113" width="97" height="98" border="0" style="position:relative; top:-4px;">
          </div>
        </div>
        </a>
                {if ($profile.picturepath) && ($smarty.get.from eq "admin")}
                    <a href="?action={$smarty.get.action}&username={$smarty.get.username}&from=admin&proc=delete_profile_picture" onclick="if(confirm('{#Delete#}?')) return true; else return false;" style="color: black"><img src="images/icon/b_drop.png"/> {#Delete#}</a>
                {/if}
        </li>
        </ul>
    
    <div style="padding:5px; float:left; width:200px; margin-top:10px; line-height:22px;">
        {include file="profile_detail.tpl"}
    </div>
    <div style="float:left; width:325px; margin:0 0 10px 10px; line-height:1.3em">
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
    <a href="{$action}" onclick="{$onclick}" class="btn-user-action">Nachricht schicken<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>
    {if $smarty.session.sess_id}
    {if !in_array($profile.username, $favorites_list)}
    <a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
    {else}
    <a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
    {/if}
    {/if}
    </div>
</div>
<!--end btn-group -->
<!-- -->
</div>
{*include file="chat.tpl" mode="instant"*}
{if !$smarty.session.sess_id}
    {include file="register.tpl"}
{else}
	{if $smarty.get.from eq "admin"}
	{else}

{if ($smarty.get.part ne 'partial')}
{if count($fotoalbum)}
<div style="float:left; width:645px; margin-top:10px;">
<h1 class="title">{#Foto_Album#}</h1>
<ul id="container-profile-list" style="float:left;">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
    <a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
        <div class="img-profile"><img src="thumbnails.php?file={$item.picturepath}&w=97&h=98" width="97" height="98" /></div>
    </div>
    </a>
</li>
{/foreach}
</ul>
</div>
{/if}

		{if $random_contacts}
		<div style="float:left; width:645px; margin-top:10px;">
		{include file="random_members_box.tpl" total=15}
		</div>
		{/if}
	{/if}
{/if}
{/if}
<!-- -->
