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