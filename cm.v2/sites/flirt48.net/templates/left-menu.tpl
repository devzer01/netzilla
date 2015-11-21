<!-- {$smarty.template} -->
<a href="?action=faqs" class="left-menu">{#FAQS#}</a>
{*{if $submenu eq "membership"}
<a href="?action=membership" class="left-menu active">{#MEMBERSHIP#}</a>
{else $submenu eq ""}
<a href="?action=membership" class="left-menu">{#MEMBERSHIP#}</a>
{/if}
{if $smarty.session.sess_username neq ""}
{if $submenu eq "membership"}
<a href="?action=membership" class="left-menusub">Hauptansicht</a>
<a href="?action=membership&amp;do=delete" class="left-menusub">Profil löschen</a>
{else}
{/if}
{/if}*}