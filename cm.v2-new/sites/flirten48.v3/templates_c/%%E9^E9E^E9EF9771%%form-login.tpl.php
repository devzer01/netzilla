<?php /* Smarty version 2.6.14, created on 2013-11-18 16:51:36
         compiled from form-login.tpl */ ?>
<!-- <?php echo 'form-login.tpl'; ?>
 -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">

<!--<input type="text" name="l_username" id="l_username" class="input-login-box" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
"/> -->
<input type="text" name="l_username" id="l_username" class="formfield_01" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
" style="width:190px !important; margin-bottom:5px; margin-top:5px;"/>

<input type="password" name="l_password" id="l_password" class="formfield_01" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['PASSWORD']; ?>
" style="width:190px !important; margin-bottom:5px;"/>
<span class="login-span-box">
<input name="remember" id="remember" type="checkbox" value="1" <?php if(empty($_COOKIE[notremember])){echo 'checked="checked"';}  ?> /><?php echo $this->_config[0]['vars']['Remember_me']; ?>

</span>


<a href="#" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-yellow-s"><?php echo $this->_config[0]['vars']['login']; ?>
</a>

<a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="btn-yellow-s facebook-log"><span style="display:none;">face book</span></a>

<label class="text txt-register" style="width:150px !important; float:left !important; font-weight:bold;"><a href="#" class="forgetPass" onclick="loadPagePopup('?action=forget', '100%'); return false;"><?php echo $this->_config[0]['vars']['PASSWORD']; ?>
 <?php echo $this->_config[0]['vars']['FORGOTTEN']; ?>
?</a></label>

</form>

<!--<span>
<a href="?action=register&amp;type=membership"><?php echo $this->_config[0]['vars']['Register']; ?>
</a> | 
<a href=""></a> | 
<a href="?action=resendactivation"><?php echo $this->_config[0]['vars']['resend_title']; ?>
</a>
</span>
 -->

<script>
var sendingForgetPassword = false;
</script>