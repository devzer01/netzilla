<div class="listbox">
	<h1>{#newest_lonely_heart#}</h1>
	<div class="listboxin">
		<ul>
        {section name="MLonelyHeart" loop=$MLonelyHeart}
        <li>
            <div class="listname">
				{if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&amp;username={$MLonelyHeart[MLonelyHeart].username}" class="link-inrow">{$MLonelyHeart[MLonelyHeart].username|regex_replace:"/@.*/":""}</a>
				{else}
                    <a href="./?action=register&amp;type=membership&amp;cate=lonely&amp;username={$MLonelyHeart[MLonelyHeart].username}">{$MLonelyHeart[MLonelyHeart].username|regex_replace:"/@.*/":""}</a>
				{/if}
                {assign var="thisY" value=$MLonelyHeart[MLonelyHeart].birthday|date_format:"%Y"}
                {assign var="Age" value="`$year-$thisY`"}
                ({$Age})
            </div>
            <div class="listleft">
				<a href="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}" title="{$MLonelyHeart[MLonelyHeart].username|regex_replace:'/@.*/':''} ({$Age})" class="lightview"><img border="0" src="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}&amp;w=78&amp;h=104" height="104" width="78" class="listimg" alt="{$MLonelyHeart[MLonelyHeart].username}"/></a>
            </div>
            <div class="listright">
				<div style="height:75px; overflow:hidden;">
					{$MLonelyHeart[MLonelyHeart].headline|stripslashes|substr:0:30|replace:"\n":""|wordwrap:20:"<br />":true}<br />
					{$MLonelyHeart[MLonelyHeart].text|stripslashes|substr:0:30|replace:"\n":""|wordwrap:25:"<br />":true}...<br />
				</div>
                {if $smarty.session.sess_mem eq 1}
                <a href="?action=lonely_heart_ads_view&amp;username={$MLonelyHeart[MLonelyHeart].username}&amp;backurl=index" class="button">{#Read_more#}</a>
                {else}
                <a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$MLonelyHeart[MLonelyHeart].username}" class="button">{#Read_more#}</a>
                {/if}
            </div>
        </li>
        {/section}
        {section name="FLonelyHeart" loop=$FLonelyHeart}
        <li>
            <div class="listname">
				{if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&amp;username={$FLonelyHeart[FLonelyHeart].username}" class="link-inrow">{$FLonelyHeart[FLonelyHeart].username|regex_replace:"/@.*/":""}</a>
				{else}
                    <a href="./?action=register&amp;type=membership&amp;cate=lonely&amp;username={$FLonelyHeart[FLonelyHeart].username}">{$FLonelyHeart[FLonelyHeart].username|regex_replace:"/@.*/":""}</a>
				{/if}
                {assign var="thisY" value=$FLonelyHeart[FLonelyHeart].birthday|date_format:"%Y"}
                {assign var="Age" value="`$year-$thisY`"}
                ({$Age})
            </div>
            <div class="listleft">
                    <a href="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}" title="{$FLonelyHeart[FLonelyHeart].username|regex_replace:'/@.*/':''} ({$Age})" class="lightview"><img border="0" src="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}&amp;w=78&amp;h=104" height="104" width="78" class="listimg" alt="{$FLonelyHeart[FLonelyHeart].username}"/></a>
            </div>
            <div class="listright">
				<div style="height:75px; overflow:hidden;">
					{$FLonelyHeart[FLonelyHeart].headline|stripslashes|substr:0:30|replace:"\n":""|wordwrap:20:"<br />":true}<br />
					{$FLonelyHeart[FLonelyHeart].text|stripslashes|substr:0:30|replace:"\n":""|wordwrap:25:"<br />":true}...<br />
				</div>
                {if $smarty.session.sess_mem eq 1}
                <a href="?action=lonely_heart_ads_view&amp;username={$FLonelyHeart[FLonelyHeart].username}&amp;backurl=index" class="button">{#Read_more#}</a>
                {else}
                <a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$FLonelyHeart[FLonelyHeart].username}" class="button">{#Read_more#}</a>
                {/if}
            </div>
        </li>
        {/section}
		</ul>
	</div>
</div>