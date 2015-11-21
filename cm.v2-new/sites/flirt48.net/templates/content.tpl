<!-- {$smarty.template} -->
<h2 class="title" style="margin:10px 0 0 0;">
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

</h2>

<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px;">
{$content|nl2br}
</div>
</div>

