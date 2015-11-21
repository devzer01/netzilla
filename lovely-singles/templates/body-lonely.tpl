<div class="result-box">
<div class="result-box-inside">
<div style=" display:block; float:left; margin-right:10px; width:80px;">
{if $lonelyProfile.picturepath !=""}
<a href="?action=viewprofile&username={$lonelyProfile.username}" class="linklist"><img src="thumbnails.php?file={$lonelyProfile.picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
{else}
<a href="?action=viewprofile&username={$lonelyProfile.username}" class="linklist"><img src="thumbs/default.jpg" height="104" width="78" border="0"  class="listimg"></a>
{/if}
</div>
<div style="display:block; float:left; text-align:left; margin-right:10px; width:280px;">
{#Name#}: {$lonelyProfile.username|regex_replace:"/@.*/":""}<br />
{#City#}: {$lonelyProfile.city}<br />
{#Civil_status#}: {$lonelyProfile.civilstatus}
</div>
<div style=" display:block; float:left; margin-right:10px; width:280px; text-align:left;">
{assign var="thisY" value=$lonelyProfile.birthday|date_format:"%Y"}  
{assign var="Age" value="`$year-$thisY`"}
{#Age#}:  {$Age} {#Year#}<br />
{#Appearance#}: {$lonelyProfile.appearance}<br />
{#Height#}: {$lonelyProfile.height}<br />
</div>
<div style=" display:block; float:left; margin-right:10px; width:560px; text-align:left;">
{#Description#}: {$lonelyProfile.description|nl2br|truncate:40:"...":true|stripslashes}
</div>
{if $smarty.session.sess_mem eq 1}
<a href="./?action=viewprofile&username={$lonelyProfile.username}" class="button">Jetzt Details sehen!</a>
{/if}
</div>
</div>