<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 13:38:41
         compiled from "templates/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1068838387532fd2f1053ae5-82855580%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9206ffe216f3f7c2e7655782292928f7d20e8be5' => 
    array (
      0 => 'templates/footer.tpl',
      1 => 1395640464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1068838387532fd2f1053ae5-82855580',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd2f1058392_05491000',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd2f1058392_05491000')) {function content_532fd2f1058392_05491000($_smarty_tpl) {?>
<script type='text/javascript'>

	$(function () {
		window.setInterval(function () {
			$.get("ping");
		} , 1000);
		
		window.setTimeout(function(){
			location.reload(true);
		},60000 * 10);
	});
</script>

</body>
</html><?php }} ?>
