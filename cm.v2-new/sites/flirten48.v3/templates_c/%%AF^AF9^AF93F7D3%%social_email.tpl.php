<?php /* Smarty version 2.6.14, created on 2013-11-29 18:22:42
         compiled from social_email.tpl */ ?>

<form method='post' id='emailform' action=''>
<br class="clear" />
<div style="width:160px; height:25px; margin:0 auto; margin-bottom:10px;">
<a href="#" class='sendinvites btn-red'>Send Invitations</a>
</div>
<div style="height:366px; width:632px; overflow-y:auto; overflow-x:hidden;">
<table width="100%" cellpadding="5" cellspacing="1">
	<tr>
		<td width="50" align="center" bgcolor="#FF9933"><strong>Select</strong></td>
		<td bgcolor="#FF9933"><strong>Email</strong></td>
		<td bgcolor="#FF9933"><strong>Name</strong></td>
	</tr>
	
	<?php $_from = $this->_tpl_vars['emails']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['email']):
?>
		<tr>
			<td align="center" bgcolor="#FFD7AE"><input name='email[]' type='checkbox' value='<?php echo $this->_tpl_vars['email']['email']; ?>
' checked/></td>
			<td bgcolor="#FFD7AE"><?php echo $this->_tpl_vars['email']['email']; ?>
</td>
			<td bgcolor="#FFD7AE"><?php echo $this->_tpl_vars['email']['title']; ?>
</td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
</table>

<div style="width:160px; height:25px; margin:10px auto;">
<a href="#"  id='sendinvites' class='sendinvites btn-red'>Send Invitations</a>
</div>
</div>
</form>


<?php echo '
<script type=\'text/javascript\'>
	jQuery(document).ready(function (e) {
		jQuery(\'.sendinvites\').click(function (e) {
			var options = {};
			options.url = \'?action=send_invites\';
			options.data = jQuery("#emailform").serialize();
			options.type = \'POST\';
			options.dataType = \'json\';
			options.success = function (json) {
				jQuery("#contacts").html("<h2>Invitations Sent</h2>");
				//window.close();
			};

			jQuery.ajax(options);
			
		});
	});
</script>
'; ?>
