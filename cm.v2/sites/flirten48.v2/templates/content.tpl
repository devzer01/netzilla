<!-- {$smarty.template} -->

{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	<div id="container-content" class="container-content-full">
		<div class="title">
	    	<div class="title-left"></div><h1>
	    	{if $smarty.get.action eq "terms"}
				{#AGB#}
			{elseif $smarty.get.action eq "terms-2"}
				{#AGB#}
			{elseif $smarty.get.action eq "imprint"}
				{#IMPRESSUM#}
			{elseif $smarty.get.action eq "policy"}
				{#WIDERRUFSRECHT#}
			{elseif $smarty.get.action eq "policy-2"}
				{#WIDERRUFSRECHT#}
			{/if}
	    	</h1><div class="title-right"></div>
	    </div>
		<div class="container-content-text">
			{$content|nl2br}
		</div>
	</div>
{else}
	<div class="container-content">
		<div class="title">
	    	<div class="title-left"></div><h1>
	    	{if $smarty.get.action eq "terms"}
				{#AGB#}
			{elseif $smarty.get.action eq "terms-2"}
				{#AGB#}
			{elseif $smarty.get.action eq "imprint"}
				{#IMPRESSUM#}
			{elseif $smarty.get.action eq "policy"}
				{#WIDERRUFSRECHT#}
			{elseif $smarty.get.action eq "policy-2"}
				{#WIDERRUFSRECHT#}
			{/if}
	    	</h1><div class="title-right"></div>
	    </div>
	    <!--content -->
	    <div class="container-content-text">{$content|nl2br}</div>
	</div>
	{include file="left-notlogged.tpl"}
{/if}