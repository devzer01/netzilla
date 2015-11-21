<div class="container-box-login">
  <div class="container-login-form">
    	<h2>LOGIN</h2>
    	<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
        <input name="l_username" id="l_username" type="text" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}" style="width:220px; margin-right:10px;"/>
        <input name="l_password" id="l_password" type="password" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#PASSWORD#}" style="width:220px;"/><br class="clear" />
        <div style="margin-top:5px; margin-bottom:10px;">
        <input name="" type="checkbox" value="" /><font class="text-login-checkbox">{#Remember_me#}</font>
        <a href="javascript:;" onclick="javascript:loadPagePopup('?action=forget', '100%'); return false;" class="forget-password">{#PASSWORD#} {#FORGOTTEN#}?</a>
        </div>
        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb" style="width:230px; margin-right:10px;">
        <img src="images/bg-btn-logo-fb.png" width="26" height="30" style="float:left; margin:0 5px;"/>Login with Facebook</a>
        <a href="#" class="btn-login" style="width:230px;" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">Login</a>
        </form>
    </div>
    <div class="container-register-form">
    	<h2>{#Register#}</h2>
    	<form id="form_register_small" method="post" action="?action=register">
        <input name="username" type="text" class="formfield_01" placeholder="{#USERNAME#}" AUTOCOMPLETE="OFF" style="width:220px; margin-right:10px;"/>
        <input name="email" type="text"  class="formfield_01" placeholder="{#Email#}" autocomplete='off' style="width:220px;"/><br class="clear" />
        <div style="margin-top:5px; margin-bottom:10px;">
        <input name="" type="checkbox" value="" /><font class="text-login-checkbox">Male</font>
        <input name="" type="checkbox" value="" /><font class="text-login-checkbox">Female</font>
        </div>
        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb" style="width:230px; margin-right:10px;">
        <img src="images/bg-btn-logo-fb.png" width="26" height="30" style="float:left; margin:0 5px;"/>Sign up Through Facebook</a>
        <a href="#" onclick="document.getElementById('form_register_small').submit(); return false;" class="btn-login" style="width:230px;">KOSTENLOS ANMELDEN</a>
        </form>
    </div>
</div>

<script>
var sendingForgetPassword = false;
</script>