<!-- {$smarty.template} -->
<h1 class="title">{#I_WANT_PAY_COINS#}</h1>
<div id="container-content-back">
	<div class="container-box-content-payforcoins">{#Coin_Text_Line1#}<br /><br />{#Coin_Text_Line2#}</div>

	<div class="container-box-payforcoins">
	{if $smarty.session.payment_admin}
	<span style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
		<div style="background:url(images/pay-for-coin-btn-bg-p2.png) no-repeat; width:371px; height:176px;">
	        <div class="container-payforcoin-font">
				<form action="" method="get">
				<div class="fontLeft">
				<input type="hidden" name="action" value="payment"/>
				<input type="hidden" name="package_id" value="0"/>
				<input type="text" name="price" value="10" style="width: 20px"/> {$rcurrency.value}
				</div>
			   
				<div class="fontright">
				<input type="text" name="coins" value="{$smarty.const.COIN_EMAIL}" style="width: 20px"/> Coins
				<input type="submit" value="Pay"/>
				</div>
				</form>
			</div>  
		</div>
	</span>
	{/if}
	{if $trialPackage}
	<a href="?action=payment&package_id={$trialPackage.id}" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p{$trialPackage.id}.png) no-repeat; width:371px; height:176px;">
		
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			{$trialPackage.currency_price} {$rcurrency.value}
			</div>
		   
			<div class="fontright">
			{$trialPackage.coin} Coins
			</div>
		</div>  
	</div>
	</a>
	{/if}
	{foreach from=$coinpackage item=package name="coinpackages"}
	{if $package.paypal}
	<a href="#" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;" onclick="payWithPaypal({$package.id}); return false;">
	<div style="background:url(images/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat; width:371px; height:176px;">
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			&nbsp;&nbsp;{$package.currency_price} {$rcurrency.value}
			</div>
		   
			<div class="fontright">
			&nbsp;&nbsp;{$package.coin} Coins
			</div>
		</div> 
	</div>
	</a>
	{else}
	<a href="?action=payment&package_id={$package.id}" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:50px; margin-bottom:20px;">
	<div style="background:url(images/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat; width:371px; height:176px;">
        <div class="container-payforcoin-font">
			<div class="fontLeft">
			&nbsp;&nbsp;{$package.currency_price} {$rcurrency.value}
			</div>
		   
			<div class="fontright">
			&nbsp;&nbsp;{$package.coin} Coins
			</div>
		</div> 
	</div>
	</a>
	{/if}
	{/foreach}

	</div>
</div>

<script>
{literal}
jQuery.ajaxSetup({cache:false})

function payWithPaypal(id)
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action=payment&id="+id,{literal}
			data: { paymentProvider: 'Paypal'},
			success:(function(result) {
				if(result)
				{
					window.location=result;
				}
				else
				{
					alert("Failed");
				}
			})
		});
}
{/literal}
</script>