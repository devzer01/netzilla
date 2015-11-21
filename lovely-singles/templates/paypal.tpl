
{literal}<script type="text/javascript">

jQuery(document).ready(function(){

		//create a bubble popup for each DOM element with class attribute as "text", "button" or "link" and LI, P, IMG elements.
		jQuery('.payicon-visa').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Visa</h1>{/literal}{#Coin_Text_Visa#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		jQuery('.payicon-master').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Master</h1>{/literal}{#Coin_Text_Master#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		jQuery('.payicon-ukash').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Ukash</h1>{/literal}{#Coin_Text_UKash#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		jQuery('.payicon-paypal').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Paypal</h1>{/literal}{#Coin_Text_Paypal#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		jQuery('.payicon-paysave').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Paysave</h1>{/literal}{#Coin_Text_Paysave#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		
		jQuery('.payicon-diners').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: '<h1>Diners Club</h1>{/literal}{#Coin_Text_Diners#}{literal}',

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
								
		jQuery('.payicon-jcb').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>JCB</h1>{/literal}{#Coin_Text_JCB#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
		jQuery('.payicon-disco').CreateBubblePopup({

									position : 'top',
									align	 : 'center',
									
									innerHtml: "<h1>Discovery</h1>{/literal}{#Coin_Text_Disco#}{literal}",

									innerHtmlStyle: {
													
													},
																		
									themeName: 	'black',
									themePath: 	'images/jquerybubblepopup-theme'
								 
								});
});

</script>{/literal}
<h2>{#I_WANT_PAY_COINS#}</h2>
<div class="result-box">

<div class="result-box-inside">
<div id="coins-table">

<div style="width:650px; margin:0 auto; display:block; height:auto;">
<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td valign="top"><strong>Company:</strong></td><td valign="top">
<!--Centro Plaza La Malagena S.R.L.
RNC No.1-30-82659-5
Real Sur 52a / Cocotal
bavaro Punta Cana
Dominican Republic-->
Internet Merchant Centre

</td></tr><tr><td width="200" valign="top"><strong>Business phone:</strong></td><td valign="top">01691664247</td></tr><!--<tr><td valign="top"><strong>Faxnumber:</strong></td><td valign="top">(+1) 809-571-8971</td></tr><tr><td valign="top"><strong>Contact Emailadress:</strong></td><td valign="top">info@yourbuddy24.com</td></tr>--></table>
<br />
{#Coin_Text_Line1#}<br /><br />
{#Coin_Text_Line2#}
</div>

<div id="wrapcoin">
<div class="coin-top">
<span class="coin-h">{#Coin_Price#}</span>
<span class="coin-h" style="width:440px; border-right:none;">{#COINS#}</span>
<span></span>
</div>
<div class="coin-list-area">
<table width="100%" cellpadding="0" cellspacing="0">
{foreach from=$coinpackage item=package}
<tr bgcolor="{cycle values="#910069,#66004a"}">
<td align="center" style="border-right:1px solid #FFFFFF; width:200px; height:34px; line-height:34px;"><b>{$package.currency_price}</b> {$rcurrency.value}</td>
<td align="center" style="border-right:1px solid #FFFFFF; line-height:34px;">{$package.coin}</td>

</tr>
{/foreach}
</table>
</div>
<div class="coin-bottom">
<table width="700" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" style="width:200px; height:30px; line-height:60px; font-size:14px; font-weight:bold;">{#Coin_Payment#}</td>
    <td>
    <div class="payicon-visa"></div>
    <div class="payicon-master"></div>
    <div class="payicon-diners"></div>
    <div class="payicon-jcb"></div>
    <div class="payicon-disco"></div>
    <div class="payicon-ukash"></div>
    <div class="payicon-paypal"></div>
    <!-- <div class="payicon-paysave"></div> -->
    </td>
  </tr>
</table>

</div>
</div>


</div>
</div>
</div>