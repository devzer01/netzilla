<!-- {$smarty.template} -->

    
    <!--{******************************** coins *****************************************}
    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
    {if $smarty.session.sess_username}
   <div id="container-coin">
   	Sie haben!<br /> <strong>
    <span id="coinsArea" style="padding: 0px">{if $coin}{$coin}{else}0{/if}</span> coins</strong></div>    
    {/if}
    {else}
    
    {/if}
    {******************************** End coins *****************************************}
 -->

<div id="container-menu">
    <ul>
        <li><a href="./" class="home"><span class="active">{#START_SITE#}</span></a></li>
        <li><a href="?action=search" class="search"><span class="active">{#SEARCH#}</span></a></li>
        
        {if !$smarty.session.sess_externuser} 
        {******************************** left-membership *****************************************}
        {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
        {include file="left-membership_islogged.tpl"}
        {else}
        <li class="container-btn-login"><a href="?action=register" class="login"><span class="active">Login</span></a></li>
        {/if}{/if}
        

        
    </ul>
</div>
