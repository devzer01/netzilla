<label>{#USERNAME#}:</label>
<span>
<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" onblur="checkUsername2('username')" class="box"/> 
<a id="usernametip" href="javascript:" title="Your user name can be up to 30 digits long.">?</a>
<a href="javascript: void(0)" onclick="checkUsername('username')" class="check">{#Check_username#}</a>
</span>
{*
<label>{#PASSWORD#}:</label>
<span>
<input id="password" name="password" type="text" maxlength="30" value="tester" class="box"/>
</span>
*}

{*
<label>{#PASSWORD#}:</label>
<span>
<input id="email" name="email" type="text" value="{$save.email}" class="box"/> 
<a href="javascript: void(0)" onclick="if(checkNull($('email').value) && checkFormEmail($('email').value))ajaxRequest('isEmail', 'email='+$('email').value, '', 'isUsername', 'reportError')" value="tananarak7@yahoo.com">? {#Check_mail#}</a>
</span>
*}
<label>Gender:</label>
<span>{html_radios id="gender" name="gender" options=$gender selected=1}</span>

<label>{#Birthday#}:</label>
<span>
{html_options id="date" name="date" options=$date selected=$save.date class="date"}
{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month"} 
{html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year"}
</span>

<label>{#Country#}:</label> 
<span>
<select id="country" name="country" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0, '')" class="box"></select>
</span>

<label>{#State#}:</label> 
<span>
<select id="state" name="state" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')"></select>
</span>

<label>{#City#}:</label> 
<span>
<select id="city" name="city" class="box"></select>
</span>

{literal}
<script language="javascript" type="text/javascript">
ajaxRequest('loadOptionCountry', '', '', 'loadOptionCountry', 'reportError');
getNumDate('date', $('month').options[$('month').selectedIndex].value, $('year').options[$('year').selectedIndex].value);
stepWizard('stepPage1', Array('stepPage2', 'stepPage3'));
</script>
{/literal}

{*
<label>Mobiltelefon *:</label> 
<span>
<div style="float:left;padding-top:2px">+&nbsp;</div>
<input type="text" id="phone_code" name="phone_code" value="{$save.phone_code}" class="code" maxlength="4" />
<input type="text" id="phone_number" name="phone_number" value="{$save.phone_number}" class="boxcode"/>
<a id="phone_numbertip" href="javascript:" title="Bitte gebe deine Handynummer in dem Format '01239999999' ein" >?</a>
<a href="javascript: void(0)" onclick="checkMobilePhone('phone_code', 'phone_number')" >Mobiltelefon überprüfen</a>
</span>
*}


<label>Photo (Server):</label> 
<span>
<input type="text" id="picturepath" name="picturepath" value="{$save.picturepath}" class="box" readonly>
<input type="button" value="Browse..." onclick="window.open('?action=image_dir', 'popup', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')">
</span>

<label>Photo (Local):</label> 
<span>
<input type="file" id="picturepath2" name="picturepath2" value="{$save.picturepath2}" class="box">
<br clear="all" />{#Images_policy#}
</span>

<label></label> 
<span>

</span>

<a href="javascript: void(0)" onclick="parent.location='{$url_back}'" class="butregisin">{#CANCEL#}</a>
<a href="javascript: void(0)" onclick="if(checkNullAddUser())stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))" class="butregisin">{#NEXT#}</a>

<script>
	var picturepath_obj = document.getElementById('picturepath');
</script>