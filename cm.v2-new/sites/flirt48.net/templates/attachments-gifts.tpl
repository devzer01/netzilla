<div class="container-metropopup">
    <div class="container-select-coins">
    	<h2>Stickers</h2>
    	<h3>Upon clicking a sticker, it will be sent and you will be charged the amount of coins displayed next to the sticker.</h3>
		
		{foreach from=$category item=cat name=catloop}
			<div style='float: left;'>
				<h4>{$cat.name}</h4>
				<ul>
			{section loop=$list[$cat.id] name=giftloop}
				<li><a href="#" onclick="addAttactmentGifts({$list[$cat.id][giftloop].id}); return false"><img src="../{$list[$cat.id][giftloop].image_path}" /></a> {$list[$cat.id][giftloop].coins}</li>
			{/section}
				</ul>
			</div>
		{/foreach}
    	<br class="clear" />
    </div>
</div>