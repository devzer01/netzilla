<li><a href="?action=profile" class="profile"><span>{#PROFILE#}</span></a></li>
<li><a href="?action=chat" class="chat"><span>{#MESSAGES#}</span><div id="new_msg"></div></a></li>
<li><a href="?action=pay-for-coins" class="coins"><span>{#I_WANT_PAY_COINS#}</span></a></li>
{if ($smarty.session.sess_admin) || ($smarty.session.sess_smalladmin) || ($smarty.session.sess_useradmin)}
<li><a href="?action=administrator" class="admin"><span>Admin</span></a></li>
{else}
<li><a href="?action=chat&username={$smarty.const.ADMIN_USERNAME_DISPLAY}" class="support"><span>{$smarty.const.ADMIN_USERNAME_DISPLAY}</span></a></li>
{/if}
<li><a href="?action=logout" class="logout"><span>{#LOG_OUT#}</span></a></li>