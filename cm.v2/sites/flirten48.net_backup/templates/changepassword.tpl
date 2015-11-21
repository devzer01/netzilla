<!-- {$smarty.template} -->
<div class="container-favoriten">
    <h1 class="favoriten-title">{#Change_Password#}</h1>




<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="?action=changepassword" style=" width:500px; display:block; float:left;">

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
<div class="upload-file-foto">

    <div class="line-txt-profile-edit">
        <strong class="edit-profile-02">Old {#PASSWORD#}:</strong>
        <input id="old_password" name="old_password" type="password" value="" class="formfield_01"/>
    </div>   
    <br class="clear" />
    <div class="line-txt-profile-edit">   
        <strong class="edit-profile-02">New {#PASSWORD#}:</strong>
        <input id="password" name="password" type="password" value="" class="formfield_01"/>
    </div>
    
    <div class="line-txt-profile-edit">
        <strong class="edit-profile-02" style="width:220px !important;">{#Confirm#}-New {#PASSWORD#}:</strong>
        <input id="confirm_password" name="confirm_password" type="password" value="" class="formfield_01"/>
    </div>
    
    <div style=" float:right;">
        <input type="hidden" name="submit_button" value="1" />
        <a href="index.php" class="btn-search">Back</a>
        <a href="#" onclick="$('#editProfile').submit()" class="btn-search">Submit</a>
    </div>

</div>
</form>
</div>

