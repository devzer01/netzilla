<script language="javascript" type="text/javascript">
	var old_username="{$save.username}";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>

<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">	
	<label class="text">Nickname :</label>
	<span>
		<div style="float:left">
			<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="box" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" />
		</div>
        <br clear="all"/>
		<div id="username_info" class="error_info"></div>
	</span>
	<br clear="all"/>

	<label class="text">{#Email#}:</label>
	<span>
		<input id="email" name="email" type="text" value="{$save.email}" class="box" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
        <br clear="all"/>
		<div id="email_info" class="error_info"></div>
	</span>
	<br clear="all"/>

	<label class="text">{#PASSWORD#}:</label>
	<span>
		<input id="password" name="password" type="password" maxlength="30" class="box" onblur="checkNullPassword(this.value);" autocomplete="off"/>
        <br clear="all"/>
		<div id="password_info" class="error_info"></div>
	</span>
	<br clear="all"/>

	<label class="text">{#Confirm_Password#}:</label>
	<span>
		<input id="confirm_password" name="confirm_password" type="password" maxlength="30" class="box"  onblur="checkMatching($('password').value, this.value);" />
        <br clear="all"/>
		<div id="confirm_password_info" class="error_info"></div>
	</span>
	<br clear="all"/>

	<label class="text">{#Birthday#}:</label>
	<span>
		{html_options id="date" name="date" options=$date selected=$save.date class="date"}
		{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month"}
		{html_options id="year" name="year" options=$year_range|default:1972 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year"}
	</span>
	<br clear="all"/>
    
    <label class="text">{#Gender#}:</label>
	<span>
		{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('register_form',this,'')"}
        <br clear="all"/>
		<div id="gender_info" class="error_info"></div>
	</span>
	<br clear="all"/>
	
	<label class="text">{#Country#}:</label>
	<span>
		<select id="country" name="country" class="box">
			{foreach from=$country item=foo}
				<option value="{$foo.id}">{$foo.name}</option>
			{/foreach}
		</select>
		<br clear="all"/>
		<div id="country_info" class="error_info"></div>
	</span>
	<br clear="all"/>

	<div class="text">
		<label class="text"></label><div style="display:block; width:450px; margin-bottom:3px; float:left"><input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);"/>&nbsp;{#AGB_accept_txt#}</div>
		<label class="text"></label>
        <div id="accept_info" class="error_info" style="width: auto; margin-top: 5x; line-height:15px"></div>
		<br clear="all"/>
	</div>
	<br clear="all"/>

	<input type="hidden" name="submit_form" value="1"/>
	<label class="text"></label><a href="javascript: void(0)" {if $smarty.cookies.lovelysingle_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignup1() && isEmailValid()) $('register_form').submit();" {/if} class="butregisin">{#Register#}</a>
</form>