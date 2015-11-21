<?php /* Smarty version 2.6.14, created on 2013-11-20 09:47:56
         compiled from incompleteinfo.tpl */ ?>
<!-- <?php echo 'incompleteinfo.tpl'; ?>
 -->
<div class="container-metropopup">
<div class="metropopup-content">
<font style="font-size:2em; padding-bottom:2%; display:block;"><?php echo $this->_config[0]['vars']['reg2_banner']; ?>
</font>

<?php if ($this->_tpl_vars['save']['countrycode']): ?>
<div style="line-height:18px; margin-bottom:20px;"><?php echo $this->_config[0]['vars']['reg2_headline_intro']; ?>
</div>
	<?php if (( $this->_tpl_vars['text4'] != '' )): ?>
		<div class="text_info" align="center"><?php echo $this->_tpl_vars['text4']; ?>
</div>
	<?php else: ?>
	<form id="register_form" name="register_form" method="post" action="?action=incompleteinfo<?php if ($_GET['action'] == 'incompleteinfo_skip'): ?>&nextstep=mobileverify_skip<?php else: ?>&nextstep=mobileverify<?php endif; ?>">	
		<label class="text"><?php echo $this->_config[0]['vars']['mobile']; ?>
:</label><br class="clear"/>
		<span>
			<div id="country_code" style="float:left;padding-top:2px;width:40px;">+<?php echo $this->_tpl_vars['save']['countrycode']; ?>
</div>
			<input type="text" id="phone_code2" name="phone_code2" value="<?php echo $this->_tpl_vars['save']['phone_code2']; ?>
"  maxlength="4" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" class="formfield_01" style="width:50px; margin-right:5px;"/>
			<input type="text" id="phone_number" name="phone_number" value="<?php echo $this->_tpl_vars['save']['phone_number']; ?>
" class="formfield_01" maxlength="10" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" onblur="checkNullPhone('', document.getElementById('phone_code2').value, document.getElementById('phone_number').value)" />
			<br clear="all"/>
			<!--<div id="phone_number_info" class="error_info"></div> -->
		</span>
		<br clear="all"/>
		<label class="text">&nbsp;</label>
		<span style="margin-bottom:10px; float:left; margin-left:40px;">
			<input type="hidden" name="submit_form" value="1"/>
			<a href="#" onclick="submitAjaxFormIncompleteInfo(); return false;" class="btn-red" style="width:auto !important; padding:0 10px;"><?php echo $this->_config[0]['vars']['reg2_submit']; ?>
</a>
			<?php if ($_GET['action'] == 'incompleteinfo_skip'): ?>
			<a href="./" onclick="" class="btn-popup"><?php echo $this->_config[0]['vars']['reg2_skip']; ?>
</a>
			<?php else: ?>
			<a href="#" onclick="jQuery('#mask').hide(); jQuery('.window').hide(); return false;" class="btn-red" style="width:auto !important; padding:0 10px; margin-left:10px;"><?php echo $this->_config[0]['vars']['CANCEL']; ?>
</a>
			<?php endif; ?>
		</span>
		
	</form>
	<div style="line-height:18px; margin-top:10px; clear:both"><?php echo $this->_config[0]['vars']['phone_number_guide']; ?>
</div>
	<?php endif; ?>
<?php else: ?>
<?php echo $this->_config[0]['vars']['phone_number_not_support']; ?>

<?php endif; ?>

</div></div>