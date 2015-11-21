<div class="container-metropopup">
<div class="metropopup-content">
<font style="font-size:1.5em; padding-bottom:2%; display:block;">Edit profile</font>
<form id="editprofileForm" name="editProfile" method="post" action="?action=editprofile">
<div class="container-edit-profile-group">			
	<label class="edit-profile-01">Username:<font class="request">*</font></label>
	<span>
		<input type="text" name="username" class="formfield_01" value="{$profile.username}"/>
		<br class="clear"/>
	</span>
	<label class="edit-profile-01">{#Gender#}:<font class="request">*</font></label>
	<span>
		{html_radios id="gender" name="gender" options=$gender selected=$profile.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
		<br class="clear"/>
	</span>

	<label class="edit-profile-01">{#Birthday#}:<font class="request">*</font></label>
	<span>
		{html_options id="date" name="date" options=$date selected=$profile.date style="width:50px" class="formfield_01"}
        
		{html_options options=$month id="month" name="month" selected=$profile.month style="width:110px; margin-left:5px;" class="formfield_01"} 
        
		{html_options id="year" name="year" options=$year selected=$profile.year style="width:80px; margin-left:5px;" class="formfield_01"}
	</span>
	<br class="clear" />

	<label class="edit-profile-01">{#Country#}:<font class="request">*</font></label>
	<span>
		<select id="country" name="country" onchange="loadOptionState('state', this.options[this.selectedIndex].value, '');loadOptionCity('city', $('state')[$('state').selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">{#State#}:<font class="request">*</font></label>
	<span>
		<select id="state" name="state" onchange="loadOptionCity('city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">{#City#}:<font class="request">*</font></label>
	<span>
		<select id="city" name="city" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>
</div>

<div class="container-edit-profile-group">
	<h3>{#Yourre#} {#looking_for#}<font class="request">*</font></h3>
	<label class="edit-profile-01">{#Men#}:</label>
	<span>
		{html_radios id="lookmen" name="lookmen" options=$yesno selected=$profile.lookmen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">{#Women#}:</label>
	<span>
		{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$profile.lookwomen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<h3>{#Your#} {#Description#}:</h3>
	<span>
		<textarea id="description" name="description" columns="20" rows="12" style="width:450px" onkeyup="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" onblur="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" class="description-edit">{$profile.description|stripslashes}</textarea>	
	</span>
	<br class="clear" />
</div>
 
<a href="#" onclick="editProfileAndCopy('{$server}', '{$username}'); return false;" class="btn-yellow-left">{#SUBMIT#}</a>
<br class="clear" />
</form>
</div></div>

<script language="javascript" type="text/javascript">
country_select = "{$profile.country}";
state_select = "{$profile.state}";
city_select = "{$profile.city}";
{literal}
function editProfileAndCopy(server, username)
{
	jQuery.ajax({type: "POST", {/literal}url: "?action={$smarty.get.action}&server="+server+"&username="+username{literal}, data: jQuery('#editprofileForm').serialize(), success: copyProfilesHandle, dataType: "json"});
}

jQuery(function()
{
	ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry1", "reportError");
});
{/literal}
</script>