<?php /* Smarty version Smarty-3.1.17, created on 2014-06-26 08:51:22
         compiled from "templates/public/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:48180262753abc2eae80060-17997467%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dfdbc5511d42efb56aae3081dcd157004c3d616e' => 
    array (
      0 => 'templates/public/index.tpl',
      1 => 1403763224,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48180262753abc2eae80060-17997467',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_53abc2eb040a75_56682819',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53abc2eb040a75_56682819')) {function content_53abc2eb040a75_56682819($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ('public/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>


<div class="wrap">
    
        <div class="container-big-banner">
        
            <h1>Die große Singleböse,</h1> 
            <p>Chatten, Daten, neue Freunde finden,
            Singles auch aus deiner Stadt! 
            </p>
            <div class="text-banner-01">Besuchen Sie uns auch im TV!</div>
        	
        </div>
         
        <div style=" margin:0 auto;">
            <a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo @constant('APP_PATH');?>
/register" class="btn-01">Register</a>
            <a href="http://<?php echo $_SERVER['HTTP_HOST'];?>
<?php echo @constant('APP_PATH');?>
/login" class="btn-01 login">Login</a>
            <a href="<?php echo @constant('FACEBOOK_LOGIN_URL');?>
<?php echo $_SESSION['state'];?>
" class="btn-01 facebook">Login with Facebook</a>
        </div>
    
 </div>
 
<?php echo $_smarty_tpl->getSubTemplate ('public/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<?php }} ?>
