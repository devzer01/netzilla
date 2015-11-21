<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:37
         compiled from profile_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'paginate_prev', 'profile_list.tpl', 12, false),array('function', 'paginate_middle', 'profile_list.tpl', 12, false),array('function', 'paginate_next', 'profile_list.tpl', 12, false),)), $this); ?>
<ul id="container-profile-list">
	<?php $_from = $this->_tpl_vars['result']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
	<?php if ($this->_tpl_vars['item']['username']): ?>
    <li>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "profile_item.tpl", 'smarty_include_vars' => array('nofavorite' => $this->_tpl_vars['nofavorite'],'style' => $this->_tpl_vars['style'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </li>
	<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
</ul>
<?php if ($this->_tpl_vars['paginate'] == 'true'): ?>
<br class="clear"/>
<div class="page"><?php echo smarty_function_paginate_prev(array('onclick' => "return page(this)",'class' => "pre-pager"), $this);?>
 <?php echo smarty_function_paginate_middle(array('onclick' => "return page(this)",'class' => "num-pager"), $this);?>
 <?php echo smarty_function_paginate_next(array('onclick' => "return page(this)",'class' => "next-pager"), $this);?>
</div>
<?php endif; ?>