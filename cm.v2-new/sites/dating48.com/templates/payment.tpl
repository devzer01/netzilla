<div id="container-content">

<h1>BESTELLUNG</h1>

    
        <h2 style="display:block; text-align:center; margin-top:20px;"><strong>DETAILS IHRER BESTELLUNG:</strong></h2>
        <div style="margin:30px auto; width:500px;">
            <div class="package-box radius-left">{$package.price} {$smarty.const.CURRENCY}</div>
            <div class="package-box radius-right">{$package.coin_amount} Coins</div>
            <br class="clear" />
        </div>
        <div style="margin:0; padding:0 5px 0 13px; float:left; height:auto;">
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
    		<a href="#" id="button_Worldpay"></a>

			<a href="#" id="button_Ukash"></a>
			<!--<script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId={$smarty.const.WORLDPAY_INSTID}"></script> -->
		</div>
		<br class="clear" />
   


</div>

<script>
{literal}
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
});

function PayWithWorldpay()
{
	jQuery.ajax({
			type: "POST",
			dataType: "json",
			url: '',
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
			url: "",
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
{/literal}
</script>