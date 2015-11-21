<!-- {$smarty.template} -->
    
    <li><a href="./">{#START_SITE#}</a></li>
    <li><a href="?action=search">{#SEARCH#}</a></li>
    
	{******************************** left-membership *****************************************}
    {if !$smarty.session.sess_externuser} 
    
		{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
			{include file="left-membership_islogged.tpl"}   
		{/if}

	{/if}
