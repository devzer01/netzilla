<?php /* Smarty version 2.6.14, created on 2013-11-20 10:15:11
         compiled from profile_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'profile_item.tpl', 2, false),)), $this); ?>
<li>
    <a href="?action=viewprofile&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="profile-boder"></a><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&amp;w=108&amp;h=108" width="108" height="108" class="profile-img"><p><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/@.*/", "") : smarty_modifier_regex_replace($_tmp, "/@.*/", "")); ?>
</p>
    <?php if ($_SESSION['sess_mem'] == 1): ?>
		<?php if ($this->_tpl_vars['style'] == '2'): ?>
			<a href="javascript:void(0)" onclick="removeFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container', <?php echo $this->_tpl_vars['style']; ?>
); return false;" class="q-icon q-fav"><span>fav</span></a>
		<?php else: ?>
			<a href="javascript:void(0)" onclick="jQuery(this).remove(); return addFavorite('<?php echo $this->_tpl_vars['item']['username']; ?>
','favorite-list-container');" class="q-icon q-fav"><span>fav</span></a>
		<?php endif; ?>
	<?php endif; ?>
	<a href="?action=chat&username=<?php echo $this->_tpl_vars['item']['username']; ?>
" class="q-icon q-right q-chat"><span>fav</span></a>
</li>