<div id="contactListArea">

<ul id="contactList" class="container-chat-icon">
    {foreach from=$contactList item="contact"}
    <li onclick="loadMessagesHistory('{$contact.username}','undefined', 'part2');" class="message_contact {if $contact.count gt 0}active{/if}" id="contactList-{$contact.username|replace:' ':''}">
        <a href="javascript:void(0)" class="profile-icon"></a>
        <img src="thumbnails.php?file={$contact.picturepath}&w=78&h=66" width="78" height="66" class="chat-img"/>
        <a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="quick-icon q-recent q-del"><span>{#Delete#}</span></a>
    </li>
    {/foreach}
</ul>

</div>

<script type="text/javascript">
var crc={$crc};
</script>