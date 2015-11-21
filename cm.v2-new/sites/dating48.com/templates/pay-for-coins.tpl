<!-- {$smarty.template} -->
<div id="container-content">
    <h1>{#I_WANT_PAY_COINS#}</h1>
    <div id="container-content-profile-home">
        <div style="line-height:20px; width:auto; float:left; margin:10px 10px 10px 10px; border:2px solid #9ad3ff; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:url(images/cm-theme/bg-box_03.png) #FFF repeat-x; padding:10px;">{#Coin_Text_Line1#}<br /><br />{#Coin_Text_Line2#}</div>
    
        <div style="margin:0 0 10px 10px; padding:15px; float:left; width:850px; -webkit-border-radius: 15px; -moz-border-radius: 15px; border-radius: 15px; height:auto;">
        {foreach from=$coinpackage item=package name="coinpackages"}
        <a href="?action=payment&package_id={$package.id}" style="display:block; width:371px; height:176px; float:left;  text-decoration:none; margin-left:40px; margin-bottom:20px;">
        <div style="background:url(images/pay-for-coin-btn-bg-p{$smarty.foreach.coinpackages.index+$start_package}.png) no-repeat; width:371px; height:176px;">
            
            <div style="margin-left:70px; padding-top:60px; float:left; text-align:center;">
                <div style="font-size:18px; color:#fdbe00; font-weight:bold; float:left; text-shadow: 1px 1px #000;">
                {$package.currency_price} {$rcurrency.value}
                </div>
               
                <div style="font-size:18px; color:#fdbe00; font-weight:bold; float:left; margin-left:20px; text-shadow: 1px 1px #000;">
                {$package.coin} Coins
                </div>
            </div>
            
        </div>
        </a>
        {/foreach}
        </div>
    </div>
</div>