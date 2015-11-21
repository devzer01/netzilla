<!-- {$smarty.template} -->

<h1 class="title" style="margin-top:20px;">{#Register#}</h1>

<div class="container-box-content-03">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m" style="width:900px !important;"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-03-m">
	<!-- register form -->       
	<div class="register-page-box">
	
<div align="center" style="width:auto; font-size:14px; margin-top:30px; margin-bottom:30px;">
{$text1}<br/><br/>
{$text2}<br/><br/>
<strong id="registered_email">{$mailbox}</strong><br/><br/>
{$text3}<br/><br/>

Falsche Emailadresse? <div style=" width:220px; margin-top:10px;">
<a href="#" class="btn-search" style="width:200px;" onclick="changeEmail(); return false;">Bitte HIER KLICKEN</a></div>
</div>
</div>

<div id="boxes">
<div id="dialogChangeEmail" class="window">
<div style="background-color: white; width: 100%"></div>
</div>
	
	</div>
	<!--end register form -->
	</div>
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m" style="width:900px !important;"></div>
	<div class="box-content-01-b-r"></div>
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