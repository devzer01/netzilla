{include file='public/header.tpl'}

<div class="wrap">
   		<div class="container-login-box">
            <div class="container-form-login">
                <form id='loginform'>
                	<span class='sms-error' id='login_error'></span>
                    <input id='username' name="username" type="text" placeholder="Benutzername" style="margin-top:20px;">
                    <input id='password' name="password" type="password" placeholder="Passwort">
                    <div style="float:left; width:100%; float:left;">
                        <input name="remember" id='remember' type="checkbox" value="1" style="margin-right:5px; float:left; margin-top:3px;"><span style="float:left; color:#FFF;">Angemeldet bleiben</span>
                        <a href="{$smarty.const.APP_PATH}/password" class="forgetpass">Passwort vergessen?</a>
                        <br class="clear">
                        <a href="#" id='login' class="btn-01 login">Login</a>
                        <input type='submit' style='display: none;'/>
                    </div>
                </form>
            </div>
            <br class="clear">
        </div>
        <div class="icon-login"><img src="images/mobile/icon-login-box.png" width="51" height="44"></div>
    </div>

<script type='text/javascript'>
	var app_path = '{$smarty.const.APP_PATH}';
{literal}
	$(function () {
		
		var login = function (e) {
			
			var username = $("#username").val();
			var password = $("#password").val();
			
			var remember = 0;
			
			if($("#remember").is(":checked")) {
				remember = 1;
			}
			
			$.ajax({
				url: app_path + '/login',
				data: {username: username, password: password, remember: remember},
				type: 'post',
				dataType: 'json',
				success: function(json) {
					if (json.status == 1) {
						window.location.href = app_path + "/";
					} else if (json.status == 2) {
						window.location.href = app_path + '/verify'
					} else {
						$("#login_error").html("<strong>Username oder Passwort falsch</strong>");
						$("#login_error").show();
					}
				}
			});
			
			return false;
		};
		
		$("#login").click(login);
		$("#loginform").submit(login);
	});
{/literal}
</script>

{include file='public/footer.tpl'}
