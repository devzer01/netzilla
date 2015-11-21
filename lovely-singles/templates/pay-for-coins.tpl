<div class="result-box">
<h1>{#I_WANT_PAY_COINS#}</h1>
<div class="result-box-inside">
<div id="coins-table">
<div style="width:100%; text-align:center; margin-bottom:10px;">
{if $smarty.get.type eq 'not-enough'}
<img src="images/coin-empty-{$smarty.session.lang}.jpg" width="748" height="135" />
{else}
<img src="images/coin-banner-{$smarty.session.lang}.jpg" width="748" height="135" />
{/if}
</div>
<div style="width:748px; margin:0 auto; display:block; height:auto;">
{#Coin_Text_Line1#}<br /><br />
{#Coin_Text_Line2#}
</div>

<div id="wrapcoin">
<div style="width:745px; height:auto; margin-top:10px; margin-bottom:5px; float:left;">
	{foreach from=$coinpackage item=package name="coinpackages"}
	{if $package.paypal eq ""}
		<a href="?action=cart&id={$package.id}&redirect=1" style="display:block; width:371px; height:176px; float:left;  text-decoration:none;">
		<div style="background:url(images/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat; width:371px; height:176px;">
			<div style="margin-left:130px; padding-top:33px; float:left;">
				<div style="font-size:20px; color:#000; font-weight:bold; float:left;">
				{$package.currency_price} {$rcurrency.value}
				</div>
			   
				<div style="font-size:20px; color:#000; font-weight:bold; float:left; margin-left:20px;">
				{$package.coin} Coins
				</div>
			</div>
			<br class="clear" />
			<div style="float:left; color:#ef6f03; padding:38px 0 0 170px; font-weight:bold; font-size:16px;">
				<!--<span>20</span>{if $package.currency_price ne '30'} <span style="margin-left:110px;">50</span>{/if} -->
			</div>
		</div>
		</a>
	{else}
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="{$package.paypal}">
		<input type="hidden" name="on0" value="Dein Nutzername:">
		<input type="hidden" name="os0" value="{$smarty.session.sess_username}" readonly="readonly">
		<a href="#" style="display:block; width:371px; height:176px; float:left;  text-decoration:none;" onclick="jQuery(this).parent().submit(); return false;">
		<div style="background:url(images/pay-for-coin-btn-bg-p{$package.id}.png) no-repeat; width:371px; height:176px;">
			<div style="margin-left:130px; padding-top:33px; float:left;">
				<div style="font-size:20px; color:#000; font-weight:bold; float:left;">
				{$package.currency_price} {$rcurrency.value}
				</div>
			   
				<div style="font-size:20px; color:#000; font-weight:bold; float:left; margin-left:20px;">
				{$package.coin} Coins
				</div>
			</div>
			<br class="clear" />
		</div>
		</a>
		</form>
	{/if}
	{/foreach}
</div>
<br class="clear"/>
<div style="text-align: center">
	<a href="?action=chat&username=System Admin"><img src="images/btn-payment-alternatives.png"/></a>
</div>
</div>

</div>
</div>
<br class="clear" />
</div>