<!-- {$smarty.template} -->
<div id="container-content">
<h1>{#Change_Password#}</h1>
<div style="float:left; width:845px; height:auto; padding:20px; margin:10px;">
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
<div class="line-txt-profile-edit">
    <strong class="txt-profile-edit">Old {#PASSWORD#}:</strong>
    <input id="old_password" name="old_password" type="password" value="" class="formfield_01"/>
</div>    
 <div class="line-txt-profile-edit">   
    <strong class="txt-profile-edit">New {#PASSWORD#}:</strong>
    <input id="password" name="password" type="password" value="" class="formfield_01"/>
</div>
<div class="line-txt-profile-edit">
    <strong class="txt-profile-edit">{#Confirm#}-New {#PASSWORD#}:</strong>
    <input id="confirm_password" name="confirm_password" type="password" value="" class="formfield_01"/>
</div>
<div style="padding-left:175px; margin-top:10px; float:left;">
<input type="hidden" name="submit_button" value="1" />
<a href="index.php" class="btn-search" style="width:100px; float:left;">Back</a>
<a href="#" onclick="$('editProfile').submit()" class="btn-search" style="width:100px; float:left; margin-left:5px;">Submit</a>
</div>
</form>

{if ($error ne "SAVED") and ($error ne '')}
<div style="float:left; background:url(images/cm-theme/bg-box-error.png) repeat-x; font-size:18px; color:#FFF; width:430px; height:90px; text-align:center; line-height:90px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; box-shadow: 5px 5px 5px #888888;">{$error}</div>
{/if}

</div>
</div>
