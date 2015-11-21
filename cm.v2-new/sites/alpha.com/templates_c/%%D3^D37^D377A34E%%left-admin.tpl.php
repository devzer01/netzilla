<?php /* Smarty version 2.6.14, created on 2013-11-19 03:00:41
         compiled from left-admin.tpl */ ?>
<!-- <?php echo 'left-admin.tpl'; ?>
 -->
<a href="./" class="left-menu">Front end</a>
<a href="?action=admin_manageuser" class="left-menu">Manage users</a>
<?php if ($_SESSION['sess_permission'] == 1): ?>
	<a href="?action=admin_copyfakeprofiles" class="left-menu">Copy fake profiles</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_copyfakeprofiles'): ?>
		<a href="?action=admin_copyfakeprofiles" class="left-menusub">Search</a>
		<a href="?action=admin_copyfakeprofiles_already" class="left-menusub">Already copied</a>
	<?php endif; ?>
	<a href="?action=admin_message" class="left-menu">Admin messages</a>
	<!-- <a href="?action=admin_managecard" class="left-menu">Manage E-cards</a> -->
	<a href="?action=admin_manage_picture" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_manage_picture'): ?> active<?php endif; ?>">Manage Pictures</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_manage_picture'): ?>
		<a href="?action=admin_manage_picture&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_manage_picture&type=gallery" class="left-menusub">Gallery</a>
	<?php endif; ?>

	<a href="?action=admin_approval" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_approval'): ?> active<?php endif; ?>">Approval</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_approval'): ?>
		<a href="?action=admin_approval&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_approval&type=gallery" class="left-menusub">Gallery</a>
		<a href="?action=admin_approval&type=description" class="left-menusub">Description</a>
		<a href="?action=admin_approval&type=delete_account" class="left-menusub">Delete accounts</a>
	<?php endif; ?>

	<a href="?action=admin_new_members" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_new_members'): ?> active<?php endif; ?>">Newest members</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_new_members'): ?>
		<a href="?action=admin_new_members&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_new_members&r=yesterday" class="left-menusub">Yesterday</a>
		<a href="?action=admin_new_members&r=week" class="left-menusub">This week</a>
		<a href="?action=admin_new_members&r=month" class="left-menusub">This Month</a>
		<a href="?action=admin_new_members&r=search" class="left-menusub">Search</a>
	<?php endif; ?>

	<a href="?action=admin_manage_contents" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_manage_contents'): ?> active<?php endif; ?>">Manage contents</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_manage_contents'): ?>
		<a href="?action=admin_manage_contents&page=terms" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_TERMS']; ?>
 (DE)</a>
		<a href="?action=admin_manage_contents&page=terms-2" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_TERMS']; ?>
 (EN)</a>
		<a href="?action=admin_manage_contents&page=imprint" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_IMPRINT']; ?>
</a>
		<a href="?action=admin_manage_contents&page=policy" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_PRIVACY']; ?>
 (DE)</a>
		<a href="?action=admin_manage_contents&page=policy-2" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_PRIVACY']; ?>
 (EN)</a>
		<a href="?action=admin_manage_contents&page=refund" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_REFUND']; ?>
 (DE)</a>
		<a href="?action=admin_manage_contents&page=refund-2" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_REFUND']; ?>
 (EN)</a>
	<?php endif; ?>

	<a href="?action=admin_managecoin" class="left-menu">Manage coin</a>
	<a href="?action=admin_manage_package" class="left-menu">Manage coin packages</a>
	<a href="?action=admin_coin_statistics" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_coin_statistics'): ?> active<?php endif; ?>">Coin statistics</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_coin_statistics'): ?>
		<a href="?action=admin_coin_statistics&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_coin_statistics&r=week" class="left-menusub">Last 7 days</a>
		<a href="?action=admin_coin_statistics&r=month" class="left-menusub">Last 30 days</a>
	<?php endif; ?>
	<a href="?action=admin_manage_bonus" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_bonus'): ?> active<?php endif; ?>">Bonus</a>
	<?php if ($this->_tpl_vars['submenu'] == 'admin_bonus'): ?>
		<a href="?action=admin_manage_bonus" class="left-menusub"><?php echo $this->_config[0]['vars']['MANAGE_BONUS']; ?>
</a>
		<a href="?action=admin_bonus_history" class="left-menusub"><?php echo $this->_config[0]['vars']['BONUS_HISTORY']; ?>
</a>
	<?php endif; ?>
	<a href="?action=admin_sms_provider" class="left-menu">SMS Provider</a>
	<a href="?action=admin_emoticons" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_emoticons'): ?> active<?php endif; ?>">Emoticons</a>
	<a href="?action=admin_chat_logs" class="left-menu"><?php echo $this->_config[0]['vars']['LOG_CHAT']; ?>
</a>
	<a href="?action=admin_paid" class="left-menu<?php if ($this->_tpl_vars['submenu'] == 'admin_paid'): ?> active<?php endif; ?>">Payment Transactions</a>
<?php endif; ?>
<a href="?action=logout" class="left-menu"><?php echo $this->_config[0]['vars']['LOG_OUT']; ?>
</a>
<br/>