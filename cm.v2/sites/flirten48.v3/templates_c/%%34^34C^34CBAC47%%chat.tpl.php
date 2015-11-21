<?php /* Smarty version 2.6.14, created on 2013-11-19 16:32:15
         compiled from chat.tpl */ ?>
<h1 class="title"><?php echo $this->_config[0]['vars']['MESSAGES']; ?>
</h1>
<div id="container-chat">
<div>

<?php if ($this->_tpl_vars['mode'] != 'instant'): ?>
<?php if (count ( $this->_tpl_vars['contactList'] )): ?>
<div class="container-chat-left">
	<div class="container-input-chat" id="writingArea"></div>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "chat_contact.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>
<?php else: ?>
No message.
<?php endif; ?>
<?php endif; ?>
<div class="container-chat-right">
	<div class="container-chat-history" id="messagesListArea"></div>
</div>
</div>
</div>

<?php if ($this->_tpl_vars['mode'] != 'instant'): ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "my_favorite.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php endif; ?>

<?php echo '
<script type="text/javascript">
	var currentUsername="";
	var timer1=false;
	var totalMessages=0;
	var sending = false;
	function loadMessagesHistory(username, total, part)
	{
		currentUsername = username;
		uri = \'?action=chat&type=getMessages&from=\'+encodeURIComponent(username)+\'&total=\'+total+\'&part=\'+part;
		jQuery.get(uri, function(data) {
			if(data != \'\')
			{
				if(part==\'part1\')
				{
					jQuery(\'#writingArea\').html(data);
					jQuery(\'#sms\').keyup(function(){
						var limit = parseInt(jQuery(this).attr(\'maxlength\'));
						var text = jQuery(this).val();
						var chars = text.length;
				 
						if(chars > limit){
							var new_text = text.substr(0, limit);
							jQuery(this).val(new_text);
						}

						jQuery(\'#countdown\').val(limit-chars);
					});
				}
				else
				{
					jQuery(\'#messagesListArea\').html(data);
					if(total!=0)
						loadMessagesHistory(username, total, "part1");
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
		uri = \'?action=chat&crc=\'+crc';  if ($_GET['username'] != ''): ?>+'&username='+encodeURIComponent('<?php echo $_GET['username']; ?>
')<?php endif;  echo ';
		jQuery.get(uri, function(data) {
			if(data != \'\')
			{
				jQuery(\'#contactListArea\').html(data);
				//jQuery(\'#contactList-\'+(currentUsername.replace(/\\s+/g, \'\'))).addClass("active");
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
					if(result==\'DELETED\')
					{
						jQuery(\'#contactList-\'+(username.replace(/\\s+/g, \'\'))).fadeOut(500, function() { jQuery(this).remove(); });
						jQuery(\'li.message_contact\').first().click();
					}
				})
		});		
	}

	function delegateContactList()
	{
		jQuery("ul#contactList").delegate("li", "click", function() {
		  //jQuery(this).addClass("active").siblings().removeClass("active");
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
		console.log("Func: " + sendingType +  "AND" + sending + "\\n");
		if(!sending)
		{
			if(jQuery(\'#sms\').val())
			{
				sending = true;
				if(sendingType==\'sms\')
				{
					var options = {
						url: "ajaxRequest.php",
						type: "post",
						data: "action=getCurrentUserMobileNo",
						success: function (data, status, originalRequest) {
								if((originalRequest.responseText==="Step2") || (originalRequest.responseText==="Step3"))
								{
									sending = false;
									switch (originalRequest.responseText)
									{
										case \'Step2\':
											var popup_url = \'?action=incompleteinfo\';
											break;
										case \'Step3\':
											var popup_url = \'?action=mobileverify\';
											break;
	
									}
									loadPagePopup(popup_url, \'100%\');
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
					};
					jQuery.ajax(options);
				}
				else
				{
					console.log(\'ajax post\');
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


	function checkChatEmailForm()
	{
		var sms = jQuery(\'sms\').val();
		var data = Array(
						Array(\'sms\', sms, \'==\', \'\', send_msg_sms_alert, \'sms_info\')
						);
		return checkActionFocus2(data);
	}

	function checkChatSMSForm()
	{
		var sms = jQuery(\'sms\').val();
		var data = Array(
						Array(\'sms\', sms, \'==\', \'\', send_msg_sms_alert, \'sms_info\')
						);
		return checkActionFocus2(data);
	}

	function showAttachmentsList(type)
	{
		loadPagePopup(\'?action=attachments&type=\'+type, \'100%\');
	}

	function addAttactmentCoins(amount)
	{
		var data = 
		jQuery.ajax({ type: "POST", dataType: "json", url: "?action=attachments&type=coins", data: "coins="+amount, success:(function(result){
			if(result.code=="FINISHED")
			{
				removeAttachment(\'coins\');
				jQuery(\'#attachments-list\').append(result.html);
				jQuery(\'#mask\').hide();
				jQuery(\'.window\').hide();
			}
			else
			{
				alert(result);
			}
		}) });
	}

	function removeAttachment(type)
	{
		jQuery(\'#attachments-\'+type).remove();
	}

	function removeAllAttachments()
	{
		jQuery(\'#attachments-list\').html("");
	}

	jQuery(function() {
		delegateContactList();

		'; ?>

		<?php if (( $this->_tpl_vars['mode'] == 'instant' ) && ( $_GET['username'] != '' )): ?>
			loadMessagesHistory('<?php echo $_GET['username']; ?>
', 'undefined', 'part2');
		<?php elseif ($_GET['username'] != ''): ?>
			jQuery('#contactList-<?php echo $_GET['username']; ?>
').click();
		<?php else: ?>
			jQuery('li.message_contact').first().click();
		<?php endif; ?>
		<?php echo '

		setInterval(loadContactsList,5000);
	});
</script>
'; ?>
