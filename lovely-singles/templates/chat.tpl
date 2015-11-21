<link href="css/chat.css" rel="stylesheet" type="text/css" />
<div class="result-box">
<h1>{#MESSAGES#}</h1>
<div class="result-box-inside-nobg">
{if count($contactList)}
{include file="chat_contact.tpl"}
<div id="messagesArea"></div>
<br clear="all"/>
{else}
No message.
{/if}
</div>
</div>

{literal}
<script type="text/javascript">
	var currentUsername="";
	var timer1=false;
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
			if(timer1)
			{
				clearTimeout(timer1);
			}
			timer1 = setTimeout(checkNewMessages,10000);
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
				delegateContactList();
				coinsBalance();
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
						jQuery('#contactList-'+(username.replace(/\s+/g, ''))).fadeOut(500, function() { $(this).remove(); });
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
																	var popup_url = root_path + '?action=incompleteinfo';
																	break;
																case 'Step3':
																	var popup_url = root_path + '?action=mobileverify';

															}

															Lightview.show({
																			href: popup_url,
																			rel: 'ajax',
																			options: {
																				autosize: true,
																				topclose: true
																			}
																		});
															return false;
														}
														else if(originalRequest.responseText==="Verified")
														{
															if(checkChatSMSForm())
															{
																jQuery.ajax(
																			{
																				type: "POST",
																				dataType: "json",
																				url: "?action=chat&type=writemessage&send_via_sms=1",
																				data: jQuery("#message_write_form").serialize(),
																				success:(function(result)
																					{
																						eval(result.commands);
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
										dataType: "json",
										url: "?action=chat&type=writemessage",
										data: jQuery("#message_write_form").serialize(),
										success:(function(result)
											{
												eval(result.commands);
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
		var sms = $('sms').value;

		var data = Array(
						Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
						);

		return checkActionFocus2(data);
	}

	function checkChatSMSForm()
	{
		var sms = $('sms').value;

		var data = Array(
						Array('sms', sms, '==', '', send_msg_sms_alert, 'sms_info')
						);

		return checkActionFocus2(data);
	}

	function showAttachmentsList(type)
	{
		var popup_url ='?action=attachments&type='+type;
		Lightview.show({
						href: popup_url,
						rel: 'ajax',
						options: {
							autosize: true,
							topclose: true
						}
					});
	}

	function addAttactmentCoins(amount)
	{
		var data = 
		jQuery.ajax({ type: "POST", dataType: "json", url: "?action=attachments&type=coins", data: "coins="+amount, success:(function(result){
			if(result.code=="FINISHED")
			{
				removeAttachment('coins');
				jQuery('#attachments-list').append(result.html);
				Lightview.hide();
			}
			else
			{
				alert(result);
			}
		}) });
	}

	function removeAttachment(type)
	{
		jQuery('#attachments-'+type).remove();
	}

	function removeAllAttachments()
	{
		jQuery('#attachments-list').html("");
	}

	delegateContactList();

	{/literal}
	{if $smarty.get.username ne ''}
		jQuery('#contactList-{$smarty.get.username|replace:' ':''}').click();
	{else}
		jQuery('li.message_contact').first().click();
	{/if}
	{literal}

	setInterval(loadContactsList,10000);
</script>
{/literal}