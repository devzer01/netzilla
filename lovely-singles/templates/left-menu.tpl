{*<a href="?action=faqs" class="butsearch">{#FAQS#}</a>
{if $submenu eq "membership"}
<a href="?action=membership" class="butsearch active">{#MEMBERSHIP#}</a>
{else $submenu eq ""}
<a href="?action=membership" class="butsearch">{#MEMBERSHIP#}</a>
{/if}
{if $smarty.session.sess_username neq ""}
{if $submenu eq "membership"}
<a href="?action=membership" class="butsearchsub">Hauptansicht</a>
<a href="?action=membership&amp;do=delete" class="butsearchsub">Profil löschen</a>
{else}
{/if}
{/if}*}