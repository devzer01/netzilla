
<span id='newest-result-container'></span>
<script>
{literal}
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchNewestMembers"{/literal}{if $total}, "total": {$total}{/if}{literal}}, function(data){jQuery('#newest-result-container').parent().show();if(data){ jQuery('#newest-result-container').html(data)}else{jQuery('#newest-result-container').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
	return false;
	});
{/literal}
</script>