<div class="title">
	<div class="title-left"></div><h1>Kontaktvorschläge</h1><div class="title-right"></div>
</div>
<span id='random-contacts-container'></span>


<script>
{literal}
jQuery(function()
{
	jQuery.get(
			"",
			{"action": "random-contacts", "total": {/literal}{$total}{literal}},
			function(data)
				{
					if(data)
					{
						jQuery('#random-contacts-container').html(data);
					}
				}
			);
	return false;
}
);
{/literal}
</script>