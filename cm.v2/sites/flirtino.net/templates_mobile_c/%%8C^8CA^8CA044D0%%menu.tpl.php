<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from menu.tpl */ ?>
<nav>
	<ul>
		<li><a href="." class="home"><span><?php echo $this->_config[0]['vars']['START_SITE']; ?>
</span></a></li>
		<li><a href="?action=search" class="search"><span><?php echo $this->_config[0]['vars']['SEARCH']; ?>
</span></a></li>
		<?php if ($_SESSION['sess_username'] != "" || $_COOKIE['sess_username'] != ""): ?>
			<?php if (! $_SESSION['sess_externuser']): ?> 
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "left-membership_islogged.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
		<?php endif; ?>
	</ul>
</nav>

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