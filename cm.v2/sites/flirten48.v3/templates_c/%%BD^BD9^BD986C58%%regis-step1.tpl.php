<?php /* Smarty version 2.6.14, created on 2013-11-20 17:14:50
         compiled from regis-step1.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'regis-step1.tpl', 42, false),array('function', 'html_radios', 'regis-step1.tpl', 52, false),array('modifier', 'default', 'regis-step1.tpl', 44, false),)), $this); ?>
<!-- <?php echo 'regis-step1.tpl'; ?>
 -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>
<h5 class="title"><?php echo $this->_config[0]['vars']['Register']; ?>
</h5>
<div style="margin-left:10px; margin-top:10px;">
<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
<div class="register-box-tr">
<label class="text">Nickname :</label>
<div class="line-box-register">
<input name="username" type="text" id="username" value="<?php echo $this->_tpl_vars['save']['username']; ?>
" maxlength="30" class="formfield_01" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
<div id="username_info" class="left"></div>
</div>
</div>
<div class="register-box-tr" style="margin-bottom:5px;">
<label class="text">Achtung :</label>
<div style="display:block; width:480px; float:left; font-weight:normal; font-size:10px; margin-top:5px; margin-left:10px; line-height:1.3em;">Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.</div>
</div>

<div class="register-box-tr">
<label class="text"><?php echo $this->_config[0]['vars']['Email']; ?>
:</label>
<div class="line-box-register">
<input id="email" name="email" type="text" value="<?php echo $this->_tpl_vars['save']['email']; ?>
" class="formfield_01" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
<div id="email_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text"><?php echo $this->_config[0]['vars']['PASSWORD']; ?>
:</label>
<div class="line-box-register">
<input id="password" name="password" type="password" maxlength="30" class="formfield_01" onblur="checkNullPassword(this.value);" autocomplete="off"/>
<div id="password_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text"><?php echo $this->_config[0]['vars']['Birthday']; ?>
:</label>
<div class="line-box-register">
<?php echo smarty_function_html_options(array('id' => 'date','name' => 'date','options' => $this->_tpl_vars['date'],'selected' => $this->_tpl_vars['save']['date'],'class' => 'date formfield_01'), $this);?>

<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['month'],'id' => 'month','name' => 'month','onchange' => "getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)",'selected' => $this->_tpl_vars['save']['month'],'class' => 'month formfield_01'), $this);?>

<?php echo smarty_function_html_options(array('id' => 'year','name' => 'year','options' => ((is_array($_tmp=@$this->_tpl_vars['year_range'])) ? $this->_run_mod_handler('default', true, $_tmp, 1994) : smarty_modifier_default($_tmp, 1994)),'onchange' => "getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)",'selected' => $this->_tpl_vars['save']['year'],'class' => 'year formfield_01'), $this);?>

</div>
</div>

<div class="register-box-tr">
<label class="text"><?php echo $this->_config[0]['vars']['Gender']; ?>
:</label>
<div class="line-box-register">
	<div style="float:left; line-height:30px;">
	<?php echo smarty_function_html_radios(array('id' => 'gender','name' => 'gender','options' => $this->_tpl_vars['gender'],'selected' => $this->_tpl_vars['save']['gender'],'labels' => false,'separator' => "&nbsp;&nbsp;&nbsp;&nbsp;",'onClick' => "checkNullRadioOption('register_form',this,'')"), $this);?>

    </div>
<div id="gender_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text"><?php echo $this->_config[0]['vars']['Country']; ?>
:</label>
<div class="line-box-register">
<select id="country" name="country" class="formfield_01"  autocomplete='off' style="width:310px !important">
<?php $_from = $this->_tpl_vars['country']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['foo']):
?>
<option value="<?php echo $this->_tpl_vars['foo']['id']; ?>
"><?php echo $this->_tpl_vars['foo']['name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>
<div id="country_info" class="left"></div>
</div>
</div>

<div class="register-box-tr" style="margin-bottom:10px;">
<div style="display:block; width:800px; float:left; height:auto; margin-top:5px;">
<div style="float:left; margin-left:117px;">
<input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" style="float:left;"/><span style="display:block; width:500px; float:left; margin-left:5px; line-height:1.3em;">
<div id="accept_info"></div><div style="float:left; position:absolute; z-index:2; width:500px;"><?php echo $this->_config[0]['vars']['AGB_accept_txt']; ?>
</div></span>
</div>

</div>
</div>

<div style="margin-left:10px; margin-bottom:10px;">
<input type="hidden" name="submit_form" value="1"/>
<label class="text"></label>
<a href="javascript: void(0)" <?php if ($_COOKIE['flirt48_activated'] != ""): ?> onclick="javascript:alert('<?php echo $this->_config[0]['vars']['useraccount_activated']; ?>
');" <?php else: ?> onclick="if(checkNullSignup1()) jQuery('#register_form').submit();" <?php endif; ?> class="btn-red" style="width:305px;"><?php echo $this->_config[0]['vars']['Register']; ?>
</a>

<div style="margin-left:95px;"><a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="register-facebook-02"><span>register-facebook</span></a></div>
<br class="clear" />
</div>
</form>
</div>