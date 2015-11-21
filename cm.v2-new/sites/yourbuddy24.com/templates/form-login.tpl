<div class="container-box-content-01">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-01-m-l login-height"></div>
	<div class="box-content-01-m-m login-height">
		<h1 style="margin-bottom:10px;">Login</h1>
		<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('#l_username').val()+'&amp;password='+$('#l_password').val(), '', loginSite, '')" action="" method="post">
		<input type="text" name="l_username" id="l_username" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#USERNAME#}" style="width:209px; margin-bottom:10px;"/>
		<input type="password" name="l_password" id="l_password" class="formfield_01" onkeypress="enterLogin(event)" placeholder="{#PASSWORD#}" style="width:209px; margin-bottom:10px;"/>
		<div style="float:left; margin-bottom:10px;">
			<input name="remember" id="remember" class="css-checkbox" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />
			<label for="remember" class="css-label">{#Remember_me#}</label><br class="clear" />
		</div>
		<a href="#" class="btn-register" style="margin-bottom:10px;" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">{#login#}</a>
		<a href="#" class="fb-login" style="margin-bottom:10px;"><span class="hidden">facebook</span></a>
		<br class="clear" />
		<a href="#" class="forget-pass-link" onclick="loadPagePopup('?action=forget', '100%'); return false;">{#PASSWORD#} {#FORGOTTEN#}?</a>
		</form>
	</div>
	
	<div class="box-content-01-m-r login-height"></div>
	
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m"></div>
	<div class="box-content-01-b-r"></div>
</div>

<script>
var sendingForgetPassword = false;
</script>