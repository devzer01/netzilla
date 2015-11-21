<!doctype html>

<!--
	HTML5 Reset: https://github.com/murtaugh/HTML5-Reset
	Free to use
-->

<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 

<head>

	<meta charset="utf-8">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<!-- Important stuff for SEO, don't neglect. (And don't dupicate values across your site!) -->
	<title></title>
	<meta name="title" content="" />
	<meta name="author" content="" />
	<meta name="description" content="" />
	
	<!-- Don't forget to set your site up: http://google.com/webmasters -->
	<meta name="google-site-verification" content="" />
	
	<!-- Who owns the content of this site? -->
	<meta name="Copyright" content="" />
	
	<!--  Mobile Viewport
	http://j.mp/mobileviewport & http://davidbcalhoun.com/2010/viewport-metatag
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width (wrong for most sites)
	-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Use Iconifyer to generate all the favicons and touch icons you need: http://iconifier.net -->
	<link rel="shortcut icon" href="favicon.ico" />
	
	<!-- concatenate and minify for production -->
	<link rel="stylesheet" href="landing2/assets/css/reset.css" />
	<link rel="stylesheet" href="landing2/assets/css/style.css" />
    <link rel="stylesheet" href="landing2/css/theme.css" />
	
	<!-- Lea Verou's Prefix Free, lets you use un-prefixed properties in your CSS files -->
	<script src="landing2/assets/js/libs/prefixfree.min.js"></script>
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<script src="landing2/assets/js/libs/modernizr-2.7.1.dev.js"></script>
	
	<!-- Application-specific meta tags -->
	<!-- Windows 8: see http://msdn.microsoft.com/en-us/library/ie/dn255024%28v=vs.85%29.aspx for details -->
	<meta name="application-name" content="" /> 
	<meta name="msapplication-TileColor" content="" /> 
	<meta name="msapplication-TileImage" content="" />
	<meta name="msapplication-square150x150logo" content="" />
	<meta name="msapplication-square310x310logo" content="" />
	<meta name="msapplication-square70x70logo" content="" />
	<meta name="msapplication-wide310x150logo" content="" />
	<!-- Twitter: see https://dev.twitter.com/docs/cards/types/summary-card for details -->
	<meta name="twitter:card" content="">
	<meta name="twitter:site" content="">
	<meta name="twitter:title" content="">
	<meta name="twitter:description" content="">
	<meta name="twitter:url" content="">
	<!-- Facebook (and some others) use the Open Graph protocol: see http://ogp.me/ for details -->
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	
	<script src="landing2/assets/js/libs/jquery-1.11.0.min.js"></script>
	<script src="landing2/js/jquery.placeholder.js"></script>
	<script>
	{literal}
			// To test the @id toggling on password inputs in browsers that don’t support changing an input’s @type dynamically (e.g. Firefox 3.6 or IE), uncomment this:
			// $.fn.hide = function() { return this; }
			// Then uncomment the last rule in the <style> element (in the <head>).
			$(function() {
				// Invoke the plugin
				$('input, textarea').placeholder();
			});
	{/literal}
	</script>
	
	<!-- this is where we put our custom functions -->
	<!-- don't forget to concatenate and minify if needed -->
	<script src="landing2/assets/js/functions.js"></script>
	
	
	<script type="text/javascript" src="js/script.js?v=1.2-new"></script>
	
	<script type="text/javascript">
		var old_username="";
		var username_ok = false;
		var email_ok = false;
		var password_ok = false;
	</script>
	<script type="text/javascript" src="configs/ger.js"></script>
	
	<script type="text/javascript" src="js/jquery.validationEngine.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/jquery.validationEngine-en.js" charset="utf-8"></script>
	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
	
	<link href="css/MetroNotificationStyle.min.css" rel="stylesheet" type="text/css" />   
	<script type="text/javascript" src="js/MetroNotification.js"></script>
	
	{literal}
	<style>
	
	#boxes .window {
		  position:fixed;
		  left:0;
		  top:0;
		  display:none;
		  z-index:9999;
	}
	
	.container-metropopup{background:#59669d; padding:10px; color:#FFF; position:relative; /*top:-100px;*/}
	.metropopup-content{line-height:20px; width:50%; min-width:500px !important; margin:10px 10px 10px 10px; padding:10px; height:auto; left:25%; position:relative; padding-bottom:20px;}
	
	</style>
	{/literal}
</head>

<body>

<div class="wrapper"><!-- not needed? up to you: http://camendesign.com/code/developpeurs_sans_frontieres -->

	<header>
    
		<div class="container-logo"></div>
        <div class="container-login">
            <input id='lusername' name="lusername" type="text" placeholder="Benutzername:" class="formfield_01 field-login">
            <input id='lpassword' name="lpassword" type="password" placeholder="Passwort:" class="formfield_01 field-login">
            <a href="#" id='login' class="btn-login">Login</a>
            <br class="clear">
            <a href="#" onclick="loadPagePopup('?action=forget', '100%'); return false;" class="forgetpass">Passwort vergessen?</a>
            <div id='login_error'></div>
        </div>
        
	</header>
	
	<article>
		<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
		<input type="hidden" name="submit_form" value="1"/>
		<input type="hidden" name="landing" value="2"/>
        <h1 class="container-content-title">Schnellregistrierung</h1>
        <ul class="container-content">
        
            <li>
            	<h1>Flirt48.net das Große Flirtportal Deutschlands.</h1>
                <h2>Hier kannst du flirten, chatten, neue Freunde kennen lernen. Finde viele Singles auch aus deiner Region!</h2>
                <p>Flirt48.net ist das beliebte Flirtportal Deutschlands. Hier kannst du einfach und schnell neue Freunde und andere Singles kennen lernen. Unsere Seite ist ausgezeichnet für ihre Kundenfreundlichkeit, und die Einfachheit andere Singles zum flirten kennen zu lernen!</p>
            </li>
            <li>
            	<div class="register-form">
                    <input id='username' onkeyup="checkUsernameSilentJQuery(this.value)" name="username" type="text" placeholder="Benutzername:" class="formfield_01 field-register">
                    <div id="username_info" class="left"></div>
                    <input id='email' name="email" type="text" placeholder="Email:" class="formfield_01 field-register">
                    <div id="email_info" class="left"></div>
                    <input id='password' name="password" type="password" placeholder="Passwort:" class="formfield_01 field-register">
                    <div id="password_info" class="left"></div>
                    <div class="register-line">
                        <span>Geburtstag:</span>
						{html_options id="date" name="date" options=$date selected=$save.date class="date formfield_01"}
						{html_options options=$month id="month" name="month" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month formfield_01"}
						{html_options id="year" name="year" options=$year_range|default:1994 onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year formfield_01"}
                    </div>
                    <div class="register-line">
                    	<span>Geschlecht:</span>
                        <div class="line-input">
                        	{html_radios id="gender" class='gender' name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;"}
                        	<div id="gender_info" class="left"></div>
                        </div>
                    </div>
                    <div class="register-line">
                        <span>Nationalität:</span>
                        <select id="country" name="country" class="formfield_01 select-register"  autocomplete='off'>
						{foreach from=$country item=foo}
							<option value="{$foo.id}">{$foo.name}</option>
						{/foreach}
						</select>
						<div id="country_info" class="left"></div>
                    </div>
                    <div class="register-line">
                    	<input id='accept' name="accept" type="checkbox" value="1"> Ich habe die Allgemeinen Geschäftsbedingungen und die Datenschutzerklärung gelesen und stimme diesen zu!
                    	<div id="accept_info"></div>
                    </div>
                    <div class="register-line">
                    <a href="#" onclick="if(checkNullSignupJQuery()) $('#register_form').submit();" class="btn-register">Schnellregistrierung</a>
                    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-facebook"><span>Mit Facebook Registrieren!</span></a>
                    </div>
                </div>
                <br class="clear">
            </li>
            <li class="profile-banner">
            
            </li>
        </ul>
        <div class="footer-container-content"></div>
        </form>
	</article>
</div>
<div id="mask"></div>
<br class="clear">
<footer>
    
    <a href="?action=terms">AGB's</a> | <a href="?action=imprint">Impressum</a> | <a href="?action=policy">Datenschutzerklärung</a> | <a href="?action=faqs">FAQ's</a> | <a href="?action=refund">Refund policy</a>
    
</footer>

<!-- Grab Google CDN's jQuery. fall back to local if necessary -->

<script>
{literal}
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30528203-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
{/literal}
</script>  

<script type='text/javascript'>
{literal}
	$(function () {
		$("#login").click(function (e) {
			
			var username = $("#lusername").val();
			var password = $("#lpassword").val();
			
			var remember = 0;
			
			if($("#remember").is(":checked")) {
				remember = 1;
			}
			
			$.ajax({
				url: 'ajaxRequest.php',
				data: {action: 'loginmobile', username: username, password: password, remember: remember},
				type: 'post',
				success: function(json) {
					if (json == 1) {
						window.location.href = "/";
					} else {
						$('#login_error').validationEngine('showPrompt', 'Username oder Passwort falsch', 'error', 'topRight', true);
					}
				}
			});
			
		});
	});
{/literal}
</script>

</body>
</html>
