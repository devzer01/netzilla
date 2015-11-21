<!-- {$smarty.template} -->
<h1 class="title">{#Change_Password#}</h1>
<div class="container-change-pass">
<form id="editProfile" enctype="multipart/form-data" name="editProfile" method="post" action="?action=changepassword" style=" width:auto; display:block; float:left;">

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

<label>Old {#PASSWORD#}:</label>
<input id="old_password" name="old_password" type="password" value="" class="formfield_01" style="width:250px;"/>
<br class="clear" />
<label>New {#PASSWORD#}:</label>
<input id="password" name="password" type="password" value="" class="formfield_01" style="width:250px;"/>
<br class="clear" />
<label>{#Confirm#}-New {#PASSWORD#}:</label>
<input id="confirm_password" name="confirm_password" type="password" value="" class="formfield_01" style="width:250px;"/>
<br class="clear" />
<label>&nbsp;</label>
<input type="hidden" name="submit_button" value="1" />
<a href="index.php" class="btn-back">Back</a>
<a href="#" onclick="$('editProfile').submit()" class="btn-submit">Submit</a>

</form>

{if ($error ne "SAVED") and ($error ne '')}
<div style="float:left; background:url(images/cm-theme/bg-box-error.png) repeat-x; font-size:18px; color:#FFF; width:430px; height:90px; text-align:center; line-height:90px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; box-shadow: 5px 5px 5px #888888;">{$error}</div>
{/if}

</div>

