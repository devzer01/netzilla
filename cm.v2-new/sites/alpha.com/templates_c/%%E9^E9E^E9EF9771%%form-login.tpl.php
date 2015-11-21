<?php /* Smarty version 2.6.14, created on 2013-11-18 08:26:11
         compiled from form-login.tpl */ ?>
<div class="container-login">
            	<h1>Login</h1>
            	<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
                <input name="l_username" id="l_username" type="text" class="formfield_01" style="width:215px; margin-right:10px" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['USERNAME']; ?>
" />
                <input name="l_password" id="l_password" type="text" class="formfield_01" style="width:215px;" onkeypress="enterLogin(event)" placeholder="<?php echo $this->_config[0]['vars']['PASSWORD']; ?>
" /><br class="clear" />
                <div style="float:left; margin-bottom:10px; margin-top:8px;"><input name="" type="checkbox" value="1" <?php if(empty($_COOKIE[notremember])){echo 'checked="checked"';}  ?> /><?php echo $this->_config[0]['vars']['Remember_me']; ?>
</div>
                <a href="#" class="forget-pass" onclick="loadPagePopup('?action=forget', '100%'); return false;"><?php echo $this->_config[0]['vars']['PASSWORD']; ?>
 <?php echo $this->_config[0]['vars']['FORGOTTEN']; ?>
?</a><br class="clear" />
                <a href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
" class="btn-login-fb" style="padding-left:20px; width:210px;">LOGIN WITH FACEBOOK</a>
                <a href="#" class="btn-login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">LOGIN</a>
                </form>
</div>

<script>
var sendingForgetPassword = false;
</script>