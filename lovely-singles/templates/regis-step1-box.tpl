<script language="javascript" type="text/javascript">
	var old_username="{$save.username}";
	var username_ok = false;
	var email_ok = false;
	var password_ok = false;
</script>
<div style="margin:0 10px 10px 10px; float:left;">
<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">	
	<label class="register-box w1">Nickname :</label>

	<span class="span-box-regis">
		<div style="float:left">
			<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" onkeyup="checkUsernameSilentForBox(this.value)" autocomplete="off" class="input-regis-box"/>
		</div>
        <div id="username_info"></div>
	</span>
    
<br class="clear" />
	<label class="register-box w1">{#Email#}:</label>
	<span class="span-box-regis">
		<input id="email" name="email" type="text" value="{$save.email}"  onblur="checkEmailSilentForBox(this.value);" autocomplete="off" class="input-regis-box"/>
 
		<div id="email_info"></div>
	</span>
<br class="clear" />

	<label class="register-box w1">{#PASSWORD#}:</label>
	<span class="span-box-regis">
		<input id="password" name="password" type="password" maxlength="30" onblur="checkNullPasswordForBox(this.value);" autocomplete="off" class="input-regis-box"/>
		<div id="password_info"></div>
	</span>
<br class="clear" />

	<label class="register-box w1">{#Confirm_Password#}:</label>
	<span class="span-box-regis">
		<input id="confirm_password" name="confirm_password" type="password" maxlength="30"  onblur="checkMatchingForBox($('password').value, this.value);" class="input-regis-box"/>

		<div id="confirm_password_info"></div>
	</span>
<br class="clear" />
<div style="margin:5px 0; float:left; width:250px; -webkit-border-radius: 5px;-moz-border-radius: 5px; border-radius: 5px;">
	<label class="register-box w2">{#Birthday#}:</label>

	<span style="margin-left:10px;">
		{html_options id="date" name="date" options=$date selected=$save.date}
        <select name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" style="width:78px;">
        {foreach from=$month key=k item=m}
        <option value="{$k}" {if $k eq $save.month}selected="selected"{/if}>{$m}</option>
        {/foreach}
        </select>
		{html_options id="year" name="year" options=$year_range|default:1972 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year}
	</span>
</div>
<br class="clear" />
<div style="width:250px; margin-bottom:5px;">   
    <label class="register-box">{#Gender#}:</label>
	<span style="margin-left:10px; width:160px; display:block; float:left; height:20px;">
		{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('register_form',this,'')"}	
        <div id="gender_info" style=" margin-top:10px; margin-left:25px;"></div>
	</span>
    
</div>
<br class="clear" />
	<label class="register-box w1" style=" line-height:20px;">{#Country#}:</label>
	<span>
		<select id="country" name="country" class="box">
			{foreach from=$country item=foo}
				<option value="{$foo.id}">{$foo.name}</option>
			{/foreach}
		</select>
<!--
		<div id="country_info" class="error_info-box"></div> -->
	</span>

<br class="clear" />
	<div style="height:50px; width:255px; float:left; margin-top:5px;">

        <div style="display:block; width:255px; margin-bottom:3px; float:left;">
            <input type="checkbox" name="accept" id="accept" value="1" onclick="checkAceptForBox(this);" style="float:left;"/>
            <font style="font-size:11px; float:left; width:240px;">&nbsp;{#AGB_accept_txt#}</font>
        </div>
        <div id="accept_info" style="margin-left:91px; float:left;"></div>
	</div>
<br class="clear" />
	<input type="hidden" name="submit_form" value="1"/>
<a href="javascript: void(0)" {if $smarty.cookies.lovelysingle_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="if(checkNullSignupBox() && isEmailValid()) $('register_form').submit();" {/if} class="butregisin-box">{#Register#}</a>
</form>
</div>