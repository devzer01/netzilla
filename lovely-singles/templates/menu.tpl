<div class="menubarcon">
	<a href="./">{#START_SITE#}</a>
    <a href="?action=search_gender&amp;wsex=m&amp;sex=w">{#MAN_SEARCH_WOMAN#}</a>
    <a href="?action=search_gender&amp;wsex=w&amp;sex=m">{#WOMAN_SEARCH_MAN#}</a>
	<a href="?action=search_gender&amp;wsex=m&amp;sex=m">{#MAN_SEARCH_MAN#}</a>
	<a href="?action=search_gender&amp;wsex=w&amp;sex=w">{#WOMAN_SEARCH_WOMAN#}</a>

	{if $smarty.session.sess_username}
		<span><a href="?action=pay-for-coins"><img src="images/coinstack.png" style="float:left; margin:8px 5px 0 0; border:none;" alt="" /></a><span id="coinsArea" style="padding: 0px">0</span>
		</span>
        {else}
         <div style="width:230px; height:50px; float:right; margin-right:20px; margin-top:3px;">
    <a href="?action=register&amp;type=membership" class="topmenu-register">{#Register#}</a>
    </div>
	{/if}
   
</div>
<script>
{literal}
function coinsBalance()
{
	jQuery.ajax(
	{
		type: "GET",
		url: "?action=chat&type=coinsBalance",
		success:(function(result)
			{
				jQuery('#coinsArea').text(result);
			})
	});
}

jQuery(function() {
	coinsBalance();
});
{/literal}
</script>