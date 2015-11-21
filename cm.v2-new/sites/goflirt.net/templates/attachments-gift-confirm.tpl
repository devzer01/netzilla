<div class="container-metropopup">
    <div class="container-select-coins">
    	<h1 class="title">Gifts</h1>
        <div class="container-gift-confirm">
            <p>Möchtest du dieses Geschenk für {$gift.coins} Coins senden?</p>
            <span><img src="{$gift.image_path}" height="50" width="50" /></span>
            <div class="contianer-yn-gift">
            <a href="#" onclick='addAttactmentGifts({$gift.id}, {$gift.coins});' data-id='{$gift.id}' data-coins='{$gift.coins}' id='giftyes' class="btn-search" style="width:80px;">Yes</a> 
            <a href="#" id='giftno' class="btn-search" style="width:80px;">No</a> 
            </div>
        </div> 
        <br class="clear" />
    </div>
</div>

<script type='text/javascript'>
{literal}
	jQuery(function () {
		console.log("test");
		jQuery("#giftno").click(function (e) {
			e.preventDefault();
			jQuery('#attachments-list').html("");
			jQuery('#profilePopup').hide();
			jQuery('#mask').hide();
		});		
	});
{/literal}
</script>