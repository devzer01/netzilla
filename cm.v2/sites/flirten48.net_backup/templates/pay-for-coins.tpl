<!-- {$smarty.template} -->
<section>
    <div class="container-news-bg">
        <div class="container-profile">
        <!-- -->
            <section>
				<div class="container-content-coins">
                <h1>Coins</h1>
                 <p>Hier kannst du dein Online-Konto bei uns mit Coins aufladen! Coins sind die Währung, mit der du bei uns Nachrichten in deinem Online-Postfach und per SMS bezahlen kannst, um mit unseren Mitgliedern in Kontakt zu treten. Es stehen dir hier diverse Pakete zur Aufladung deines Coin-Kontos bei uns zur Verfügung.

Lade also noch heute dein persönliches Coin-Konto bei uns auf, denn sicherlich freuen sich unsere Mitglieder schon auf deine Nachricht!</p>
                </div>
            	
                <br class="clear" />
                
			</section>

        </div>

        </div>
    </section>
    
    
        <ul class="container-package">
			{if $smarty.session.payment_admin}
				<li>
				<div style="background: url(images/pay-for-coin-btn-bg-p2.png) no-repeat; width:371px; height: 100%; padding: 70px; ">
					<form action="" method="get">
						
							<input type="hidden" name="action" value="payment"/>
							<input type="hidden" name="package_id" value="0"/>
							<input type="text" name="price" value="10" style="width: 20px"/> {$rcurrency.value}
						
				   
					
						<input type="text" name="coins" value="{$smarty.const.COIN_EMAIL}" style="width: 20px"/> Coins
						<input type="submit" value="Pay"/>
					
				</form>
				</div>
				</li>
			{/if}
 			{if $trialPackage}
			<li><a style="background-image: url(images/pay-for-coin-btn-bg-p1.png);" href="?action=payment&package_id={$trialPackage.id}"><span class="left-text-pack">{$trialPackage.currency_price} {$rcurrency.value}</span> <span>{$trialPackage.coin} Coins</span></a></li>
			{/if}            
        	{foreach from=$coinpackage item=package name="coinpackages"}
        		{if $package.paypal}
					<li><a style="background-image: url(images/pay-for-coin-btn-bg-p{$package.id}.png);" href="#" onclick="payWithPaypal({$package.id}); return false;"><span class="left-text-pack">{$package.currency_price} {$rcurrency.value}</span> <span>{$package.coin} Coins</span></a></li>
				{else}
	            	<li><a style="background-image: url(images/pay-for-coin-btn-bg-p{$package.id}.png);" href="?action=payment&package_id={$package.id}"><span class="left-text-pack">{$package.currency_price} {$rcurrency.value}</span> <span>{$package.coin} Coins</span></a></li>
	            {/if}
	        {/foreach}
            <br class="clear" />
        </ul>



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