{include file='mobile/header.tpl'}
    
        <div class="container-big-banner">
	        <div class="banner-t1">
	            <h1>Single und Flirt gesucht?</h1> 
	            <p>Hier wartet Dein Traumpartner auf Dich!</p>
	        </div>
    	</div>
         
        <div class="wrap">
        	<div class="index-space">
	            <a href="?action=register" class="btn-01 register"><span class="icon-btn"></span><font>Schnellregistrierung</font><span class="icon-next"></span></a>
	            <a href="?action=login" class="btn-01 login"><span class="icon-btn"></span><font>Login</font><span class="icon-next"></span></a>
	            <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-01 facebook"><span class="icon-btn"></span><font>Mit Facebook Registrieren!</font><span class="icon-next"></span></a>
        	</div>
    	</div>
    	<div class="push"></div>

{include file='mobile/footer.tpl'}