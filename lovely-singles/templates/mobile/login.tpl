{include file='mobile/header.tpl'}

<div class="header-bar"></div>

<div class="wrap">
   		<div class="container-login-box">
            <div class="container-form-login">
            	<h1 class="title-login">Login</h1>
                <form>
                    <input id='username' name="username" type="text" placeholder="Benutzername" style="margin-top:20px;">
                    <input id='password' name="password" type="password" placeholder="Passwort">
                    <div style="float:left; width:100%; float:left;">
                        <input name="remember" id='remember' type="checkbox" value="1" style="margin-right:5px; float:left; margin-top:3px;"><span style="float:left; color:#000; margin-top:3px;">Angemeldet bleiben</span>
                        <a href="?action=forget" class="forgetpass">Passwort vergessen?</a>
                        <br class="clear">
                        <a href="#" id='login' class="btn-01 login">Login</a>
                    </div>
                </form>
            </div>
            <br class="clear">
        </div>
        <div class="error-box" id='login_error' style='display: none;'>
        	
        </div>
    </div>

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
