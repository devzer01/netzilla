<!-- {$smarty.template} -->
{if !$smarty.session.sess_externuser}
<form id="search_form" name="search_form" method="post" action="proc_from.php?from=./?action=search">
<label class="quicktext">{#Nickname#} :</label><span><input type="text" id="q_nickname" name="q_nickname" value="{$smarty.get.q_nickname}" class="txt-box-nickname"/></span>

<div style="float:left; padding:0 auto; margin:5px 0;">
<a href="#" onclick="document.getElementById('search_form').submit(); return false;" class="btn-regiter">{#Search#}</a>
</div>
</form>
{/if}