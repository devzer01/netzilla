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

function deletePhoto(id, approval)
{
	if(confirm('Are you sure to delete this photo?'))
	{
		jQuery.post("ajaxRequest.php",{"action": "deletePhoto", "fotoid": id, "approval": approval}, function(data){getPage('?action=fotoalbum', 'contentDiv');});
	}
	return false;
}

//browse-over
</script>
{/literal}

<div style="float:left; margin-top:12px;">
<!--box profile -->
<div class="container-box-content-02">
	<div class="box-content-02-top"></div>
	
	<div class="box-content-02-middle">
		<!--Profile -->
		<h1 style="margin-bottom:10px; text-align:center;">Hallo <strong style="color:#ffff00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
		<ul class="container-profile-icon" style=" margin-left:50px;">
			<li>
				{if $profile.picturepath eq ""}
				<a href="javascript:void;" class="fancybox profile-icon{if $profile.approval} approval{/if}"></a>
				<img src="thumbnails.php?file={$profile.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
				{else}
				<a href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class="fancybox profile-icon{if $profile.approval} approval{/if}"></a>
				<img src="thumbnails.php?file={$profile.picturepath}&w=110&h=92" width="110" height="92" class="profile-img"/>
				{/if}
			</li>
		</ul>
		<br class="clear" />
		
		<!--upload photo profile -->
		<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
		<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:300px; height:45px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer; 
		position:absolute;"/>
		</form>
		<!--end upload photo profile -->

		<a href="javascript:void(0)" id="upload" class="btn-user-action">Upload Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-10.png" width="21" height="21" /></span></a>

		{if $profile.picturepath}	
		<a href="?action=editprofile&proc=delete_profile_picture" id="link_fotoalbum_del"  onclick="if(confirm('Confirm to Delete?')) return true; else return false;" class="btn-user-action">{#Delete#} Profile Photo<span class="icon-action"><img src="images/cm-theme/s-icon-09.png" width="21" height="21" /></span></a>
		{/if}

		<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','contentDiv');" class="btn-user-action">{#Edit_Profile#}<span class="icon-action"><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span></a>
		<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv');" class="btn-user-action">{#FOTOALBUM#}<span class="icon-action"><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span></a>

		<a href="#my_gifts" id="link_my_gifts" onclick ="getPage('?action=my_gifts','contentDiv');" class="btn-user-action">Geschenke<span class="icon-action"><img src="images/cm-theme/s-icon-04.png" width="21" height="21"></span></a>

		<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')"class="btn-user-action">{#Change_Password#}<span class="icon-action"><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span></a>
		<a href="?action=pay-for-coins" class="btn-user-action">Coins<span class="icon-action"><img src="images/cm-theme/s-icon-coins-attach.png" width="20" height="20" /></span></a>
		

		<!--end Profile -->
		<!--Profile Detail --> 
		<div class="container-recent" style="margin-top:10px;">

		<h2>Profilangaben</h2>
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
	<div id="contentDiv"></div>
</div>
<!--end online -->
<br class="clear" />