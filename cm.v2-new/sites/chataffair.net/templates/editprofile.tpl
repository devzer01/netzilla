<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="?action=editprofile">
<div class="container-edit-profile-group">			
	<label class="edit-profile-01">{#Gender#}:<font class="request">*</font></label>
	<span>
		{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
		<br class="clear"/>
	</span>

	<label class="edit-profile-01">{#Birthday#}:<font class="request">*</font></label>
	<span>
		{html_options id="date" name="date" options=$date selected=$save.date style="width:60px" class="formfield_01"}
        
		{html_options options=$month id="month" name="month" selected=$save.month style="width:100px; margin-left:5px;" class="formfield_01"} 
        
		{html_options id="year" name="year" options=$year selected=$save.year style="width:80px; margin-left:5px;" class="formfield_01"}
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

<div class="container-edit-profile-group" style="float:right; width:400px;">
	<h3 style="margin-bottom:10px;">{#Your#} {#Description#}:</h3>
	<span>
		<textarea id="description" name="description" columns="20" rows="12" style="width:400px; height:100px;" onkeyup="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" onblur="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" class="formfield_01">{$save.description|stripslashes}</textarea>	
	</span>
	<br class="clear"/>
	<label></label>

	<br class="clear" />
	<label><strong>{#Select_Image#}:</strong></label>
	<span>
		<input name="upload_pic" type="hidden" value="yes" />
		<input name="picturepath" type="file" id="picturepath" style="width:350px;" />
		<br />{#Images_policy#}
	</span>
	<br class="clear" />
</div>

<div class="container-edit-profile-group">
	<h3>{#Yourre#} {#looking_for#}<font class="request">*</font></h3>
	<label class="edit-profile-01">{#Men#}:</label>
	<span>
		{html_radios id="lookmen" name="lookmen" options=$yesno selected=$save.lookmen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">{#Women#}:</label>
	<span>
		{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$save.lookwomen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear" />
</div>
 <br class="clear" />
<input type="hidden" name="save_profile" value="{$smarty.session.sess_id}" />
<a href="#" onclick="if(validateEditProfile()) jQuery('#editProfile').submit(); return false;" class="btn-submit" style="float:right;">{#SUBMIT#}</a>
<br class="clear" />
</form>

<script language="javascript" type="text/javascript">
country_select = "{$save.country}";
state_select = "{$save.state}";
city_select = "{$save.city}";
{literal}
function validateEditProfile()
{
	if(jQuery('#country').val()==0)
	{
		alert(country_alert);
		return false;
	}
	else if(jQuery('#state').val()==0)
	{
		alert(state_alert);
		return false;
	}
	else if(jQuery('#city').val()==0)
	{
		alert(city_alert);
		return false;
	}
	else
	{
		return true;
	}
}

jQuery(function()
{
	ajaxRequest("loadOptionCountry", "", "", "loadOptionCountry1", "reportError");
});
{/literal}
</script>