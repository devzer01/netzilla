<?php /* Smarty version 2.6.14, created on 2013-11-29 18:29:05
         compiled from profile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'regex_replace', 'profile.tpl', 45, false),)), $this); ?>
<!-- <?php echo 'profile.tpl'; ?>
 -->
<?php echo '
<script>
jQuery(document).ready(function($) {
	window.onhashchange = function () {
		loadByHash();
	}

	loadByHash();
});

function loadByHash()
{
	if(window.location.hash.replace("#", "")!="")
	{
		jQuery(\'#link_\'+window.location.hash.replace("#", "")).trigger(\'click\');
	}
	else
	{
		getPage(\'?action=fotoalbum\',\'contentDiv\');
	}
}

function getPage(url, target)
{
	jQuery.get(url, function(data) {
		if(data != \'\')
		{
			jQuery(\'#\'+target).html(data);
		}
	});
	return false;
}

function getFileDialog()
{
	jQuery(\'#profilepic\').trigger(\'click\');
}

//browse-over
</script>
'; ?>


<div class="bg-box-viewprofile">
<h5 class="title"><?php echo ((is_array($_tmp=$this->_tpl_vars['profile']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/@.*/", "") : smarty_modifier_regex_replace($_tmp, "/@.*/", "")); ?>
</h5>
<ul id="container-profile-list" style="float:left; margin-bottom:5px;">
    <li>
        <a  href="thumbnails.php?file=<?php echo $this->_tpl_vars['profile']['picturepath']; ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['profile']['username'])) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/@.*/', '') : smarty_modifier_regex_replace($_tmp, '/@.*/', '')); ?>
" class='lightview' rel='gallery[profile]'>
        <div class="profile-list">
        <div class="boder-profile-img">
        <?php if ($this->_tpl_vars['profile']['approval']): ?>
        <!-- watermark -->
        <img src="images/cm-theme/wait.png" width="120" height="121" />
        <?php else: ?>
        <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
        <?php endif; ?>
        </div>
        <div class="img-profile">
        <img src="thumbnails.php?file=<?php echo $this->_tpl_vars['profile']['picturepath']; ?>
&w=97&h=98" width="97" height="98" border="0" style="position:relative; top:-4px;"/>
        </div>
        </div>
        </a>
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

<!--btn-gorup -->
<div class="container-btn-group">

<!--upload photo profile -->
<form id="profilepic_form" method="post" enctype="multipart/form-data" action="?action=editprofile">
<input type="file" id="profilepic" name="profilepic" onchange="this.form.submit();" style="width:300px; height:45px; opacity:0; filter:alpha(opacity=0); margin-left:20px; cursor:pointer; 
position:absolute;"/>
</form>
<!--end upload photo profile -->

<a href="javascript:void(0)" id="upload" class="btn-user-action">Upload Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-10.png" width="21" height="21" /></span></a>

<?php if ($this->_tpl_vars['profile']['picturepath']): ?>	
<a href="?action=editprofile&proc=delete_profile_picture" id="link_fotoalbum_del"  onclick="if(confirm('Löschen?')) return true; else return false;" class="btn-user-action"><?php echo $this->_config[0]['vars']['Delete']; ?>
 Profil Foto<span class="icon-action"><img src="images/cm-theme/s-icon-09.png" width="21" height="21" /></span></a>
<?php endif; ?>

<a href="#editprofile" id="link_editprofile" onclick ="getPage('?action=editprofile','contentDiv');" class="btn-user-action"><?php echo $this->_config[0]['vars']['Edit_Profile']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-08.png" width="21" height="21" /></span></a>
<a href="#fotoalbum" id="link_fotoalbum" onclick="getPage('?action=fotoalbum', 'contentDiv')" class="btn-user-action"><?php echo $this->_config[0]['vars']['FOTOALBUM']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-05.png" width="21" height="21" /></span></a>
<a href="#my_favorite" id="link_my_favorite" onclick="getPage('?action=my_favorite','contentDiv')" class="btn-user-action">My <?php echo $this->_config[0]['vars']['FAVOURITES']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></span></a>
<a href="#changepassword" id="link_changepassword"  onclick="getPage('?action=changepassword','contentDiv')" class="btn-user-action"><?php echo $this->_config[0]['vars']['Change_Password']; ?>
<span class="icon-action"><img src="images/cm-theme/s-icon-06.png" width="21" height="21" /></span></a>
<a href="?action=pay-for-coins" class="btn-user-action">Coins<span class="icon-action"><img src="images/cm-theme/s-icon-07.png" width="21" height="21" /></span></a>

<?php if (@SOCIAL_ENABLED == '1'): ?>
	<a href="#social" id="link_social" onclick='getPage("?action=social", "contentDiv")' class="btn-user-action">Social<span class="icon-action"><img src="images/cm-theme/s-icon-11.png" width="21" height="21" /></span></a>
<?php endif; ?>
</div>
<!--end btn-gorup-->

</div>
<!--start right -->
<div class="container-profile-page-right">
	<!--content -->
    <div id="contentDiv">
    </div>
	<!--end content -->
</div>
<!--end start right -->
