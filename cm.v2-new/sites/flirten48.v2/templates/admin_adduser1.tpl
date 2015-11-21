<!-- {$smarty.template} -->
<label class="text-admin">Username:</label>
<span>
<input name="username" type="text" id="username" value="{$save.username}" maxlength="30" onkeyup="checkUsernameSilent(this.value)" autocomplete="off" class="formfield_admin" style="width:240px;"/> 
<span id="username_info" class="left"></span>
</span>
<br class="clear" />
<label class="text-admin">Gender:</label>
<span style="float:left;">{html_radios id="gender" name="gender" options=$gender selected=1 }</span>
<br class="clear" />
<label class="text-admin">Birthday:</label>
<span>
{html_options id="date" name="date" options=$date selected=$save.date style="width:50px" class="formfield_admin"}
{html_options options=$month id="month" name="month" onchange="getNumDate('#date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month style="width:110px; margin-left:5px;" class="formfield_admin"} 
{html_options id="year" name="year" options=$year onchange="getNumDate('#date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year style="width:80px; margin-left:5px;" class="formfield_admin"}
</span>
<br class="clear" />
<label class="text-admin">Country:</label> 
<span>
<select id="country" name="country" onchange="loadOptionState('#state', this.options[this.selectedIndex].value, '');loadOptionCity('#city', jQuery('#state').val(), '')" class="formfield_admin" style="width:250px;"></select>
</span>
<br class="clear" />
<label class="text-admin">State:</label> 
<span>
<select id="state" name="state" onchange="loadOptionCity('#city', this.options[this.selectedIndex].value, '')" class="formfield_admin" style="width:250px;"></select>
</span>
<br class="clear" />
<label class="text-admin">City:</label> 
<span>
<select id="city" name="city" class="formfield_admin" style="width:250px;"></select>
</span>
<br class="clear" />
{literal}
<script language="javascript" type="text/javascript">
ajaxRequest('loadOptionCountry', '', '', loadOptionCountry, reportError);
stepWizard('stepPage1', Array('stepPage2', 'stepPage3'));
</script>
{/literal}

<label class="text-admin">Photo (Server):</label> 
<span style="float:left;">
<input type="text" id="picturepath" name="picturepath" value="{$save.picturepath}" class="formfield_admin" readonly>
<input type="button" value="Browse..." onclick="window.open('?action=image_dir', 'popup', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')">
</span>
<br class="clear" />
<label class="text-admin">Photo (Local):</label> 
<span style=" float:left;">
<input type="file" id="picturepath2" name="picturepath2" value="{$save.picturepath2}" class="box"></span>
<br class="clear" />
<label class="text-admin"></label> 
<span></span>

<a href="javascript: void(0)" onclick="if(username_ok)stepWizard('stepPage2', Array('stepPage1', 'stepPage3'))" class="butregisin">NEXT</a>
<br class="clear" />
<script>
	var old_username="";
	var username_ok = false;
	var picturepath_obj = document.getElementById('picturepath');
</script>