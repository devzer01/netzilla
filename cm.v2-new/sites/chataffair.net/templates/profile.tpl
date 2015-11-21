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
</script>
<style>

	div.delete { display:none; text-align: right; padding-top: 10px; margin: -123px 0px 0 0; height: 123px; position: relative; z-index: 100; background-color: #FFFFFF; opacity:0.4; filter:alpha(opacity=40);}
	div.delete a {margin: 5px}
	li:hover div.delete { display:block; }
</style>
{/literal}

<div class="container-box-content profile-page-top">
	<div class="profile-img" style="float:left; width:910px;">
       
        
        {if $profile.approval}
        <!-- watermark -->
        <div style="position:absolute; z-index:33;"><img src="images/cm-theme/wait.png" width="169" height="168" /></div>
        {else}
        
        {/if}
         <a  href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class='lightview' rel='gallery[profile]'>
        <img src="thumbnails.php?file={$profile.picturepath}&w=169&h=168" width="169" height="168" border="0"/>
        </a>

        <span>
        <h1>Hallo! <strong>{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
        <p>{#Birthday#}: <strong>{$profile.age}</strong></p>
        </span>
        <ul class="container-menu-profile">
        	<li><a href="#editprofile" id="link_editprofile" title="Profil editieren" onclick ="getPage('?action=editprofile','profileDetailContainer');"><img src="images/cm-theme/profile-menu-icon.png" width="53" height="53" /></a></li>
            <li><a href="#fotoalbum" id="link_fotoalbum" title="Fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')"><img src="images/cm-theme/profile-menu-icon-02.png" width="53" height="53" /></a></li>
            <li><a href="#my_favorite" id="link_my_favorite" title="My Favorite" onclick="getPage('?action=my_favorite','contentDiv')"><img src="images/cm-theme/profile-menu-icon-03.png" width="53" height="53" /></a></li>
            <li><a href="#changepassword" id="link_changepassword" title="Passwort ändern"  onclick="getPage('?action=changepassword','contentDiv')"><img src="images/cm-theme/profile-menu-icon-04.png" width="53" height="53" /></a></li>
            <li><a href="?action=pay-for-coins" title="Coins"><img src="images/cm-theme/profile-menu-icon-05.png" width="53" height="53" /></a></li>
        </ul>
        <!--upload and delete -->
        <ul class="container-btn-profile-img">
            <li>
                <form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile" style="display: none">
                <input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();"/>
                </form>
                <a href="#" onclick="getFileDialog(); return false;" class="btn-upload-img-profile">
                <img src="images/icon/up.png" width="16"/>{#Upload#}</a><!--{#Upload#} -->
                {if $profile.picturepath}
                
            {/if}
            </li>
            <li><a href="?action=editprofile&proc=delete_profile_picture" onclick="if(confirm('Löschen?')) return true; else return false;" class="btn-del-img-profile">
                <img src="images/cm-theme/icon-del.png"/>{#Delete#}</a><!--{#Delete#} -->
            </li>
        </ul>
        <!--End upload and delete -->
    </div>
    <div id="profileDetailContainer" class="container-profile-detail">
        <h2>Profile Detail</h2>
        {include file="profile_detail.tpl"}
    </div>
    
    <br class="clear" />
</div>






<br class="clear" />
<div id="contentDiv"></div>
