<div class="container-box-content">
<h1 class="title">BESTELLUNG</h1>
    <div style="line-height:20px; width:auto; height:auto; margin:10px; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;">
        <h5 class="title">DETAILS IHRER BESTELLUNG:</h5>
        <div style="margin:30px auto; width:500px;">
            <div class="package-box radius-left">{$package.price|number_format:2:".":","} {$smarty.const.CURRENCY}</div>
            <div class="package-box radius-right">{$package.coin_amount} Coins</div>
            <br class="clear" />
        </div>
        <div style="margin:0 0 10px 10px; padding:10px 0; float:left; height:auto;">
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
			{if in_array(ceil($package.price), array(10, 20, 50, 100))}
			<a href="#" id="button_Ukash"></a>
			{/if}
			{if $package.price lt 100}
			<a href="#" id="button_Ccbill"></a>
			{/if}
			{if $smarty.session.sess_id eq 1}
			<a href="#" id="button_Paysafecard"></a>
			{/if}
			<!-- <script language="JavaScript" src="https://secure.worldpay.com/wcc/logo?instId={$smarty.const.WORLDPAY_INSTID}"></script> -->
		</div>
        
		<br class="clear" />
        
    </div>
    <div style="float:left; width:150px; height:30px; margin-left:10px; margin-bottom:10px;"> 
    <a href="?action=pay-for-coins" class="btn-submit">back</a>
    </div>
    <br class="clear" />
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
				alert(result);
					//jQuery("#UTID").val(result);
					//jQuery('#ukashForm').submit();
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