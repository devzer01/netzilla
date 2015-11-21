{include file='mobile/header.tpl'}

<div class="wrap">
   		<div class="container-register-box">
            <h1 class="title-login">Login</h1>
                <form>
                	<span class='sms-error-login' id='login_error'></span>
                    <input id='username' name="username" type="text" placeholder="Benutzername" style="margin-top:5px;">
                    <input id='password' name="password" type="password" placeholder="Passwort">
                    <div style="float:left; width:100%; float:left;">
                        <input name="remember" id='remember' type="checkbox" value="1" style="margin-right:5px; float:left; margin-top:3px;"><span style="float:left; color:#FFF;">Angemeldet bleiben</span>
                        <a href="?action=forget" class="forgetpass">Passwort vergessen?</a>
                        <br class="clear">
                        <a href="#" id='login' class="btn-02 btn-login">Login</a>
                        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-02 btn-facebook">Login with Facebook!</a>
                    </div>
                </form>
            <br class="clear">
        </div>
    </div>
<div class="push"></div>
<script type='text/javascript'>
{literal}
	$(function () {
		$("#login").click(function (e) {
			
			var username = $("#username").val();
			var password = $("#password").val();
			
			var remember = 0;
			
			if($("#remember").is(":checked")) {
				remember = 1;
			}
			
			$.ajax({
				url: 'ajaxRequest.php',
				data: {action: 'loginmobile', username: username, password: password, remember: remember},
				type: 'post',
				success: function(json) {
					if (json == 1) {
						window.location.href = "/";
					} else {
						$("#login_error").html("<strong>Username oder Passwort falsch</strong>");
						$("#login_error").show();
					}
				}
			});
			
		});
	});
{/literal}
</script>

{include file='mobile/footer.tpl'}
