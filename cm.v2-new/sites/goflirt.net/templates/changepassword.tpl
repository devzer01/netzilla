<h1 class="title">{#Change_Password#}</h1>
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

<div class="container-change-password">
    <span>
        <label>Alt {#PASSWORD#}:</label>
        <input id="old_password" name="old_password" type="password" value="" class="formfield_01" style="width:250px;"/>
    </span> 
    <br class="clear" />
    <span>   
        <label>Neu {#PASSWORD#}:</label>
        <input id="password" name="password" type="password" value="" class="formfield_01" style="width:250px;"/>
    </span>
    
    <span>
        <label>{#Confirm#}-Neu {#PASSWORD#}:</label>
        <input id="confirm_password" name="confirm_password" type="password" value="" class="formfield_01" style="width:250px;"/>
    </span>
    
    <span>
        <input type="hidden" name="submit_button" value="1" />
        <a href="index.php" class="btn-register" style="width:125px; margin-left:160px; margin-right:8px;">Back</a>
        <a href="#" onclick="jQuery('#editProfile').submit()" class="btn-register" style="width:125px;">Submit</a>
    </span>
	<br class="clear" />
</div>
</form>