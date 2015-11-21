<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from profile_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'profile_item.tpl', 2, false),)), $this); ?>
<a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="profile-boder"></a>
<img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&amp;w=108&amp;h=108" width="108" height="108" class="profile-img"/><p><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/@.*/", "") : smarty_modifier_regex_replace($_tmp, "/@.*/", "")); ?>
</p>
	<?php if (( $this->_tpl_vars['nofavorite'] != 'true' ) && ( $this->_tpl_vars['item']['username'] != $_SESSION['sess_username'] )): ?>
		<?php if (! in_array ( $this->_tpl_vars['item']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
			<a href="javascript:void(0)" onclick="jQuery(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container', '<?php echo $this->_tpl_vars['style']; ?>
');" class="q-icon q-fav"><span>fav</span></a>
		<?php endif; ?>
	<?php elseif ($this->_tpl_vars['item']['username'] != $_SESSION['sess_username']): ?>
		<a href="javascript:void(0)" onclick="removeFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container', '<?php echo $this->_tpl_vars['style']; ?>
'); return false;" class="q-icon q-del-left"><span>fav</span></a>
	<?php endif; ?>
	<a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="q-icon q-right q-chat"><span>fav</span></a>