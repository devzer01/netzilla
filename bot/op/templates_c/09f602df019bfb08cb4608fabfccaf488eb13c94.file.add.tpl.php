<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 14:13:53
         compiled from "templates/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1397707049532fd36ab82ac1-21505129%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '09f602df019bfb08cb4608fabfccaf488eb13c94' => 
    array (
      0 => 'templates/add.tpl',
      1 => 1395645125,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1397707049532fd36ab82ac1-21505129',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd36abad721_54514809',
  'variables' => 
  array (
    'all' => 0,
    'success' => 0,
    'sites' => 0,
    'site_id' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd36abad721_54514809')) {function content_532fd36abad721_54514809($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/usr/local/zend/share/pear/smarty3/plugins/function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<h3>Add Profile</h3>


<?php if ($_smarty_tpl->tpl_vars['all']->value) {?>
    <div class="alert alert-info">All sites have been enabled</div>
<?php } else { ?>
   <div class="alert alert-info">When your daily quota is met, you will be able to create profile for any site</div>
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['success']->value!='') {?>
	<div class="alert alert-success"><?php echo $_smarty_tpl->tpl_vars['success']->value;?>
</div>
<?php }?>

<form method='post' action='add'>
		<div class="boxcontainner">
			<span class="label2">Site name:</span>
			<span class="field">
				<select name="site_id" id="site_id">
					<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['sites']->value,'selected'=>$_smarty_tpl->tpl_vars['site_id']->value),$_smarty_tpl);?>

				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">Sex:</span>
			<span class="field">
				<select name="sex" id="sex">
					<option value="Female">Female</option>
					<option value="Male">Male</option>
					<option value="Gay">Gay</option>
					<option value="Lesbian">Lesbian</option>
				</select>
			</span>
		</div>
		<div class="boxcontainner">
			<span class="label2">Username:</span>
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

<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
