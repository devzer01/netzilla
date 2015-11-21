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
<h5 class="title">{$profile.username|regex_replace:"/@.*/":""}</h5>
<ul id="container-profile-list" style="float:left; margin-bottom:5px;">
    <li>
        <a  href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class='lightview' rel='gallery[profile]'>
        <div class="profile-list">
        <div class="boder-profile-img">
        {if $profile.approval}
        <!-- watermark -->
        <img src="images/cm-theme/wait.png" width="120" height="121" />
        {else}
        <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
        {/if}
        </div>
        <div class="img-profile">
        <img src="thumbnails.php?file={$profile.picturepath}&w=97&h=98" width="97" height="98" border="0" style="position:relative; top:-4px;"/>
        </div>
        </div>
        </a>
    </li>
</ul>

<div style="padding:5px; float:left; width:200px; margin-top:10px; line-height:22px;">
	{include file="profile_detail.tpl"}
</div>
<div style="float:left; width:325px; margin:0 0 10px 10px; line-height:1.3em">
    {include file="profile_detail_description.tpl"}
</div>

<!--btn-gorup -->
<div class="container-btn-group">

<!--upload photo profile -->
<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:300px; height:45px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer; 
position:absolute;"/>
</form>
<!--end upload photo profile -->

<a href="javascript:void(0)" id="upload" class="btn-user-action">Upload Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-10.png" width="21" height="21" /></span></a>

{if $profile.picturepath}	
<a href="?action=editprofile&proc=delete_profile_picture" id="link_fotoalbum_del"  onclick="if(confirm('Löschen?')) return true; else return false;" class="btn-user-action">{#Delete#} Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-09.png" width="21" height="21" /></span></a>
{/if}

<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','contentDiv');" class="btn-user-action">{#Edit_Profile#}<span class="icon-action"><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span></a>
<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')" class="btn-user-action">{#FOTOALBUM#}<span class="icon-action"><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span></a>
<a href="#my_favorite" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')" class="btn-user-action">My {#FAVOURITES#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')" class="btn-user-action">{#Change_Password#}<span class="icon-action"><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span></a>
<a href="?action=pay-for-coins" class="btn-user-action">Coins<span class="icon-action"><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span></a>

{if $smarty.const.SOCIAL_ENABLED eq "1"}
	<a href="#social" id="link_social" onclick='getPage("?action=social", "contentDiv")' class="btn-user-action">Social<span class="icon-action"><img src="images/cm-theme/s-icon-11.png" width="21" height="21" /></span></a>
{/if}
</div>
<!--end btn-gorup-->

</div>
<!--start right -->
<div class="container-profile-page-right">
	<!--content -->
    <div id="contentDiv">
    </div>
	<!--end content -->
</div>
<!--end start right -->

