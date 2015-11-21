<!-- {$smarty.template} -->
{include file="complete-profile.tpl"}

<h2>{#Edit_Profile#}</h2>
<div class="result-box">
<div class="register-page-box-inside">
<div class="container-edit-profile">
{literal}
<script type="text/javascript" language="javascript">
function removePic()
{
	{/literal}
	{if $smarty.session.sess_admin eq "1" and $smarty.get.from eq "admin"}
		parent.location = "?action=editprofile&type=removepic&user={$smarty.get.user}&proc={$smarty.get.proc}";
	{else}
		parent.location = "?action=editprofile&type=removepic";
	{/if}
	{literal}
}
function updateEdit_datetime()
{
	ajaxRequest("updateEdit_datetime", "username={/literal}{$save.username}{literal}", "", "updateLoop", "reportError");
}

function updateLoop(originalRequest)
{
	if(originalRequest.status == 200)
		setTimeout("updateEdit_datetime()", 30000);
}

updateEdit_datetime();
</script>
{/literal}
<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="">
<div class="container-img-profile">
{if $save.picturepath neq ""}
<div id="edit-profile-my_pic">
<img border=1 src="thumbnails.php?file={$save.picturepath}&w=90&h=103" height="103px" width="90px"><br class="clear" />
<a href="javascript:if(confirm('Are you sure to remove foto?')) removePic(); " class="linkwhite">{#remove_pic#}</a>
</div>

{else}
<div id="edit-profile-my_pic"><img src="thumbnails.php?file=default.jpg"></div>
{/if}

<div class="containeredit-profile-username">
<h1 class="name-edit-profile">{#USERNAME#}: {$save.username}</h1>
<br class="clear" />
<strong>Email:</strong> {$save.email}
<br class="clear" />
<strong style="float:left; margin-right:3px">Zodiac:</strong> <span id="zodiac_text" style="width:100px;">{$zodiac[$save.zodiac]}</span>
</div>

<br class="clear" />

</div>

<div class="container-edit-profile-group">			
	<br class="clear" />
	<label>{#Gender#}:</label>
	<span>
		{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="gender_info" class="error_info"></div>
	</span>

	<label>{#Birthday#}:</label>
	<span>
		{html_options id="date" name="date" options=$date selected=$save.date onchange="getZodiac(document.getElementById('date').value, document.getElementById('month').value)"}
		{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value), getZodiac(document.getElementById('date').value, document.getElementById('month').value)" selected=$save.month} 
		{html_options id="year" name="year" options=$year onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year}
	</span>

	<br class="clear" />
	<label>{#Country#}:</label>
	<span>
		{html_options id="country" name="country" options=$country selected=$save.country style="width:155px" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', 0,''); checkNullSelectOption(this)"}
		<br clear="all"/>
		<div id="country_info" class="error_info"></div>
	</span>

	<label>{#State#}:</label>
	<span>
		{html_options id="state" name="state" options=$state selected=$save.state style="width:155px" onchange="loadOptionCity('city', this.options[this.selectedIndex].value,''); checkNullSelectOption(this)"}
		<br clear="all"/>
		<div id="state_info" class="error_info"></div>
	</span>

	<br class="clear" />
	<label>{#City#}:</label>
	<span>
		{html_options id="city" name="city" options=$city selected=$save.city style="width:155px" onchange="checkNullSelectOption(this)"}
		<br clear="all"/>
		<div id="city_info" class="error_info"></div>
	</span>
	<br class="clear" />
</div>

{literal}
<script language="javascript" type="text/javascript">
country_select = "{/literal}{$save.country}{literal}";
state_select = "{/literal}{$save.state}{literal}";
city_select = "{/literal}{$save.city}{literal}";
fileExtension = "{/literal}{$config_image}{literal}";
fileDescription = "Images ({/literal}{$config_image}{literal})";
ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry1", "reportError");
getNumDate("date", $("month").options[$("month").selectedIndex].value, $("year").options[$("year").selectedIndex].value);
InitFlashObj();
</script>
{/literal}

<div class="container-edit-profile-group">
	<label>{#Height#} ({#Cm#}):</label>
	<span>
		<input id="height" name="height" type="text" value="{$save.height}" style="width:150px" class="input" maxlength="3" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullInputText(this, 'Please enter your height.')" onblur="checkNullInputText(this, 'Please enter your height.')"/>
		<br clear="all"/>
		<div id="height_info" class="error_info"></div>
	</span>

	<label>{#Weight#} ({#Kg#}):</label>
	<span>
		<input id="weight" name="weight" type="text" value="{$save.weight}" style="width:150px" class="input" maxlength="3" onkeypress="return isValidCharacterPattern(event,this.value,3)" onkeyup="checkNullInputText(this, 'Please enter your weight.')" onblur="checkNullInputText(this, 'Please enter your weight.')"/>
		<br clear="all"/>
		<div id="weight_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Appearance#}:</label>
	<span>{html_options id="appearance" name="appearance" options=$appearance selected=$save.appearance style="width:155px"}</span>

	<label>{#Eyes_Color#}:</label>
	<span>{html_options id="eyescolor" name="eyescolor" options=$eyescolor selected=$save.eyescolor style="width:155px"}</span>
	<br class="clear" />

	<label>{#Hair_Color#}:</label>
	<span>{html_options id="haircolor" name="haircolor" options=$haircolor selected=$save.haircolor style="width:155px"}</span>

	<label>{#Hair_Length#}:</label>
	<span>{html_options id="hairlength" name="hairlength" options=$hairlength selected=$save.hairlength style="width:155px"}</span>
	<br class="clear" />

	<label>{#Beard#}:</label>
	<span>{html_options id="beard" name="beard" options=$beard selected=$save.beard style="width:155px"}</span>

	<label>{#Zodiac#}:</label>
	<span>
		<span id="zodiac_val">{$zodiac[$save.zodiac]}</span>
		{*html_options id="zodiac" name="zodiac" options=$zodiac selected=$save.zodiac style="width:155px"*}
	</span>
	<br class="clear" />

	<label>{#Civil_status#}:</label>
	<span>{html_options id="civilstatus" name="civilstatus" options=$status selected=$save.civilstatus style="width:155px"}</span>

	<label>{#Sexuality#}:</label>
	<span>{html_options id="sexuality" name="sexuality" options=$sexuality selected=$save.sexuality style="width:155px"}</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<label>{#Tattos#}:</label>
	<span>
		{html_radios id="tattos" name="tattos" options=$yesno selected=$save.tattos labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="tattos_info" class="error_info"></div>
	</span>

	<label>{#Smoking#}:</label>
	<span>
		{html_radios id="smoking" name="smoking" options=$yesno selected=$save.smoking labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="smoking_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Glasses#}:</label>
	<span>
		{html_radios id="glasses" name="glasses" options=$yesno selected=$save.glasses labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="glasses_info" class="error_info"></div>
	</span>

	{*<label>{#Handicapped#}:</label>
	<span>{html_radios id="handicapped" name="handicapped" options=$yesno selected=$save.handicapped labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}</span>
	<br class="clear" />*}

	<label>{#Piercings#}:</label>
	<span>
		{html_radios id="piercings" name="piercings" options=$yesno selected=$save.piercings labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="piercings_info" class="error_info"></div>
	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<h3>{#Yourre#} {#looking_for#}</h3>
	<br class="clear" />
	<label>{#Men#}:</label>
	<span>
		{html_radios id="lookmen" name="lookmen" options=$yesno selected=$save.lookmen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="lookmen_info" class="error_info"></div>
	</span>

	<label>{#Women#}:</label>
	<span>
		{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$save.lookwomen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="lookwomen_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Pairs#}:</label>
	<span>
		{html_radios id="lookpairs" name="lookpairs" options=$yesno selected=$save.lookpairs labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="lookpairs_info" class="error_info"></div>
	</span>

	<label>{#Min#} {#Age#}:</label>
	<span>{html_options id="minage" name="minage" options=$age onchange="ageRange('minage', 'maxage')" selected=$save.minage labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}</span>
	<br class="clear" />

	<label>{#Max#} {#Age#}:</label>
	<span>{html_options id="maxage" name="maxage" options=$age selected=$save.maxage labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}</span>

	<label>{#Relationship#}:</label>
	<span>
		{html_radios id="relationship" name="relationship" options=$yesno selected=$save.relationship labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="relationship_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#One_Night_Stand#}:</label>
	<span>
		{html_radios id="onenightstand" name="onenightstand" options=$yesno selected=$save.onenightstand labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="onenightstand_info" class="error_info"></div>
	</span>

	<label>{#Affair#}:</label>
	<span>
		{html_radios id=""affair name="affair" options=$yesno selected=$save.affair labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="affair_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Friendship_and_more#}:</label>
	<span>
		{html_radios id="friendship" name="friendship" options=$yesno selected=$save.friendship labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="friendship_info" class="error_info"></div>
	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<h3>{#Prefenrence#}</h3>
	<br class="clear" />
	<label>{#Cybersex#}:</label>
	<span>
		{html_radios id="cybersex" name="cybersex" options=$yesno selected=$save.cybersex labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="cybersex_info" class="error_info"></div>
	</span>

	<label>{#Picture_Swapping#}:</label>
	<span>
		{html_radios id="picture_swapping" name="picture_swapping" options=$yesno selected=$save.picture_swapping labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="picture_swapping_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Live_dating#}:</label>
	<span>
		{html_radios id="live_dating" name="live_dating" options=$yesno selected=$save.live_dating labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="live_dating_info" class="error_info"></div>
	</span>

	<label>{#Role_playing#}:</label>
	<span>
		{html_radios id="role_playing" name="role_playing" options=$yesno selected=$save.role_playing labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="role_playing_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#sm#}:</label>
	<span>
		{html_radios id="s_m" name="s_m" options=$yesno selected=$save.s_m labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="s_m_info" class="error_info"></div>
	</span>

	<label>{#Partner_exchange#}:</label>
	<span>
		{html_radios id="partner_exchange" name="partner_exchange" options=$yesno selected=$save.partner_exchange labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="partner_exchange_info" class="error_info"></div>
	</span>
	<br class="clear" />

	<label>{#Voyeurism#}:</label>
	<span>
		{html_radios id="voyeurism" name="voyeurism" options=$yesno selected=$save.voyeurism labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" onClick="checkNullRadioOption('editProfile',this,'')"}
		<br clear="all"/>
		<div id="voyeurism_info" class="error_info"></div>
	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<label>{#Your#} {#Description#}:</label>
	<span>
		<textarea id="description" name="description" columns="20" rows="12" style="width:450px" class="textarea" onkeyup="checkNullTextArea(this, 'Please tell us abit more about yourself.')" onblur="checkNullTextArea(this, 'Please tell us abit more about yourself.')">{$save.description}</textarea>	
	</span>
	<br clear="all"/>
	<label></label>
	<div id="description_info" class="error_info" style="font-weight: bold;"></div>

{if $smarty.session.sess_admin eq "1" and $smarty.get.from eq "admin"}
	<br class="clear" />
	<label>{#Select_Image#}:</label>
	<span>
		<input type="text" id="picturepath" name="picturepath" value="{$save.picturepath}" readonly="readonly" />
		<input type="button" value="Browse" onclick="window.open('?action=image_dir', 'popup', 'location=0,status=1,scrollbars=1,width=500,height=500,resizable=1')" />					
	</span>
{/if}

<br class="clear" />
<label>{#Select_Image#}:</label>
<span style="width:auto;">
	<input name="upload_pic" type="hidden" value="yes" />
	<input name="picturepath" type="file" id="picturepath" style="width:350px;" />
</span>
<br class="clear" />
</div>
<!-- <input name="submit_button" type="image" src="images/senden_bt.gif" onclick="return callNullEditprofile()" > 	-->		
 
<input type="hidden" name="submit_button" value="1" />
<a href="javascript: void(0)" onclick="if(callNullEditprofile()) $('editProfile').submit()" class="butregisin">{#SUBMIT#}</a>
<a href="{if ($smarty.session.sess_admin eq "1") and ($smarty.get.from eq "admin")}{$smarty.session.SmartyPaginate.default.url}{else}index.php{/if}" class="butregisin">{#BACK#}</a>
<br class="clear" />	
</form>

</div>	
</div></div>
<script>
	var picturepath_obj = document.getElementById("picturepath");
</script>