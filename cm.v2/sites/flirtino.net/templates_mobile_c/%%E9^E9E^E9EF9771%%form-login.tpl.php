<?php /* Smarty version 2.6.14, created on 2014-01-06 12:47:54
         compiled from form-login.tpl */ ?>
<div class="container-login-box">
	<div class="container-login">
		<h1>Login</h1>
		<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
		<input name="l_username" id="l_username" type="text" class="formfield_01" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
" style=" width:215px; margin-right:10px"/>
		<input name="l_password" id="l_password" type="password" class="formfield_01" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['PASSWORD']; ?>
" style=" width:215px;"/><br class="clear" />
		<div style="float:left; margin-bottom:10px; margin-top:8px;"><input  name="remember" id="remember" type="checkbox" value="1" /><?php echo $this->_config[0]['vars']['Remember_me']; ?>
</div>
		<a href="#" class="forget-pass" onclick="loadPagePopup('?action=forget', '100%'); return false;"><?php echo $this->_config[0]['vars']['PASSWORD']; ?>
 <?php echo $this->_config[0]['vars']['FORGOTTEN']; ?>
?</a><br class="clear" />
		<a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="btn-login-fb" style="padding-left:20px; width:210px;">LOGIN WITH FACEBOOK</a>
		<a href="#" class="btn-login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">LOGIN</a>
		</form>
	</div>
	<div class="container-register">
		<h1><?php echo $this->_config[0]['vars']['Register']; ?>
</h1>
		<form id="form_register_small" method="post" action="?action=register">
		<input name="username" type="text" class="formfield_01" style=" width:215px; margin-right:10px" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
" AUTOCOMPLETE="OFF"/>
		<input name="email" type="text"  class="formfield_01" style=" width:215px;" placeholder="<?php echo $this->_config[0]['vars']['Email']; ?>
" autocomplete='off'/><br class="clear" />
		<div style="float:left; margin-bottom:10px; margin-top:8px;"><input name="" type="radio" value="" />Male</div>
		<div style="float:left; margin-bottom:10px; margin-top:8px; margin-left:10px;"><input name="" type="radio" value="" />Female</div><br class="clear" />
		<input name="submitbutton" type="submit" value="submit" style="display: none"/>
		<a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="btn-login-fb" style="padding-left:32px; width:198px;">SIGN UP THROUGH FACEBOOK</a>
		<a href="#" class="btn-login" onclick="document.getElementById('form_register_small').submit(); return false;">REGISTER</a>
		</form>
	</div>
</div>

<script>
var sendingForgetPassword = false;
</script>