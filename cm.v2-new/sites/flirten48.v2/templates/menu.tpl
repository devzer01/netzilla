<!-- {$smarty.template} -->
<div id="container-menu-icon">
    {******************************** coin *****************************************}
    <!--
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    {if $smarty.session.sess_username}
    <div id="container-coin">
    Sie haben!<br /> <strong>
    <span id="coinsArea" style="padding: 0px">{if $coin}{$coin}{else}0{/if}</span> coins</strong></div>    
    {/if}
    
    {else}
    <a href="?action=register" class="register-btn-top"><span>register</span></a>
    {/if} -->
    {******************************** End coin *****************************************}
    
    <ul>
    <li><a href="./" class="home"><span>{#START_SITE#}</span></a></li>
    <li><a href="?action=search" class="search"><span>{#SEARCH#}</span></a></li>
    
    {if !$smarty.session.sess_externuser} 
    {******************************** left-membership *****************************************}
	    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	    	{include file="left-membership_islogged.tpl"}
	    {else}
	    {/if}
    {/if}
    
    </ul>
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
	{/literal}
	{if $smarty.const.USERNAME_CONFIRMED}
	coinsBalance();
	{/if}
	{literal}
});
{/literal}
</script>