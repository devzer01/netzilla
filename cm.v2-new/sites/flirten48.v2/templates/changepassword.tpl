<!-- {$smarty.template} -->
<div class="title">
	<div class="title-left"></div><h1>{#Change_Password#}</h1><div class="title-right"></div>
</div>

<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="?action=changepassword">

{if $error}
	{if $error eq "SAVED"}
		{assign var="error" value=$smarty.config.chpd1}
		{assign var="redirect" value="1"}
	{else}
		{assign var="redirect" value="0"}
	{/if}
<script>
{literal}
jQuery(document).ready(function($) {
	jQuery.smallBox({
		title: "{/literal}{$error}{literal}",
		content: "",
		timeout: 5000,
		color:"#ec008c",
		img: "images/cm-theme/Passwort.png"
	});
	{/literal}
	{if $redirect eq "1"}
	//jQuery('#link_fotoalbum').trigger('click');
	window.location.hash = "#fotoalbum";
	{/if}
	{literal}
});
{/literal}
</script>
{/if}
<div class="container-change-pass-form">

	<label>Old {#PASSWORD#}:</label>
	    <input id="old_password" name="old_password" type="password" value="" class="formfield_01" style="width:250px;"/>
	<br class="clear" /> 
	  
	<label>New {#PASSWORD#}:</label>
	    <input id="password" name="password" type="password" value="" class="formfield_01" style="width:250px;"/>
	<br class="clear" />
	
	<label>{#Confirm#}-New {#PASSWORD#}:</label>
	    <input id="confirm_password" name="confirm_password" type="password" value="" class="formfield_01" style="width:250px;"/>
	<br class="clear" />
	
	<input type="hidden" name="submit_button" value="1" />
    <label></label>
	<a href="index.php" class="btn-upload" style="margin-right:10px; width:84px; text-align:center;">Back</a>
	<a href="#" onclick="jQuery('#editProfile').submit()" class="btn-upload"  style="width:84px; text-align:center;">Submit</a>
	<br class="clear" />
</div>

</form>


