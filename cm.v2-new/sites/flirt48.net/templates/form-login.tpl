<!-- {$smarty.template} -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('l_username').value+'&amp;password='+$('l_password').value, '', loginSite, '')" action="" method="post">

<label class="text">{#USERNAME#}:</label>
<input type="text" name="l_username" id="l_username" class="input-login-box" onkeypress="enterLogin(event)"/>

<label class="text">{#PASSWORD#}:</label>
<input type="password" name="l_password" id="l_password" class="input-login-box" onkeypress="enterLogin(event)"/>
<span class="login-span-box">
<input name="remember" id="remember" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />{#Remember_me#}
</span>

<label class="text txt-register"><a href="#" class="forgetPass" onclick="loadPagePopup('?action=forget', '100%'); return false;">{#PASSWORD#}{#FORGOTTEN#}?</a></label>
<a href="#" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-yellow-s">{#login#}</a>

<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-yellow-s facebook-log"><!--<img src="images/cm-theme/btn_facebooklogin.png"> --><span style="display:none;">face book</span></a>
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