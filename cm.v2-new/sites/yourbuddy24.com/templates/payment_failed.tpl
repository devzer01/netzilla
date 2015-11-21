<!-- {$smarty.template} -->
<div class="container-content">
	<h1 class="title">Payment failed</h1>
	<div class="container-box-content-03">
		<div class="box-content-01-t-l"></div>
		<div class="box-content-01-t-m" style="width:900px !important;"></div>
		<div class="box-content-01-t-r"></div>
		<div class="box-content-03-m">
			{if $payment_failed_message}
				{$payment_failed_message}
			{else}
				The payment was not successful!
			{/if}
		</div>
		<div class="box-content-01-b-l"></div>
		<div class="box-content-01-b-m" style="width:900px !important;"></div>
		<div class="box-content-01-b-r"></div>
	</div>
</div>