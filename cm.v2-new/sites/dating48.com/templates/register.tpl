<!-- {$smarty.template} -->
<div id="container-content">
<div style="background:url(images/cm-theme/bg-register-box.png) no-repeat left bottom; float:left;">
{if $text}
<div style="line-height:20px; width:auto; margin:10px 10px 10px 10px; border:1px solid #000; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px; background:#fff6dd; padding:10px; text-align:center;">
{$text}
</div>
{/if}

<div style="float:left; padding:10px;">
    {******************************** login *****************************************}
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    {if $smarty.session.sess_username}
        <!--Html -->
    {/if}
    {else}
    <div id="container-login-page">
    <h2 style="padding:10px;">Login</h2>
    	{include file="left-notlogged.tpl"}
    </div>
    {/if}

    <div class="profile-page-register">
    <h2 style="padding:10px;">{#Register#}</h2>
    <div style="padding:0 10px;">
    {include file="regis-step1.tpl"}
    </div>
    </div>
</div>

</div>
</div>