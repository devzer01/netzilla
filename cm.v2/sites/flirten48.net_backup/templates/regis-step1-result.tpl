<!-- {$smarty.template} -->
<div class="container-favoriten">
<h1 class="favoriten-title">{#Register#}</h1>
	<div align="center" style="width:auto; font-size:14px; margin-top:30px; margin-bottom:30px;">
		{$text1}<br/><br/>
		{$text2}<br/><br/>
		<strong id="registered_email">{$mailbox}</strong><br/><br/>
		{$text3}<br/><br/>
 
		Falsche Emailadresse? <a href="#" onclick="loadPagePopup('?action=change_email', '100%'); return false;" style="color:#F00; font-weight:bold;">Bitte HIER KLICKEN</a>
	</div>
</div>

<div id="boxes">
<div id="dialogChangeEmail" class="window">
	<div style="background-color: white; width: 100%"></div>
</div>
</div>

<script>
{literal}
var sendingChangeEmail = false;

function submitChangeEmail()
{
	if(!sendingChangeEmail)
	{
		sendingChangeEmail = true;
		jQuery.ajax({ type: "POST", url: "?action=change_email", data: jQuery("#change_email_form").serialize(), success:(function(result){sendingChangeEmail = false; if(result=="CHANGED") {jQuery('#mask').hide(); jQuery('.window').hide(); jQuery('#registered_email').text(jQuery('#email').val());}else{alert(result);}}) });
	}
}
{/literal}
</script>