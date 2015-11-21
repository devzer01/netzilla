<!-- {$smarty.template} -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
<h1>Login</h1>

<input type="text" name="l_username" id="l_username" class="input-user" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}" />
<input type="password" name="l_password" id="l_password" class="input-pass" onkeypress="enterLogin(event)" placeholder="{#PASSWORD#}" />
<span class="login-span-box">
	<input name="remember" id="remember" type="checkbox" class="check-member" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />{#Remember_me#}
</span>


<a href="#" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-login">{#login#}</a>

<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb"><span style="display:block; background:url(images/cm-theme/fb.png) no-repeat 10px 12px; width:210px; margin:0 auto;">Login with Facebook</span></a>

<a href="?action=register" class="btn-register">{#Register#}</a>
<a href="#" class="forget-pass" onclick="loadPagePopup('?action=forget', '100%'); return false;">{#PASSWORD#} {#FORGOTTEN#}?</a>


</form>

<!--<span>
<a href="?action=register&amp;type=membership">{#Register#}</a> | 
<a href=""></a> | 
<a href="?action=resendactivation">{#resend_title#}</a>
</span>
 -->

<script>
var sendingForgetPassword = false;
</script>