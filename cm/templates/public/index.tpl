{include file='public/header.tpl'}

<div class="wrap">
    
        <div class="container-big-banner">
        
            <h1>Die große Singleböse,</h1> 
            <p>Chatten, Daten, neue Freunde finden,
            Singles auch aus deiner Stadt! 
            </p>
            <div class="text-banner-01">Besuchen Sie uns auch im TV!</div>
        	
        </div>
         
        <div style=" margin:0 auto;">
            <a href="http://{$smarty.server.HTTP_HOST}{$smarty.const.APP_PATH}/register" class="btn-01">Register</a>
            <a href="http://{$smarty.server.HTTP_HOST}{$smarty.const.APP_PATH}/login" class="btn-01 login">Login</a>
            <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="btn-01 facebook">Login with Facebook</a>
        </div>
    
 </div>
 
{include file='public/footer.tpl'}
