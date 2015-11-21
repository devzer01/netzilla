<div class="container-content">
	<h1 class="title">Order details</h1>
	<div class="container-pay-coins">
		<span class="left">{$package.price|number_format:2:".":","} {$package.currency}</span>
		<span class="right">{$package.coin_amount} Coins</span>
	</div>
	<ul class="container-btn-pay-for-coins">
		{if $smarty.const.WORLDPAY_TEST_MODE eq 1}
		<form action="https://secure-test.worldpay.com/wcc/purchase" id="worldpayForm" method="POST">
			<input type="hidden" name="testMode" value="100">
		{else}
		<form action="https://secure.worldpay.com/wcc/purchase" id="worldpayForm" method="POST">
			<input type="hidden" name="testMode" value="0">
		{/if}
			<input type="hidden" name="instId" value="{$smarty.const.WORLDPAY_INSTID}">
			<input type="hidden" name="cartId" value="{$smarty.get.id}">
			<input type="hidden" name="currency" value="{$package.currency}">
			<input type="hidden" name="amount" value="{$package.price}">
			<input type="hidden" name="desc" value="{$package.price} {$package.currency} for {$package.coin_amount} coins">
		</form>
		<form action="https://direct.ukash.com/hosted/entry.aspx" name="ukashForm" id="ukashForm" method="post">
			<input type="hidden" name="UTID" id="UTID" value=""/>
		</form>
		{if $smarty.const.ENABLE_PAYMENT_WORLDPAY}
		<li><a href="#" id="button_Worldpay"><img src="images/cm-theme/payment-page-btn-credit-card.png" width="433" height="130" /></a></li>
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_UKASH}
		{if in_array(ceil($package.price), array(20, 30, 50, 100))}
		<li><a href="#" id="button_Ukash"><img src="images/cm-theme/payment-page-btn-ukash.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_CCBILL}
		{if $package.price lt 100}
		<li><a href="#" id="button_Ccbill"><img src="images/cm-theme/payment-page-btn-giropay.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_PAYSAFECARD}
		{if in_array(ceil($package.price), array(20, 30, 50, 100))}
		<li><a href="#" id="button_Paysafecard"><img src="images/cm-theme/payment-page-btn-paysafecard.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_VEROTEL}
		<li><a href="#" id="button_Verotel"><img src="images/cm-theme/payment-page-btn-verotel.png" width="433" height="130" /></a></li>
		{/if}
	</ul>
</div>

<script>
{literal}
jQuery.ajaxSetup({cache:false})
jQuery(document).ready(function() {
	jQuery("#button_Worldpay").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithWorldpay();
		return false;
	});
	jQuery("#button_Ukash").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithUkash();
		return false;
	});
	jQuery("#button_Ccbill").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithCCBill();
		return false;
	});
	jQuery("#button_Paysafecard").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithPaysafecard();
		return false;
	});
});

function PayWithWorldpay()
{
	jQuery.ajax({
			type: "POST",
			dataType: "json",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'Worldpay'},
			success: function(data) {
				if(data)
				{
					jQuery.each(data, function(key, val) {
						jQuery('#worldpayForm input[name='+key+']').val(val);
					});
					jQuery('#worldpayForm').submit();
				}
			}
		});
}

function PayWithUkash()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'Ukash'},
			success:(function(result) {
				if(result)
				{
					jQuery("#UTID").val(result);
					jQuery('#ukashForm').submit();
				}
			})
		});
}

function PayWithCCBill()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'CCBill'},
			success: function(data) {
				if(data)
				{
					location = data;
				}
			}
		});
}

function PayWithPaysafecard()
{
	jQuery.ajax({
			type: "POST",
			dataType: "json",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'Paysafecard'},
			success: function(data) {
				if(data)
				{
					if(data.error)
						alert(data.error);
					else
						location = data.url;
				}
			}
		});
}

{/literal}
</script>