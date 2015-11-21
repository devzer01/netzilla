<!-- {$smarty.template} -->
<h1>Random Contact</h1>
<ul id='random-contacts-container' class="container-profile-border"></ul>
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