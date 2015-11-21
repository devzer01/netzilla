<?php /* Smarty version Smarty-3.1.17, created on 2014-06-26 09:08:06
         compiled from "templates/public/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:25856148353abc6d634df70-84374517%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '276fa7dd599e5705d40a47c0208de932fc3dd1a4' => 
    array (
      0 => 'templates/public/register.tpl',
      1 => 1403763224,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '25856148353abc6d634df70-84374517',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'text' => 0,
    'save' => 0,
    'date' => 0,
    'month' => 0,
    'year_range' => 0,
    'gender' => 0,
    'country' => 0,
    'foo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.17',
  'unifunc' => 'content_53abc6d63c2121_71023894',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_53abc6d63c2121_71023894')) {function content_53abc6d63c2121_71023894($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include '/usr/local/zend/share/pear/smarty3/plugins/function.html_options.php';
if (!is_callable('smarty_function_html_radios')) include '/usr/local/zend/share/pear/smarty3/plugins/function.html_radios.php';
?><?php echo $_smarty_tpl->getSubTemplate ('public/header.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

<div class="wrap">
     <div class="container-register-box">
        <div class="container-form-login">
        <h1>Herzlich Willkommen</h1>
        	<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
        	
        		<?php if (isset($_smarty_tpl->tpl_vars['text']->value)&&$_smarty_tpl->tpl_vars['text']->value!='') {?> 
        			<span id='main_error'><?php echo $_smarty_tpl->tpl_vars['text']->value;?>
</span>
        		<?php }?>
        	
        	
                <input id='username' name="username" value="<?php echo $_smarty_tpl->tpl_vars['save']->value['username'];?>
" type="text" placeholder="Nickname" style="margin-top:20px;">
                <span id="username_error" class="sms-error"></span>
                
                <input id='email' name="email" value="<?php echo $_smarty_tpl->tpl_vars['save']->value['email'];?>
" type="text" placeholder="Email">
                <span id="email_error" class="sms-error"></span>
                
                <input id='password' name="password" type="password" placeholder="Passwort">
                <span id="password_error" class="sms-error"></span>
                
                <div style="float:left; width:100%">
                <span class="title-text">Geburtstag:</span>
                <select id='date' name="date" style="width:17%;">
	                <?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['date']->value,'selected'=>$_smarty_tpl->tpl_vars['save']->value['date']),$_smarty_tpl);?>

                </select>
                <select id="month" name="month" style="width:50%;">
                	<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['month']->value,'selected'=>$_smarty_tpl->tpl_vars['save']->value['month']),$_smarty_tpl);?>

                </select>
                <select id="year" name="year" style="width:30%;">
                	<?php echo smarty_function_html_options(array('options'=>(($tmp = @$_smarty_tpl->tpl_vars['year_range']->value)===null||$tmp==='' ? 1994 : $tmp),'selected'=>$_smarty_tpl->tpl_vars['save']->value['year']),$_smarty_tpl);?>

                </select>
                </div>
                
                <div style="float:left; width:100%; margin-bottom:0;">
                <span class="title-text">Geschlecht:</span>
                <div id='genderdiv'>
                	<div><?php echo smarty_function_html_radios(array('id'=>"gender",'name'=>"gender",'options'=>$_smarty_tpl->tpl_vars['gender']->value,'selected'=>$_smarty_tpl->tpl_vars['save']->value['gender'],'labels'=>false,'separator'=>"&nbsp;&nbsp;&nbsp;&nbsp;"),$_smarty_tpl);?>
</div>
                	<span id='gender_error' class="sms-error"></span>
                </div>
                </div>
                
                <div style="float:left; width:100%">
                <span class="title-text">Nationalität:</span>
                <select id="country" name="country">
					<?php  $_smarty_tpl->tpl_vars['foo'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['foo']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['country']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['foo']->key => $_smarty_tpl->tpl_vars['foo']->value) {
$_smarty_tpl->tpl_vars['foo']->_loop = true;
?>
						<option value="<?php echo $_smarty_tpl->tpl_vars['foo']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['foo']->value['name'];?>
</option>
					<?php } ?>
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
                    <a href="<?php echo @constant('FACEBOOK_LOGIN_URL');?>
<?php echo $_SESSION['state'];?>
" class="btn-01 facebook">Mit Facebook Registrieren!</a>
                </div>
                <input type='hidden' name='submit_form' value='1' />
                <input type='hidden' name='mobile' value='1' />
          </form>
        </div>
        <br class="clear">
        </div>
    </div>
<script type='text/javascript'>


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
		} else if (!isValidEmailAddress(email)) {
			$("#email").addClass('error');
			$("#email").removeClass('ok');
			$("#email").focus();
			$("#email_error").html("<strong>Email nicht korrekt!</strong>");
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
	
	function isValidEmailAddress(email) {
    	var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    	return pattern.test(email);
	};

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

</script>
<?php echo $_smarty_tpl->getSubTemplate ('public/footer.tpl', $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
