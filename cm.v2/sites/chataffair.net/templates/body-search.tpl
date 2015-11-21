<!-- {$smarty.template} -->
<div class="result-box">

	{if $datas[0] != ""}
		 
	    {if $data_total eq 0 and $smarty.get.action eq "search"}
	    	{if $smarty.get.next le 1}
		    	<div class="result-box-inside">
		    		<b>{#No_Ads_Found#}</b>
		    	</div>
		    {/if}    
	    {/if}
	    
	    {if $noresult eq 1}
	    	{if $smarty.get.next le 1}
	    	<div class="result-box-inside">
	    		<b>{#No_Ads_Found#}</b>
	    	</div>
	    	{/if}
	    {/if}
	
	    {if $smarty.session.resulttype eq 2}
	        <!--{if (!$extended && $data_total >= $maxProfiles)}
	                <b>Deine Suche ergab mehr als {$maxProfiles} Treffer. Um ein besseres Suchresultat zu erreichen, gib bitte weitere Kriterien an.</b>
	        {elseif (!$extended && $data_total > $minProfiles)}
	                <b>Deine Suche ergab {$data_total} Treffer.</b>
	        {/if}-->
	    {/if}
	
		{assign var="adv_headline" value="no"}
	    {section name=newmen loop=$datas}

	        {if $smarty.session.resulttype != 2 }
	        <!-- profile -->
	        	{*if $datas[newmen].advanced_result eq "yes" && $adv_headline eq "no"}			
					{assign var="adv_headline" value="yes"}
					<h1>{#additional_search#}</h1>
		    	{elseif $datas.advanced_result eq "yes"}
					<h1>{#additional_search#}</h1>
				{elseif $noresult eq 1 && $smarty.section.newmen.first}	
					<h1>{#additional_search#}</h1>
				{/if*}
				
				{if $datas[newmen].advanced_result eq "yes"}
					<h1>{#additional_search#}</h1>
				{else}
		        	<div class="result-box-inside">
						{include file="body-search-profile.tpl" datas=$datas[newmen]}
	                    <br class="clear" />
					</div>
				{/if}
				
			{else}
			<!-- ads -->
				{if $datas[newmen].advanced_result eq "yes"}
					<h1>{#additional_search#}</h1>
				{else}	        	
	        		<div class="result-box-inside"> 
						{include file="body-search-ads.tpl" datas=$datas[newmen]}
					</div>
	        	{/if}
			{/if}
	     
	    {/section}
	     
	{else}
	    {if $smarty.get.next le 1}
	    <div class="result-box-inside">
	    	{#No_Ads_Found#}
	    </div>
	    {/if}
	    
	{/if}

	{if $smarty.get.action eq "show_advsearch"}
    <br class="clear" />
		<div class="page">{$show_advsearch_pages}</div>
	{else}
    <br class="clear" />
		<div class="page">{paginate_prev} {paginate_middle} {paginate_next}</div>
	{/if}
    
</div>		