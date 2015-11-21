{include file='mobile/header.tpl'}

 <div class="header-bar"></div>
    <div class="container-big-banner">
    
        
        <div class="banner-t1">
        

        <h1 style="width:60%;">Jetzt direkt loslegen & chatten.</h1> 
        <p>Tausende Singles warten auf dich!</p>
        </div>
        
        <!--<div class="text-banner-01">Besuchen Sie uns auch im TV!</div>
         -->
    </div>
    <div style="width:100%; height:30px; background:url(images/bg.jpg) bottom repeat-x;"></div>
    <div class="wrap">
 
        <div style=" margin:0 auto;">
            <a href="?action=register" class="btn-01 register">Register</a>
            <a href="?action=login" class="btn-01 login">Login</a>
            <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-01 facebook">Login with Facebook</a>
        </div>
    
    </div>
 
{include file='mobile/footer.tpl'}
