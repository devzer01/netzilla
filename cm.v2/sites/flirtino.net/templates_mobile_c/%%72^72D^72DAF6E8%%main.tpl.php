<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from main.tpl */ ?>
<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
	<!--start banner verify -->
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "banner-verify-mobile.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<!--end banner verify -->

	<?php if (( ( $this->_tpl_vars['bonusid'] != '' ) && ( $this->_tpl_vars['bonusid'] > 0 ) )): ?>
	<span id="bonusverify_box">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bonusverify_step1.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</span>
	<?php endif; ?>	

	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "online.tpl", 'smarty_include_vars' => array('total' => '12')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "newest_members_box.tpl", 'smarty_include_vars' => array('total' => '12')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "my_favorite.tpl", 'smarty_include_vars' => array('style' => '2')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "online.tpl", 'smarty_include_vars' => array('total' => '12')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "newest_members_box.tpl", 'smarty_include_vars' => array('total' => '12')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>