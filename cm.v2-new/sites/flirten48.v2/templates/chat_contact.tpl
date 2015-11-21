<!-- {$smarty.template} -->

<div id="contactListArea">

    <ul id="contactList" class="container-profile-chat-list">
    {foreach from=$contactList item="contact"}
        <li onclick="loadMessagesHistory('{$contact.username}','undefined', 'part2');" class="message_contact {if $contact.count gt 0}active{/if}" id="contactList-{$contact.username|replace:' ':''}">
        <img src="thumbnails.php?file={$contact.picturepath}&w=65&h=65" width="65" height="65" />
        <p>
        {$contact.username|truncate:7:"...":true:false}
        </p>
        <a href="#" class="link-profile"></a>
        <a href="#" class="q-left q-chat-del" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" title="Delete"></a>
        </li>
    {/foreach}
    </ul> 
     
</div>

<script type="text/javascript">
var crc={$crc};
</script>