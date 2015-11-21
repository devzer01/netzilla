<h1>Online</h1>
<ul id='online-result-container' class="container-profile-border"></ul>
<br class="clear">
<script>
{literal}
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchOnline", "total": {/literal}{$total}{literal}}, function(data){jQuery('#online-result-container').parent().show();if(data){ jQuery('#online-result-container').html(data)}else{jQuery('#online-result-container').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
	return false;
	});
{/literal}
</script>