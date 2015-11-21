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
<a id="gftclose" class="close-gift-box"></a>
<ul class="container-emoticons">
{foreach from=$list_gifts item=emoticon name=emoticons}
	<li>
    <a class="gftcons" data-text="{$emoticon.text_version}" href="#" title='{$emoticon.text_version}' onclick="addAttactmentGifts({$emoticon.id}); return false"><img src="{$emoticon.image_path}" height="50" width="50" /></a>
    </li>
{/foreach}

<br class="clear" />
</ul>
