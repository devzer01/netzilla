{include file="newest-menu.tpl"}
{if $countNewProfile > 0}
	<div class="result-box">
	{section name=i loop=$frauenResult}
	{******************}
		<div class="result-box-inside">
			<div style=" display:block; float:left; margin-right:10px; width:80px;">
				{if $smarty.session.sess_username !=""}
				<a href="thumbnails.php?file={$frauenResult[i].picturepath}" title="{$frauenResult[i].username|regex_replace:'/@.*/':''}" class="linklist lightview"><img src="thumbnails.php?file={$frauenResult[i].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
				{else}
				<a href="?action=register&type=membership&cate=profile&username={$frauenResult[i].username}" class="linklist"><img src="thumbnails.php?file={$frauenResult[i].picturepath}&w=78&h=104" height="104" width="78" border="0"  class="listimg"></a>
				{/if}
			</div>
			<div style=" display:block; float:left; margin-right:10px; width:280px;"">
				{#Name#}: <a href="?action=viewprofile&username={$frauenResult[i].username}" class="link-inrow">{$frauenResult[i].username}</a><br />
				{#City#}: {$frauenResult[i].city}<br />
				{#Civil_status#}: {$frauenResult[i].civilstatus}
			</div>
            <div style=" display:block; float:left; margin-right:10px; width:280px;"">
			{assign var="thisY" value=$frauenResult[i].birthday|date_format:"%Y"}  
			{assign var="Age" value="`$year-$thisY`"}
			{#Age#}:  {$Age} {#Year#}<br />
			{#Appearance#}: {$frauenResult[i].appearance}<br />
			{#Height#}: {$frauenResult[i].height}
            </div>
            <div style=" display:block; float:left; margin-right:10px; width:560px; text-align:left;">
			{#Description#}: {$frauenResult[i].description|nl2br|truncate:40:"...":true|stripslashes}<br />
		{if $smarty.session.sess_username != ""}	    
	    { if $smarty.session.sess_mem=="1"}
	    	<a href="?action=viewprofile&username={$frauenResult[i].username}" class="button">{#Member_profile#}</a>
	    {else}
	    	<a href="#" class="button" onClick='return GB_show("Nur fÃ¼r Mitglieder", "alert.php", 170, 420)'>{#Member_profile#}</a>
	    {/if}
        {else}
            <a href="?action=register&amp;type=membership&amp;cate=lonely&amp;username={$frauenResult[i].username}" class="button">{#Mail_to#}</a>
        {/if}
		</div></div>
		{/section}
        <br class="clear" />
		</div>
		{else}
		<div class="result-box">
		<div class="result-box-inside">
		{#Have_no_data_yet#}
		</div>
	</div>
{/if}