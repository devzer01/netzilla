<h1 class="title" style="margin-top:15px;">
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
<div class="container-box-content-03">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m" style="width:900px !important;"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-03-m">{$content|nl2br}</div>
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m" style="width:900px !important;"></div>
	<div class="box-content-01-b-r"></div>
</div>
<!--End box content --> 
<br class="clear" />