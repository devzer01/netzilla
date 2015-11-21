<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 13:38:40
         compiled from "templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:662634187532fd2f0efe299-49655742%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '44473f1c7779fd2764925cf1eff848b4714c81c4' => 
    array (
      0 => 'templates/home.tpl',
      1 => 1395640464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '662634187532fd2f0efe299-49655742',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'error' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd2f0f39bd6_88916510',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd2f0f39bd6_88916510')) {function content_532fd2f0f39bd6_88916510($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<h2> Netzilla Operations </h2>

<?php if ($_smarty_tpl->tpl_vars['error']->value!='') {?>
	<div><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</div>
<?php }?>

<form method='post' action='login'>
	Username: <input type='text' name='username' />
	Password: <input type='password' name='password' />
	<input type='submit' />
</form>

<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
