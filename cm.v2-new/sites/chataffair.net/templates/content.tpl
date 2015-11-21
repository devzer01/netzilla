<!-- {$smarty.template} -->
<div class="container-box-content">
<h1 class="title">
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

</h1>

<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding:10px;">
{$content|nl2br}
</div>
</div>

</div>