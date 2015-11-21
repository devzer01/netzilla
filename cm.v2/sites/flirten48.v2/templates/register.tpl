<!-- {$smarty.template} -->

{if $text}
	<div style="line-height:20px; width:auto; -webkit-border-radius: 10px; -moz-border-radius: 10px; border-radius: 10px;
	background:url(images/cm-theme/bg-opd-r.png); padding:10px; text-align:center; border:2px solid rgba(0,0,0,0.1); color:#FFF;">
		{$text}
	</div>
{/if}

{if ($smarty.get.action eq "register")}
	<!--only register page -->
	<div id="container-content-profile-home" class="container-register">
	
	    {include file="regis-step1.tpl"}
	
	    {******************************** login *****************************************}
	    {if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	    {if $smarty.session.sess_username}
	        <!--Html -->
	    {/if}
	    {else}
	    	{include file="left-notlogged.tpl"}
	    {/if}
	</div>
	<!--end only register page -->
{else}
	<div class="container-view-profile-register">
	{include file="regis-step1.tpl"}
	</div>
{/if}