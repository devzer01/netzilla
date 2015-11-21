{if $smarty.const.COIN_VERIFY_MOBILE gt 0}
{if !$mobile_verified}
<div style="width:950px; height:100px; float:left;">
<a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/bannere-mobile.png"/></a></div>
<br class="clear" />
{/if}
{/if}