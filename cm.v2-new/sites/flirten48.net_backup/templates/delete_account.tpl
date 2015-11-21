<div class="container-favoriten">
<h1 class="favoriten-title">{#delete_account#}</h1>
    <div class="upload-file-foto">
        {if $smarty.get.confirm eq 1}
            {#delete_account_successfully#}
        {else}
            {#delete_account_description#}<br/>
            <div style="margin-top:20px;">
                <a href="?action={$smarty.get.action}&confirm=1" class="btn-search" style=" padding-left:20px; padding-right:20px;">{#Yes#}</a>
                <a href="?action=profile" class="btn-search" style=" padding-left:20px; padding-right:20px;">{#No#}</a>
            </div>
        {/if}
    <br class="clear"/>
    </div>
</div>