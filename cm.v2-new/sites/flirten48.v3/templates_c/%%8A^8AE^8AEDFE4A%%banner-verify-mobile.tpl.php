<?php /* Smarty version 2.6.14, created on 2013-11-19 16:29:25
         compiled from banner-verify-mobile.tpl */ ?>
<?php if (@COIN_VERIFY_MOBILE > 0): ?>
<?php if (! $this->_tpl_vars['mobile_verified']): ?>
<a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/bannere-mobile.png"/></a>
<?php endif; ?>
<?php endif; ?>