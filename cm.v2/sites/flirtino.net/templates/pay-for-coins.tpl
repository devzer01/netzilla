<div class="container-content-02">
	<h1>{#I_WANT_PAY_COINS#}</h1>
	<div class="content-page">{#Coin_Text_Line1#}<br /><br />{#Coin_Text_Line2#}</div>
	<ul class="container-coins-btn">
		{if $smarty.session.payment_admin}
		<li>
			<div style="background:url(images/cm-theme/pay-for-coin-btn-bg-p2.png) no-repeat; width:371px; height:176px;">
				<div style="display:block; width:321px; height:116px; margin-left: 50px; margin-top: 60px; float:left;  text-decoration:none;">
				<form action="" method="get">
					<input type="hidden" name="action" value="payment"/>
					<input type="hidden" name="package_id" value="0"/>
					<input type="text" name="price" value="10" style="width: 20px"/> {$rcurrency.value}
					<input type="text" name="coins" value="{$smarty.const.COIN_EMAIL}" style="width: 20px"/> Coins
					<input type="submit" value="Pay"/>
					</form>
				</div>
			</div>
		</li>
		{/if}
		{if $trialPackage}
			<li>
				<a href="?action=payment&package_id={$trialPackage.id}" style=" background:url(images/cm-theme/pay-for-coin-btn-bg-p{$trialPackage.id}.png) no-repeat;">
				<span class="left-text-pack">{$trialPackage.currency_price} {$rcurrency.value}</span> <span>{$trialPackage.coin} Coins</span></a>
			</li>
		{/if}
		{foreach from=$coinpackage item=package name="coinpackages"}
		{if $package.paypal}
			<li>
				<a href="#" onclick="payWithPaypal({$package.id}); return false;" style=" background:url(images/cm-theme/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat;">
				<span class="left-text-pack">{$package.currency_price} {$rcurrency.value}</span> <span>{$package.coin} Coins</span></a>
			</li>
		{else}
			<li>
				<a href="?action=payment&package_id={$package.id}" style=" background:url(images/cm-theme/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat;">
				<span class="left-text-pack">{$package.currency_price} {$rcurrency.value}</span> <span>{$package.coin} Coins</span></a>
			</li>
		{/if}
		{/foreach}
	</ul>
	</div>
	<br class="clear" />
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