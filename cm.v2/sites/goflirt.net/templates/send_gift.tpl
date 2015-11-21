<script type='text/javascript'>
{literal}
	jQuery(function(e) {
		jQuery(".gftcons").unbind('click').click(function (e) {
			e.preventDefault();
			jQuery("#sms").val(jQuery("#sms").val() + jQuery(this).attr('data-text'));
			jQuery("#iconlist").fadeOut();
		});
		jQuery("#gftclose").click(function (e) {
			e.preventDefault();
			jQuery("#iconlist").fadeOut();
		});
	});
{/literal}
</script>
<a id="gftclose" class="close-gift-box"><span>Close</span></a>
<ul class="container-emoticons">
{foreach from=$list_gifts item=emoticon name=emoticons}
	<li>
    	<a class="gftcons" data-text="{$emoticon.text_version}" href="#" title='{$emoticon.text_version}' onclick="addAttactmentGifts({$emoticon.id}, {$emoticon.coins}); return false"><img id='gift_{$emoticon.id}' src="{$emoticon.image_path}" height="50" width="50" /></a>
    	[{$emoticon.coins}]
    </li>
{/foreach}

<br class="clear" />
</ul>
