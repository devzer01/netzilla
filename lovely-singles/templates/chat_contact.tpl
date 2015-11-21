<div id="contactListArea">
	<ul id="contactList">
	{foreach from=$contactList item="contact"}
		<li onclick="loadMessagesHistory('{$contact.username}','undefined', 'all'); {*jQuery(this).find('span.notification').hide();*}" class="message_contact {if $contact.count gt 0}new-list{/if}" id="contactList-{$contact.username|replace:' ':''}">
		<div class="contactImage"><img src="thumbnails.php?file={$contact.picturepath}&w=50&h=50" width="50" height="50"/></div>
		<div class="contactName">
			<a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="delete-chat"><img src="images/close.png" width="20" border="0"/></a><br/>
			{$contact.username}<br/>
			<span class='notification'>{if $contact.count gt 0}{$contact.count} neu!{/if}</span>
		</div>
		</li>
	{/foreach}
	</ul>
</div>

<script type="text/javascript">
var crc={$crc};
</script>