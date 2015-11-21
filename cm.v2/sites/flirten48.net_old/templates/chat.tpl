<h2 class="title" style="margin:10px 0 0 0;">{#MESSAGES#}</h2>
<div id="container-chat">
<div>

{if $mode ne 'instant'}
{if count($contactList)}
{include file="chat_contact.tpl"}
{else}
No message.
{/if}
{/if}
<div id="messagesArea"></div>
</div>
</div>

{if $mode ne 'instant'}
{include file="my_favorite.tpl"}
{/if}

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
					jQuery('#sms').keyup(function(){
						var limit = parseInt(jQuery(this).attr('maxlength'));
						var text = jQuery(this).val();
						var chars = text.length;
				 
						if(chars > limit){
							var new_text = text.substr(0, limit);
							jQuery(this).val(new_text);
						}

						jQuery('#countdown').val(limit-chars);
					});
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
			}
		});
		return false;
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
															loadPagePopup(popup_url, '100%');
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

	function showAttachmentsList(type)
	{
		loadPagePopup('?action=attachments&type='+type, '100%');
	}

	function addAttactmentCoins(amount)
	{
		var data = 
		jQuery.ajax({ type: "POST", dataType: "json", url: "?action=attachments&type=coins", data: "coins="+amount, success:(function(result){
			if(result.code=="FINISHED")
			{
				removeAttachment('coins');
				jQuery('#attachments-list').append(result.html);
				jQuery('#mask').hide();
				jQuery('.window').hide();
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

	jQuery(function() {
		delegateContactList();

		{/literal}
		{if ($mode eq 'instant') && ($smarty.get.username ne '')}
			loadMessagesHistory('{$smarty.get.username}', 'undefined', 'all');
		{elseif $smarty.get.username ne ''}
			jQuery('#contactList-{$smarty.get.username}').click();
		{else}
			jQuery('li.message_contact').first().click();
		{/if}
		{literal}

		setInterval(loadContactsList,10000);
	});
</script>
{/literal}