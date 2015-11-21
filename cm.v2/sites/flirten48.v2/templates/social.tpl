<div class="title">
	<div class="title-left"></div><h1>Social</h1><div class="title-right"></div>
</div>

<ul class="container-photo">
{if $sitemember.facebook_token ne ""}
<li>
	<img src="images/cm-theme/socail-fb.png" width="102" height="102" border="0" style="position:relative; top:0px;"/>
    <a href="#" id='posttowall' class="link-profile"></a>
</li>
{else}
<li>
    <img src="images/cm-theme/socail-fb.png" width="102" height="102" border="0" />
    <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="link-profile"></a>
</li>
{/if}

<li>
    <img src="images/cm-theme/socail-gm.png" width="102" height="102" border="0" style="position:relative; top:0px;"/>
    <a href="#" id='invitegmail' class="link-profile"></a>
</li>
    
<li>
    <img src="images/cm-theme/socail-live.png" width="102" height="102" border="0" style="position:relative; top:0px;"/>
    <a href="#" id='invitelive' class="link-profile"></a>
</li>
    
<li>
    <img src="images/cm-theme/socail-yahoo.png" width="102" height="102" border="0" style="position:relative; top:0px;"/>
    <a href="#" id='inviteyahoo' class="link-profile"></a>
</li>
    
</ul>

<div id='contacts'></div>

{literal}
<script type='text/javascript'>

	function doSocialPopup(action) {
		//TODO: boi set the ajax loader image in the line below.
		jQuery("#contacts").html("<div class='loader'><img src='images/cm-theme/icon_loader.gif' /></div>");
		var spec = 'height=600,width=800';
		var w = window.open("?action=" + action, 'window', spec);
		var intval = window.setInterval(function () {
			if (w.closed) {
				var url = "?action=" + action + "&subaction=loadcontacts";
				console.log('window.closed fired, loading (' + url + ')');
				window.clearInterval(intval);
				jQuery("#contacts").load(url);	
			}
		}, 200);
	}

	jQuery(document).ready(function (e) {
		jQuery("#posttowall").click(function (e) {
			e.preventDefault();
			jQuery.get("?action=fblogin&testPost=1", function (data) {
				console.log('fblogin loaded');
			});
		});
		
		jQuery("#invitegmail").click(function (e) {
			e.preventDefault();
			doSocialPopup('oauthgmail');
		});
		
		jQuery("#invitelive").click(function (e) {
			e.preventDefault();
			doSocialPopup('oauthlive');
		});
		
		jQuery("#inviteyahoo").click(function (e) {
			e.preventDefault();
			doSocialPopup('oauthyahoo');		
		});
		
	});
</script>
{/literal}