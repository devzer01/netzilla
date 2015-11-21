<!-- {$smarty.template} -->
<div id="container-content" style="padding-bottom:10px !important;">
<h1>PROFILE: <strong style="color:#fdbe00;">{$profile.username|regex_replace:"/@.*/":""}</strong></h1>
<div style="line-height:20px; width:880px; min-height:auto; float:left; margin:10px 10px 0 10px; border:2px solid #9ad3ff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:url(images/cm-theme/bg-box_03.png) repeat-x #FFF;">


<ul id="container-profile-list" style="float:left; margin-bottom:5px;">
    <li>
    <a href="thumbnails.php?file={$profile.picturepath}" title="{$profile.username|regex_replace:'/@.*/':''}" class='lightview' rel='gallery[profile]'>
    <div class="profile-list">
       <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
       <div class="img-profile">
          <img src="thumbnails.php?file={$profile.picturepath}&w=112&h=113" width="112" height="113" border="0">
      </div>
    </div>
    </a>
    </li>
</ul>

<div id="profileDetailContainer" style="padding:5px; float:left; width:710px; margin-top:10px; margin-bottom:10px;">
<label class="label-profile-box"><strong>{#Gender#}: </strong>{$profile.gender}</label>
<label class="label-profile-box"><strong>{#Country#} </strong>{$profile.country}<br /></label><br class="clear" />
<label class="label-profile-box"><strong>{#Age#}: </strong> {if $profile.birthday eq "0000-00-00"}No entry{else}{$profile.age}{/if}</label>
<label class="label-profile-box-right"><strong>{#State#}: </strong>{$profile.state}<br /></label>
<br class="clear" />
<strong>{#Description#}:</strong><br/>
{$profile.description|stripslashes|strip_tags|nl2br}
</div>
</div>

<div class="container-sub-menu">
<a href="?action=chat&username={$smarty.get.username}"><span><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span><strong>Nachricht schicken</strong></a>
{if !in_array($profile.username, $favorites_list)}
<a href="#" onclick="jQuery(this).remove(); return addFavorite('{$smarty.get.username}')"><span><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span><strong>{#Add_to_Favorite#}</strong></a>
{else}
<a href="#" onclick="if(removeFavorite('{$smarty.get.username}')) jQuery(this).remove(); return false;"><span><img src="images/cm-theme/s-icon-03.png" width="21" height="21" /></span><strong>{#Remove_from_Favorite#}</strong></a>
{/if}
</div>

</div>


<div id="container-content-profile-home">

{if count($fotoalbum)}
<div id="container-content">
<h1>PHOTO GALLERY</h1>
<ul id="container-profile-list" style="float:left;">
{foreach from=$fotoalbum item=item name="fotoalbum"}
<li>
    <a href="thumbnails.php?file={$item.picturepath}" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
        <div class="img-profile"><img src="thumbnails.php?file={$item.picturepath}&w=112&h=113" width="112" height="113" /></div>
    </div>
    </a>
</li>
{/foreach}
</ul>
</div>
{else}
<div id="container-content">
<h1>PHOTO GALLERY</h1>
<div style="line-height:20px; width:auto; margin:20px; padding:20px; border:1px solid #9ad3ff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:url(images/cm-theme/bg-box_03.png) repeat-x; text-align:center;">
Not your Photo Gallery.
</div>

</div>
{/if}
</div>