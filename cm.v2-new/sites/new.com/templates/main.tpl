{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	<!--start banner verify -->
	{include file="banner-verify-mobile.tpl"}
	<!--end banner verify -->

	{if (($bonusid != '') && ($bonusid > 0))}
	<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
	</span>
	{/if}	
	
	{include file="newest_members_box.tpl" total="16"}
	{include file="my_favorite.tpl" style="2"}
	
{else}
	
	{include file="newest_members_box.tpl" total="16"}
	
{/if}