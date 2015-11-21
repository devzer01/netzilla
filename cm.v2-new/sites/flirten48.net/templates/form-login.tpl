<!-- {$smarty.template} -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">

<!--<input type="text" name="l_username" id="l_username" class="input-login-box" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}"/> -->
<input type="text" name="l_username" id="l_username" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}" style="width:190px !important; margin-bottom:5px; margin-top:5px;"/>

<input type="password" name="l_password" id="l_password" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#PASSWORD#}" style="width:190px !important; margin-bottom:5px;"/>
<span class="login-span-box">
<input name="remember" id="remember" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />{#Remember_me#}
</span>


<a href="#" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-yellow-s">{#login#}</a>

<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-yellow-s facebook-log"><span style="display:none;">face book</span></a>

<label class="text txt-register" style="width:150px !important; float:left !important; font-weight:bold;"><a href="#" class="forgetPass" onclick="loadPagePopup('?action=forget', '100%'); return false;">{#PASSWORD#} {#FORGOTTEN#}?</a></label>

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