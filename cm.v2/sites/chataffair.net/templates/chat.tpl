
<div id="container-chat-area" >
        <div style="float:left; width:848px; margin:0; margin-bottom:10px; margin-left:150px;"> 
            <h1 class="title-chat">Versende jetzt eine Nachricht über SMS an <span id="talkto"></span> für nur 5 Coins!</h1>
        </div>
        <br class="clear" />
        	<div class="container-chatting">
            <form id="message_write_form">
            	<input type="hidden" name="act" value="writemsg" />
				<input id="to" name="to" type="hidden" style="width:180px" value="" style="float: left">
            	<div class="chatting-bg-img">
                <a href="#"><img src="images/cm-theme/chat-area/profile-img-bb.jpg" width="165" height="166" id="profileImg" /></a>
                </div>
                <div class="container-input-chat-box">
                  <textarea id="sms" name="sms" class="formfield_02" style="width:675px; height:53px;" maxlength="140"></textarea>
                  <span><strong>(Maximale Zeichen: 140)</strong></span> <span style="float:right;">
                  <input type="text" style="background:none; border:none; color:#fff; font-weight:bold; text-shadow: #000 1px 1px 2px; text-align:right;" value="140" size="3" name="countdown" id="countdown" readonly="">
                  Zeichen übrig.</span>
                  <br class="clear" />
                  <a href="javascript:void;" onclick="javascript:sendChatMessage('sms');" class="btn-chat-sms">SMS Versenden</a>
                  <a href="javascript:void;" onclick="javascript:sendChatMessage('email');" class="btn-chat-email">Email Versenden</a>
                </div>
            </form>    
            </div>
            <div id="contactListArea">
            <ul id="contactList" class="container-chat-list">
            
            {foreach from=$contactList item="contact"}
           	 	<li onclick="javascript:loadMessagesHistory('{$contact.username}','undefined', 'all');" class="chatlist {if $contact.count gt 0}new-list{/if}" id="contactList-{$contact.username|replace:' ':''}">
                	<div class="chat-list-box">
                    <div class="chat-list-box-img">
                    <a href="#" class="profile-list-img">
                    	<img id="avatar-{$contact.username}" path="{$contact.picturepath}" src="thumbnails.php?file={$contact.picturepath}&w=61&h=61" width="61" height="61" {if $contact.username ne $smarty.const.ADMIN_USERNAME_DISPLAY}onclick="loadPagePopup('?action=viewprofile&part=partial&username={$contact.username}'); event.stopPropagation();"{/if}/>				
                    </a>
                    </div>
                    <a href="#" class="link-to-chat">
                    <div class="chat-list-name">{$contact.username}</div>
                    
                        <ul class="chat-list-icon">
                        	{if $contact.isFavorited eq $smarty.session.sess_id}
                            <li><img src="images/cm-theme/chat-area/chat-fav.png" width="18" height="18" /></li>
                            {/if}
                            <li>{if $contact.count gt 0}{$contact.count}<img src="images/cm-theme/chat-area/chat-massage.png" width="18" height="18" /> neu!{/if}</li>
                        </ul>
                    </a>
                    <a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="del-profile-list">
				    	<img src="images/cm-theme/chat-area/chat-del.png" width="18" height="18" />
                	</a>
				    </div>
                </li>
			{/foreach}
			</ul>
			
            <script type="text/javascript">
			var crc={$crc};
			</script>

            
            </div>
            <div class="container-chat-history"><div class="scroll-box">
            	<div id="messagesContainer"><div id="messageArea">
            	</div></div>
        	</div></div>
            
        </div>

                <!-- Portfolio 4 Column End -->
<!--<h2 class="title" style="margin:10px 0 0 0;">{#MESSAGES#}</h2>
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
</div> -->


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
		// De-Active (iPokz)
		jQuery("div.active-list").removeClass("active-list");
		if(currentUsername != username ){
			pathfile = jQuery("#avatar-"+(username.replace(/\s+/g, ''))).attr("path");
			jQuery("textarea.formfield_02").val("");
			jQuery("#talkto").html(username);
			jQuery("#to").val(username);
			jQuery("#profileImg").attr("src", "thumbnails.php?file="+pathfile+"&w=165&h=166");
		}
		jQuery('#contactList-'+(username.replace(/\s+/g, ''))).children().addClass("active-list");
		currentUsername = username;
		uri = '?action=chat&type=getMessages&from='+encodeURIComponent(username)+'&total='+total+'&part='+part;
		jQuery.get(uri, function(data) {
			if(data != '')
			{
				if(part=='all')
				{
					console.log('all');
					// jQuery('#messagesArea').html(data);
					jQuery('#messagesContainer').html(data);
				}
				else
				{
					console.log('Container');
					jQuery('#messagesContainer').html(data);
				}
			}
			if(timer1)
			{
				clearTimeout(timer1);
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
				jQuery('#contactList-'+(currentUsername.replace(/\s+/g, ''))).children().addClass("active-list");
				delegateContactList();
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
		  jQuery(this).addClass("active-list").siblings().removeClass("active-list");
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
						console.log("Sent Email");
						jQuery.ajax(
									{
										type: "POST",
										url: "?action=chat&type=writemessage",
										data: jQuery("#message_write_form").serialize(),
										success:(function(result)
											{
												console.log("Server Response"+result);
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
													loadMessagesHistory(jQuery('#to').val());
													jQuery('#sms').val("");
													jQuery('#countdown').val("{/literal}{$smarty.const.MAX_CHARACTERS}{literal}");
													coinsBalance();
													sending = false;
													jQuery("#contactList-"+(currentUsername.replace(/\s+/g, '')) ).prependTo("#contactList");
													// return result;
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
	{if ($mode eq 'instant') && ($smarty.get.username ne '')}
		loadMessagesHistory('{$smarty.get.username}', 'undefined', 'all');
	{elseif $smarty.get.username ne ''}
		jQuery('#contactList-{$smarty.get.username}').click();
	{else}
		jQuery('ul.container-chat-list li').first().click();
	{/if}
	{literal}

	setInterval(loadContactsList,5000);
</script>
{/literal}