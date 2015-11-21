<?php /* Smarty version Smarty-3.1.17, created on 2014-03-24 13:38:40
         compiled from "templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:366016436532fd2f0f41234-34305670%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be439f82a4dbec61746f62a0df07c19a7eecd966' => 
    array (
      0 => 'templates/header.tpl',
      1 => 1395640464,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '366016436532fd2f0f41234-34305670',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_532fd2f1014710_94656800',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_532fd2f1014710_94656800')) {function content_532fd2f1014710_94656800($_smarty_tpl) {?><!DOCTYPE html>
<html lang="utf8">
	<head>
	<title>Send Message Statistics</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<!-- Bootstrap -->
	<base href='<?php echo @constant('BASE_URL');?>
'>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" rel="stylesheet" media="screen">
	<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" >
	    if(typeof String.prototype.trim !== 'function') {
  			String.prototype.trim = function() {
    			return this.replace(/^\s+|\s+$/g, ''); 
  			}
	    }
	</script>
	
	<script src="js/js/highcharts.js"></script>
	<script src="js/js/modules/data.js"></script>
	<script src="js/js/modules/exporting.js"></script>
	
	<script src="js/jquery.stickytableheaders.min.js"></script>
	
	<style>
	
	th
	{
    padding: 5px; /* NOTE: th padding must be set explicitly in order to support IE */
    text-align: center;        
    font-weight:bold;
    line-height: 2em;
    color: #FFF;
    background-color: #555;
	}
	
	td
	{
		text-align: center;
	}
	
	table tr:hover
	{
		background-color:#f2e8da;
	}
	
	</style>
	
	</head>
	<body>

	<?php echo $_smarty_tpl->getSubTemplate ('menu.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
