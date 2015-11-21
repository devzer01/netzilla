{if !$smarty.session.sess_externuser}
<form id="search_form" name="search_form" method="post" action="proc_from.php?from=./?action=search">
<div class="qsboxsearch">
<label>{#Nickname#} :</label><input type="text" id="q_nickname" name="q_nickname" class="box"  value="{$smarty.get.q_nickname}"/>
<br clear="all" />
<a href="#" onclick="document.getElementById('search_form').submit(); return false;" class="butsearchbox">{#Search#}</a>
</div>
</form>
{/if}