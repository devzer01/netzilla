<!-- {$smarty.template} -->
<h1>Random Contact</h1>
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