<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

{include file="top.tpl"}

<body>
    <div id="menu-top">
        <nav style="width:950px; margin:0 auto;">
        {******************************** coin *****************************************}
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    
		{if $smarty.session.sess_username}
        <ul style="color:#ff9c00; width:180px; float:right; margin-top:25px; font-size:16px;">
		<li>
			<div id="container-coin">Sie haben! <strong>
				<span id="coinsArea" style="padding: 0px;">{if $coin}{$coin}{else}0{/if}</span> coins</strong>
			</div>    
		</li>
        </ul>
		{/if}

	{/if}
        </nav>
    </div>
   
<div id="warper">
    <header>
        <div id="container-logo"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div>
        
<!--left menu -->
        <div class="scroll">
            <div class="container-scroll">
            	<nav id="left-menu">
                	<ul>
                    	
						{if !$smarty.session.sess_externuser}
							{include file="menu.tpl"}
						{/if}

                    </ul>
                </nav>
               <div class="container-banner"><img src="images/cm-theme/banner-01.png" width="282" height="203" /></div>
                 <!--<div class="container-banner"><img src="images/cm-theme/banner-02.png" width="282" height="134" /></div>
                <div class="container-banner"><img src="images/cm-theme/banner-02.png" width="282" height="134" /></div>
                <div class="container-banner">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</div> -->
                
            </div>
    	</div>
