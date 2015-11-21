<!-- {$smarty.template} -->
{literal}
<script language="javascript" type="text/javascript">
country_select = "{/literal}{$save.country}{literal}";
state_select = "{/literal}{$save.state}{literal}";
city_select = "{/literal}{$save.city}{literal}";
fileExtension = "{/literal}{$config_image}{literal}";
fileDescription = "Images ({/literal}{$config_image}{literal})";
ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry1", "reportError");
/*getNumDate("date", document.getElementById("month").options[document.getElementById("month").selectedIndex].value, document.getElementById("year").options[document.getElementById("year").selectedIndex].value);*/
InitFlashObj();
</script>
{/literal}

<label>{#USERNAME#}:</label>
<span>
<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" class="box" onkeypress="return isValidCharacterPattern(event,this.value,1)" /> 
<a id="usernametip" href="javascript:" title="{#usr_ex_txt#}" class="check" tabindex="1">?</a>
<a href="javascript: void(0)" onclick="checkUsername('username')" class="check" tabindex="2">{#Check_username#}</a>
</span>
<label>{#FORNAME#}:</label>
<span>
<input name="forname" type="text" id="forname" value="{$save.forname}" maxlength="30" class="box" />
</span>
<br clear="all"/>
<label>{#SURNAME#}:</label>
<span>
<input name="surname" type="text" id="surname" value="{$save.surname}" maxlength="30" class="box" />
</span>
<br clear="all"/>
<label>{#PASSWORD#}:</label>
<span>
<input id="password" name="password" type="password" maxlength="30" class="box" />
</span>
<br clear="all"/>
<label>{#Confirm#} {#PASSWORD#}:</label>
<span>
<input id="confirm_password" name="confirm_password" type="password" maxlength="30" class="box" />
</span>
<br clear="all"/>
<label>{#Email#}:</label>
<span>
<input id="email" name="email" type="text" value="{$save.email}" class="box" onkeypress="return isValidCharacterPattern(event,this.value,2)" /> 
<a href="javascript: void(0)" onclick="if(checkNull($('email').value) && checkFormEmail($('email').value))ajaxRequest('isEmail', 'email='+$('email').value, '', 'isUsername', 'reportError')" value="tananarak7@yahoo.com" class="check" tabindex="3">
{#Check_mail#}</a>
</span> 
<br clear="all"/>      
<label>{#Gender#}:</label>
<span>{html_radios id="gender" name="gender" options=$gender selected=$save.gender}</span>
<br clear="all"/>
<label>{#Birthday#}:</label>
<span>
{html_options id="date" name="date" options=$date selected=$save.date class="date"}
{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month"} 
{html_options id="year" name="year" options=$year|default:1972 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year"}
</span>
<br clear="all"/>
<label>{#Country#}:</label>
<span>
<select id="country" name="country" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')" class="box"></select>
</span>
<br clear="all"/>
<label>{#State#}:</label>
<span>
<select id="state" name="state" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')" class="box"></select>
</span>
<br clear="all"/>
<label>{#City#}:</label>
<span>
{literal}
<script>
ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry", "reportError");
/*getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value);
stepWizard('stepPage1', Array('stepPage2', 'stepPage3'));*/
</script>
{/literal}
<select id="city" name="city" class="box"></select>
</span>
<br clear="all"/>
<label>{#mobile#}:</label>
<span>
<div style="float:left;padding-top:2px">+&nbsp;</div><input type="text" id="phone_code" name="phone_code" value="{$save.phone_code}" class="code" maxlength="4" onkeypress="return isValidCharacterPattern(event,this.value,3)" /> 
<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="boxcode" /> 
&nbsp;<a id="phone_numbertip" href="javascript:" title="{#phone_ex_txt#}" tabindex="4">?</a>
<a href="javascript: void(0)" onclick="checkMobilePhone('phone_code', 'phone_number')" tabindex="5">{#Check#}</a>
</span>

<div class="text">* {#mobile_app_intro_txt#}<br /><br />
<input type="checkbox" name="accept" id="accept" value="1">&nbsp;<a href="?action=agbs" class="link">{#agb#}</a>, {#AGB_accept_txt#}
</div>

<a href="javascript: void(0)" onclick="parent.location='{$url_back}'" class="butregisin">{#CANCEL#}</a>
<a href="javascript: void(0)" onclick="if(checkNullSignup1())stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))" class="butregisin">{#NEXT#}</a>