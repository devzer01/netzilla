{include file='mobile/header.tpl'}
<div class="wrap">

        <div class="container-register-box">
        <h1 class="title-register">Herzlich Willkommen</h1>
        	<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
        	
        		{if $text neq ""} 
        			<span id='main_error'>{$text}</span>
        		{/if}
        	
        	
                <input id='username' name="username" value="{$save.username}" type="text" placeholder="Nickname" style="margin-top:5px;">
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
                	{html_options options=$month}
                </select>
                <select id="year" name="year" style="width:30%;">
                	{html_options options=$year_range|default:1994 selected=$save.year}
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
					{foreach from=$country item=foo}
						<option value="{$foo.id}">{$foo.name}</option>
					{/foreach}
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
                    <a id='register' href="#" class="btn-02 btn-register">Schnellregistrierung</a>
                    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-02 btn-facebook">Mit Facebook Registrieren!</a>
                </div>
                <input type='hidden' name='submit_form' value='1' />
                <input type='hidden' name='mobile' value='1' />
          </form>
        <br class="clear">
        </div>
    </div>
<div class="push"></div>
<script type='text/javascript'>
{literal}

	function checkUsername(username)
	{
		var retval = true;
		$.ajax({
        	url: "ajaxRequest.php?action=isUsername", 
        	data: {username: username},
        	type: 'post',
         	success: function(json) {
         		if (json == 1) retval = false;
         	},
         	async: false
    	});          
    	
    	return retval;
	}
	
	function checkForm()
	{
	
		var retval = true;
	
		var username = $("#username").val();
		$("#username_error").hide();
		if (username == "") {
			$("#username").addClass('error');
			$("#username").removeClass('ok');
			$("#username").focus();
			$("#username_error").html("<strong>Nickname eingeben</strong>");
			$("#username_error").show();
			retval = false;
		}
		
		var email = $("#email").val();
		$("#email_error").hide();
		if (email == "") {
			$("#email").addClass('error');
			$("#email").removeClass('ok');
			$("#email").focus();
			$("#email_error").html("<strong>email eingeben</strong>");
			$("#email_error").show();
			retval = false;
		}
		
		var password = $("#password").val();
		$("#password_error").hide();
		if (password == "") {
			$("#password").addClass('error');
			$("#password").removeClass('ok');
			$("#password").focus();
			$("#password_error").html("<strong>Passwort eingeben</strong>");
			$("#password_error").show();
			retval = false;
		} else if (password.length < 6) {
			//test
			$("#password").addClass('error');
			$("#password").removeClass('ok');
			$("#password").focus();
			$("#password_error").html("<strong>Passwort zu kurz</strong>");
			$("#password_error").show();
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

	$(function () {
		
		$("#password").blur(function (e) {
			if ($(this).val() == "") {
				$(this).addClass('error');
				$(this).removeClass('ok');
				$("#password_error").show();
				$("#password_error").html("<strong>Passwort eingeben</strong>");
			} else if (password.length < 6) {
				$("#password").addClass('error');
				$("#password").removeClass('ok');
				$("#password_error").html("<strong>Passwort zu kurz</strong>");
				$("#password_error").show();
			} else {
				$(this).addClass('ok');
				$(this).removeClass('error');
				$("#password_error").hide();
			}
		});
		
		$("#email").blur(function (e) {
			if ($(this).val() == "") {
				$(this).addClass('error');
				$(this).removeClass('ok');
				$("#email_error").show();
				$("#email_error").html("<strong>email eingeben</strong>");
			} else {
				$(this).addClass('ok');
				$(this).removeClass('error');
				$("#email_error").hide();
			}
		});
		
		$("#username").blur(
			function(e) {
				if ($(this).val() == "") {
					$(this).addClass('error');
					$(this).removeClass('ok');
					$("#username_error").html("<strong>Nickname eingeben</strong>");
				} else {
					var valid = checkUsername($(this).val());
					if (valid) {
						$(this).addClass('ok');
						$(this).removeClass('error');
						$("#username_error").hide();
					} else {
						$(this).addClass('error');
						$(this).removeClass('ok');
						$("#username_error").html("<strong>Nickname existiert bereits</strong>");
						$("#username_error").show();
					}
				}
			}
		);
	
		$("#register").click(function (e) {
			e.preventDefault();
			if (checkForm()) {
				$("#register_form").submit();
			}
		});
	});
{/literal}
</script>
{include file='mobile/footer.tpl'}