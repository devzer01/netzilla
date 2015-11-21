{if $smarty.session.sess_username neq "" or $smarty.cookies.sess_username neq ""}
	<!--start banner verify -->
	{include file="banner-verify-mobile.tpl"}
	<!--end banner verify -->

	{if (($bonusid != '') && ($bonusid > 0))}
	<span id="bonusverify_box">
		{include file="bonusverify_step1.tpl"}
	</span>
	{/if}	

	{include file="online.tpl" total="12"}
	{include file="newest_members_box.tpl" total="12"}
	{include file="my_favorite.tpl" style="2"}
{else}
	{include file="online.tpl" total="12"}
	{include file="newest_members_box.tpl" total="12"}
{/if}