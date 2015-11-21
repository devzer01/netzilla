<!-- {$smarty.template} -->
<div class="container-favoriten">
<h1 class="favoriten-title">
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

<div class="upload-file-foto">
{$content|nl2br}
</div>

</div>

