<div style="float:left; margin-top:12px;">
<!--box profile -->
<div class="container-box-content-02">
	<div class="box-content-02-top"></div>
	
	<div class="box-content-02-middle">
		<!--Profile -->
		<h1 style="margin-bottom:10px; text-align:center;">Hello <strong style="color:#ffff00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
		<ul class="container-profile-icon" style=" margin-left:50px;">
			<li>
				<a href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class="fancybox profile-icon{if $profile.approval} approval{/if}"></a>
				<img src="thumbnails.php?file={$profile.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
			</li>
		</ul>
		<br class="clear" />
		
		<a href="?action=chat&username={$profile.username}" class="btn-user-action">{#Message_Send#}<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>

		{if $smarty.session.sess_id}
		{if !in_array($profile.username, $favorites_list)}
		<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')" class="btn-user-action">{#Add_to_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
		{else}
		<a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;" class="btn-user-action">{#Remove_from_Favorite#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
		{/if}
		{/if}

		<!--end Profile -->
		<!--Profile Detail --> 
		<div class="container-recent" style="margin-top:10px;">

		<h2>Profile Detail</h2>
			<div class="container-profile-detail">
				{include file="profile_detail.tpl"}
				{include file="profile_detail_description.tpl"}
			</div>
		</div>
		<!--end Profile Detail -->
		
		</div>       
	<div class="box-content-02-buttom"></div>
</div>    
<!--End box profile --> 
</div>
<!--online -->
<div class="container-profile-page">
	{if ($smarty.get.part ne 'partial')}
		{if count($fotoalbum)}
			<div style="float:left; width:645px; margin-top:10px;">
			<h1 class="title">{#Foto_Album#}</h1>
			<ul class="container-profile-icon" style="float:left;">
			{foreach from=$fotoalbum item=item name="fotoalbum"}
				<li>
					<a href="thumbnails.php?file={$item.picturepath}" class="fancybox profile-icon {if $item.approval} approval{/if}" data-fancybox-group="gallery"></a>
					<img src="thumbnails.php?file={$item.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
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
	{/if}
</div>
<!--end online -->
<br class="clear" />