<ul class="container-menu">
    <li><a href="./" class="home"><span>home</span></a></li>
	<li><a href="?action=search" class="search"><span>search</span></a></li>
	{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
		{if !$smarty.session.sess_externuser} 
			{include file="left-membership_islogged.tpl"}
		{/if}
	{/if}
</ul>


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
	{/literal}
	{if $smarty.const.USERNAME_CONFIRMED}
	coinsBalance();
	{/if}
	{literal}
});
{/literal}
</script>