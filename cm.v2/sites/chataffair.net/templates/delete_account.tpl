<div class="container-box-content">
<h1 class="title">
{#delete_account#}
</h1>

<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; padding:10px;">
{if $smarty.get.confirm eq 1}
    {#delete_account_successfully#}
{else}
    {#delete_account_description#}<br/>
    <a href="?action={$smarty.get.action}&confirm=1" class="btn-red">{#Yes#}</a> <a href="?action=profile" class="btn-red">{#No#}</a>
{/if}
</div>
</div>

</div>