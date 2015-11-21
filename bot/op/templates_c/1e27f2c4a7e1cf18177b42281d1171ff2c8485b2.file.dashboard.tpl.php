<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 13:39:42
         compiled from "templates/dashboard.tpl" */ ?>
<?php /*%%SmartyHeaderCode:51231547532fd32ee9f044-72515015%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1e27f2c4a7e1cf18177b42281d1171ff2c8485b2' => 
    array (
      0 => 'templates/dashboard.tpl',
      1 => 1395640464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '51231547532fd32ee9f044-72515015',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sites' => 0,
    'site' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd32eed5d75_28610741',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd32eed5d75_28610741')) {function content_532fd32eed5d75_28610741($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<h2> Netzilla Operations - Dashboard </h2>

<h3>Profiler Quota</h3>

<table class='table table-striped table-bordered'>
	<tr>
		<td>Site</td>
		<td>Quota</td>
		<td>Created</td>
	</tr>
	<?php  $_smarty_tpl->tpl_vars['site'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['site']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sites']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['site']->key => $_smarty_tpl->tpl_vars['site']->value) {
$_smarty_tpl->tpl_vars['site']->_loop = true;
?>
		<?php if ($_smarty_tpl->tpl_vars['site']->value['quota']>0||$_smarty_tpl->tpl_vars['site']->value['override']>0) {?>
			<tr>
				<td><?php echo $_smarty_tpl->tpl_vars['site']->value['name'];?>
</td>
				<?php if ($_smarty_tpl->tpl_vars['site']->value['quota']<$_smarty_tpl->tpl_vars['site']->value['override']) {?>
					<td style='background-color: #FF3300;'><?php echo $_smarty_tpl->tpl_vars['site']->value['override'];?>
</td>
				<?php } else { ?>
					<td><?php echo $_smarty_tpl->tpl_vars['site']->value['quota'];?>
</td>
				<?php }?>
				<td><?php echo $_smarty_tpl->tpl_vars['site']->value['created'];?>
</td>
			</tr>
		<?php }?>
	<?php } ?>
	
</table>

<?php echo $_smarty_tpl->getSubTemplate ('footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
