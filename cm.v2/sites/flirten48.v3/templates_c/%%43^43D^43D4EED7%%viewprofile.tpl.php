<?php /* Smarty version 2.6.14, created on 2013-11-20 17:14:50
         compiled from viewprofile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'viewprofile.tpl', 4, false),)), $this); ?>
<!-- <?php echo 'viewprofile.tpl'; ?>
 -->
 <div class="bg-box-viewprofile">
    <!--<?php echo $this->_config[0]['vars']['USERNAME']; ?>
: -->
    <h5 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['profile']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/@.*/", "") : smarty_modifier_regex_replace($_tmp, "/@.*/", "")); ?>
</h5>
        <ul id="container-profile-list" style="float:left; margin-bottom:5px;">
        
        <li>
        <a <?php if ($this->_tpl_vars['profile']['picturepath'] && ( $_GET['part'] != 'partial' )): ?>href="thumbnails.php?file=<?php echo $this->_tpl_vars['profile']['picturepath']; ?>
"  class='lightview' rel='gallery[profile]'<?php elseif ($_GET['part'] == 'partial'): ?>href="?action=viewprofile&username=<?php echo $this->_tpl_vars['profile']['username']; ?>
"<?php endif; ?> title="<?php echo ((is_array($_tmp=$this->_tpl_vars['profile']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/@.*/', '') : smarty_modifier_regex_replace($_tmp, '/@.*/', '')); ?>
">
        <div class="profile-list">
           <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121"/></div>
           <div class="img-profile">
              <img src="thumbnails.php?file=<?php echo $this->_tpl_vars['profile']['picturepath']; ?>
&w=112&h=113" width="97" height="98" border="0" style="position:relative; top:-4px;">
          </div>
        </div>
        </a>
                <?php if (( $this->_tpl_vars['profile']['picturepath'] ) && ( $_GET['from'] == 'admin' )): ?>
                    <a href="?action=<?php echo $_GET['action']; ?>
&username=<?php echo $_GET['username']; ?>
&from=admin&proc=delete_profile_picture" onclick="if(confirm('<?php echo $this->_config[0]['vars']['Delete']; ?>
?')) return true; else return false;" style="color: black"><img src="images/icon/b_drop.png"/> <?php echo $this->_config[0]['vars']['Delete']; ?>
</a>
                <?php endif; ?>
        </li>
        </ul>
    
    <div style="padding:5px; float:left; width:200px; margin-top:10px; line-height:22px;">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "profile_detail.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <div style="float:left; width:325px; margin:0 0 10px 10px; line-height:1.3em">
        <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "profile_detail_description.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </div>
    <!--btn-group -->
<div class="container-btn-group">
    <?php if ($_SESSION['sess_mem'] == 1): ?>
        <?php $this->assign('action', "?action=chat&amp;username=".($_GET['username'])); ?>
        <?php $this->assign('onclick', ""); ?>
    <?php else: ?>
        <?php $this->assign('action', "#"); ?>
        <?php $this->assign('onclick', "loadPagePopup('?action=register_popup&username=".($_GET['username'])."', '100%'); return false;"); ?>
    <?php endif; ?>
    <div>
    <a href="<?php echo $this->_tpl_vars['action']; ?>
" onclick="<?php echo $this->_tpl_vars['onclick']; ?>
" class="btn-user-action">Nachricht schicken<span class="icon-action"><img src="images/cm-theme/s-icon.png" width="21" height="21" /></span></a>
    <?php if ($_SESSION['sess_id']): ?>
    <?php if (! in_array ( $this->_tpl_vars['profile']['username'] , $this->_tpl_vars['favorites_list'] )): ?>
    <a href="#" onclick="jQuery(this).remove(); return addFavorite('<?php echo $_GET['username']; ?>
')" class="btn-user-action"><?php echo $this->_config[0]['vars']['Add_to_Favorite']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
    <?php else: ?>
    <a href="#" onclick="if(removeFavorite('<?php echo $_GET['username']; ?>
')) jQuery(this).remove(); return false;" class="btn-user-action"><?php echo $this->_config[0]['vars']['Remove_from_Favorite']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
    <?php endif; ?>
    <?php endif; ?>
    </div>
</div>
<!--end btn-group -->
<!-- -->
</div>
<?php if (! $_SESSION['sess_id']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "register.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php else: ?>
	<?php if ($_GET['from'] == 'admin'): ?>
	<?php else: ?>

<?php if (( $_GET['part'] != 'partial' )): ?>
<?php if (count ( $this->_tpl_vars['fotoalbum'] )): ?>
<div style="float:left; width:645px; margin-top:10px;">
<h1 class="title"><?php echo $this->_config[0]['vars']['Foto_Album']; ?>
</h1>
<ul id="container-profile-list" style="float:left;">
<?php $_from = $this->_tpl_vars['fotoalbum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fotoalbum'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fotoalbum']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['fotoalbum']['iteration']++;
?>
<li>
    <a href="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img"><img src="images/cm-theme/profile-boder-img.png" width="120" height="121" /></div>
        <div class="img-profile"><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=97&h=98" width="97" height="98" /></div>
    </div>
    </a>
</li>
<?php endforeach; endif; unset($_from); ?>
</ul>
</div>
<?php endif; ?>

		<?php if ($this->_tpl_vars['random_contacts']): ?>
		<div style="float:left; width:645px; margin-top:10px;">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "random_members_box.tpl", 'smarty_include_vars' => array('total' => 15)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<!-- -->