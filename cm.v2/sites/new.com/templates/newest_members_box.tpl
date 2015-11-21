<div class="container-content-box">
	<h1>{#Newest_main#}</h1>
	<ul id="newest-result-container" class="container-profile-border">
	</ul>
</div>
<script>
{literal}
jQuery(function(){
	jQuery.get("",{"action": "search", "type": "searchNewestMembers"{/literal}{if $total}, "total": {$total}{/if}{literal}}, function(data){jQuery('#newest-result-container').parent().show();if(data){ jQuery('#newest-result-container').html(data)}else{jQuery('#newest-result-container').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
	return false;
	});
{/literal}
</script>