<div id="contactListArea">
	<ul id="contactList">
	{foreach from=$contactList item="contact"}
		<li onclick="loadMessagesHistory('{$contact.username}','undefined', 'all');" class="message_contact {if $contact.count gt 0}new-list{/if}" id="contactList-{$contact.username|replace:' ':''}">
		<div class="contactImage">
        	<img src="thumbnails.php?file={$contact.picturepath}&w=50&h=50" width="50" height="50" {if $contact.username ne $smarty.const.ADMIN_USERNAME_DISPLAY}onclick="loadPagePopup('?action=viewprofile&part=partial&username={$contact.username}'); event.stopPropagation();"{/if}/>
			{if $contact.isFavorited eq $smarty.session.sess_id}
        	<div class="fav-chat"><img src="images/cm-theme/s-icon-02.png" width="21" height="21" /></div>
			{/if}
         </div>
		<div class="contactName">
			{$contact.username}<br/>
			<span class='notification'>{if $contact.count gt 0}{$contact.count} neu!{/if}</span>
		</div>
        <a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="delete-chat"></a>
		</li>
	{/foreach}
	</ul>
</div>

<script type="text/javascript">
var crc={$crc};
</script>