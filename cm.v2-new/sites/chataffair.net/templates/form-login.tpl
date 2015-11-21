<!-- {$smarty.template} -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('l_username').value+'&amp;password='+$('l_password').value, '', loginSite, '')" action="" method="post">
	<h1>Members login here</h1>
    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="facebook-login"><span>facebook</span></a>
    <span>:::::: or login below ::::::</span>
    <input name="l_username" id="l_username" type="text" placeholder="Benutzername" class="formfield_01 login-field"/>
    <input name="l_password" id="l_password" type="text"  placeholder="Passwort" class="formfield_01 login-field"/>
    <br class="clear" />
    <div class="login-checkbox">
       <input name="remember" id="remember" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />
       {#Remember_me#}
	</div>
            
    <div style="margin-top:6px;"><a href="javascript:void(0);" class="forgetPass" onclick="loadPagePopup('?action=forget', '100%'); return false;" class="forgotpass"><strong>{#PASSWORD#}{#FORGOTTEN#}?</strong></a></div>
    <a href="javascript:void(0);" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-blue btn-login">{#login#}</a>
</form>
<script>
{literal}
	var sendingForgetPassword = false;
{/literal}
</script>