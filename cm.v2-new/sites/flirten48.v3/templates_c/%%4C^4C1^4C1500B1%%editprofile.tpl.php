<?php /* Smarty version 2.6.14, created on 2014-04-17 16:59:50
         compiled from editprofile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_radios', 'editprofile.tpl', 7, false),array('function', 'html_options', 'editprofile.tpl', 13, false),array('modifier', 'stripslashes', 'editprofile.tpl', 58, false),)), $this); ?>
<h5 class="title"><?php echo $this->_config[0]['vars']['Edit_Profile']; ?>
</h5>
<div style="margin:20px;">
<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="?action=editprofile">
<div class="container-edit-profile-group">			
	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['Gender']; ?>
:<font class="request">*</font></label>
	<span>
		<?php echo smarty_function_html_radios(array('id' => 'gender','name' => 'gender','options' => $this->_tpl_vars['gender'],'selected' => $this->_tpl_vars['save']['gender'],'labels' => false,'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;"), $this);?>

		<br class="clear"/>
	</span>

	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['Birthday']; ?>
:<font class="request">*</font></label>
	<span>
		<?php echo smarty_function_html_options(array('id' => 'date','name' => 'date','options' => $this->_tpl_vars['date'],'selected' => $this->_tpl_vars['save']['date'],'style' => "width:50px",'class' => 'formfield_01'), $this);?>

        
		<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['month'],'id' => 'month','name' => 'month','selected' => $this->_tpl_vars['save']['month'],'style' => "width:110px; margin-left:5px;",'class' => 'formfield_01'), $this);?>
 
        
		<?php echo smarty_function_html_options(array('id' => 'year','name' => 'year','options' => $this->_tpl_vars['year'],'selected' => $this->_tpl_vars['save']['year'],'style' => "width:80px; margin-left:5px;",'class' => 'formfield_01'), $this);?>

	</span>
	<br class="clear" />

	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['Country']; ?>
:<font class="request">*</font></label>
	<span>
		<select id="country" name="country" onchange="loadOptionState('#state', this.options[this.selectedIndex].value, '');loadOptionCity('#city', $('state')[$('state').selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['State']; ?>
:<font class="request">*</font></label>
	<span>
		<select id="state" name="state" onchange="loadOptionCity('#city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['City']; ?>
:<font class="request">*</font></label>
	<span>
		<select id="city" name="city" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>
</div>

<div class="container-edit-profile-group">
	<h3><?php echo $this->_config[0]['vars']['Yourre_looking_for']; ?>
<font class="request">*</font></h3>
	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['Men']; ?>
:</label>
	<span>
		<?php echo smarty_function_html_radios(array('id' => 'lookmen','name' => 'lookmen','options' => $this->_tpl_vars['yesno'],'selected' => $this->_tpl_vars['save']['lookmen'],'labels' => false,'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;"), $this);?>

	</span>
	<br class="clear"/>

	<label class="edit-profile-01"><?php echo $this->_config[0]['vars']['Women']; ?>
:</label>
	<span>
		<?php echo smarty_function_html_radios(array('id' => 'lookwomen','name' => 'lookwomen','options' => $this->_tpl_vars['yesno'],'selected' => $this->_tpl_vars['save']['lookwomen'],'labels' => false,'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;"), $this);?>

	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<h3><?php echo $this->_config[0]['vars']['Your']; ?>
 <?php echo $this->_config[0]['vars']['Description']; ?>
:</h3>
	<span>
		<textarea id="description" name="description" columns="20" rows="12" style="width:450px" onkeyup="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" onblur="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" class="description-edit"><?php echo ((is_array($_tmp=$this->_tpl_vars['save']['description'])) ? $this->_run_mod_handler('stripslashes', true, $_tmp) : stripslashes($_tmp)); ?>
</textarea>	
	</span>
	<br class="clear"/>
	<label></label>
	<br class="clear" />

	<!-- <label><strong><?php echo $this->_config[0]['vars']['Select_Image']; ?>
:</strong></label>
	<span style="width:350px;">
		<input name="upload_pic" type="hidden" value="yes" />
		<input name="picturepath" type="file" id="picturepath" style="width:350px;" />
		<br /><?php echo $this->_config[0]['vars']['Images_policy']; ?>

	</span>
	<br class="clear" /> -->
</div>
 
<input type="hidden" name="save_profile" value="<?php echo $_SESSION['sess_id']; ?>
" />
<a href="#" onclick="if(validateEditProfile()) jQuery('#editProfile').submit(); return false;" class="btn-yellow-left"><?php echo $this->_config[0]['vars']['SUBMIT']; ?>
</a>
<br class="clear" />
</form>
</div>
<script language="javascript" type="text/javascript">
country_select = "<?php echo $this->_tpl_vars['save']['country']; ?>
";
state_select = "<?php echo $this->_tpl_vars['save']['state']; ?>
";
city_select = "<?php echo $this->_tpl_vars['save']['city']; ?>
";
<?php echo '
function validateEditProfile()
{
	if(jQuery(\'#country\').val()==0)
	{
		alert(country_alert);
		return false;
	}
	else if(jQuery(\'#state\').val()==0)
	{
		alert(state_alert);
		return false;
	}
	else if(jQuery(\'#city\').val()==0)
	{
		alert(city_alert);
		return false;
	}
	else
	{
		return true;
	}
}

jQuery(function()
{
	'; ?>

	<?php if ($this->_tpl_vars['show_mobileverify']): ?>
	showVerifyMobileDialog();
	<?php endif; ?>
	<?php echo '
	ajaxRequest("loadOptionCountry", "", "", loadOptionCountry1, "reportError");
});
'; ?>

</script>