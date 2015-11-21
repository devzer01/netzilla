<span id='search-result-container'></span>
<script>
{literal}
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchOnline", "total": {/literal}{$total}{literal}}, function(data){jQuery('#search-result-container').parent().show();if(data){ jQuery('#search-result-container').html(data)}else{jQuery('#search-result-container').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
	return false;
	});
{/literal}
</script>