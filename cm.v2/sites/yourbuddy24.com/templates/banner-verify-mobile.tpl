{if $smarty.const.COIN_VERIFY_MOBILE gt 0}
{if !$mobile_verified}
<div style="float:left;"><a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/bannere-mobile.png" width="650" height="132"/></a></div>
<br class="clear" />
{/if}
{/if}