<div class="container-metropopup">
    <div class="container-select-coins">
    	<h1 class="title">Gifts</h1>
    	<ul class="container-select-gifts">
			{foreach from=$list_gifts item=emoticon name=emoticons}
				<li>
			    	<a class="gftcons" data-text="{$emoticon.text_version}" href="#" title='{$emoticon.text_version}' onclick="confirmAttachmentGift({$emoticon.id}); return false"><img id='gift_{$emoticon.id}' src="{$emoticon.image_path}" height="50" width="50" />
			    	<p>{$emoticon.coins} coins</p></a>
			    </li>
			{/foreach}
			
			<br class="clear" />
		</ul>
    </div>
</div>