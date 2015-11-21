<!-- {$smarty.template} -->
<h1 class="title" style="margin-top:15px;">Wahlen Sie Ihren Usernamen</h1>

<div class="container-box-content-03">
	<div class="box-content-01-t-l"></div>
	<div class="box-content-01-t-m" style="width:900px !important;"></div>
	<div class="box-content-01-t-r"></div>
	<div class="box-content-03-m">
    
    <form method="post" action="">
        {if $error_message}
            <div style="background:#F00; padding:10px; text-align:center; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; border:1px solid #000; color:#FFF; 
            font-size:14px; margin-bottom:10px;">
                {$error_message}
            </div>
        {/if}
        {foreach from=$usernames item="item"}
        <input type="radio" name="username" value="{$item}" {if strtolower($item) eq strtolower($smarty.session.sess_username)}checked="checked"{/if}/> {$item}<br/>
        {/foreach}
        
        <div style="float:left; width:100%; margin-top:15px; margin-bottom:15px;">
        <h4 style="font-size:14px; font-weight:bold; color:#ffff00;">oder geben Sie einen anderen Usernamen ein</h4>
        <input type="radio" name="username" id="other_username" value="" onclick="this.form.username2.focus()" style="float:left; margin-right:10px;"/>
        <input type="text" name="username2" id="username2" class="formfield_01" onclick="document.getElementById(
        'other_username').checked=true" style="width:250px;"/>
        
        </div>
        
        <a href="#" onclick="this.parentNode.submit(); return false;" class="btn-register" style="width:180px;">Bestätigen</a>
        
    </form>
    <br class="clear" />
    
    </div>
	<div class="box-content-01-b-l"></div>
	<div class="box-content-01-b-m" style="width:900px !important;"></div>
	<div class="box-content-01-b-r"></div>
</div>
