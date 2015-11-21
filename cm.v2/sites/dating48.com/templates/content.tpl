<!-- {$smarty.template} -->
<div id="container-content">
<h1>
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

<div class="container-content-profile-box">
<div class="content-profile-box">
{$content|nl2br}
</div>
</div>
</div>

