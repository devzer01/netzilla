<!-- {$smarty.template} -->
{literal}
	<script type='text/javascript'>
		jQuery(function () {
			jQuery("#register").click(function (e) {
				e.preventDefault();
				jQuery("#registerform").submit();
			});
		});
	</script>
{/literal}
	{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
		<section>
    <div class="container-news-bg">
        <div class="container-news">
        <!-- -->
            <section id='onlineusers'>
            	   
			</section>
            <!-- -->
            <script>
				{literal}
				jQuery(function(){
					jQuery.get("",{"action": "search", "type": "searchOnline", "template" : 'bigicon_online', "total": 4}, function(data){jQuery('#onlineusers').show();if(data){ jQuery('#onlineusers').html(data)}else{jQuery('#onlineusers').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
					return false;
					});
				{/literal}
			</script>
			{if $smarty.const.COIN_VERIFY_MOBILE gt 0}
			{if !$mobile_verified}
            	<div class="container-banner"><a href="#" onclick="showVerifyMobileDialog(); return false;"><img src="images/cm-theme/banner.png"/></a></div>
            {/if}
            {/if}
            <!-- --> <br class="clear" />
        </div>

        </div>
       
    </section>
	{else}
			<section>
		    <div class="container-news-bg">
		        <div class="container-news">
		        <!-- -->
		            <section>
		            	<div class="container-login">
		                	
		                	{include file='login_box.tpl'}
		                    
		                    <div class="feature-middle"></div>
		                    
		                    <div class="register-box">
		                    <h1>Herzlich Willkommen</h1>
		                    	<form id='registerform' method='post' action='?action=register'>
		                    	<label>Nickname:</label><input name="username" type="text" class="formfield_01"/><br />
		                        <label>E-Mail:</label><input name="email" type="text" class="formfield_01"/><br />
		                        
		                        <a id='register' href="#" class="regis">Koatenlos Anmelden</a>
		                        <a href="{$smarty.const.FACEBOOK_LOGIN_URL}{$smarty.session.state}" class="regis-facebook"><span>Mit Facebook Registrieren!</span></a>
		                        </form>
		                    </div>
		                    <br class="clear" />
		                    <!--
		                    <div class="container-banner"><a href="#"><img src="images/banner.png"/></a></div>
		                    -->
		                </div>
					</section>
		            
		        </div>
		
		        </div>
		    </section>
    {/if}
    
    <section id="newestmembers">
    	<div id='container-newest' class="container-favoriten">
        </div>
    </section>
    
     <script type="text/javascript">
			{literal}
			jQuery(function(){
				jQuery.get("",{"action": "search", "type": "searchNewestMembers", "total": 6, 'template' : 'flirten'}, function(data){jQuery('#container-newest').show();if(data){ jQuery('#container-newest').html(data)}else{jQuery('#container-newest').html("{/literal}<div align='center' style='padding:10px;'>{#NoResult#}</div>{literal}")}});
				return false;
				});
			{/literal}
			</script>
    