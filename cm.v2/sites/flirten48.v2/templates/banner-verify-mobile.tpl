{if $smarty.const.COIN_VERIFY_MOBILE gt 0}
{if !$mobile_verified}
<a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/bannere-mobile.png"/></a>
{/if}
{/if}