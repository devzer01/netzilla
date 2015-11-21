{literal}
	<script type='text/javascript'>
		jQuery(function () {
			jQuery("#alogin").click(function (e) {
				e.preventDefault();
				ajaxRequest('login', 'username=' + jQuery('#l_username').val() + '&password='+jQuery('#l_password').val(), null, loginSite, null)
			});
		});
	</script>
{/literal}

<div class="login-box">
<h1 class="title-page-register">LOG IN</h1>
<form id='loginform' action="" method="post">
    <label>Benutzername:</label><input id='l_username' name="l_username" type="text"  class="formfield_01"/><br />
    <label>Passwort:</label><input id='l_password' name="l_password" type="password" class="formfield_01"/><br />
    <label><input name="" type="checkbox" value="" class="check-box" />Angemeldet bleiben</label><br />
    <a id='alogin' href="#" class="login">Login</a>
    <a id='apassword' href="#" onclick="loadPagePopup('?action=forget', '100%'); return false;" class="passwort">Passwortvergessen?</a>
    <a id='afacebook' href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="facebook">Login with Facebook</a>
</form>
</div>