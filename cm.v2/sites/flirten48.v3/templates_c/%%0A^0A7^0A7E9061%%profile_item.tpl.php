<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:37
         compiled from profile_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'profile_item.tpl', 10, false),)), $this); ?>
<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="link-profile-img">
<div class="profile-list">
	<div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
	<div class="img-profile">
	<img border="0" src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&amp;w=97&amp;h=98" width="97" height="98" alt="<?php echo $this->_tpl_vars['item']['username']; ?>
"/>
	</div>
</div>
</a>

<p><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/@.*/", "") : smarty_modifier_regex_replace($_tmp, "/@.*/", "")); ?>
</p>
<?php if ($_SESSION['sess_mem'] == 1): ?>
<div class="container-quick-icon" style="top:-55px;">
<?php if ($this->_tpl_vars['style'] == '2'): ?>
	<a href="javascript:void(0)" class="quick-icon-left del-icon" title="<?php echo $this->_config[0]['vars']['Delete']; ?>
" onclick="removeFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container', <?php echo $this->_tpl_vars['style']; ?>
); return false;"></a>
<?php else: ?>
	<?php if ($this->_tpl_vars['item']['username'] != $_SESSION['sess_username']): ?>
	<a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="quick-icon-left message-icon" title="Message"></a>
	<?php endif; ?>
	<?php if (( $this->_tpl_vars['nofavorite'] != 'true' ) && ( $this->_tpl_vars['item']['username'] != $_SESSION['sess_username'] )): ?>
		<?php if (! in_array ( $this->_tpl_vars['item']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
		<a href="javascript:void(0)" class="quick-icon-right fav-icon" title="Favorite" onclick="jQuery(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container');"></a>
		<?php else: ?>
		<!--ALIGN ICON HERE-->
		<!-- <div class="fav"><img src="images/cm-theme/icon-fav-g.png"/></div> -->
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['item']['username'] != $_SESSION['sess_username']): ?>
		<a href="javascript:void(0)" class="quick-icon-right del-icon-g" title="<?php echo $this->_config[0]['vars']['Delete']; ?>
" onclick="return removeFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container')"></a>
	<?php endif; ?>
<?php endif; ?>
</div>
<?php endif; ?>