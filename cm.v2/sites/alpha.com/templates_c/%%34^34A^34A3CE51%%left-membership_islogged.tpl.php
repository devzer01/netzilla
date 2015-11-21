<?php /* Smarty version 2.6.14, created on 2013-11-19 03:00:33
         compiled from left-membership_islogged.tpl */ ?>
<li><a href="?action=profile" class="profile"><span>profile</span></a></li>
<li><a href="?action=chat&type=inbox" class="chat"><span>chat</span></a></li>
<li><a href="?action=pay-for-coins" class="coins"><span>coins</span></a></li>
<?php if ($_SESSION['sess_admin']): ?>
	<li><a href="?action=administrator" class="admin"><span>Admin</span></a></li>
<?php else: ?>
	<li><a href="?action=chat&username=<?php echo @ADMIN_USERNAME_DISPLAY; ?>
" class="admin"><span><?php echo @ADMIN_USERNAME_DISPLAY; ?>
</span></a></li>
<?php endif; ?>            
<li><a href="?action=logout" class="logout"><span>logout</span></a></li>