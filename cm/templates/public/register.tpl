{include file='public/header.tpl'}
<div class="wrap">
     <div class="container-register-box">
        <div class="container-form-login">
        <h1>Herzlich Willkommen</h1>
        	<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
        	
        		{if isset($text) && $text neq ""} 
        			<span id='main_error'>{$text}</span>
        		{/if}
        	
        	
                <input id='username' name="username" value="{$save.username}" type="text" placeholder="Nickname" style="margin-top:20px;">
                <span id="username_error" class="sms-error"></span>
                
                <input id='email' name="email" value="{$save.email}" type="text" placeholder="Email">
                <span id="email_error" class="sms-error"></span>
                
                <input id='password' name="password" type="password" placeholder="Passwort">
                <span id="password_error" class="sms-error"></span>
                
                <div style="float:left; width:100%">
                <span class="title-text">Geburtstag:</span>
                <select id='date' name="date" style="width:17%;">
	                {html_options options=$date selected=$save.date}
                </select>
                <select id="month" name="month" style="width:50%;">
                	{html_options options=$month selected=$save.month}
                </select>
                <select id="year" name="year" style="width:30%;">
                	{html_options options=$year_range selected=1994}
                </select>
                </div>
                
                <div style="float:left; width:100%; margin-bottom:0;">
                <span class="title-text">Geschlecht:</span>
                <div id='genderdiv'>
                	<div>{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}</div>
                	<span id='gender_error' class="sms-error"></span>
                </div>
                </div>
                
                <div style="float:left; width:100%">
                <span class="title-text">Nationalität:</span>
                <select id="country" name="country">
					{html_options options=$country selected=1}
                </select>
                </div>
                
                <div style="float:left; width:100%; float:left;">
                    
                    <div style="width:5%; float:left; margin-right:2%;">
                    <input type='checkbox' name="accept" id="accept" value="1" style="margin-top:3px;"></div>
                    <span style="float:left; width:93%; display:block; margin-bottom:10px;">
                    Ich habe die Allgemeinen Geschäftsbedingungen und die Datenschutzerklärung gelesen und stimme diesen zu!
                    </span>
                    <span id='accept_error' class="sms-error"></span>
                    <br class="clear">
                    <a id='register' href="#" class="btn-01">Schnellregistrierung</a>
                    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-01 facebook">Mit Facebook Registrieren!</a>
                </div>
                <input type='hidden' name='submit_form' value='1' />
                <input type='hidden' name='mobile' value='1' />
          </form>
        </div>
        <br class="clear">
        </div>
    </div>
<script type='text/javascript'>
	var app_path = '{$smarty.const.APP_PATH}';
{literal}
	function checkUsername(username)
	{
		if (username.length < 6) return false;
		var retval = true;
		$.ajax({
        	url: app_path + "/ajax/isusername/" + username, 
        	type: 'get',
        	dataType: 'json',
         	success: function(json) {
         		if (json.status == 1) retval = false;
         	},
         	async: false
    	});          
    	
    	return retval;
	}
	
	function checkEmail(email)
	{
		var retval = true;
		$.ajax({
        	url: app_path + "/ajax/isemail/" + email, 
        	type: 'get',
        	dataType: 'json',
         	success: function(json) {
         		if (json.status == 1) retval = false;
         	},
         	async: false
    	});          
    	
    	return retval;
	}
	
	function resetErrorField(field)
	{
		$("#" + field + "_error").hide();
	}
	
	function setErrorField(field, msg)
	{
		$("#" + field).addClass('error');
		$("#" + field).removeClass('ok');
		$("#" + field).focus();
		$("#" + field + "_error").html("<strong>" + msg + "</strong>");
		$("#" + field + "_error").show();
	}
	
	function setOkField(field)
	{
		$("#" + field).addClass('ok');
		$("#" + field).removeClass('error');
		$("#" + field + "_error").hide();
	}
	
	function checkForm()
	{
		var retval = true;
	
		var username = $("#username").val();
		resetErrorField("username");
		if (username == "") {
			setErrorField("username", "Nickname eingeben");
			retval = false;
		}
		
		var email = $("#email").val();
		resetErrorField("email");
		if (email == "") {
			setErrorField("email", "email eingeben");
			retval = false;
		} else if (!isValidEmailAddress(email)) {
			setErrorField("email", "Email nicht korrekt!");
			retval = false;
		}
		
		var password = $("#password").val();
		resetErrorField("password");
		if (password == "") {
			setErrorField("password", "Passwort eingeben");
			retval = false;
		} else if (password.length < 6) {
			setErrorField("password", "Passwort zu kurz");
			retval = false;
		}
		
		$("#gender_error").hide();
		if($("input:radio[name='gender']").is(":checked")) {
		} else {
			$("#gender_error").html("<strong>geschlecht auswählen</strong>");
			$("#gender_error").show();
			retval = false;
		}
		
		$("#accept_error").hide();
		if($("input:checkbox[name='accept']").is(":checked")) {
		} else {
			$("#accept_error").html("<strong>Bitte AGB akzeptieren</strong>");
			$("#accept_error").show();
			retval = false;
		}
		return retval;
	}
	
	function isValidEmailAddress(email) {
		if (email == "") return false;
		var re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,4}$/;
	    return re.test(email);
	};

	$(function () {
		
		$("#password").blur(function (e) {
			if ($(this).val() == "") {
				setErrorField("password", "Passwort eingeben");
			} else if (password.length < 6) {
				setErrorField("password", "Passwort zu kurz");
			} else {
				setOkField("password");
			}
		});
		
		$("#email").blur(function (e) {
			if (isValidEmailAddress($(this).val())) {
				setOkField("email");
			} else {
				setErrorField("email", "email eingeben");
			}
		});
		
		$("#username").blur(
			function(e) {
				if ($(this).val() == "") {
					setErrorField("username", "Nickname eingeben");
				} else {
					var valid = checkUsername($(this).val());
					if (valid) {
						setOkField("username");
					} else {
						setErrorField("username", "Nickname existiert bereits");
					}
				}
			}
		);
	
		$("#register").click(function (e) {
			e.preventDefault();
			if (checkForm()) {
				$.ajax({
					url: app_path + '/register',
					type: 'post',
					dataType: 'json',
					data: $("#register_form").serialize(),
					success: function(json) {
						if (json.status == 1) {
							$.each(json.errors, function (k,v) {
								setErrorField(v.field, v.error);
							});
						} else if (json.status == 0) {
							window.location.href = app_path + '/verify';
						}
					}
				});
			}
		});
	});
{/literal}
</script>
{include file='public/footer.tpl'}