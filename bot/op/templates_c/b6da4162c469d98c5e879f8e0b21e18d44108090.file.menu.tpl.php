<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 13:38:41
         compiled from "templates/menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:966185690532fd2f1018ac2-20548678%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b6da4162c469d98c5e879f8e0b21e18d44108090' => 
    array (
      0 => 'templates/menu.tpl',
      1 => 1395640464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '966185690532fd2f1018ac2-20548678',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd2f104f684_90760503',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd2f104f684_90760503')) {function content_532fd2f104f684_90760503($_smarty_tpl) {?><nav class="navbar navbar-default" role="navigation">
	<ul class="nav navbar-nav">
		<?php if (isset($_SESSION['user_id'])) {?>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/dashboard" class="nav">Dashboard</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/add" class="nav">Add Profile</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/edit" class="nav">Edit Profile</a></li>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/addemail" class="nav">Add Email</a></li>
			<?php if ($_SESSION['user_level']>1) {?>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/mandashboard" class="nav">Manager Dashboard</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/quota" class="nav">Set Quota</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/dayquota" class="nav">Set Daily Quota</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/adduser" class="nav">Add Profiler</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/listuser" class="nav">List Profiler</a></li>
				<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/sitequota" class="nav">Site Quota</a></li>
			<?php }?>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/password" class="nav">Change Password</a></li> 
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/logout" class="nav">Log Out</a></li>
		<?php } else { ?>
			<li><a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
/bot/op/" class="nav">Home</a></li>
		<?php }?>
	</ul>
</nav><?php }} ?>
