<div class="result-box">
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
{if ($smarty.get.action eq "terms") or ($smarty.get.action eq "terms-2")}
<ul class="container-flag">
<li><a href="?action=terms"><img src="images/uk.png" width="30" height="30" /></a></li>
<li><a href="?action=terms-2"><img src="images/ger.png" width="30" height="30" /></a></li>
</ul>
{elseif ($smarty.get.action eq "policy") or ($smarty.get.action eq "policy-2")}
<ul class="container-flag">
<li><a href="?action=policy"><img src="images/uk.png" width="30" height="30" /></a></li>
<li><a href="?action=policy-2"><img src="images/ger.png" width="30" height="30" /></a></li>
</ul>
{/if}
</h1>
<br class="clear" />

<div class="result-box-inside">

{$content|nl2br}

</div>

</div>