<h1 class="title">{#delete_account#}</h1>

<div id="container-content-profile-home">

	{if $smarty.get.confirm eq 1}
		{#delete_account_successfully#}
	{else}
		{#delete_account_description#}<br/><br class="clear" />
		<a href="?action={$smarty.get.action}&confirm=1" class="btn-red">{#Yes#}</a> <a href="?action=profile" class="btn-red" style="margin-left:20px;">{#No#}</a>
	{/if}
<br class="clear"/>
</div>