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
{/literal}
<div id="container-content-profile-home">
<div style="line-height:20px; width:660px; min-height:265px; float:left; margin:10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd;">

<h5 class="title">{#USERNAME#}: <strong style="color:#fdbe00;">{$profile.username|regex_replace:"/@.*/":""}</strong> <div class="small-icon"><a href="#editprofile" class="edit-icon" title="Edit Profile" onclick ="getPage('?action=editprofile','profileDetailContainer');"></a></div></h5>

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
               <div class="img-profile" style="top:-123px !important;">
               		<img src="thumbnails.php?file={$profile.picturepath}&w=112&h=113" width="112" height="113" border="0"/>
              </div>
          </div>
        </a>
        <div style="margin-top:5px; height:23px; width:121px; overflow:hidden; background:url(images/cm-theme/upload-pic.png) no-repeat;">
        <!--<div class="upload-pic"></div> -->
		<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
		<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:80px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer;"/>
		<!-- <input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:80px;"/> -->
		</form>
        </div>
		<!--<a href="#" onclick="getFileDialog(); return false;" style="color: black"><img src="images/icon/up.png" width="16"/> {#Upload#}</a><br/> -->
		{if $profile.picturepath}
			<a href="?action=editprofile&proc=delete_profile_picture" onclick="if(confirm('Löschen?')) return true; else return false;" class="del-pic">{#Delete#}</a>
		{/if}
    </li>
</ul>

<div id="profileDetailContainer" style="padding:5px; float:left; width:500px; margin-top:10px; margin-bottom:10px;">
{include file="profile_detail.tpl"}
</div>
</div>

<div style="float:left; margin-bottom:10px; margin-top:10px; width:333px;">
<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','profileDetailContainer');" class="btn-user-action">{#Edit_Profile#}<span class="icon-action"><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span></a>
<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')" class="btn-user-action">{#FOTOALBUM#}<span class="icon-action"><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span></a>
<a href="#my_favorite" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')" class="btn-user-action">My {#FAVOURITES#}<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')"class="btn-user-action">{#Change_Password#}<span class="icon-action"><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span></a>
<a href="?action=pay-for-coins" class="btn-user-action">Coins<span class="icon-action"><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span></a>
<!--<a href="?action=delete_account" class="btn-user-action">{#delete_account#}<span class="icon-action"><img src="images/cm-theme/s-icon-09.png" width="21" height="21" /></span></a> -->
</div>
<br class="clear" />
<div id="contentDiv"></div>
</div>