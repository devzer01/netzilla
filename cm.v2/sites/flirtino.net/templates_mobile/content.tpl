<div class="container-content-02">
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
{elseif $smarty.get.action eq "refund"}
	Refund policy
{/if}
</h1>
<div class="content-page">
{$content|nl2br}
</div>
</div>