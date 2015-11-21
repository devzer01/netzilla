<!-- {$smarty.template} -->
<h2 class="title" style="margin:10px 0 0 0;">Wahlen Sie Ihren Usernamen</h2>
<div id="container-content-profile-home">
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px;">

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
			<br/>oder geben Sie einen anderen Usernamen ein<br/>
            <div style="float:left; width:100%;">
			<input type="radio" name="username" id="other_username" value="" onclick="this.form.username2.focus()" style="float:left; margin-right:10px;"/>
            <input type="text" name="username2" id="username2" class="formfield_01" onclick="document.getElementById(
			'other_username').checked=true"/>
            </div>
			<a href="#" onclick="this.parentNode.submit(); return false;" class="btn-yellow-left" style="margin-left:28px;">Bestätigen</a>
            
		</form>
        <br class="clear" />
	</div>
</div>