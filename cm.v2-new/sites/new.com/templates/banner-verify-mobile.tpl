{if $smarty.const.COIN_VERIFY_MOBILE gt 0}
{if !$mobile_verified}
<div style="width:1024px; height:120px; float:left; margin-top:5px"><a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/banner-mobile.png" width="1025" height="121" /></a></div>
<br class="clear"/><br/>
{/if}
{/if}