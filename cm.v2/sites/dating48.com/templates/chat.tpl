<div id="container-content">
    <h1>{#MESSAGES#}</h1>
    <div id="container-content-profile-home">
        <div style="padding:20px; float:left; width:858px;">
        
            {if count($contactList)}
            {include file="chat_contact.tpl"}
            {else}
            No message.
            {/if}
            <div id="messagesArea"></div>
         </div>
     </div>
            
    <div id="boxes">
    	<div id="dialogVerifyMobile" class="window">
            <div style="background-color: white; width: 100%"></div>
        </div>
    </div>
</div>
{include file="my_favorite.tpl"}

{literal}
<script type="text/javascript">
	var currentUsername="";
	var timer1;
	var totalMessages=0;
	var sending = false;
	function loadMessagesHistory(username, total, part)
	{
		currentUsername = username;
		uri = '?action=chat&type=getMessages&from='+encodeURIComponent(username)+'&total='+total+'&part='+part;
		jQuery.get(uri, function(data) {
			if(data != '')
			{
				if(part=='all')
				{
					jQuery('#messagesArea').html(data);
				}
				else
				{
					jQuery('#messagesContainer').html(data);
				}
			}
			timer1 = setTimeout(checkNewMessages,5000);
		});
		return false;
	}

	function checkNewMessages()
	{
		loadMessagesHistory(currentUsername, totalMessages);
	}

	function loadContactsList()
	{
		uri = '?action=chat&crc='+crc{/literal}{if $smarty.get.username ne ''}+'&username='+encodeURIComponent('{$smarty.get.username}'){/if}{literal};
		jQuery.get(uri, function(data) {
			if(data != '')
			{
				jQuery('#contactListArea').html(data);
				jQuery('#contactList-'+(currentUsername.replace(/\s+/g, ''))).addClass("active");
				delegateContactList()
;			}
		});
		return false;
	}

	function coinsBalance()
	{
		jQuery.ajax(
		{
			type: "GET",
			url: "?action=chat&type=coinsBalance",
			success:(function(result)
				{
					jQuery('#coinsArea').text(result);
				})
		});
	}

	function deleteContact(username)
	{
		jQuery.ajax(
		{
			type: "GET",
			url: "?action=chat&type=deleteContact&username="+username,
			success:(function(result)
				{
					if(result=='DELETED')
					{
						jQuery('#contactList-'+(username.replace(/\s+/g, ''))).fadeOut(500, function() { jQuery(this).remove(); });
						jQuery('li.message_contact').first().click();
					}
				})
		});		
	}

	function delegateContactList()
	{
		jQuery("ul#contactList").delegate("li", "click", function() {
		  jQuery(this).addClass("active").siblings().removeClass("active");
		});
	}

	function markAsRead(username)
	{
		if(timer1)
			clearTimeout(timer1);
		jQuery.ajax(
		{
			type: "GET",
			url: "?action=chat&type=markAsRead&username="+username,
			success:(function(result)
				{
					loadMessagesHistory(currentUsername, 0);
				})
		});
	}

	function sendChatMessage(sendingType)
	{
		if(!sending)
		{
			if(jQuery('#sms').val())
			{
				sending = true;
				if(sendingType=='sms')
				{
					var mbox = new Ajax.Request("ajaxRequest.php",
												{
													method: "post",
													parameters: "action=getCurrentUserMobileNo",
													onComplete: function(originalRequest) {
														if((originalRequest.responseText==="Step2") || (originalRequest.responseText==="Step3"))
														{
															sending = false;
															switch (originalRequest.responseText)
															{
																case 'Step2':
																	var popup_url = '?action=incompleteinfo';
																	break;
																case 'Step3':
																	var popup_url = '?action=mobileverify';

															}

															/*Lightview.show({
																			href: popup_url,
																			rel: 'ajax',
																			options: {
																				autosize: true,
																				topclose: true
																			}
																		});*/
	jQuery("#dialogVerifyMobile").load(popup_url);

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
	jQuery('#dialogVerifyMobile').css('top',  winH/2-jQuery('#dialogVerifyMobile').height()/2);
	jQuery('#dialogVerifyMobile').css('left', winW/2-jQuery('#dialogVerifyMobile').width()/2);

	//transition effect
	jQuery('#dialogVerifyMobile').fadeIn(1500);

															return false;
														}
														else if(originalRequest.responseText==="Verified")
														{
															if(checkChatSMSForm())
															{
																jQuery.ajax(
																			{
																				type: "POST",
																				url: "?action=chat&type=writemessage&send_via_sms=1",
																				data: jQuery("#message_write_form").serialize(),
																				success:(function(result)
																					{
																						if(result==="SENT")
																						{
																							loadMessagesHistory(jQuery('#to').val());
																							jQuery('#sms').val("");
																							jQuery('#countdown').val("{/literal}{$smarty.const.MAX_CHARACTERS}{literal}");
																							coinsBalance();
																							sending = false;
																						}
																						else if(result==="NOCOIN")
																						{
																							window.location='?action=pay-for-coins';
																						}
																						else
																						{
																							alert(result);
																						}
																					})
																			});
																return true;
															}
														}
													}
												});
				}
				else
				{
					if(checkChatEmailForm())
					{
						jQuery.ajax(
									{
										type: "POST",
										url: "?action=chat&type=writemessage",
										data: jQuery("#message_write_form").serialize(),
										success:(function(result)
											{
												if(result==="SENT")
												{
													loadMessagesHistory(jQuery('#to').val());
													jQuery('#sms').val("");
													jQuery('#countdown').val("{/literal}{$smarty.const.MAX_CHARACTERS}{literal}");
													coinsBalance();
													sending = false;
												}
												else if(result==="NOCOIN")
												{
													window.location='?action=pay-for-coins';
												}
												else
												{
													alert(result);
												}
											})
									});
						return true;
					}
				}
			}
		}
	}

	function checkChatEmailForm()
	{
		var sms = jQuery('sms').val();
		var data = Array(
						Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
						);
		return checkActionFocus2(data);
	}

	function checkChatSMSForm()
	{
		var sms = jQuery('sms').val();
		var data = Array(
						Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
						);
		return checkActionFocus2(data);
	}

	delegateContactList();

	{/literal}
	{if $smarty.get.username ne ''}
		jQuery('#contactList-{$smarty.get.username}').click();
	{else}
		jQuery('li.message_contact').first().click();
	{/if}
	{literal}

	setInterval(loadContactsList,5000);
</script>
{/literal}