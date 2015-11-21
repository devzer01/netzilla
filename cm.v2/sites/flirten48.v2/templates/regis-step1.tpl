<!-- {$smarty.template} -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>


<div class="title">
	<div class="title-left"></div><h1>{#Register#}</h1><div class="title-right"></div>
</div>

<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
<div class="container-register-form">

	<label class="text">Nickname :</label>
	<input type="text" autocomplete="off" value="{$save.username}" onkeyup="checkUsernameSilent(this.value)" class="input-register validate[required,minSize[6]]" maxlength="30" id="username" name="username" />
	<div id="username_info" class="left"></div>
	<br class="clear" />
	
	<label class="text">Achtung :</label>
	<div style="height:24px; padding-top:15px;">
		Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.
	</div>
	<br class="clear" />
	
	<label class="text">{#Email#}:</label>
	<input id="email" name="email" type="text" value="{$save.email}" class="input-register" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
	<div id="email_info" class="left"></div>
	<br class="clear" />
	
	<label class="text">{#PASSWORD#}:</label>
	<input id="password" name="password" type="password" maxlength="30" class="input-register" onblur="checkNullPassword(this.value);" autocomplete="off"/>
	<div id="password_info" class="left"></div>
	<br class="clear" />
	
	<label class="text">{#Birthday#}:</label>
	{html_options id="date" name="date" options=$date class="select-register" selected=$save.date style="width:80px;"}
	{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" class="select-register" selected=$save.month style="width:144px;"}
	{html_options id="year" name="year" options=$year_range|default:1994 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="select-register" style="width:90px;"}
	<br class="clear" />
	
	<label class="text">{#Gender#}:</label>
	{html_options id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" class="select-register" style="width:322px;"}
	<div id="gender_info" class="left"></div>
	<br class="clear" />
	

	<label class="text">{#Country#}:</label>
	<select id="country" name="country" class="select-register" style="width:322px;" autocomplete='off' style="width:310px !important">
	{foreach from=$country item=foo}
	<option value="{$foo.id}">{$foo.name}</option>
	{/foreach}
	</select>
	<div id="country_info" class="left"></div>
	<br class="clear" />
	
	<div style="margin-left:150px; padding-top:10px;">
		<input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" />
		{#AGB_accept_txt#}
		<div id="accept_info"></div>
	</div>
    <br class="clear" />
	

	<input type="hidden" name="submit_form" value="1" />
	<a href="javascript: void(0)" {if $smarty.cookies.flirt48_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1()) jQuery('#register_form').submit();" {/if} class="btn-register">{#Register#}</a>
	<a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-login-fb"><span style="display:block; background:url(images/cm-theme/fb.png) no-repeat 10px 10px; width:240px; margin:0 auto;">Register with Facebook</span></a>
	<br class="clear" />
	
</div></form>
<br class="clear" />
</div>
