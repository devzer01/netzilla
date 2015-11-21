{*if !$smarty.session.sess_externuser*}
{if $smarty.session.sess_username neq ""}
<div class="footer-container">
	<div class="footer-area">
		<a href="?action=mymessage&type=inbox"><img src="images/footerbar/ficon-sms.png" border="0" align="absmiddle"/> SMS {#MESSAGES#} <div id="text_msg"></div></a>
		<div class="footer-area-tab"></div>
		<a href="?action=mymessage&type=inbox"><img src="images/footerbar/ficon-email.png" border="0" align="absmiddle"/> Email {#MESSAGES#} <div id="email_msg"></div></a>
		<div class="footer-area-tab"></div>
		<a href="?action=faqs"><img src="images/footerbar/ficon-faqs.png" border="0" align="absmiddle"/> {#FAQS#}</a>
		<div class="footer-area-tab"></div>
		<a href="?action=favorite"><img src="images/footerbar/ficon-favourites.png" border="0" align="absmiddle"/> {#FAVOURITES#}</a>
		<div class="footer-area-tab"></div>
		<a href="?action=pay-for-coins"><img src="images/footerbar/ficon-coins.png" border="0" align="absmiddle"/> {#I_WANT_PAY_COINS#}</a>
		<div class="footer-area-tab"></div>
		<a href="javascript:void(0);" id="instant_bar"><span id="instant_name" style="width:150px;">Sofortkontakt{if $smarty.session.last_username neq ""}: {$smarty.session.last_username|truncate:13:"...":true}{/if}</span></a><!-- style="float:right;"  style="float:right;"-->	
	</div>
</div>
{/if}

<div class="footerbar">
	<div class="footerbarcon">
		<a href="?action=terms"> {#AGB#} </a> - 
		<a href="?action=imprint">{#IMPRESSUM#}</a> - 
		<a href="?action=policy">{#WIDERRUFSRECHT#}</a> 
		{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}- <a href="?action=delete_account">{#delete_account#}</a>{/if}
	</div>
</div>
<span id="instant_hidden" style="display:none">{if $smarty.session.last_username neq ""}{$smarty.session.last_username}{/if}</span>

{literal}
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30528203-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
{/literal}

{if $smarty.session.sess_username neq ""}
{literal}
<script type="text/javascript">
	var currentMsgCount = null;
	function showCurrentStatus()
	{
		var mbox = new Ajax.Request("ajaxRequest.php", 
									{
										method: "post", 
										parameters: "action=fetchAllStatus", 
										onComplete: function(originalRequest) {
											if(originalRequest.responseText!="")
											{
												var currentStatus = new Array();
												currentStatus = eval(originalRequest.responseText);
												if(currentStatus['0']==0)
													jQuery("#text_msg").html("");
												else
													jQuery("#text_msg").html("<font class='alert-message-box'>"+ currentStatus['0'] + " " + newmessage + "!</font>");
												
												if(currentStatus['1']==0)
													jQuery("#email_msg").html("");
												else
													jQuery("#email_msg").html("<font class='alert-message-box'>"+ currentStatus['1'] + " " + newmessage + "!</font>");

												if(currentStatus['2']==0)
												{
													currentMsgCount = 0;
													jQuery("#new_msg").html("");
												}
												else
												{
													jQuery("#new_msg").html("<font style='display:block; position:relative; top:-8px; margin-left:1px; text-align:center; height:19px; font-size:9px; font-weight:bold; font-style:italic; line-height:15px;  width:55px; background: url(images/new-mes.png); float:left; color:#FFFFFF;'>"+ currentStatus['2'] + " " + newmessage + "!</font>");

													if(currentMsgCount != null)
													{
														if(currentMsgCount < currentStatus['2'])
														{
															currentMsgCount = currentStatus['2'];
															notificationSoundElement.pause();
															notificationSoundElement.play();
														}
													}
													else
														currentMsgCount = currentStatus['2'];
												}
												
												if(currentStatus['3']==0)
													jQuery("#new_sugg").html("");
												else
													jQuery("#new_sugg").html("<font style='display:block; position:relative; top:-8px; margin-left:10px; text-align:center; height:19px; font-size:9px; font-weight:bold; font-style:italic; line-height:15px; float:left; width:55px; background: url(images/new-mes.png); color:#FFFFFF;'>"+ currentStatus['3'] + " " + newmessage + "!</font>");
											}
										}
									});
	}

	function showCurrentProfile()
	{
		var mbox = new Ajax.Request("ajaxRequest.php", 
									{
										method: "post", 
										parameters: "action=getRandomUser", 
										onComplete: function(originalRequest) {
											if(originalRequest.responseText!="")
											{
												var currentProfile = new Array();
												currentProfile = eval(originalRequest.responseText);
												//alert(currentProfile['0']);
														//jQuery.gritter.removeAll();
														var nickname = currentProfile['0'];
														
														if(nickname.length>10)
															var newnickname = nickname.substring(0,10) + '...';
														else
															var newnickname = nickname;
														
														//alert(nickname.length + ' ' + newnickname);

														jQuery('#instant_name').html('Sofortkontakt: ' + newnickname);//+currentProfile['0']);
														jQuery('#instant_hidden').html(currentProfile['0']);
														
														jQuery.gritter.add({
															// (string | mandatory) the heading of the notification
															title: ' ',
															// (string | mandatory) the text inside the notification
															text: currentProfile['0'],
															// (string | optional) the image to display on the left
															image: 'thumbnails.php?file='+currentProfile['1']+'&w=133&h=149',
															// Noi modified on 2012-05-14 adding url param to add function
															url: '?action=chat&username='+currentProfile['0'],
															// (bool | optional) if you want it to fade out on its own or just sit there
															sticky: false,
															// (int | optional) the time you want it to be alive for before fading out
															time: '6000',
															before_open: function(){
																if(jQuery('.gritter-item-wrapper').length == 1)
																{
																	// Returning false prevents a new gritter from opening
																	jQuery.gritter.removeAll();
																	return false;
																}
															}
														});
														
														return false;
											}
										}
									});
	}

	jQuery('#instant_bar, #instant_name').click(function(){
		//alert(jQuery('#instant_name').html().replace(": ", ""));
		//var url = '?action=viewprofile&username='+ jQuery('#instant_name').html().replace("Sofortkontakt: ", "");
		var url = '?action=chat&username='+ jQuery('#instant_hidden').html();
		window.location = url; //'?action=viewprofile&username=BarbieTY';
	});

	jQuery(function(){

		// global setting override
        /*
		$.extend($.gritter.options, {
		    class_name: 'gritter-light', // for light notifications (can be added directly to $.gritter.add too)
		    position: 'bottom-left', // possibilities: bottom-left, bottom-right, top-left, top-right
			fade_in_speed: 100, // how fast notifications fade in (string or int)
			fade_out_speed: 100, // how fast the notices fade out
			time: 3000 // hang on the screen for...
		});
        */

		jQuery(document).ready(function() {

			/*showCurrentStatus();
			var refreshId = setInterval(function() {
				showCurrentStatus();
			}, 3000);*/

			showCurrentStatus();
			//showCurrentProfile();
			var refreshProfile = setInterval(function() {
				showCurrentProfile();
			}, 120000);
			
			var refreshStatus = setInterval(function() {
				showCurrentStatus();
			}, 10000);/**/

			jQuery.ajaxSetup({ cache: false });
		});

	});

</script>	
{/literal}
{/if}