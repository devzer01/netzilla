<!-- {$smarty.template} -->
<div class="listbox">
	<h1>{#newest_lonely_heart#}</h1>
	<div class="listboxin">
		<ul>
        {section name="MLonelyHeart" loop=$MLonelyHeart}
        <li>
            <div class="listname">
                {$MLonelyHeart[MLonelyHeart].username|regex_replace:"/@.*/":""}
                {assign var="thisY" value=$MLonelyHeart[MLonelyHeart].birthday|date_format:"%Y"}
                {assign var="Age" value="`$year-$thisY`"}
                ({$Age})
            </div>
            <div class="listleft">
                {if $MLonelyHeart[MLonelyHeart].picturepath ne ""}
                    {if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&username={$MLonelyHeart[MLonelyHeart].username}" class="linklist"><img border="0" src="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}&w=78&h=104" height="104" width="78" onload="if(this.width > 78) this.width = 78; if(this.height > 104) this.height = 104;" class="listimg"/></a>
                    {else}
                    <a href="./?action=register&type=membership&cate=lonely&username={$MLonelyHeart[MLonelyHeart].username}"><img border="0" src="thumbnails.php?file={$MLonelyHeart[MLonelyHeart].picturepath}&w=78&h=104" height="104" width="78" onload="if(this.width > 78) this.width = 78; if(this.height > 104) this.height = 104;" class="listimg"/></a>
                    {/if}
                {else}
                    {if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&username={$MLonelyHeart[MLonelyHeart].username}" class="linklist"><img border="0" src="thumbs/default.jpg"  width="78" height="104" class="listimg"  /></a>
                    {else}
                    <a href="?action=register&type=membership&cate=lonely&username={$MLonelyHeart[MLonelyHeart].username}"><img border="0" src="thumbs/default.jpg"  width="78" height="104" class="listimg"  /></a>
                    {/if}
                {/if}                
            </div>
            <div class="listright">
                {$MLonelyHeart[MLonelyHeart].city}<br />
                {#male#}, {$MLonelyHeart[MLonelyHeart].civilstatus}<br />
                {#looking_for#}:<br />
                {if $MLonelyHeart[MLonelyHeart].lookmen==1}
                {#Men#}
                {/if}
                
                {if $MLonelyHeart[MLonelyHeart].lookwomen==1}
                {#Women#}
                {/if}<br />
                {if $smarty.session.sess_mem eq 1}
                <a href="?action=mymessage&type=writemessage&username={$MLonelyHeart[MLonelyHeart].username}" class="button">{#Mail_to#}</a>
                {else}
                <a href="?action=register&type=membership&cate=lonely&username={$MLonelyHeart[MLonelyHeart].username}" class="button">{#Mail_to#}</a>
                {/if}
            </div>
        </li>
        {/section}
        {section name="FLonelyHeart" loop=$FLonelyHeart}
        <li>
            <div class="listname">
                {$FLonelyHeart[FLonelyHeart].username|regex_replace:"/@.*/":""}
                {assign var="thisY" value=$FLonelyHeart[FLonelyHeart].birthday|date_format:"%Y"}
                {assign var="Age" value="`$year-$thisY`"}
                ({$Age})
            </div>
            <div class="listleft">
                {if $FLonelyHeart[FLonelyHeart].picturepath ne ""}
                    {if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&username={$FLonelyHeart[FLonelyHeart].username}" class="linklist"><img border="0" src="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}&w=78&h=104" height="104" width="78" onload="if(this.width > 78) this.width = 78; if(this.height > 104) this.height = 104;" class="listimg"/></a>
                    {else}
                    <a href="./?action=register&type=membership&cate=lonely&username={$FLonelyHeart[FLonelyHeart].username}"><img border="0" src="thumbnails.php?file={$FLonelyHeart[FLonelyHeart].picturepath}&w=78&h=104" height="104" width="78" onload="if(this.width > 78) this.width = 78; if(this.height > 104) this.height = 104;" class="listimg"/></a>
                    {/if}
                {else}
                    {if $smarty.session.sess_mem eq 1}
                    <a href="?action=viewprofile&username={$FLonelyHeart[FLonelyHeart].username}" class="linklist"><img border="0" src="thumbs/default.jpg"  width="78" height="104" class="listimg"  /></a>
                    {else}
                    <a href="?action=register&type=membership&cate=lonely&username={$FLonelyHeart[FLonelyHeart].username}"><img border="0" src="thumbs/default.jpg"  width="78" height="104" class="listimg"  /></a>
                    {/if}
                {/if}
            </div>
            <div class="listright">
                {$FLonelyHeart[FLonelyHeart].city}<br />
                {#female#}<br />
                {#looking_for#}:
                {if $FLonelyHeart[FLonelyHeart].lookmen==1}
                {#Men#}
                {/if}
                
                {if $FLonelyHeart[FLonelyHeart].lookwomen==1}
                {#Women#}
                {/if}<br /><br />
                {if $smarty.session.sess_mem eq 1}
                <a href="?action=mymessage&type=writemessage&username={$FLonelyHeart[FLonelyHeart].username}" class="button">{#Mail_to#}</a>
                {else}
                <a href="?action=register&type=membership&cate=lonely&username={$FLonelyHeart[FLonelyHeart].username}" class="button">{#Mail_to#}</a>
                {/if}
            </div>
        </li>
        {/section}
		</ul>
	</div>
</div>