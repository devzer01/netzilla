<!-- {$smarty.template} -->
{if $smarty.session.sess_username eq "" or $smarty.cookies.sess_username eq ""}
<div>
{else}
<div class="result-box">
{/if}

<ul id="container-profile-list">   
    <li>
    {if $lonelyProfile.picturepath !=""}
        <a href="?action=viewprofile&username={$lonelyProfile.username}">
        <div class="profile-list">
            <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
            <div class="img-profile">
            <img src="thumbnails.php?file={$lonelyProfile.picturepath}&w=112&h=113" width="112" height="113" border="0">
            </div>
        </div>
        </a>
        {else}
        <a href="?action=viewprofile&username={$lonelyProfile.username}">
        <div class="profile-list">
            <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
            <div class="img-profile">
            <img src="thumbs/default.jpg" height="113" width="112" border="0">
            </div>
        </div>
        </a>
        {/if}

    </li>
</ul>

<div class="profile-detail">
<div style="display:block; float:left; text-align:left; margin-right:10px; width:280px;">
<strong>{#Name#}:</strong> {$lonelyProfile.username|regex_replace:"/@.*/":""}<br />
<strong>{#City#}:</strong> {$lonelyProfile.city}<br />
<strong>{#Civil_status#}:</strong> {$lonelyProfile.civilstatus}
</div>

<div style=" display:block; float:left; margin-right:10px; width:280px; text-align:left;">
{assign var="thisY" value=$lonelyProfile.birthday|date_format:"%Y"}  
{assign var="Age" value="`$year-$thisY`"}
<strong>{#Age#}:</strong>  {$Age} {#Year#}<br />
<strong>{#Appearance#}:</strong> {$lonelyProfile.appearance}<br />
<strong>{#Height#}:</strong> {$lonelyProfile.height}<br />
</div>

<div style=" display:block; float:left; margin-right:10px; width:560px; text-align:left;">
<strong>{#Description#}:</strong> {$lonelyProfile.description|nl2br|truncate:40:"...":true|stripslashes}
</div>

{if $smarty.session.sess_mem eq 1}
<a href="./?action=viewprofile&username={$lonelyProfile.username}" class="button">Jetzt Details sehen!</a>
{/if}
</div>

</div><br class="clear" />




























