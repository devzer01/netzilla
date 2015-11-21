{include file='public/header.tpl'}

<div id="container-content">
<h1 class="title">{#Register#}</h1>
	<div align="center" style="width:auto; font-size:14px; margin-top:30px; margin-bottom:30px;">
		Vielen Dank für deine Registrierung bei Flirt48.net. Eine Email ist bereits an die von dir angegebene Email- adresse unterwegs.<br/><br/>
		Bitte überprüfe auch deinen SPAM- oder Junkmail-Ordner in deinem Email-Client ob unsere Mail dort eingegangen ist und füge den Absender gegebenenfalls als vertrauenswürdigen Absender hinzu, damit wir dich auch in Zukunft immer auf dem Laufenden halten können!<br/><br/><br/><br/>
		<span style='font-size: +2;'><strong>Bitte bestätige deine Daten durch Anklicken des darin enthaltenen Links!</strong></span><br/><br/><br/>
		<span style='font-size: -2;'>Oder</span><br/><br/><br/>
		<span style='font-size: +2;'><strong>Falls der Link nicht funktioniert, gebe bitte hier den in der Email enthaltenen Verifizierungs-Code ein </strong></span><br/> <br/>
<div>Verifizierungs-Code: <input type='text' id='activation' name='activation' /><span id="activation_info"></span><a href='#' id='activate'>Aktivieren</a></div>  
<hr />		
<h3 id='lshowsend' style='cursor: pointer;'>Verifizierungs-Code erneut anfordern</h3>		
<div id='divresend' style='display: none;'>
Falls der Verifizierungs-Code nicht angekommen ist, kannst du ihn dir erneut an <span id='registered_email'>{$mailbox}</span> zusenden lassen.<br/>
<br/>
<div>Verifizierungs-Code erneut zusenden? <a href='#' id='resend'>Senden</a></div>
</div> 
<hr />		
<h3 id='lshowchange' style='cursor: pointer;'>Meine E-Mail ändern</h3>
<div id='divchange' style='display: none;'>
	Falls du dich vertippt hast, kannst du hier deine E-Mail Adresse ändern.<br/>
<br/>
<div>Meine neue E-Mail Adresse <input value='{$mailbox}' type='text' name='newemail' id='newemail' /> <a href='#'>Weiter</a> </div>
	</div>
</div>
</div>

<div id="boxes">
<div id="dialogChangeEmail" class="window">
	<div style="background-color: white; width: 100%"></div>
</div>
</div>

<script type='text/javascript'>
var email = '{$mailbox}';
var username = '{$username}';
var password = '{$password}';
var app_path = '{$smarty.const.APP_PATH}';

{literal}
	$(function (e) {
	
	jQuery('#lshowsend').click(function (e) {
		jQuery('#divresend').show();
	});
	
	jQuery('#lshowchange').click(function (e) {
		jQuery('#divchange').show();
	});
	
	$('#activation').keydown(function () {
		$('#activation_info').html("");
		$('#activation_info').removeClass('error_info_small');
	});
	
	$("#activate").click(function (e) {
		jQuery.ajax({
			url: app_path + '/verify/check', 
			data: {username: username, code: $('#activation').val()},
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (json.ok == 1) {
					window.location.href = app_path + '/verify/activate/' + encodeURIComponent(username) + '/' + encodeURIComponent(password) + '/' + $('#activation').val();
				} else {
					jQuery('#activation_info').html("Der Code ist leider nicht Korrekt");
					jQuery('#activation_info').addClass('error_info_small');
				}
			}
		});
		
	});
	
	jQuery('#resend').click(function (e) {
		sendActivationEmail();	
	});
});

function sendActivationEmail() {
	jQuery.ajax({
		url: "ajaxRequest.php?action=sendActivateEmail&email=" + email + '&username=' + username,
		type: 'get',
		dataType: 'json',
		success: function(json) {
			console.log('email sent');	
		}
	});
}

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
	var email = jQuery('#newemail').val();
	jQuery.ajax({ type: "POST", url: "?action=change_email", data: {email: email}, success:(function(result){sendingChangeEmail = false; if(result=="CHANGED") {jQuery('#mask').hide(); jQuery('.window').hide(); jQuery('#registered_email').text(jQuery('#newemail').val());}else{alert(result);}}) });
}
{/literal}
</script>

{include file='public/footer.tpl'}