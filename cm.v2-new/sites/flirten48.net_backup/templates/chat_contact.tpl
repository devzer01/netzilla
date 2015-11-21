<section>
        <!-- {$smarty.template} -->
        <div class="chat-bg">
		<div id="container-chat-area">
		
		<h1 class="title-chat">Versende jetzt eine Nachricht über SMS an zerocoolz für nur 5 Coins!</h1>
                       
                        <br class="clear" />
                        <div class="container-chatting">
                        <ul class="container-profile-list chat-profile">
                            <li>
                                <div>
                                    <img id='chaticonpic' src="thumbnails.php?file={$currentContact.picturepath}&w=141&h=170" width="141" height="170">
                                    <a id="profil1" class="settings-button"><img src="images/cm-theme/profile-list-bg.png" width="145" height="145" /></a>
                                </div>
                                <p id='member_username'></p>
								<script type="text/javascript">
								{literal}
	                                jQuery(document).ready(function($) {
	                                	$('#profil1').toolbar({content: '#user-options-profil1', position: 'left'});
	                                	
	                                	$("#emoticons").click(function (e) {
	                                		e.preventDefault();
	                                		$("#iconlist").css("left", $("#ulicons").offset().left + 'px');
	                                		$("#iconlist").css("top", ($("#ulicons").offset().top + 30) + 'px');
	                                		$("#iconlist").fadeIn();
	                                	});
	                                	
	                                	$('#coins').click(function (e) {
	                                		e.preventDefault();
	                                		showAttachmentsList();
	                                	});
	                                	
	                                	$('#profil1').on('toolbarItemClick', function (event, elm) {
	                                		switch (elm.id) {
			        	    					case 'profile':
			        	    						document.location.href = '?action=viewprofile&username=' + $("#chaticonpic").data('username');
			        	    						break;
			        	    					case 'favorite':
			        	    						$(elm).remove(); 
			        	    						addFavorite($("#chaticonpic").data('username'),'favorite-list-container');
			        	    						break;
			        	    					case 'delete':
			        	    						deleteContact($("#chaticonpic").data('username'));
			        	    						break;
			        	    				}
	                                	});
	                                	
	                                });
	                                
	                                function setChatIcon(username, picture) {
	                                	$("#chaticonpic").attr('src', 'thumbnails.php?file=' + picture + '&w=141&h=170');
	                                	$("#member_username").html(username);
	                                	$("#chaticonpic").data('username', username);
	                                	$("#chaticonpic").data('picture', picture);
	                                }
	                                
                                {/literal}
                                </script>
                                <div id="user-options-profil1" class="toolbar-icons" style="display: none;">
                                    <a href="#" id='profile' title="Profile"><i class="icon-user"></i></a>
                                    <a href="#" id='favorite' title="Favoriten"><i class="icon-star"></i></a>
                                    <a href="#" id='delete' title="Delete"><i class="icon-remove"></i></a>
                                </div>
                            </li>
                        </ul>
						 <div class="container-input-chat-box">
						 	<form id="message_write_form" name="message_write_form" method="post" onsubmit="return false;">
						 		<input type="hidden" name="act" value="writemsg" />
								<input id="to" name="to" type="hidden" style="width:180px" value="{$username}" style="float: left">
		                        <textarea id="sms" name="sms" maxlength="{$smarty.const.MAX_CHARACTERS}" onclick="markAsRead($('#to').val())" tabindex="1" class="formfield_02" style="width:675px; height:53px;">{$save.message}</textarea>
		                        <ul id='ulicons' class="special-icon-chat">
		                        	{**if ($smarty.const.ATTACHMENTS eq 1) and ($smarty.const.ATTACHMENTS_COIN eq 1) and ($already_topup)**}
                        				<li><a id='coins' href="#" class="coin"><span>Coin</span></a></li>
                        			{**/if**}
                            		<li><a id='emoticons' href="#" class="smiley"><span>smiley</span></a></li>
                        		</ul>
                        		<div id="attachments-list"></div>
                        		<div id='container' style='position: absolute; z-index: 10; display: none;'></div>
		                        <span><strong>(Maximale Zeichen: 140)</strong></span> <span style="float:right;"><strong><span id='countdown' style="padding-top:0 !important;">140 </span></strong> Zeichen übrig.</span>
		                        <br class="clear" />
		                        <a href="javascript:void(0);" onclick="sendChatMessage('sms');" class="btn-chat-sms">SMS Versenden</a>
		                        <a href="javascript:void(0);" onclick="sendChatMessage('email');" class="btn-chat-email">Email Versenden</a>
		                     </form>
                        </div>
                   	</div>
                   	<ul class="container-chat-list">
						{foreach from=$contactList item="contact"}
							<li class="chatlist" data-username='{$contact.username}' data-picture='{$contact.picturepath}' onclick="loadMessagesHistory('{$contact.username}','undefined', 'all') && setChatIcon('{$contact.username}', '{$contact.picturepath}');" id="contactList-{$contact.username|replace:' ':''}">
                                <div class="chat-list-box active-list">
                                    <div class="chat-list-box-img">
                                        <a href="#" class="profile-list-img"><img src="thumbnails.php?file={$contact.picturepath}&w=61&h=61" width="61" height="61" /></a>
                                    </div>
                                <a href="#" class="link-to-chat">
                                <div class="chat-list-name">{$contact.username}</div>
                                
                                <ul class="chat-list-icon">
                                	{if in_array($contact.username, $favorites_list)}
                                    	<li><img src="images/cm-theme/chat-area/chat-fav.png" width="18" height="18" /></li>
                                    {/if}
                                    {if $contact.count > 0 } 
                                    	<li><img src="images/cm-theme/chat-area/chat-massage.png" width="18" height="18" />{$contact.count}</li>
                                    {/if}
                                </ul>
                                </a>
                                <a href="#" class="del-profile-list" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;"><img src="images/cm-theme/chat-area/chat-del.png" width="18" height="18" /></a>
                                </div>
                            </li>
						{/foreach}
						</ul>
						<div class="container-chat-history">
							<div class="scroll-box" id='messagesArea'>
								
							</div>
						</div>
                        <br class="clear" />
						
		</div>
	</div>
     <!--end chat -->
</section>

<div id='iconlist'>
	{include file='emoticons.tpl'}
</div>
<script type="text/javascript">
var crc={$crc};
</script>