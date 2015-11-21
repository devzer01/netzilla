<?php /* Smarty version 2.6.14, created on 2013-11-19 16:32:14
         compiled from left-membership_islogged.tpl */ ?>
<!-- <?php echo 'left-membership_islogged.tpl'; ?>
 -->
<li><a href="?action=profile"><img src="images/cm-theme/icon-profile.png"/><br /><span><?php echo $this->_config[0]['vars']['PROFILE']; ?>
</span></a></li>
<li><a href="?action=chat&type=inbox"><img src="images/cm-theme/icon-message.png"/><br /><span><?php echo $this->_config[0]['vars']['MESSAGES']; ?>
</span></a><div id="new_msg"></div></li>
<li><a href="?action=pay-for-coins"><img src="images/cm-theme/icon-buycoin.png"/><br /><span><?php echo $this->_config[0]['vars']['I_WANT_PAY_COINS']; ?>
</span></a></li>
<?php if ($_SESSION['sess_admin']): ?>
<li><a href="?action=administrator"><img src="images/cm-theme/icon-admin.png"/><br /><span>Admin<!--istrator --></span></a></li>
<?php else: ?>
<li><a href="?action=chat&username=<?php echo @ADMIN_USERNAME_DISPLAY; ?>
"><img src="images/cm-theme/icon-team.png"/><br /><span><?php echo @ADMIN_USERNAME_DISPLAY; ?>
</span></a></li>
<?php endif; ?>
<li><a href="?action=logout"><img src="images/cm-theme/icon-log-out.png"/><br /><span><?php echo $this->_config[0]['vars']['LOG_OUT']; ?>
</span></a></li>
