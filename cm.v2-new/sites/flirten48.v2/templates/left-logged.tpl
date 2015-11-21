<!-- {$smarty.template} -->
<div class="container-welcome">
	<!-- -->
    <div class="title">
    	<div class="title-left"></div><h1>Hallo! {$smarty.session.sess_username}</h1><div class="title-right"></div>
    </div>
    <ul class="container-my-profile">
    	<li>
        	<img src="thumbnails.php?file={$MyPicture}&w=102&h=103" width="102" height="102" />
            <a href="?action=profile" class="link-profile{if $profile.approval} link-profile-approval{/if}"></a>
        </li>
     </ul>
     <div class="container-your-coins">
     	<h2>Sie haben!<br /><strong style="color:#ff0000;" id="coinsArea">0</strong> coins</h2>
     </div>
     
     <div class="container-gif">
     <p><strong>Erste Kontaktanzeige: </strong><br />Einfach mal Leute kennen lernen. Und nicht so einen Facebook Kinderkram :) Wer weiss? Vielleicht finde ich hier den richtigen</p>
     
     
     {if $user_gifts}
     <strong> Your Gifts:</strong><br />
     <ul class="container-gif">
     	{foreach from=$user_gifts item=gift}
 			<li><a href="?action=profile#my_gifts"><img src="{$gift.image_path}" width="30" height="30" /></a></li>
     	{/foreach}
 	 </ul>
     {/if}
     	
     
     
     {if $recent_contacts}
     <strong> Letzte Nachrichten:</strong><br />
     <ul class="container-gif">
     	{foreach from=$recent_contacts item="item"}
     	<li><a href="?action=viewprofile&username={$item.username}"><img src="thumbnails.php?file={$item.picturepath}&w=30&h=30" width="30" height="30" /></a></li>
     	{/foreach}
     </ul>
     {/if}
     <br class="clear" />
     </div>
    <!-- -->
</div>
<!-- end {$smarty.template} -->