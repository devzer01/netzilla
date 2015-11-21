	<div id="contactListArea">
		<ul id="contactList" class="container-profile-chat">
		{foreach from=$contactList item="contact"}
			<li onclick="loadMessagesHistory('{$contact.username}','undefined', 'part2');" class="message_contact {if $contact.count gt 0}active{/if}" id="contactList-{$contact.username|replace:' ':''}">
				<a href="javascript:void(0)">
					<div class="profile-list-most">
						<div class="boder-profile-img-most"><img src="images/cm-theme/profile-boder-img.png" width="88" height="89" /></div>
						<div class="img-profile-most">
							<img src="thumbnails.php?file={$contact.picturepath}&w=72&h=73" width="72" height="73"/>
						</div>
					</div>
				</a>
                <div class="container-quick-icon">
				<a href="#" onclick="if(confirm('Bist du sicher, dass du deinen Chatpartner aus deiner Chat-Übersicht entfernen willst?')) deleteContact('{$contact.username}'); return false;" class="quick-icon-right del-icon" style=" margin-right:2px;" title="Delete"></a>
			</div>
			</li>
			
		{/foreach}
		</ul>
	</div>
<!-- <div class="container-chat-right">
<div class="container-chat-history" id="messagesArea">

    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f<a href="#">consectetur adipisicing</a></p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f</p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet,dscdscvdsvdvsvd df<br /> sdv sdgvsdv dsv <br />dsv sdv sf sdf s<br /> dsf dsf s sd sd sdfsdfsdf f</p>
    </div>
    
    <div class="container-history-right">
    <h1><strong>boyanoss</strong> [2013-10-24 17:14:04]</h1>
    <p>
    Lorem ipsum dolor sit amet, <a href="#">consectetur adipisicing elit, sed do eiusmod tempor incididunt</a> ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
    </div>
    
    <div class="container-history-left">
    <h1><strong>zerocoolz</strong> [2013-10-24 17:14:04]</h1>
    <p>Lorem ipsum dolor sit amet,dscdscvdsvdvsvd</p>
    </div>
    
</div>
</div> -->









<script type="text/javascript">
var crc={$crc};
</script>