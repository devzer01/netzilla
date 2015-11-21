<!-- {$smarty.template} -->
<script language="javascript" type="text/javascript">
	var old_username="";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>

<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
<div class="register-box-tr">
<label class="text">Nickname :</label>
<div class="line-box-register">
<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="formfield_01" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
<div id="username_info" class="left"></div>
</div>
</div>
<div class="register-box-tr">
<label class="text">Achtung :</label>
<div style="display:block; width:400px; margin-bottom:3px; float:left; font-weight:normal; font-size:10px; margin-top:5px; margin-left:10px;">Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.</div>
</div>

<div class="register-box-tr">
<label class="text">{#Email#}:</label>
<div class="line-box-register">
<input id="email" name="email" type="text" value="{$save.email}" class="formfield_01" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
<div id="email_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#PASSWORD#}:</label>
<div class="line-box-register">
<input id="password" name="password" type="password" maxlength="30" class="formfield_01" onblur="checkNullPassword(this.value);" autocomplete="off"/>
<div id="password_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Birthday#}:</label>
<div class="line-box-register">
{html_options id="date" name="date" options=$date selected=$save.date class="date"}
{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month"}
{html_options id="year" name="year" options=$year_range|default:1994 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year"}
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Gender#}:</label>
<div class="line-box-register">
	<div style="float:left; line-height:30px;">
	{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('register_form',this,'')"}
    </div>
<div id="gender_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<label class="text">{#Country#}:</label>
<div class="line-box-register">
<select id="country" name="country" class="formfield_01"  autocomplete='off'>
{foreach from=$country item=foo}
<option value="{$foo.id}">{$foo.name}</option>
{/foreach}
</select>
<div id="country_info" class="left"></div>
</div>
</div>

<div class="register-box-tr">
<div style="display:block; width:530px; margin-bottom:3px; margin-top:5px; float:left; height:20px;">
<div style="float:left; margin-left:117px;">
<input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" style="float:left;"/><span style="display:block; width:380px; float:left; margin-left:5px;">{#AGB_accept_txt#}</span>
</div>
<div id="accept_info"></div>
</div>
</div>

<div style="margin-left:10px; margin-bottom:10px;">
<input type="hidden" name="submit_form" value="1"/>
<label class="text"></label>
<a href="javascript: void(0)" {if $smarty.cookies.connectforever_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1()) $('register_form').submit();" {/if} class="btn-login">{#Register#}</a>
<br class="clear" />
</div>

</form>