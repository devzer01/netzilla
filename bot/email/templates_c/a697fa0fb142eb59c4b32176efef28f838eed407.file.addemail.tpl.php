<?php /* Smarty version Smarty-3.1-DEV, created on 2014-03-19 12:41:40
         compiled from "templates/addemail.tpl" */ ?>
<?php /*%%SmartyHeaderCode:119678365453292e14855311-34953574%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a697fa0fb142eb59c4b32176efef28f838eed407' => 
    array (
      0 => 'templates/addemail.tpl',
      1 => 1395207133,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '119678365453292e14855311-34953574',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'success' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1-DEV',
  'unifunc' => 'content_53292e148619b4_84343515',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53292e148619b4_84343515')) {function content_53292e148619b4_84343515($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<h3>Add Email</h3>

<?php if ($_smarty_tpl->tpl_vars['success']->value!=''){?>
	<div class="alert alert-success"><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</div>
<?php }?>

<form method='post' action='addemail'>
		<div class="boxcontainner">
			<span class="label2">Email:</span>
			<span class="field">
				<input name="username" id="username" class="box" type="text">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">Password:</span>
			<span class="field">
				<input name="password" id="password" class="box" type="text">
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">&nbsp;</span>
				<input name="submit" value="Insert" class="button" type="submit">
			<span class="field">
			</span>
		</div>
</form>

<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>