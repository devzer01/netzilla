<div id="contactListArea">

<ul id="contactList" class="container-chat-icon" >
{foreach from=$contactList item="contact"}
    <li onclick="loadMessagesHistory('{$contact.username}','undefined', 'part2');" class="message_contact {if $contact.count gt 0}new-active{/if}" id="contactList-{$contact.username|replace:' ':''}">
        <a href="javascript:void(0)" class="profile-boder"></a>
        <img src="thumbnails.php?file={$contact.picturepath}&w=70&h=70" width="70" height="70" class="profile-img-chat"/>
        <a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" 
        class="q-icon q-right q-del"><span>delete</span></a>
    </li>
{/foreach}
</ul>

</div>

<script type="text/javascript">
var crc={$crc};
</script>