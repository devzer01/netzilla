<!-- {$smarty.template} -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>

<h1>{#Register#}</h1>  
<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
	<!-- register form -->       
	<div class="register-page-box">
	<div class="regis-box-line">
	<label>{#USERNAME#}:</label>
	<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="formfield_01 input-register validate[required,minSize[6]]" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
	</div>
	
	<div class="regis-box-line">
	<label>Achtung :</label>
	<p>Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.</p>
	</div>
	
	<div class="regis-box-line">
	<label>{#Email#}:</label>
	<input id="email" name="email" type="text" value="{$save.email}" class="formfield_01 input-register" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
	</div>
	
	<div class="regis-box-line">
	<label>{#PASSWORD#}:</label>
	<input id="password" name="password" type="password" maxlength="30" class="formfield_01 input-register" onblur="checkNullPassword(this.value);" autocomplete="off"/>
	</div>
	
	<div class="regis-box-line">
	<label>{#Birthday#}:</label>
	{html_options id="date" name="date" options=$date selected=$save.date class="formfield_01" style="width:80px; margin-right:5px;"}
	{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="formfield_01" style="width:150px; margin-right:5px;"}
	{html_options id="year" name="year" options=$year_range|default:1994 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="formfield_01" style="width:100px;"}
	</div>

	<div class="regis-box-line" style="width:600px;">
	<label>{#Gender#}:</label>
	<div style="margin-top:5px;">
	{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;" onClick="checkNullRadioOption('register_form',this,'')"}
	</div>
	</div>
	
	<div class="regis-box-line">
	<label>{#Country#}:</label>
	<select id="country" name="country" class="formfield_01" autocomplete='off' style="width:345px !important">
	{foreach from=$country item=foo}
	<option value="{$foo.id}">{$foo.name}</option>
	{/foreach}
	</select>
	<div id="country_info" class="left"></div>
	</div>
	
	<div class="regis-box-line">
	<label>&nbsp;</label>
	<input id="accept" name="" type="checkbox" value="" style="margin-right:5px;"/>{#AGB_accept_txt#}
	<div id="accept_info"></div>
	</div>
	
	
	<div style="margin-left:170px; margin-bottom:40px; float:left;">
        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb" style="width:230px; margin-right:10px;">
        <img src="images/bg-btn-logo-fb.png" width="26" height="30" style="float:left; margin:0 5px;">Sign up Through Facebook</a>
        <a href="javascript:;" {if $smarty.cookies.flirt48_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1()) $('#register_form').submit();" {/if} class="btn-login" style="width:230px;">{#Register#}</a>
    </div>
	
	
	
	</div>
	<!--end register form -->
</form>