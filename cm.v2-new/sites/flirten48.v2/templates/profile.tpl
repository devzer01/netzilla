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

<div class="container-profile-page">
	<div class="title">
    	<div class="title-left"></div><h1>{$profile.username|regex_replace:"/@.*/":""}</h1><div class="title-right"></div>
    </div>
    
    <ul class="container-my-profile">
		<li>
	    	<img src="thumbnails.php?file={$profile.picturepath}&w=102&h=102" width="102" height="102" />
	        <a  href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class='lightview link-profile{if $profile.approval} link-profile-approval{/if}' rel='gallery[profile]'></a>
	    </li>
	</ul>
	
	<div class="container-your-coins">
     	<h2>Sie haben!<br /><strong style="color:#ff0000;" id="coinsArea">0</strong> coins</h2>
 	</div>

	<div class="container-profile-detail">
		{include file="profile_detail.tpl"}
	    {include file="profile_detail_description.tpl"}
	</div>

<!--btn-gorup -->
<div class="container-btn-group">
	<ul class="container-profilebtn-group">
	<!--upload photo profile -->
	<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
	<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:300px; height:45px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer; 
	position:absolute;"/>
	</form>
	<!--end upload photo profile -->
	
	<li><a href="javascript:void(0)" id="upload" class="btn-user-action">Upload Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-10.png" width="21" height="21" /></span></a></li>
	{if $profile.picturepath}	
	<li><a href="?action=editprofile&proc=delete_profile_picture" id="link_fotoalbum_del"  onclick="if(confirm('Löschen?')) return true; else return false;" class="btn-user-action">{#Delete#} Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-09.png" width="21" height="21" /></span></a></li>
	{/if}
	<li><a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','contentDiv');" class="btn-user-action">{#Edit_Profile#}<span class="icon-action"><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span></a></li>
	<li><a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')" class="btn-user-action">{#FOTOALBUM#}<span class="icon-action"><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span></a></li>
	<li><a href="#my_favorite" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')" class="btn-user-action">My {#FAVOURITES#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a></li>
	<li><a href="#my_gifts" id="link_my_gifts" onclick ="getPage('?action=my_gifts','contentDiv');" class="btn-user-action">My Gifts <span class="icon-action"><img src="images/cm-theme/s-icon-04.png" width="21" height="21"></span></a></li>
	<li><a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')" class="btn-user-action">{#Change_Password#}<span class="icon-action"><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span></a></li>
	<li><a href="?action=pay-for-coins" class="btn-user-action">Coins<span class="icon-action"><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span></a></li>
	
	{if $smarty.const.SOCIAL_ENABLED eq "1"}
		<li><a href="#social" id="link_social" onclick='getPage("?action=social", "contentDiv")' class="btn-user-action">Social<span class="icon-action"><img src="images/cm-theme/s-icon-11.png" width="21" height="21" /></span></a></li>
	{/if}
	</ul>
</div>
<!--end btn-gorup-->

</div>
<!--start right -->
<div class="container-profile-page-content">
	<!--content -->
    <div id="contentDiv"></div>
	<!--end content -->
</div>
<!--end start right -->

