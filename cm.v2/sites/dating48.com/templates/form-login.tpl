<!-- {$smarty.template} -->
<form id="loginForm" onsubmit="ajaxRequest('login', 'username='+$('l_username').value+'&amp;password='+$('l_password').value, '', loginSite, '')" action="" method="post">
<p>
<label for="username">{#USERNAME#}:</label>
<input type="text" name="l_username" id="l_username" class="input-login-box" onkeypress="enterLogin(event)"/>
</p>
<p>
<label for="password">{#PASSWORD#}:</label>
<input type="password" name="l_password" id="l_password" class="input-login-box" onkeypress="enterLogin(event)"/>
</p>
<div style="float:left; margin-left:93px; margin-top:10px; color:#666; text-shadow:1px 1px 1px #fff; font-weight:bold;">
<input name="remember" id="remember" type="checkbox" value="1" {php}if(empty($_COOKIE[notremember])){echo 'checked="checked"';} {/php} />{#Remember_me#}
</div>
<a href="#" id="login" onclick="ajaxRequest('login', 'username='+document.getElementById('l_username').value+'&amp;password='+document.getElementById('l_password').value+rememberMe(), '', loginSite, '')" class="btn-login">{#login#}</a>
</form>

<div id="boxes">
<div id="dialogForget" class="window">
	<div style="background-color: white; width: 100%"></div>
</div>
</div>

<script>
{literal}
var sendingForgetPassword = false;
function showForgetBox()
{
	var url = "?action=forget";
	jQuery("#dialogForget").load(url);

	//Get the screen height and width
	var maskHeight = jQuery(document).height();
	var maskWidth = jQuery(window).width();

	//Set heigth and width to mask to fill up the whole screen
	jQuery('#mask').css({'width':maskWidth,'height':maskHeight});
	
	//transition effect		
	//$('#mask').fadeIn(1000);	
	jQuery('#mask').fadeTo("fast",0.8);	

	//Get the window height and width
	var winH = jQuery(window).height();
	var winW = jQuery(window).width();
		  

	//Set the popup window to center
	jQuery('#dialogForget').css('top',  winH/2-jQuery('#dialogForget').height()/2);
	jQuery('#dialogForget').css('left', winW/2-jQuery('#dialogForget').width()/2);

	//transition effect
	jQuery('#dialogForget').fadeIn(1500);
}
{/literal}
</script>