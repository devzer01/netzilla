
<ul class="container-top-menu">
	<li class="topmenu-left"><a href="." class="menu-home"><span>{#START_SITE#}</span></a></li>
    <li class="topmenu-left"><a href="?action=search" class="menu-search"><span>{#SEARCH#}</span></a></li>
    <li class="topmenu-left"><a href="?action=profile" class="menu-profile"><span>Profil</span></a></li>

    <li class="topmenu-right"><a href="?action=logout" class="menu-logout"><span>Logout</span></a></li>
    <li class="topmenu-right"><a href="?action=pay-for-coins" class="menu-coins"><span>Coins</span></a></li>
    <li class="topmenu-right"><a href="?action=chat" class="menu-chat"><span>Nachrichten</span></a></li> 
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