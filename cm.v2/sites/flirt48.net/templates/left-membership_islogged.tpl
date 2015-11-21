<!-- {$smarty.template} -->
<li><a href="?action=profile"><img src="images/cm-theme/icon-profile.png"/><br /><span>{#PROFILE#}</span></a></li>
<li><a href="?action=chat&type=inbox"><img src="images/cm-theme/icon-message.png"/><br /><span>{#MESSAGES#}</span></a><div id="new_msg"></div></li>
<li><a href="?action=pay-for-coins"><img src="images/cm-theme/icon-buycoin.png"/><br /><span>{#I_WANT_PAY_COINS#}</span></a></li>
{if ($smarty.session.sess_admin) || ($smarty.session.sess_smalladmin) || ($smarty.session.sess_useradmin)}
<li><a href="?action=administrator"><img src="images/cm-theme/icon-admin.png"/><br /><span>Admin<!--istrator --></span></a></li>
{else}
<li><a href="?action=chat&username={$smarty.const.ADMIN_USERNAME_DISPLAY}"><img src="images/cm-theme/icon-team.png"/><br /><span>{$smarty.const.ADMIN_USERNAME_DISPLAY}</span></a></li>
{/if}
<li><a href="?action=logout"><img src="images/cm-theme/icon-log-out.png"/><br /><span>{#LOG_OUT#}</span></a></li>

