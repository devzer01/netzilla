<div style="float:left; margin-top:12px;">
<!--box profile -->
<div class="container-box-content-02">
	<div class="box-content-02-top"></div>
	
	<div class="box-content-02-middle">
		<!--Profile -->
		<h1 style="margin-bottom:10px; text-align:center;"><strong style="color:#ffff00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
		<ul class="container-profile-icon" style=" margin-left:50px;">
			<li>
				<a {if $profile.picturepath && ($smarty.get.part ne 'partial')}href="thumbnails.php?file={$profile.picturepath}"  class='fancybox profile-icon' {elseif $smarty.get.part eq 'partial'}href="?action=viewprofile&username={$profile.username}" class='profile-icon'{else}class='profile-icon'{/if} title="{$profile.username|regex_replace:'/@.*/':''}">
				<img src="thumbnails.php?file={$profile.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/></a>
			</li>
		</ul>
        
         <br class="clear" />
		
		{if ($smarty.get.part ne 'partial')}
		<a href="?action=chat&username={$profile.username}" class="btn-user-action">{#Message_Send#}<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>

		{if $smarty.session.sess_id}
			{if !in_array($profile.username, $favorites_list)}
				<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
			{else}
				<a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
			{/if}
			{if $smarty.const.ENABLE_STICKER eq "1"}
				<a href="#" id="a_display_gifts" class="btn-user-action">Geschenke<span class="icon-action"><img src="images/cm-theme/s-icon-04.png" width="21" height="21" /></span></a>
			{/if}
		{/if}
		{/if}

		<!--end Profile -->
		<!--Profile Detail --> 
		<div class="container-recent" style="margin-top:10px;">

		<h2>Profilangaben</h2>
			<div class="container-profile-detail">
				{include file="profile_detail.tpl"}
				{include file="profile_detail_description.tpl"}
			</div>
		</div>
        <br class="clear" />
        
		{if $my_user_gifts}
        <div class="container-gif">
         <strong> Deine Geschenke:</strong>
         <ul class="container-gif-item">
         	{foreach from=$my_user_gifts item=gift}
            	<li><a href="#"><img src="{$gift.image_path}" width="30" height="30" /></a></li>
            {/foreach}
             <br class="clear" />
         </ul>
         </div>
		 {/if}
         
		<!--end Profile Detail -->
		
		</div>       
	<div class="box-content-02-buttom"></div>
</div>    
<!--End box profile --> 
</div>
<!--Foto_Album -->
{if ($smarty.get.part ne 'partial')}
<div class="container-profile-page" id='displayarea'>
		{if count($fotoalbum) && $smarty.session.sess_id}
			<div style="float:left; width:645px;">
			<h1 class="title">{#Foto_Album#}</h1>
			<ul class="container-profile-icon" style="float:left;">
			{foreach from=$fotoalbum item=item name="fotoalbum"}
				<li>
					<a href="thumbnails.php?file={$item.picturepath}" class="fancybox profile-icon {if $item.approval} approval{/if}" data-fancybox-group="gallery"></a>
					<img src="thumbnails.php?file={$item.picturepath}&w=110&h=92" width="110" height="92" class="profile-img" />
				</li> 
			{/foreach}
			</ul>
			</div>
		{else}
			{if $random_contacts}
				<div style="float:left; width:645px; margin-top:10px;">
				{include file="random_members_box.tpl" total=15}
				</div>
			{/if}
		{/if}
</div>
{/if}
<!--end Foto_Album -->
<br class="clear" />

{literal}
<script type='text/javascript'>
	jQuery(function (e) {
		jQuery("#a_display_gifts").click(function (e) {
			console.log('test');
			e.preventDefault(); {/literal}
			jQuery("#displayarea").load("?action=display_gifts&username={$smarty.get.username}");
			{literal}
		});
	});
</script>
{/literal}