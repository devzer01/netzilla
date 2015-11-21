<div class="container-metropopup">
	<div class="metropopup-content">
	<font style="font-size:2em; padding-bottom:2%; display:block;">{#Banner_Password#}{$text}</font>
	<div style="float:left; line-height:25px; margin-right:10px; color: red;" id="forget_error"></div><br class="clear"/>
	<form id="forget_form" name="forget_form">
	<input id="f_email" name="f_email" type="text" style="width:350px; margin-top:3px;" placeholder="{#Your#} {#Email#}" class="formfield_01" onkeypress="return isValidCharacterPattern(event,this.value,2)" />
	<a href="#" onclick="submitForgetPassword(); return false" class="btn-register">{#SUBMIT#}</a>
	</form>
    <br class="clear" />
	</div>
</div>

<script>
{literal}
function submitForgetPassword()
{
	if(!sendingForgetPassword)
	{
		sendingForgetPassword = true;
		jQuery.ajax({ type: "POST", url: "?action=forget", data: jQuery("#forget_form").serialize(), success:(function(result)
			{
				sendingForgetPassword = false;
				if(result=="SENT")
				{
					jQuery('#forget_error').html('Wir haben dein aktuelles Passwort an die von dir angegebene Emailadresse weiter geleitet');
					jQuery('#forget_form').remove();
					setTimeout(function(){
						jQuery('#mask').hide();
						jQuery('.window').hide();
						}, 3000);
				}
				else
				{
					jQuery('#forget_error').html(result);
				}
			}) });
	}
}
{/literal}
</script>