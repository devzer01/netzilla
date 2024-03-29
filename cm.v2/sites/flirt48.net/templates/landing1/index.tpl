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
	<link rel="stylesheet" href="landing1/assets/css/reset.css" />
	<link rel="stylesheet" href="landing1/assets/css/style.css" />
    <link rel="stylesheet" href="landing1/css/theme.css" />
	
	<!-- Lea Verou's Prefix Free, lets you use un-prefixed properties in your CSS files -->
	<script src="landing1/assets/js/libs/prefixfree.min.js"></script>
	
	<!-- This is an un-minified, complete version of Modernizr. 
		 Before you move to production, you should generate a custom build that only has the detects you need. -->
	<script src="landing1/assets/js/libs/modernizr-2.7.1.dev.js"></script>
	
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

	{literal}
	<!-- Grab Google CDN's jQuery. fall back to local if necessary -->
	<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> 
	<script>window.jQuery || document.write("<script src='landing1/assets/js/libs/jquery-1.11.0.min.js'>\x3C/script>")</script>-->
	<script src="landing1/assets/js/libs/jquery-1.11.0.min.js"></script>
	<script src="landing1/js/jquery.placeholder.js"></script>
	<script>
			// To test the @id toggling on password inputs in browsers that don’t support changing an input’s @type dynamically (e.g. Firefox 3.6 or IE), uncomment this:
			// $.fn.hide = function() { return this; }
			// Then uncomment the last rule in the <style> element (in the <head>).
			$(function() {
				// Invoke the plugin
				$('input, textarea').placeholder();
			});
	</script>
	<script type="text/javascript" src="js/script.js?v=1.3-new"></script>
	
	<script type="text/javascript">
		var old_username="";
		var username_ok = false;
		var email_ok = false;
		var password_ok = false;
		var email_validated = false;
	</script>
	<script type="text/javascript" src="configs/ger.js"></script>
	
	<script type="text/javascript" src="js/jquery.validationEngine.js" charset="utf-8"></script>
	<script type="text/javascript" src="js/jquery.validationEngine-en.js" charset="utf-8"></script>
	<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css" />
	
	<link href="css/MetroNotificationStyle.min.css" rel="stylesheet" type="text/css" />   
	<script type="text/javascript" src="js/MetroNotification.js"></script>
	
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

<div class="wrapper">

	<header>
    
		<div class="container-logo"></div>
        <div class="container-login">
            <input id='lusername' name="username" type="text" placeholder="Benutzername:" class="formfield_01 field-login">
            <input id='lpassword' name="password" type="password" placeholder="Passwort:" class="formfield_01 field-login">
            <a href="#" id='login' class="btn-login">Login</a>
            <br class="clear">
            <a href="#" onclick="loadPagePopup('?action=forget', '100%'); return false;" class="forgetpass">Passwort vergessen?</a>
            <div id='login_error'></div>
        </div>
        
	</header>
	
	<article class="container-cotent-01">
		<form id="register_form" name="register_form" method="post" action="?action=register&amp;type=membership">
		<input type="hidden" name="submit_form" value="1"/>
		<input type="hidden" name="landing" value="1"/>
		<div class="container-register">
        	<div class="register-box">
            <div class="container-title">
            	<h1>Schnellregistrierung</h1>
                <div class="regis-icon"></div>
            </div>
            	
                
                <div class="register-form">
                    <input name="username" id='username' type="text" onkeyup="checkUsernameSilentJQuery(this.value)" placeholder="Benutzername:" class="formfield_01 field-register">
                    <div id="username_info" class="left"></div>
                    <input name="email" id='email' onchange="isValidEmailJQuery();" type="text" placeholder="Email:" class="formfield_01 field-register">
                    <div id="email_info" class="left"></div>
                    <input name="password" id='password' type="password" placeholder="Passwort:" class="formfield_01 field-register">
                    <input name="password_confirm" id="password_confirm" type="password" placeholder="Passwort wiederholen:" class="formfield_01 field-register">
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
                    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-facebook">Mit Facebook Registrieren!</a>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
        <aside class="beta">
        	<h1>Hier kannst du flirten, chatten, neue Freunde kennen lernen</h1>
			<p>Flirt48.net ist das beliebte Flirtportal Deutschlands. Hier kannst du einfach und schnell neue Freunde und andere Singles kennen lernen. Unsere Seite ist ausgezeichnet für ihre Kundenfreundlichkeit, und die Einfachheit andere Singles zum flirten kennen zu lernen!</p>
        </aside>
	<br class="clear">
	</article>
    
    <article class="container-cotent-03">
    
    <ul class="container-list-02">
    <h1>Flirten ist so einfach!</h1>
    	<li><img src="landing1/images/dating-01.jpg"><p>Michael und Cora haben wir aktiv beim flirten unterstützt!</p></li>
        <li><img src="landing1/images/dating-02.jpg"><p>Frank und Verena haben sich am ersten Tag hier schon kennen gelernt!</p></li>
        <li><img src="landing1/images/dating-03.jpg"><p>Gemeinsam ist es doch am schönsten!</p></li>
        <li><img src="landing1/images/dating-04.jpg"><p>Bei uns haben sie die Flirtgarantie!</p></li>
        <li><img src="landing1/images/dating-05.jpg"><p>Auch neue Freunde kann man bei uns finden!</p></li>
    </ul>
    
    </article>
    
    <article class="container-cotent-02">
    	
        <ol class="container-list">
            <h1>Ausgezeichnet zum Flirtportal 2014</h1>
            <li>Einfache Bedienung</li>
            <li>In 2 Min ist dein Profil erstellt</li>
            <li>Komfortable und einfache Suche</li>
            <li>Ausgesuchte Partnervorschläge</li>
            <li>Einfaches und schnelles kennen lernen</li>
            <li>Online Hilfe 24 Stunden</li>
            <li>Unser Online Team steht ihnen immer hilfsbereit zur Seite</li>
        </ol>
    </article>
    

</div>    
<div id="mask"></div>
<br class="clear">
<footer>
    
    <a href="?action=terms">{#AGB#}</a>   |  
<a href="?action=imprint">{#IMPRESSUM#}</a>   |  
<a href="?action=policy">{#WIDERRUFSRECHT#}</a> | 
<a href="?action=faqs">{#FAQS#}</a> | 
<a href="?action=refund">{#REFUND_POLICY#}</a>
{if $smarty.session.sess_username != "" or $smarty.cookies.sess_username neq ""}| <a href="?action=delete_account">{#delete_account#}</a>{/if}
    
</footer>

<script src="landing1/assets/js/functions.js"></script>
{literal}
<script>

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-30528203-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
{/literal}

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