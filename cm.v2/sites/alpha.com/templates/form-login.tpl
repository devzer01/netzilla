<div class="container-login">
            	<h1>Login</h1>
            	<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
                <input name="l_username" id="l_username" type="text" class="formfield_01" style="width:215px; margin-right:10px" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}" />
                <input name="l_password" id="l_password" type="text" class="formfield_01" style="width:215px;" onkeypress="enterLogin(event)" placeholder="{#PASSWORD#}" /><br class="clear" />
                <div style="float:left; margin-bottom:10px; margin-top:8px;"><input name="" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />{#Remember_me#}</div>
                <a href="#" class="forget-pass" onclick="loadPagePopup('?action=forget', '100%'); return false;">{#PASSWORD#} {#FORGOTTEN#}?</a><br class="clear" />
                <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb" style="padding-left:20px; width:210px;">LOGIN WITH FACEBOOK</a>
                <a href="#" class="btn-login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">LOGIN</a>
                </form>
</div>

<script>
var sendingForgetPassword = false;
</script>