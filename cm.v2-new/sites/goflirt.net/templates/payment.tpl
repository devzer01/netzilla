<div class="container-content" style="min-height:450px;">
	<h1 class="title">Order details</h1>
	<div class="container-pay-coins">
		<span class="left">{$package.price|number_format:2:".":","} {$package.currency}</span>
		<span class="right">{$package.coin_amount} Coins</span>
	</div>
	<ul class="container-btn-pay-for-coins" style="margin-bottom:10px;">
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
		{if $smarty.const.ENABLE_PAYMENT_PAYMENTWALL eq '1'}
		<li><a href="#" id="button_Paymentwall"><img src="images/cm-theme/payment-page-btn-paymentwall.png" width="433" height="130" /></a></li>
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_WORLDPAY eq '1'}
		<li><a href="#" id="button_Worldpay"><img src="images/cm-theme/payment-page-btn-credit-card.png" width="433" height="130" /></a></li>
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_VEROTEL eq '1'}
		<li><a href="#" id="button_Verotel"><img src="images/cm-theme/payment-page-btn-verotel.png" width="433" height="130" /></a></li>
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_CCBILL_CREDIT eq '1'}
		{if $package.price lt 100}
		<li><a href="#" id="button_Ccbill_Credit"><img src="images/cm-theme/payment-page-btn-CCBillCredit.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_CCBILL_DIRECTPAY eq '1'}
		{if $package.price lt 100}
		<li><a href="#" id="button_Ccbill_Directpay"><img src="images/cm-theme/payment-page-btn-CCBillDirectPay.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_CCBILL_EUDEBIT eq '1'}
		{if $package.price lt 100}
		<li><a href="#" id="button_Ccbill_EUDebit"><img src="images/cm-theme/payment-page-btn-CCBillEUDebit.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_PAYSAFECARD eq '1'}
		{if in_array(ceil($package.price), array(20, 30, 50, 100))}
		<li><a href="#" id="button_Paysafecard"><img src="images/cm-theme/payment-page-btn-paysafecard.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
		{if $smarty.const.ENABLE_PAYMENT_UKASH eq '1'}
		{if in_array(ceil($package.price), array(20, 30, 50, 100))}
		<li><a href="#" id="button_Ukash"><img src="images/cm-theme/payment-page-btn-ukash.png" width="433" height="130" /></a></li>
		{/if}
		{/if}
	</ul>
    <br class="clear" />
</div>

<script>
{literal}
jQuery.ajaxSetup({cache:false})
jQuery(document).ready(function() { assignButton();});

function assignButton()
{
	{/literal}{if $smarty.const.ENABLE_PAYMENT_PAYMENTWALL eq '1'}{literal}
	jQuery("#button_Paymentwall").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithPaymentwall();
		return false;
	});
	{/literal}{/if}{if $smarty.const.ENABLE_PAYMENT_WORLDPAY eq '1'}{literal}
	jQuery("#button_Worldpay").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithWorldpay();
		return false;
	});
	{/literal}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_VEROTEL eq '1'}{literal}
	jQuery("#button_Verotel").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithVerotel();
		return false;
	});
	{/literal}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_UKASH eq '1'}{literal}
	jQuery("#button_Ukash").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithUkash();
		return false;
	});
	{/literal}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_CREDIT eq '1'}
	{if $package.price lt 100}{literal}
	jQuery("#button_Ccbill_Credit").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithCCBillCredit();
		return false;
	});
	{/literal}{/if}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_DIRECTPAY eq '1'}
	{if $package.price lt 100}{literal}
	jQuery("#button_Ccbill_Directpay").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithCCBill();
		return false;
	});
	{/literal}{/if}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_EUDEBIT eq '1'}
	{if $package.price lt 100}{literal}
	jQuery("#button_Ccbill_EUDebit").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithCCBillEUDebit();
		return false;
	});
	{/literal}{/if}{/if}{literal}
	{/literal}{if $smarty.const.ENABLE_PAYMENT_PAYSAFECARD eq '1'}{literal}
	jQuery("#button_Paysafecard").click(function(){
		jQuery(this).unbind();
		jQuery(this).click(function(){return false;});
		PayWithPaysafecard();
		return false;
	});
	{/literal}{/if}{literal}	
}

{/literal}{if $smarty.const.ENABLE_PAYMENT_PAYMENTWALL eq '1'}{literal}
function PayWithPaymentwall()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'Paymentwall'},
			success: function(data) {
				if(data)
				{
					location = data;
				}
			}
		});
}
{/literal}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_WORLDPAY eq '1'}{literal}
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
{/literal}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_VEROTEL eq '1'}{literal}
function PayWithVerotel()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'Verotel'},
			success: function(data) {
				if(data)
				{
					location = data;
				}
			}
		});
}
{/literal}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_UKASH eq '1'}{literal}
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
{/literal}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_DIRECTPAY eq '1'}
{if $package.price lt 100}{literal}
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
{/literal}{/if}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_CREDIT eq '1'}
{if $package.price lt 100}{literal}
function PayWithCCBillCredit()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'CCBillCredit'},
			success: function(data) {
				if(data)
				{
					location = data;
				}
			}
		});
}
{/literal}{/if}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_CCBILL_EUDEBIT eq '1'}
{if $package.price lt 100}{literal}
function PayWithCCBillEUDebit()
{
	jQuery.ajax({
			type: "POST",
			{/literal}url: "?action={$smarty.get.action}&id={$smarty.get.id}",{literal}
			data: { paymentProvider: 'CCBillEUDebit'},
			success: function(data) {
				if(data)
				{
					location = data;
				}
			}
		});
}
{/literal}{/if}{/if}{literal}

{/literal}{if $smarty.const.ENABLE_PAYMENT_PAYSAFECARD eq '1'}{literal}
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
					{
						alert(data.error);
					}
					else
					{
						location = data.url;
					}
				}
			}
		});
}
{/literal}{/if}
</script>