
<div class="result-box">
<h1>{#delete_account#}</h1>
<br class="clear" />

<div class="result-box-inside">
	{if $smarty.get.confirm eq 1}
		{#delete_account_successfully#}
	{else}
		{#delete_account_description#}<br/><br class="clear" />
		<a href="?action={$smarty.get.action}&confirm=1" class="btn-red">{#Yes#}</a> <a href="?action=profile" class="btn-red" style="margin-left:20px;">{#No#}</a>
	{/if}
<br class="clear"/>
</div></div>