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
		jQuery('h3.accordion').accordion({
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

		jQuery('h3.accordion2').accordion({
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
<div id="container-content">
<h1>{#FAQS#}</h1>
<div class="container-content-profile-box">
<div class="content-profile-box">
<strong>{#FAQ_Intro#}</strong><br /><br />

<div id="accordion">
<!-- panel -->
<h3 class="accordion accordion-close" id="body-section1">{#FAQ_Q1#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A1#}
</div>
</div>
<!-- end panel -->
<!-- panel -->
<h3 class="accordion accordion-close" id="body-section2">{#FAQ_Q2#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A2#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section3">{#FAQ_Q3#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A3#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section4">{#FAQ_Q4#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A4#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section5">{#FAQ_Q5#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A5#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section6">{#FAQ_Q6#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A6#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section7">{#FAQ_Q7#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A7#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section8">{#FAQ_Q8#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A8#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section9">{#FAQ_Q9#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A9#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section10">{#FAQ_Q10#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A10#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section11">{#FAQ_Q11#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A11#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section12">{#FAQ_Q12#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A12#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section13">{#FAQ_Q13#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A13#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section14">{#FAQ_Q14#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A14#|replace:'[coin_email]':$coin_conts.0.coin_email|replace:'[coin_sms]':$coin_conts.0.coin_sms}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section15">{#FAQ_Q15#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A15#}
</div>
</div>
<!-- end panel -->

<!-- panel -->
<h3 class="accordion accordion-close" id="body-section16">{#FAQ_Q16#}</h3>
<div style="display: none;" class="containerfaq">
<div class="contentfaq">
{#FAQ_A16#}
</div>
</div>
<!-- end panel -->

</div>

</div>
</div>
</div>