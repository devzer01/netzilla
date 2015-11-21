<?php /* Smarty version 2.6.14, created on 2013-11-29 17:31:42
         compiled from social.tpl */ ?>
<h5 class="title">Social</h5>

<ul id="container-profile-list" style="float:left; margin:20px 42px;">
<?php if ($this->_tpl_vars['sitemember']['facebook_token'] != ""): ?>
<li class="socail-right">
    <a  href="#" id='posttowall'>
    <div class="profile-list">
    <div class="boder-profile-img">
    <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
    </div>
    <div class="img-profile">
    <img src="images/cm-theme/socail-fb.png" width="97" height="98" border="0" style="position:relative; top:0px;"/>
    </div>
    </div>
    </a>
</li>
<?php else: ?>
<li class="socail-right">
    <a  href="<?php echo @FACEBOOK_LOGIN_URL;  echo $_SESSION['state']; ?>
">
    <div class="profile-list">
    <div class="boder-profile-img">
    <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
    </div>
    <div class="img-profile">
    <img src="images/cm-theme/socail-fb.png" width="97" height="98" border="0" style="position:relative; top:0px;"/>
    </div>
    </div>
    </a>
</li>
<?php endif; ?>

<li class="socail-right">
    <a  href="#" id='invitegmail'>
    <div class="profile-list">
    <div class="boder-profile-img">
    <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
    </div>
    <div class="img-profile">
    <img src="images/cm-theme/socail-gm.png" width="97" height="98" border="0" style="position:relative; top:0px;"/>
    </div>
    </div>
    </a>
</li>
    
<li class="socail-right">
    <a  href="#" id='invitelive'>
    <div class="profile-list">
    <div class="boder-profile-img">
    <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
    </div>
    <div class="img-profile">
    <img src="images/cm-theme/socail-live.png" width="97" height="98" border="0" style="position:relative; top:0px;"/>
    </div>
    </div>
    </a>
</li>
    
<li>
    <a  href="#" id='inviteyahoo'>
    <div class="profile-list">
    <div class="boder-profile-img">
    <img src="images/cm-theme/profile-boder-img.png" width="120" height="121" />
    </div>
    <div class="img-profile">
    <img src="images/cm-theme/socail-yahoo.png" width="97" height="98" border="0" style="position:relative; top:0px;"/>
    </div>
    </div>
    </a>
</li>
    
</ul>

<div id='contacts'></div>

<?php echo '
<script type=\'text/javascript\'>

	function doSocialPopup(action) {
		//TODO: boi set the ajax loader image in the line below.
		jQuery("#contacts").html("<div class=\'loader\'><img src=\'images/cm-theme/icon_loader.gif\' /></div>");
		var spec = \'height=600,width=800\';
		var w = window.open("?action=" + action, \'window\', spec);
		var intval = window.setInterval(function () {
			if (w.closed) {
				var url = "?action=" + action + "&subaction=loadcontacts";
				console.log(\'window.closed fired, loading (\' + url + \')\');
				window.clearInterval(intval);
				jQuery("#contacts").load(url);	
			}
		}, 200);
	}

	jQuery(document).ready(function (e) {
		jQuery("#posttowall").click(function (e) {
			e.preventDefault();
			jQuery.get("?action=fblogin&testPost=1", function (data) {
				console.log(\'fblogin loaded\');
			});
		});
		
		jQuery("#invitegmail").click(function (e) {
			e.preventDefault();
			doSocialPopup(\'oauthgmail\');
		});
		
		jQuery("#invitelive").click(function (e) {
			e.preventDefault();
			doSocialPopup(\'oauthlive\');
		});
		
		jQuery("#inviteyahoo").click(function (e) {
			e.preventDefault();
			doSocialPopup(\'oauthyahoo\');		
		});
		
	});
</script>
'; ?>