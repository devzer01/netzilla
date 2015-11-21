<!-- {$smarty.template} -->
<h2>{#newest_lonely_heart#}</h2>
<ul>
{section name="FLonelyHeart" loop=$FLonelyHeart}
<li>
<a href="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}" title="{$FLonelyHeart[FLonelyHeart].username|regex_replace:'/@.*/':''} ({$Age})" class="lightview"><img border="0" src="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}&amp;w=219&amp;h=120" width="219" height="120" class="listimg" alt="{$FLonelyHeart[FLonelyHeart].username}"/></a>
<p>
<strong>
{if $smarty.session.sess_mem eq 1}
<a href="?action=viewprofile&amp;username={$FLonelyHeart[FLonelyHeart].username}">{$FLonelyHeart[FLonelyHeart].username|regex_replace:"/@.*/":""}</a>
{else}
<a href="./?action=register&amp;type=membership&amp;cate=lonely&amp;username={$FLonelyHeart[FLonelyHeart].username}">{$FLonelyHeart[FLonelyHeart].username|regex_replace:"/@.*/":""}</a>
{/if}
</strong>
<span style="float:right;">
<strong>
{assign var="thisY" value=$FLonelyHeart[FLonelyHeart].birthday|date_format:"%Y"}
{assign var="Age" value="`$year-$thisY`"}
Age: ({$Age})
</strong>
</span>
</p>
<p style="height:60px; overflow:hidden;">
{$FLonelyHeart[FLonelyHeart].headline|stripslashes|substr:0:20|replace:"\n":"<br>"|wordwrap:20:"<br />":true}<br />
{$FLonelyHeart[FLonelyHeart].text|stripslashes|substr:0:30|replace:"\n":"<br>"|wordwrap:23:"<br />":true}...<br />
</p>
{if $smarty.session.sess_mem eq 1}
<a href="?action=lonely_heart_ads_view&amp;username={$FLonelyHeart[FLonelyHeart].username}&amp;backurl=index" class="btn-regiter">{#Read_more#}</a>
{else}
<a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$FLonelyHeart[FLonelyHeart].username}" class="btn-regiter">{#Read_more#}</a>
{/if}
</li>
{/section}
{section name="MLonelyHeart" loop=$MLonelyHeart}
<li>
<a href="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}" title="{$MLonelyHeart[MLonelyHeart].username|regex_replace:'/@.*/':''} ({$Age})" class="lightview"><img border="0" src="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}&amp;w=219&amp;h=120" width="219" height="120" alt="{$MLonelyHeart[MLonelyHeart].username}"/></a>
<p>
<strong>
{if $smarty.session.sess_mem eq 1}
<a href="?action=viewprofile&amp;username={$MLonelyHeart[MLonelyHeart].username}" class="link-inrow">{$MLonelyHeart[MLonelyHeart].username|regex_replace:"/@.*/":""}</a>
{else}
<a href="./?action=register&amp;type=membership&amp;cate=lonely&amp;username={$MLonelyHeart[MLonelyHeart].username}">{$MLonelyHeart[MLonelyHeart].username|regex_replace:"/@.*/":""}</a>
{/if}
</strong>
<span style="float:right;">
<strong>
{assign var="thisY" value=$MLonelyHeart[MLonelyHeart].birthday|date_format:"%Y"}
{assign var="Age" value="`$year-$thisY`"}
Age:({$Age})
</strong>
</span>
</p>
<p style="height:60px; overflow:hidden;">
{$MLonelyHeart[MLonelyHeart].headline|stripslashes|substr:0:20|replace:"\n":"<br>"|wordwrap:20:"<br />":true}<br />
{$MLonelyHeart[MLonelyHeart].text|stripslashes|substr:0:30|replace:"\n":"<br>"|wordwrap:23:"<br />":true}...<br />
</p>
{if $smarty.session.sess_mem eq 1}
<a href="?action=lonely_heart_ads_view&amp;username={$MLonelyHeart[MLonelyHeart].username}&amp;backurl=index" class="btn-regiter">{#Read_more#}</a>
{else}
<a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$MLonelyHeart[MLonelyHeart].username}" class="btn-regiter">{#Read_more#}</a>
{/if}
</li>
{/section}

</ul>