<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:36
         compiled from menu.tpl */ ?>
<!-- <?php echo 'menu.tpl'; ?>
 -->
<div id="container-menu-icon">
        <?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
    
    <?php if ($_SESSION['sess_username']): ?>
    <div id="container-coin">
    <!-- <img src="images/cm-theme/coin.png" width="30"/><br /> -->
    Sie haben!<br /> <strong>
    <span id="coinsArea" style="padding: 0px"><?php if ($this->_tpl_vars['coin']):  echo $this->_tpl_vars['coin'];  else: ?>0<?php endif; ?></span> coins</strong></div>    
    <?php endif; ?>
    
    <?php else: ?>
    <a href="?action=register" class="register-btn-top"><span>register</span></a>
    <?php endif; ?>
        
    <ul>
    <li><a href="./"><img src="images/cm-theme/icon-home.png"  width="58" height="59" /><br /><span><?php echo $this->_config[0]['vars']['START_SITE']; ?>
</span></a></li>
    <li><a href="?action=search"><img src="images/cm-theme/icon-search.png"/><br /><span><?php echo $this->_config[0]['vars']['SEARCH']; ?>
</span></a></li>
    
    <?php if (! $_SESSION['sess_externuser']): ?> 
        <?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left-membership_islogged.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
    <?php endif;  endif; ?>
    
    </ul>
</div>
<script>
<?php echo '
function coinsBalance()
{
	jQuery.ajax(
	{
		type: "GET",
		url: "?action=chat&type=coinsBalance",
		success:(function(result)
			{
				jQuery(\'#coinsArea\').text(result);
			})
	});
}

jQuery(function() {
	'; ?>

	<?php if (@USERNAME_CONFIRMED): ?>
	coinsBalance();
	<?php endif; ?>
	<?php echo '
});
'; ?>

</script>