<!--end left menu --> 
     
    </header>
    
    <div id="container-main-content">
    	<div class="main-content">       
			
			{************************************* Start body *************************************}
{if $section eq "blank"}
	{include file="blank.tpl"}
{elseif $section eq "blank_membership"}
	{include file="membership_1.tpl"}
{elseif $section eq "blank_alert"}
	{include file="blank_alert.tpl"}
{elseif $section eq "okay_message"}
	{include file="payment_okay.tpl"}
{elseif $section eq "failed_message"}
	{include file="payment_failed.tpl"}
{else}
	{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}
		{if $smarty.get.action eq "adsearch"}
			{include file="adsearch.tpl"}
		{elseif $smarty.get.action eq "testpay"}
			{include file="payment_1.tpl"}
		{elseif $smarty.get.action eq "payportal1"}
			{include file="payment_1.tpl"}
		{elseif $smarty.get.action eq "pay-for-coins"}
			{include file="pay-for-coins.tpl"}
		{elseif $smarty.get.action eq "terms"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "terms-2"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "policy"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "policy-2"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "refund"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "refund-2"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "imprint"}
			{include file="content.tpl"}
		{elseif $smarty.get.action eq "webcam"}
			{include file="cam_default.tpl"}
		{elseif $smarty.get.action eq "membership"}
			{if $smarty.get.do eq "delete"}
				{include file="membership_delete.tpl"}
			{else}
				{include file="membership_1.tpl"}
			{/if}
		{elseif $smarty.get.action eq "membershipfront"}
			{include file="membershipfront.tpl"}
		{elseif $smarty.get.action eq "payportal"}
			{include file="membership_1.tpl"}
		{elseif $smarty.get.action eq "evnValidation"}
			{include file="membership_validation.tpl"}
		{elseif $smarty.get.action eq "birthday"}
			{include file="birthday.tpl"}
		{elseif $smarty.get.action eq "incompleteinfo_skip"}
			{include file="incompleteinfo_skip.tpl"}
		{elseif $smarty.get.action eq "mobileverify_skip"}
			{include file="mobileverify_skip.tpl"}
		{elseif $smarty.get.action eq "editprofile"}
			{include file="editprofile.tpl"}
		{elseif $smarty.get.action eq "changepassword"}
			{include file="changepassword.tpl"}
		{elseif $smarty.get.action eq "faqs"}
			{include file="faqs.tpl"}
		{elseif $smarty.get.action eq "favorite"}
			{include file="favorite.tpl"}
		{elseif ($smarty.get.action eq "fotoalbum") or ($smarty.get.action eq "fotoalbum_view")}
			{include file="fotoalbum.tpl"}
		{elseif $smarty.get.action eq "lonely_heart_ads"}
			{if $smarty.get.do eq "edit"}
				{include file="lonelyHeart_edit.tpl"}
			{elseif $smarty.get.do eq "write"}
				{include file="lonelyHeart_write.tpl"}
			{elseif $smarty.get.do eq "view"}
				{include file="lonelyHeart_view.tpl"}
			{elseif $smarty.get.do eq "search"}
				{include file="lonelyHeart_search.tpl"}
			{else}
				{include file="lonelyHeart.tpl"}
			{/if}
		{elseif $smarty.get.action eq "lonely_heart_ads_view"}
			{if $smarty.get.do eq "view"}
				{include file="lonelyHeart_view.tpl"}
			{elseif $smarty.get.do eq "write"}
				{include file="lonelyHeart_write.tpl"}
			{else}
				{include file="lonelyHeart.tpl"}
			{/if}
		{elseif $smarty.get.action eq "mymessage"}
			{include file="mymessage.tpl"}
		{elseif $smarty.get.action eq "newest"}
			{if $smarty.get.new eq "f"}
				{include file="main-women.tpl"}
			{elseif $smarty.get.new eq "m"}
				{include file="main-men.tpl"}
			{elseif $smarty.get.new eq "p"}
				{include file="main-paar.tpl"}
			{/if}
		{elseif ($smarty.get.action eq "register") and ($smarty.get.type eq "upgrade")}
			{include file="upgrade.tpl"}
		{elseif $smarty.get.action eq "incompleteprofile"}
			{include file="incompleteprofile.tpl"}
		{elseif $smarty.get.action eq "bonusverify"}
			{include file="bonusverify_step2.tpl"}
		{elseif $smarty.get.action eq "suggestion_box"}
			{include file="suggestion.tpl"}
		{elseif ($smarty.get.action eq "adv_search")}
			{include file="advance_search.tpl"}
		{elseif $smarty.get.action eq "sendcard"}
			{include file="sendcard.tpl"}
		{elseif $smarty.get.action eq "sendcard_to"}
			{include file="sendcard_to.tpl"}
		{elseif $smarty.get.action eq "sendmail"}
			{include file="sendmail.tpl"}
		{elseif $smarty.get.action eq "okay_message"}
			{include file="payment_okay.tpl"}
		{elseif $smarty.get.action eq "failed_message"}
			{include file="payment_failed.tpl"}
		{elseif $smarty.get.action eq "validCode2"}
			{include file="sms_validcode2.tpl"}
		{elseif ($smarty.get.action eq "SMS") or ($smarty.get.action eq "sendSMS") or ($smarty.get.action eq "validCode") or ($smarty.get.action eq "validCode2")}
			{if $section eq "SMSAlert"}
				{include file="sms_blank.tpl"}
			{elseif $section eq "sendSMS"}
				{include file="sms_sendmessage.tpl"}
			{elseif $section eq "validCode"}
				{include file="sms_validcode.tpl"}
			{elseif $section eq "okay"}
				{include file="sms_okay.tpl"}
			{elseif $section eq "validation"}
				{include file="sms_validation.tpl"}
			{/if}
		{elseif $smarty.get.action eq "thankyou"}
			{include file="thankyou.tpl"}
		{elseif $smarty.get.action eq "viewcard"}
			{include file="viewcard.tpl"}
		{elseif $smarty.get.action eq "viewcard_mail"}
			{include file="viewcard.tpl"}
		{elseif $smarty.get.action eq "viewmessage"}
			{include file="viewmessage.tpl"}
		{elseif $smarty.get.action eq "viewprofile"}
			{include file="viewprofile.tpl"}
		{elseif $smarty.get.action eq "writecard"}
			{include file="writecard.tpl"}
		{elseif $smarty.get.action eq "suggestionalbum"}
			{include file="suggestion_album.tpl"}
		{elseif $smarty.get.action eq "question"}
			{include file="question.tpl"}
		{else}
			{if file_exists("`$smarty.const.SITE`templates/`$smarty.get.action`.tpl")}
				{include file="`$smarty.get.action`.tpl"}
			{else}
				{include file="main.tpl"}
			{/if}
		{/if}	
	{elseif $smarty.get.action eq "forget"}
		{include file="forget.tpl"}
	{elseif $smarty.get.action eq "register"}
		{if ($section eq "regis-step1-result")}
			{include file="regis-step1-result.tpl"}
		{else}
			{include file="register.tpl"}
		{/if}
	{elseif $smarty.get.action eq "viewcard_mail"}
		{include file="viewcard.tpl"}
	{elseif $smarty.get.action eq "terms"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "terms-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "policy"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "policy-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "refund"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "refund-2"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "imprint"}
		{include file="content.tpl"}
	{elseif $smarty.get.action eq "membership"}
		{include file="membership_1.tpl"}
	{elseif $smarty.get.action eq "birthday"}
		{include file="birthday.tpl"}
	{elseif $smarty.get.action eq "activate"}
		{include file="activate.tpl"}
	{elseif $smarty.get.action eq "activate2"}
		{include file="activate2.tpl"}
	{elseif $smarty.get.action eq "search_gender"}
		{include file="body-search.tpl"}
	{elseif $smarty.get.action eq "newest"}
		{if $smarty.get.new eq "f"}
			{include file="main-women.tpl"}
		{elseif $smarty.get.new eq "m"}
			{include file="main-men.tpl"}
		{elseif $smarty.get.new eq "p"}
			{include file="main-paar.tpl"}
		{/if}
	{elseif $smarty.get.action eq "faqs"}
		{include file="faqs.tpl"}
	{elseif $smarty.get.action eq "resendactivation"}
		{include file="resendactivation.tpl"}
	{else}
		{if file_exists("`$smarty.const.SITE`templates/`$smarty.get.action`.tpl")}
			{include file="`$smarty.get.action`.tpl"}
		{else}
			{include file="main.tpl"}
		{/if}
	{/if}	
{/if}
{************************************* End body *************************************}
            
            {include file="footer.tpl"}

        </div>
    </div>
</div>

{literal}
<script type="text/javascript">
(function(jQuery){
	jQuery(window).load(function(){
		jQuery.ajax(
		{
			type: "GET",
			url: "?action=chat&type=coinsBalance",
			success:(function(result)
				{
					jQuery('#coinsArea').text(result);
				})
		});
	});
})(jQuery);
</script>
{/literal}

<div id="mask"></div>
</body>
</html>