<div class="container-metropopup">
<div class="metropopup-content">
<font style="font-size:1.5em; padding-bottom:2%; display:block;">Edit profile</font>
<form id="editprofileForm" name="editProfile" method="post" action="?action={$smarty.get.action}&proc=saveProfile">
<div class="container-edit-profile-group" style="float: left">			
	<label class="edit-profile-01">Username:</label>
	<span>
		<input type="hidden" name="id" value="{$profile.id}"/>
		{$profile.username}<br class="clear"/>
	</span>
	<label class="edit-profile-01">Gender:</label>
	<span>
		{html_radios id="gender" name="gender" options=$gender selected=$profile.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
		<br class="clear"/>
	</span>

	<label class="edit-profile-01">Birthday:</label>
	<span>
		{html_options id="date" name="date" options=$date selected=$profile.date style="width:50px" class="formfield_01"}
        
		{html_options options=$month id="month" name="month" selected=$profile.month style="width:110px; margin-left:5px;" class="formfield_01"} 
        
		{html_options id="year" name="year" options=$year selected=$profile.year style="width:80px; margin-left:5px;" class="formfield_01"}
	</span>
	<br class="clear" />

	<label class="edit-profile-01">Country:</label>
	<span>
		<select id="q_country" name="country" onchange="loadOptionState('q_state', this.options[this.selectedIndex].value, '');loadOptionCity('q_city', $('q_state')[$('q_state').selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">State:</label>
	<span>
		<select id="q_state" name="state" onchange="loadOptionCity('q_city', this.options[this.selectedIndex].value, '')" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">City:</label>
	<span>
		<select id="q_city" name="city" class="formfield_01"><option></option></select>
	</span>
	<br class="clear"/>
	<h3>Looking for</h3>
	<label class="edit-profile-01">Men:</label>
	<span>
		{html_radios id="lookmen" name="lookmen" options=$yesno selected=$profile.lookmen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear"/>

	<label class="edit-profile-01">Women:</label>
	<span>
		{html_radios id="lookwomen" name="lookwomen" options=$yesno selected=$profile.lookwomen labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
	</span>
	<br class="clear" />
	<h3>Description:</h3>
	<span>
		<textarea id="description" name="description" columns="20" rows="12" style="width:450px" onkeyup="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" onblur="checkNullTextArea(this, 'Bitte teilen Sie uns abit mehr über sich.')" class="description-edit">{$profile.description|stripslashes}</textarea>	
	</span>
	<br class="clear" />
</div>
<div style="float: left"><img src="thumbnails.php?file={$profile.picturepath}&w=200&h=200"/></div><br class="clear" />
<a href="#" onclick="jQuery('#editprofileForm').submit(); return false;" class="btn-yellow-left">Save</a>
<br class="clear" />
</form>
</div></div>

<script language="javascript" type="text/javascript">
q_country_select = "{$profile.country}";
q_state_select = "{$profile.state}";
q_city_select = "{$profile.city}";
{literal}
jQuery(function()
{
	ajaxRequest("loadOptionCountry", "", "", "q_loadOptionCountry", "reportError");
});
{/literal}
</script>