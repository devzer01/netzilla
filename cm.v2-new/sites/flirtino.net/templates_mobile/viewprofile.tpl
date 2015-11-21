<!-- {$smarty.template} -->
<div class="bg-box-viewprofile">
	<h1>{$profile.username|regex_replace:"/@.*/":""}</h1>
	<ul class="container-profile-list" style="margin:10px 5px 0 5px; float:left; height:125px;">
		<li><a href="thumbnails.php?file={$profile.picturepath}" class="fancybox profile-boder"></a><img src="thumbnails.php?file={$profile.picturepath}&w=108&h=108" width="108" height="108" class="profile-img"/></li>
	</ul>
	<!--upload photo profile -->
	<!--end upload photo profile -->
	{include file="profile_detail.tpl"}
	<div style="float:left; padding:0 20px; margin-bottom:10px;">               
	{include file="profile_detail_description.tpl"}
	<div class="container-btn-menu-profile"> 
		<a href="?action=chat&username={$profile.username}">{#Message_Send#}</a>
		{if $smarty.session.sess_id}
		{if !in_array($profile.username, $favorites_list)}
		<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')">{#Add_to_Favorite#}</a>
		{else}
		<a href="#">{#Remove_from_Favorite#}</a>
		{/if}
		{/if}
	</div>
	
	</div>
</div>

<div class="container-view-profile-right">
	{if ($smarty.get.part ne 'partial')}
		{if count($fotoalbum)}
			<div style="float:left; width:645px; margin-top:10px;">
			<h1>{#Foto_Album#}</h1>

            
             <ul class="container-profile-list photoalbum">
            {foreach from=$fotoalbum item=item name="fotoalbum"}
            <li><a href="thumbnails.php?file={$item.picturepath}" class="fancybox profile-boder {if $item.approval} approval{/if}" data-fancybox-group="gallery"></a>
            <img src="thumbnails.php?file={$item.picturepath}&w=108&h=108" width="108" height="108" class="profile-img"/>
            </li>
            {/foreach} 
            </ul>
            
			</div>
		{else}
			{if $random_contacts}
				<div style="float:left; width:645px;">
				{include file="random_members_box.tpl" total=15}
				</div>
			{/if}
		{/if}
	{/if}
</div>