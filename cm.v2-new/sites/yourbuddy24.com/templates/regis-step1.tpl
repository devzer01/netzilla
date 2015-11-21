<!-- {$smarty.template} -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
	var email_validated = false;
</script>

<h1 class="title" style="margin-top:20px;">{#Register#}</h1>  
<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
<div class="container-box-content-03">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m" style="width:900px !important;"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-03-m">
	<!-- register form -->       
	<div class="register-page-box">
	<div class="regis-box-line">
	<label>{#USERNAME#}:</label>
	<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="formfield_01 input-register validate[required,minSize[6]]" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
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
	<label>{#Confirm_Password#}:</label>
 		<input id="confirm_password" name="confirm_password" type="password" maxlength="30" class="formfield_01" onblur="checkNullPassword(this.value); isPasswordMatch();" onkeyup="isPasswordMatch();" autocomplete="off"/>
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
	
	<div class="regis-box-line">
	<label>&nbsp;</label>
	<input type="hidden" name="submit_form" value="1"/>
	<a href="javascript: void(0)" {if $smarty.cookies.flirt48_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1() && isEmailValid()) $('#register_form').submit();" {/if} class="btn-register" style="width:345px;">{#Register#}</a>
	</div>
	
	<div class="regis-box-line">
	<label>&nbsp;</label>
	<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="facebook-regis"></a>
	</div>
	
	</div>
	<!--end register form -->
	</div>
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m" style="width:900px !important;"></div>
	<div class="box-content-01-b-r"></div>
</div>
</form>