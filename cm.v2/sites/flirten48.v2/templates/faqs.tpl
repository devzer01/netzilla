<!-- {$smarty.template} -->
<!-- jquery plugins -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery_002.js"></script>
<script type="text/javascript" src="js/highlight.js"></script>
<script type="text/javascript">
{literal}
	jQuery(document).ready(function() {

		//syntax highlighter
		hljs.tabReplace = '    ';
		hljs.initHighlightingOnLoad();

		//accordion
		jQuery('h2.accordion').accordion({
			defaultOpen: 'section1',
			cookieName: 'accordion_nav',
			speed: 'slow',
			animateOpen: function (elem, opts) { //replace the standard slideUp with custom function
				elem.next().slideFadeToggle(opts.speed);
			},
			animateClose: function (elem, opts) { //replace the standard slideDown with custom function
				elem.next().slideFadeToggle(opts.speed);
			}
		});

		jQuery('h2.accordion2').accordion({
			defaultOpen: 'sample-1',
			cookieName: 'accordion2_nav',
			speed: 'slow',
			cssClose: 'accordion2-close', //class you want to assign to a closed accordion header
			cssOpen: 'accordion2-open',
			
		});

		//custom animation for open/close
		jQuery.fn.slideFadeToggle = function(speed, easing, callback) {
			return this.animate({opacity: 'toggle', height: 'toggle'}, speed, easing, callback);
		};

	});
{/literal}
</script>

{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	<div class="container-content-full">
{else}
	<div class="container-content">
{/if}

<div class="title">
	<div class="title-left"></div><h1>{#FAQS#}</h1><div class="title-right"></div>
</div>
                
<div id="container-content-profile-home">
<div class="container-content-text">
<strong>{#FAQ_Intro#}</strong><br /><br />

<div id="accordion">
<!-- panel -->
<h2 class="accordion accordion-close" id="body-section1">{#FAQ_Q1#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A1#}
</div>
</div>
<!-- end panel -->
<!-- panel -->
<h2 class="accordion accordion-close" id="body-section2">{#FAQ_Q2#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A2#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section3">{#FAQ_Q3#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A3#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section4">{#FAQ_Q4#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A4#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section5">{#FAQ_Q5#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A5#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section6">{#FAQ_Q6#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A6#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section7">{#FAQ_Q7#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A7#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section8">{#FAQ_Q8#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A8#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section9">{#FAQ_Q9#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A9#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section10">{#FAQ_Q10#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A10#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section11">{#FAQ_Q11#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A11#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section12">{#FAQ_Q12#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A12#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section13">{#FAQ_Q13#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A13#|replace:'[coin_email]':$smarty.const.COIN_EMAIL|replace:'[coin_sms]':$smarty.const.COIN_SMS}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h2 class="accordion accordion-close" id="body-section14">{#FAQ_Q14#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A14#}
</div>
</div>
<!-- end panel -->


<!--<h2 class="accordion accordion-close" id="body-section15">{#FAQ_Q15#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A15#}
</div>
</div>


<h2 class="accordion accordion-close" id="body-section16">{#FAQ_Q16#}</h2>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A16#}
</div>
</div> -->


</div>

</div>
</div>

{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}

{else}
	</div>{include file="left-notlogged.tpl"}
{/if}