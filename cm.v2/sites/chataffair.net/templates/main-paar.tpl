<!-- {$smarty.template} -->
{include file="newest-menu.tpl"}
{if $countNewProfile > 0}
	<div class="result-box">
	{section name=i loop=$pairsResult}

	{******************}
		<div class="result-box-inside">
			<div style=" display:block; float:left; margin-right:10px; width:80px;">
                {if $pairsResult[i].picturepath !=""}
                <a href="thumbnails.php?file={$pairsResult[i].picturepath}" title="{$pairsResult[i].username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbnails.php?file={$pairsResult[i].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
                {else}
                <a href="?action=viewprofile&username={$pairsResult[i].username}" class="linklist"><img src="thumbs/default.jpg" height="104" width="78" border="0"  class="listimg"></a>
                {/if}
			</div>
            <div style=" display:block; float:left; margin-right:10px; width:280px;"">
                {#Name#}: <a href="?action=viewprofile&username={$pairsResult[i].username}" class="link-inrow">{$pairsResult[i].username}</a><br />
                {#City#}: {$pairsResult[i].city}<br />
                {#Civil_status#}: {$pairsResult[i].civilstatus}
            </div>
            {assign var="thisY" value=$pairsResult[i].birthday|date_format:"%Y"}  
            {assign var="Age" value="`$year-$thisY`"}
            {#Age#}:  {$Age} {#Year#}<br />
            {#Appearance#}: {$pairsResult[i].appearance}<br />
            {#Height#}: {$pairsResult[i].height}<br />
            {#Description#}: {$pairsResult[i].description|nl2br|truncate:40:"...":true|stripslashes}<br />

            {if $smarty.session.sess_mem eq 1}
            <a href="./?action=viewprofile&username={$pairsResult[i].username}" class="button">Jetzt Details sehen!</a>
            {/if}
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