<!-- {$smarty.template} -->
{if $smarty.session.sess_username eq "" or $smarty.cookies.sess_username eq ""}
<div>
{else}
<div class="result-box">
{/if}
<div class="result-box-inside">
<div style=" display:block; float:left; margin-right:20px; width:80px;">
{if $userProfile.picturepath !=""}
<a href="?action=viewprofile&username={$userProfile.username}" class="linklist"><img src="thumbnails.php?file={$userProfile.picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
{else}
<a href="?action=viewprofile&username={$userProfile.username}" class="linklist"><img src="thumbs/default.jpg" height="104" width="78" border="0"  class="listimg"></a>
{/if}
</div>
<div style="display:block; float:left; text-align:left; margin-right:10px; width:160px;">
{#Name#}: {$userProfile.username|regex_replace:"/@.*/":""}<br />
{#City#}: {$userProfile.city}<br />
{#Civil_status#}: {$userProfile.civilstatus}
</div>
<div style=" display:block; float:left; margin-right:10px; width:160px; text-align:left;">
{assign var="thisY" value=$userProfile.birthday|date_format:"%Y"}  
{assign var="Age" value="`$year-$thisY`"}
{#Age#}:  {$Age} {#Year#}<br />
{#Appearance#}: {$userProfile.appearance}<br />
{#Height#}: {$userProfile.height}<br />
</div>
<div style=" display:block; float:left; margin-right:10px; width:360px; text-align:left;">
{#Description#}: {$userProfile.description|nl2br|truncate:40:"...":true|stripslashes}
</div>
{if $smarty.session.sess_mem eq 1}
<a href="./?action=viewprofile&username={$userProfile.username}" class="button">{#Member_profile#}</a>
{/if}
</div>
<br class="clear" />
</div>
<br class="clear" />
