<div id="contactListArea">

<ul id="contactList" class="container-chat-icon">
    {foreach from=$contactList item="contact"}
    <li onclick="loadMessagesHistory('{$contact.username}','undefined', 'part2');" class="message_contact {if $contact.count gt 0}new-list{/if}" id="contactList-{$contact.username|replace:' ':''}">
    	
        <div class="profile-icon-chat">
            <a href="javascript:void(0)" class="profile-icon" {if $contact.username ne $smarty.const.ADMIN_USERNAME_DISPLAY}onclick="loadPagePopup('?action=viewprofile&part=partial&username={$contact.username}'); event.stopPropagation();"{/if}></a>
            <img src="thumbnails.php?file={$contact.picturepath}&w=78&h=66" width="68" class="chat-img"/>
        </div>
        <p>{$contact.username}</p>
        
        
		{if $contact.isFavorited eq $smarty.session.sess_id}
			<div class="icon-fav-contact"></div>
		{/if}
        <a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="chat-list-del"><span>{#Delete#}</span></a> 
        
    </li>
    {/foreach}
</ul>

</div>

<script type="text/javascript">
var crc={$crc};
</script>

