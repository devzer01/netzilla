<h2 class="title" style="margin:10px 0 0 0;">{#delete_account#}</h2>

<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px;">
	{if $smarty.get.confirm eq 1}
		{#delete_account_successfully#}
	{else}
		{#delete_account_description#}<br/>
		<a href="?action={$smarty.get.action}&confirm=1" class="btn-red">{#Yes#}</a> <a href="?action=profile" class="btn-red">{#No#}</a>
	{/if}
<br class="clear"/>
</div>
</div>