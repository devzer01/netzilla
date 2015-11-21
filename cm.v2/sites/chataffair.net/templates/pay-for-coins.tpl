<!-- {$smarty.template} -->
<div class="container-box-content">
<h1 class="title">{#I_WANT_PAY_COINS#}</h1>
	<div style="margin:20px 0;">
	{#Coin_Text_Line1#}<br /><br />{#Coin_Text_Line2#}
	</div>
	<div class="container-box-payforcoins">
	{if $5coinspackage neq ""}
	<a href="?action=payment&package_id={$5coinspackage.id}" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p1.png) no-repeat; width:371px; height:176px;">
		
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			{$5coinspackage.currency_price} {$rcurrency.value}
			</div>
		   
			<div class="fontright">
			{$5coinspackage.coin} Coins
			</div>
		</div>  
	</div>
	</a>
	{/if}
	{foreach from=$coinpackage item=package name="coinpackages"}
	<a href="?action=payment&package_id={$package.id}" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p{$smarty.foreach.coinpackages.index+$start_package}.png) no-repeat; width:371px; height:176px;">
		
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			{$package.currency_price} {$rcurrency.value}
			</div>
		   
			<div class="fontright">
			{$package.coin} Coins
			</div>
		</div> 
	</div>
	</a>
	{/foreach}
	</div>

<br class="clear" />
</div>