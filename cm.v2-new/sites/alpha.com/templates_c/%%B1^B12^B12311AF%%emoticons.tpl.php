<?php /* Smarty version 2.6.14, created on 2013-11-20 10:57:15
         compiled from emoticons.tpl */ ?>
<script type='text/javascript'>
<?php echo '
	jQuery(function(e) {
		jQuery(".emtcons").unbind(\'click\').click(function (e) {
			e.preventDefault();
			jQuery("#sms").val(jQuery("#sms").val() + jQuery(this).attr(\'data-text\'));
			jQuery("#iconlist").fadeOut();
		});
		jQuery("#emtclose").click(function (e) {
			e.preventDefault();
			jQuery("#iconlist").fadeOut();
		});
	});
'; ?>

</script>
<a id='emtclose'><span>Close</span></a>
<ul class="container-emoticons">
<?php $_from = $this->_tpl_vars['emoticons']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['emoticons'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['emoticons']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['emoticon']):
        $this->_foreach['emoticons']['iteration']++;
?>
	<li>
    <a class="emtcons" data-text="<?php echo $this->_tpl_vars['emoticon']['text_version']; ?>
" href="#">
    <img src="../<?php echo $this->_tpl_vars['emoticon']['image_path']; ?>
" height="54" />
    <p style="text-align:center; color:#999;"><?php echo $this->_tpl_vars['emoticon']['text_version']; ?>
</p>
    </a>
    </li>
<?php endforeach; endif; unset($_from); ?>

<br class="clear" />
</ul>