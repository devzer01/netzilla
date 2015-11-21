<!-- {$smarty.template} -->
<div id="container-content">
<h1 class="title">{#Register#}</h1>
	<div align="center" style="width:auto; font-size:14px; margin-top:30px; margin-bottom:30px;">
		{$text1}<br/><br/>
		{$text2}<br/><br/>
		<strong id="registered_email">{$mailbox}</strong><br/><br/>
		{$text3}<br/><br/>
		<div id='change_email'>Falsche Emailadresse? <a href="#" onclick="changeEmail(); return false;" style="color:#F00; font-weight:bold;">Bitte HIER KLICKEN</a></div>
		<div id='email_result' style='display: none;'>Ihre <font>Email</font> Adresse ist <font> nicht korrekt!</font> <a href="#" onclick="changeEmail(); return false;"> Bitte HIER KLICKEN</a></div>
		
	</div>
</div>

<div id="boxes">
<div id="dialogChangeEmail" class="window">
	<div style="background-color: white; width: 100%"></div>
</div>
</div>

<script>
var email = '{$mailbox}';
var username = '{$username}';
{literal}
var sendingChangeEmail = false;

jQuery(function (e) {
	jQuery.ajax({
		url: "ajaxRequest.php?action=sendActivateEmail&email=" + email + '&username=' + username,
		type: 'get',
		dataType: 'json',
		success: function(json) {
			console.log('email sent');	
		}
	});
});

function changeEmail()
{
	var url = "?action=change_email";
	jQuery("#dialogChangeEmail").load(url);

	//Get the screen height and width
	var maskHeight = jQuery(document).height();
	var maskWidth = jQuery(window).width();

	//Set heigth and width to mask to fill up the whole screen
	jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	//$('#mask').fadeIn(1000);	
	jQuery('#mask').fadeTo("fast",0.8);	

	//Get the window height and width
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
		  

	//Set the popup window to center
	jQuery('#dialogChangeEmail').css('top',  winH/2-jQuery('#dialogChangeEmail').height()/2);
	jQuery('#dialogChangeEmail').css('left', winW/2-jQuery('#dialogChangeEmail').width()/2);

	//transition effect
	jQuery('#dialogChangeEmail').fadeIn(1500);

}

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