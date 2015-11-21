<!-- {$smarty.template} -->
<div id="container-content-profile-home" {if $smarty.get.part eq 'partial'}style="width: 680px"{/if}>
<div style="line-height:20px; width:660px; float:left; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd;">

<h5 class="title">{#USERNAME#}: <strong style="color:#fdbe00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h5>
<ul id="container-profile-list" style="float:left; margin-bottom:5px;">

<li>
<a {if $profile.picturepath && ($smarty.get.part ne 'partial')}href="thumbnails.php?file={$profile.picturepath}"  class='lightview' rel='gallery[profile]'{elseif $smarty.get.part eq 'partial'}href="?action=viewprofile&username={$profile.username}"{/if} title="{$profile.username|regex_replace:'/@.*/':''}">
<div class="profile-list">
   <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121"/></div>
   <div class="img-profile">
	  <img src="thumbnails.php?file={$profile.picturepath}&w=112&h=113" width="112" height="113" border="0" style="position:relative; top:-4px;">
  </div>
</div>
</a>
		{if ($profile.picturepath) && ($smarty.get.from eq "admin")}
			<a href="?action={$smarty.get.action}&username={$smarty.get.username}&from=admin&proc=delete_profile_picture" onclick="if(confirm('{#Delete#}?')) return true; else return false;" style="color: black"><img src="images/icon/b_drop.png"/> {#Delete#}</a>
		{/if}
</li>
</ul>

<div style="padding:5px; float:left; width:500px; margin-top:10px;">
{include file="profile_detail.tpl"}
</div>
</div>

{if ($smarty.get.part ne 'partial')}
	{if $smarty.session.sess_mem eq 1}
		{assign var="action" value="?action=chat&amp;username=`$smarty.get.username`"}
		{assign var="onclick" value=""}
	{else}
		{assign var="action" value="#"}
		{assign var="onclick" value="loadPagePopup('?action=register_popup&username=`$smarty.get.username`', '100%'); return false;"}
	{/if}
<div style="float:left; margin-bottom:10px; margin-top:10px; width:330px;">
<a href="{$action}" onclick="{$onclick}" class="btn-user-action">Nachricht schicken<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>
{if $smarty.session.sess_id}
{if !in_array($profile.username, $favorites_list)}
<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
{else}
<a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
{/if}
<!-- <a href="#" onclick="loadPagePopup('?action=send-coins&username={$smarty.get.username}', '100%'); return false;" class="btn-user-action">Send coins<span class="icon-action"><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span></a> -->
{/if}
{if $smarty.const.ENABLE_STICKER eq "1"}
	<a href="#" id="a_display_gifts" class="btn-user-action">Display Gifts<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>
{/if}
</div>
<br class="clear" />
{if count($fotoalbum)}
<div id="container-photo-gallery">
<h5 class="title">PHOTO GALLERY</h5>
<ul id="container-profile-list" style="float:left;">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
    <a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
        <div class="img-profile"><img src="thumbnails.php?file={$item.picturepath}&w=112&h=113" width="112" height="113" /></div>
    </div>
    </a>
</li>
{/foreach}
</ul>
</div>
{/if}
</div>
{*include file="chat.tpl" mode="instant"*}
{if !$smarty.session.sess_id}
    {include file="register.tpl"}
{else}
	{if $smarty.get.from eq "admin"}
	{else}
		{if $random_contacts}
		<div id="container-content">
		{include file="random_members_box.tpl"}
		</div>
		{/if}
	{/if}
{/if}
{/if}

{literal}
<script type='text/javascript'>
	jQuery(function (e) {
		jQuery("#a_display_gifts").click(function (e) {
			e.preventDefault(); {/literal}
			jQuery("#container-content").load("?action=display_gifts&username={$smarty.get.username}");
			{literal}
		});
		
	});
</script>
{/literal}