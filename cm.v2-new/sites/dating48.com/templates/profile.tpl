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
</script>
{/literal}
<div id="container-content" style="padding-bottom:10px !important;">
<h1>PROFILE: <strong style="color:#fdbe00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
<div style="line-height:20px; width:880px; min-height:auto; float:left; margin:10px 10px 0 10px; border:2px solid #9ad3ff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:url(images/cm-theme/bg-box_03.png) repeat-x #FFF;">

<div class="small-icon"><a href="#editprofile" class="edit-icon" title="Edit Profile" onclick ="getPage('?action=editprofile','profileDetailContainer');"></a></div>

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
               		<img src="thumbnails.php?file={$profile.picturepath}&w=112&h=113" width="112" height="113" border="0"/>
              </div>
          </div>
        </a>
    </li>
</ul>

<div id="profileDetailContainer" style="padding:5px; float:left; width:710px; margin-top:10px; margin-bottom:10px;">
{include file="profile_detail.tpl"}
</div>
</div>

<div class="container-sub-menu">
<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','profileDetailContainer');">
<span><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span><strong>{#Edit_Profile#}</strong>
</a>
<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')">
<span><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span><strong>{#FOTOALBUM#}</strong>
</a>
<a href="#my_favorite" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')">
<span><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span><strong>My {#FAVOURITES#}</strong>
</a>
<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')">
<span><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span><strong>{#Change_Password#}</strong>
</a>
<a href="?action=pay-for-coins">
<span><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span><strong>COINS</strong>
</a>
</div>

</div>
<div id="contentDiv"></div>