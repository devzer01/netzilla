<?php /* Smarty version 2.6.14, created on 2013-11-19 16:30:58
         compiled from fotoalbum.tpl */ ?>
<!-- <?php echo 'fotoalbum.tpl'; ?>
 -->
<script>
<?php echo '
function deletePhoto(id, approval)
{
	if(confirm(\'Bist du sicher, dass du dieses Foto entfernen willst?\'))
	{
		jQuery.post("ajaxRequest.php",{"action": "deletePhoto", "fotoid": id, "approval": approval}, function(data){getPage(\'?action=fotoalbum\', \'contentDiv\');});
	}
	return false;
}
'; ?>

</script>
<div id="container-photo-gallery">
<h5 class="title"><?php echo $this->_config[0]['vars']['Foto_Album']; ?>
</h5>

	<?php if ($_GET['action'] == 'fotoalbum'): ?>
    <?php if ($this->_tpl_vars['total'] >= 10): ?>
        <strong><?php echo $this->_config[0]['vars']['Full_Fotoalbum']; ?>
</strong><br />
    <?php else: ?>
		<form id="upload_foto_form" name="upload_foto_form" method="post" action="?action=fotoalbum" enctype="multipart/form-data" style="padding:20px 40px 10px 40px;">
        	<strong><?php echo $this->_config[0]['vars']['Upload_your_picture']; ?>
</strong>
            <input type="file" id="upload_file" name="upload_file"/><br />
            <?php echo $this->_config[0]['vars']['Images_policy']; ?>
<br />
        	<a href="#" id="" onclick="jQuery('#upload_foto_form').submit(); return false;" value="" class="btn-yellow-left">Upload</a>
        	<?php if ($this->_tpl_vars['text'] != ""): ?><br />
        		<?php echo $this->_tpl_vars['text']; ?>

        	<?php endif; ?>
            <br class="clear" />
            <!--<a href="http://blueimp.github.com/jQuery-File-Upload/" style="color:#F00; font-size:16px;">Sample</a> -->
        </form>
	<?php endif; ?>
<?php endif; ?>


<?php if (count ( $this->_tpl_vars['fotoalbum'] )): ?>
<?php echo '
<style>
#container-photoalbum-list{ margin:10px 0 10px 0 !important;}
#container-photoalbum-list li{margin-left:26px !important; margin-top:10px !important;}
</style>
'; ?>

<ul id="container-photoalbum-list" style="float:left;">
<?php $_from = $this->_tpl_vars['fotoalbum']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['fotoalbum'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fotoalbum']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['item']):
        $this->_foreach['fotoalbum']['iteration']++;
?>
<li>
    <a href="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
" class='lightview' rel='gallery[mygallery]'>
    <div class="profile-list">
        <div class="boder-profile-img">
		<?php if ($this->_tpl_vars['item']['approval']): ?>
		<!-- watermark -->
		<img src="images/cm-theme/wait.png" width="120" height="121"/>
		<?php else: ?>
		<img src="images/cm-theme/profile-boder-img.png" width="120" height="121"/>
		<?php endif; ?>
		</div>
        <div class="img-profile"><img src="thumbnails.php?file=<?php echo $this->_tpl_vars['item']['picturepath']; ?>
&w=97&h=98" width="97" height="98" class="foto-image" style="top:5px; left:5px; position:relative;"/></div>
    </div>
    </a>
    <div class="container-quick-icon-foto">
    <a href="#" class="quick-icon-left del-icon" title="Delete" onclick="return deletePhoto(<?php echo $this->_tpl_vars['item']['id'];  if ($this->_tpl_vars['item']['approval']): ?>, 'APPROVAL'<?php endif; ?>)"></a>
    </div>
</li> 
<?php endforeach; endif; unset($_from); ?> 
</ul>
<?php endif; ?>
</div>