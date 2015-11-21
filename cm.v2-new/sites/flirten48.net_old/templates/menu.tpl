<!-- {$smarty.template} -->
<div id="container-menu-icon">
    <ul>
    <li><a href="./"><img src="images/cm-theme/icon-home.png"  width="58" height="59" /><br /><span>{#START_SITE#}</span></a></li>
    <li><a href="?action=search"><img src="images/cm-theme/icon-search.png"/><br /><span>{#SEARCH#}</span></a></li>
    
    {if !$smarty.session.sess_externuser} 
    {******************************** left-membership *****************************************}
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    {include file="left-membership_islogged.tpl"}
    {else}
    {/if}{/if}
    
    </ul>
    
    
    {******************************** coin *****************************************}
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    
    {if $smarty.session.sess_username}
   <div id="container-coin">
   <!-- <img src="images/cm-theme/coin.png" width="30"/><br /> -->
   	Sie haben!<br /> <strong>
    <span id="coinsArea" style="padding: 0px">{if $coin}{$coin}{else}0{/if}</span> coins</strong></div>    
    {/if}
    
    {else}
    <a href="?action=register" class="register-btn-top"><span>register</span></a>
    {/if}
    {******************************** End login *****************************************}
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