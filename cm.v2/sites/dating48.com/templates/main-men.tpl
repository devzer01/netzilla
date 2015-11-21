<!-- {$smarty.template} -->
{if $countNewProfile > 0}
	<div class="result-box">
	{section name=i loop=$mannResult}
	{******************}
		<div class="result-box-inside">
			<div style=" display:block; float:left; margin-right:30px; width:80px;">
                {if $smarty.session.sess_username !=""}
                <a href="thumbnails.php?file={$mannResult[i].picturepath}" title="{$mannResult[i].username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbnails.php?file={$mannResult[i].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
                {else}
                <a href="?action=register&type=membership&cate=profile&username={$mannResult[i].username}" class="linklist"><img src="thumbnails.php?file={$mannResult[i].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
                {/if}
			</div>
            <div style=" display:block; float:left; margin-right:10px; width:280px;"">
                {#Name#}:
				{if $smarty.session.sess_username != ""}
					<a href="?action=viewprofile&username={$mannResult[i].username}" class="link-inrow">{$mannResult[i].username}</a>
				{else}
					<a href="?action=register&amp;type=membership&amp;cate=profile&amp;username={$mannResult[i].username}" class="link-inrow">{$mannResult[i].username}</a>
				{/if}
				<br />
                {#City#}: {$mannResult[i].city}<br />
                {#Civil_status#}: {$mannResult[i].civilstatus}
            </div>
             <div style=" display:block; float:left; margin-right:10px; width:280px;"">
            {assign var="thisY" value=$mannResult[i].birthday|date_format:"%Y"}  
            {assign var="Age" value="`$year-$thisY`"}
            {#Age#}:  {$Age} {#Year#}<br />
            {#Appearance#}: {$mannResult[i].appearance}<br />
            {#Height#}: {$mannResult[i].height}
            </div>
            <div style=" display:block; float:left; margin-right:10px; width:560px; text-align:left;">
            {#Description#}: {$mannResult[i].description|nl2br|truncate:40:"...":true|stripslashes}<br />

        {if $smarty.session.sess_username != ""}	    
			{ if $smarty.session.sess_mem=="1"}
				<a href="?action=viewprofile&username={$mannResult[i].username}" class="button">{#Member_profile#}</a>
			{else}
				<a href="#" class="button" onClick='return GB_show("Nur für Mitglieder", "alert.php", 170, 420)'>{#Member_profile#}</a>
			{/if}
        {else}
            <a href="?action=register&amp;type=membership&amp;cate=profile&amp;username={$mannResult[i].username}" class="button">{#Mail_to#}</a>
        {/if}
        </div>
		</div>
	{/section}
	</div>
{else}
	<div class="result-box">
		<div class="result-box-inside">
			{#Have_no_data_yet#}
		</div>
	</div>
{/if}