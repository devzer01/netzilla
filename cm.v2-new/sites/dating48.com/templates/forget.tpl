<div class="container-metropopup">
	<div class="metropopup-content">
	

	<font style="font-size:2em; padding-bottom:2%; display:block;">{#Banner_Password#}{$text}</font>

	<form id="forget_form" name="forget_form">
	<!--<div style="float:left; line-height:25px; margin-right:10px;">{#Your#} {#Email#}:</div> -->
	<input id="f_email" name="f_email" type="text" style="width:350px; margin-top:3px;" placeholder="{#Your#} {#Email#}" class="formfield_01" onkeypress="return isValidCharacterPattern(event,this.value,2)" />
	<a href="#" onclick="submitForgetPassword(); return false" class="btn-popup">{#SUBMIT#}</a>
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
		jQuery.ajax({ type: "POST", url: "?action=forget", data: jQuery("#forget_form").serialize(), success:(function(result){sendingForgetPassword = false; if(result=="SENT") {jQuery('#mask').hide(); jQuery('.window').hide();}else{alert(result);}}) });
	}
}
{/literal}
</script>