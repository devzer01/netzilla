<!-- {$smarty.template} -->
<!--<li><a href="?action=profile"><img src="images/cm-theme/icon-profile.png"/><br /><span>{#PROFILE#}</span></a></li>
<li><a href="?action=chat&type=inbox"><img src="images/cm-theme/icon-message.png"/><br /><span>{#MESSAGES#}</span></a><div id="new_msg"></div></li>
<li><a href="?action=pay-for-coins"><img src="images/cm-theme/icon-buycoin.png"/><br /><span>{#I_WANT_PAY_COINS#}</span></a></li>
{if $smarty.session.sess_admin}
<li><a href="?action=administrator"><img src="images/cm-theme/icon-admin.png"/><br /><span>Admin</span></a></li>
{else}
<li><a href="?action=chat&username={$smarty.const.ADMIN_USERNAME_DISPLAY}"><img src="images/cm-theme/icon-team.png"/><br /><span>Team</span></a></li>
{/if}
<li><a href="?action=logout"><img src="images/cm-theme/icon-log-out.png"/><br /><span>{#LOG_OUT#}</span></a></li> -->

<li><a href="?action=profile" class="profile"><span class="active">{#PROFILE#}</span></a></li>
<li><a href="?action=chat&type=inbox" class="chat"><span class="active">{#MESSAGES#}</span></a><div id="new_msg"></div></li>
<li><a href="?action=pay-for-coins" class="coins"><span class="active">{#I_WANT_PAY_COINS#}</span></a></li>
<li class="container-btn-login"><a href="?action=logout" class="login"><span class="active">{#LOG_OUT#}</span></a></li>

