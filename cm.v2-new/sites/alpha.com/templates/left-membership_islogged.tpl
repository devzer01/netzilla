<li><a href="?action=profile" class="profile"><span>profile</span></a></li>
<li><a href="?action=chat&type=inbox" class="chat"><span>chat</span></a></li>
<li><a href="?action=pay-for-coins" class="coins"><span>coins</span></a></li>
{if $smarty.session.sess_admin}
	<li><a href="?action=administrator" class="admin"><span>Admin</span></a></li>
{else}
	<li><a href="?action=chat&username={$smarty.const.ADMIN_USERNAME_DISPLAY}" class="admin"><span>{$smarty.const.ADMIN_USERNAME_DISPLAY}</span></a></li>
{/if}            
<li><a href="?action=logout" class="logout"><span>logout</span></a></li>