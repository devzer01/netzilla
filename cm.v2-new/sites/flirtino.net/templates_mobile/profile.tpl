<!-- {$smarty.template} -->
{literal}
<script>
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	loadByHash();
});

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		jQuery('#link_'+window.location.hash.replace("#", "")).trigger('click');
	}
	else
	{
		getPage('?action=fotoalbum','contentDiv');
	}
}

function getPage(url, target)
{
	jQuery.get(url, function(data) {
		if(data != '')
		{
			jQuery('#'+target).html(data);
		}
	});
	return false;
}

function getFileDialog()
{
	jQuery('#profilepic').trigger('click');
}

//browse-over
</script>
{/literal}

<div class="bg-box-viewprofile">
	<h1>{$profile.username|regex_replace:"/@.*/":""}</h1>
	<ul class="container-profile-list" style="margin:10px 5px 0 5px; float:left; height:125px;">
		<li><a href="thumbnails.php?file={$profile.picturepath}" class="fancybox profile-boder{if $profile.approval} approval{/if}"></a><img src="thumbnails.php?file={$profile.picturepath}&w=108&h=108" width="108" height="108" class="profile-img"/></li>
	</ul>
	<!--upload photo profile -->
	<!--end upload photo profile -->
	{include file="profile_detail.tpl"}
	<div style="float:left; padding:0 20px; margin-bottom:10px;">               
	{include file="profile_detail_description.tpl"}
	<div class="container-btn-menu-profile"> 
		<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
		<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:300px; height:45px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer; 
		position:absolute;"/>
		</form>
		<a href="javascript:void(0)" id="upload">Upload Profil Foto</a>
		{if $profile.picturepath}
		<a href="?action=editprofile&proc=delete_profile_picture" id="link_fotoalbum_del"  onclick="if(confirm('Löschen?')) return true; else return false;">{#Delete#} Profil Foto</a>
		{/if}
		<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','contentDiv');">{#Edit_Profile#}</a>
		<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')">{#FOTOALBUM#}</a>
		<a href="#my_favorite" id="link_my_favorite"  onclick="getPage('?action=my_favorite&style=1','contentDiv')">{#FAVOURITES#}</a>
		<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')">{#Change_Password#}</a>
		<a href="?action=pay-for-coins">Coins</a>
	</div>
	
	</div>
</div>

<div class="container-view-profile-right">
	<div id="contentDiv"></div>
</div>