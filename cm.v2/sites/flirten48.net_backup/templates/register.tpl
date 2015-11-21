<!-- {$smarty.template} -->
{if $smarty.get.action ne 'viewprofile'}
<section>
    <div class="container-news-bg">
        <div class="container-profile">
{else}
<div style="width:940px; margin:0 auto; padding:0 5px;">
{/if}
        <!-- -->
            <section>
				<div class="container-content-coins">
                
                <h1>{#Register#}</h1>
                 
                    <div class="container-login">
                    <!--  seperate out to login box tpl -->
                    {include file='login_box.tpl'}
                    <!-- end login box tpl -->
 
                    <div class="register-page-box">
                    
                    {if $text}
					<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px; text-align:center;">
						{$text}
					</div>
					{/if}
	                    
                    
                    <form id="register_form" name="register_form" method="post" action="?action=register&type=membership">
                    <h1 class="title-page-register">{#Register#}</h1>
                    <div class="regis-box-line">
                        <label>Nickname:</label>
                        <input id='username' name="username" value="{$save.username}" type="text" class="formfield_01 input-register validate[required,minSize[6]]"/>
                        <div id="username_info" class="left"></div>
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>Achtung :</label>
                    	<p style="width:520px;">Derzeit können wir nicht sicher gehen, dass Du bei Gmail auch die Registrierungsmail erhältst. Bitte nutze nach Möglichkeit einen anderen Email Provider.</p>
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>{#Email#}:</label>
                    	<input id="email" name="email" value="{$save.email}" type="text" class="formfield_01 input-register validate[required,custom[email]]" onblur="checkEmailSilent(this.value);" autocomplete="off"/>
                    	<div id="email_info" class="left"></div>
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>Passwort:</label>
                    	<input id="password" name="password" type="password" class="formfield_01 input-register validate[required,minSize[6]]" onblur="checkNullPassword(this.value);" autocomplete="off"/>
                    	<div id="password_info" class="left"></div>
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>{#Birthday#}:</label>
                    	{html_options id="date" name="date" options=$date style="width:80px; margin-right:5px;" selected=$save.date class="date formfield_01"}
						{html_options options=$month id="month" name="month" style="width:150px; margin-right:5px;" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.month class="month formfield_01"}
						{html_options id="year" name="year" options=$year_range|default:1994 style="width:100px;" onchange="getNumDate('date', document.getElementById('month').options[document.getElementById('month').selectedIndex].value, document.getElementById('year').options[document.getElementById('year').selectedIndex].value)" selected=$save.year class="year formfield_01"}
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>{#Gender#}:</label>
                        <div style="margin-top:5px;">
                        	{html_radios id="gender" name="gender" options=$gender selected=$save.gender labels=false separator="&nbsp;&nbsp;&nbsp;&nbsp;" class='validate[required]' onClick="checkNullRadioOption('register_form',this,'')"}
                        </div>
                        <div id="gender_info" class="left"></div>
                    </div>
                    
                    <div class="regis-box-line">
                    	<label>{#Country#}:</label>
                        <select id="country" name="country" class="formfield_01"  autocomplete='off' style="width:345px !important">
						{foreach from=$country item=foo}
							<option value="{$foo.id}">{$foo.name}</option>
						{/foreach}
						</select>
						<div id="country_info" class="left"></div>
                    </div>
                    
                    <div class="regis-box-line">
                        <label>&nbsp;</label>
                        <input type="checkbox" name="accept" id="accept" value="1" onclick="checkAcept(this);" class='validate[required]' style="margin-right:5px;"/>{#AGB_accept_txt#}
                        <div id="accept_info"></div>
                    </div>
                     
                   	<div class="regis-box-line">
                    <label>&nbsp;</label>
                    <input type="hidden" name="submit_form" value="1"/>
                    <a href="javascript: void(0)" {if $smarty.cookies.flirt48_activated neq ""} onclick="javascript:alert('{#useraccount_activated#}');" {else} onclick="$('#register_form').submit();" {/if} class="btn-submit-register">{#Register#}</a>
                    
                    </div>
                    
                    <div class="regis-box-line">
                    <label>&nbsp;</label>
                    <a href="#" class="facebook-regis"></a>
                    </div>
                    </form>
                    </div>
                    
                    <!--<div class="register-box">
                    <h1>Herzlich Willkommen</h1>
                    	<label>Nickname:</label><input name="" type="text" class="formfield_01"/><br />
                        <label>E-Mail:</label><input name="" type="text" class="formfield_01"/><br />
                        
                        <a href="#" class="regis">Koatenlos Anmelden</a>
                        <a href="#" class="regis-facebook"><span>Mit Facebook Registrieren!</span></a>
                        
                    </div> -->
                    <br class="clear" />
                </div>

                </div>
            	
                <br class="clear" />
                
			</section>

{if $smarty.get.action ne 'viewprofile'}
        </div>

		</div>
    </section>
{else}
</div>
{/if}

{literal}
<script type="text/javascript">
	$(document).ready(function(){
	    $("#register_form").validationEngine();
	   });
	
	function userLen(field, rules, i, options){
		  if (field.val().length < 6) {
		     // this allows the use of i18 for the error msgs
		     return "Der Benutzername muß mindestens 6 Zeichen lang sein";
		  }
	}
	
	
	function passLen(field, rules, i, options){
		  if (field.val().length < 6) {
		     // this allows the use of i18 for the error msgs
		     return "Das Passwort muß mindestens 6 Zeichen lang sein";
		  }
	}
</script>
{/literal}