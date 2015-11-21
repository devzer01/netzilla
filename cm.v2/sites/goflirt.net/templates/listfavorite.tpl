<!-- {$smarty.template} -->
{if $datas[0] != ""}

{section name=newmen loop=$datas}
<div class="result-box-inside">

<div style=" display:block; float:left; margin-right:10px; width:80px;">
{if $datas[newmen].picturepath !=""}
<a href="?action=viewprofile&username={$datas[newmen].username}" class="linklist"><img src="thumbnails.php?file={$datas[newmen].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
{else}
<a href="?action=viewprofile&username={$datas[newmen].username}" class="linklist"><img src="thumbs/default.jpg" height="104" width="78" border="0"  class="listimg"></a>
{/if}
</div>
<div style=" display:block; float:left; margin-right:10px; width:180px; height:72px;">
{#Name#}: {$datas[newmen].username}<br />
{#Area#}: {$datas[newmen].area}<br />
{#City#}: {$datas[newmen].city}<br />
{#Civil_status#}: {$datas[newmen].civilstatus}
</div>
<div style=" display:block; float:left; margin-right:10px; width:180px; height:72px">
{assign var="thisY" value=$datas[newmen].birthday|date_format:"%Y"}  
{assign var="Age" value="`$year-$thisY`"}
{#Age#}:  {$Age} {#Year#}<br />
{#Appearance#}: {$datas[newmen].appearance}<br />
{#Height#}: {$datas[newmen].height}<br />
</div>

<div style=" display:block; float:left; margin-right:10px; width:360px; min-height:40px;">
{#Description#}: {$datas[newmen].description|nl2br|truncate:40:"...":true|stripslashes}
</div>
<br class="clear" />
{if $smarty.session.sess_username != ""}
<a href="javascript:void(0)" class="button" onclick="if(confirm(confirm_delete_box)) goUrl('?action=favorite&do=del&searchChar={$smarty.get.searchChar}&username={$datas[newmen].username}')">{#Remove#}</a>
{ if $smarty.session.sess_mem=="1"}
    <a href="?action=viewprofile&username={$datas[newmen].username}" class="button">{#Member_profile#}</a>
    {else}
    <a href="javascript:void(0)" class="button" onClick="return GB_show('For members only', 'alert.php', 170, 420)">{#Member_profile#}</a>
{/if}
<!--{ if $smarty.session.sess_mem=="1"}
    <a href="?action=lonely_heart_ads_view&username={$datas[newmen].username}" class="button">{#Member_ADs#}</a>
    {else}
    <a href="#" class="button" onClick="return GB_show('For members only', 'alert.php', 170, 420)">{#Member_ADs#}</a>
{/if}-->
<!--{ if $smarty.session.sess_mem=="1"}
    <a href="?action=mymessage&type=writemessage&username={$datas[newmen].username}" class="button">{#Mail_to#}</a>
    {else}
    <a href="#" class="button" onClick="return GB_show('For members only', 'alert.php', 170, 420)">{#Mail_to#}</a>
{/if}-->

{/if}
<br class="clear" />
</div>
{/section}
<div class="page">{#page#} : {paginate_prev} {paginate_middle} {paginate_next}</div>
{else}
<div class="result-box">
<div class="result-box-inside">
{#Have_no_data_yet#}
</div>
</div>
{/if}