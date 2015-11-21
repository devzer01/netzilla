<!-- {$smarty.template} -->
<li><a href="?action=profile">{#PROFILE#}</a></li>
<li><a href="?action=chat&type=inbox">{#MESSAGES#}</a><div id="new_msg"></div></li>
<li><a href="?action=pay-for-coins">{#I_WANT_PAY_COINS#}</a></li>
{if $smarty.session.sess_admin}
	<li><a href="?action=administrator">Admin</a></li>
{else}
	<li><a href="?action=chat&username={$smarty.const.ADMIN_USERNAME_DISPLAY}">{$smarty.const.ADMIN_USERNAME_DISPLAY}</a></li>
{/if}
<li><a href="?action=logout">{#LOG_OUT#}</a></li>

