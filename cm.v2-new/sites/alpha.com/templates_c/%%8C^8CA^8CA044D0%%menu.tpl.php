<?php /* Smarty version 2.6.14, created on 2013-11-19 02:45:09
         compiled from menu.tpl */ ?>
<ul class="container-menu">
    <li><a href="./" class="home"><span>home</span></a></li>
	<li><a href="?action=search" class="search"><span>search</span></a></li>
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