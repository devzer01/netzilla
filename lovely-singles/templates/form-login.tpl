<form onsubmit="ajaxRequest('login', 'username='+$('l_username').value+'&amp;password='+$('l_password').value, '', loginSite, '')" action="" method="post">
<label>{#USERNAME#} :</label><input type="text" name="l_username" id="l_username" class="box" onkeypress="enterLogin(event)"/>
<label>{#PASSWORD#} :</label><input type="password" name="l_password" id="l_password" class="box" onkeypress="enterLogin(event)"/>
<br clear="all" />
<label style="padding-top:10px;"><input name="remember" id="remember" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} /> {#Remember_me#}</label>
<br clear="all" />
<a href="#" class="butsearch" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')">{#login#}</a>
<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="butsearch">Facebook Login</a>
<span>
	<a href="?action=forget">{#PASSWORD#} {#FORGOTTEN#}?</a>
	<a href="?action=resendactivation">{#resend_title#}</a>
</span>
</form>
