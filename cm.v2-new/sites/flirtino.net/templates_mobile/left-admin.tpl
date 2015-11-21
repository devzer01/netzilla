<!-- {$smarty.template} -->
<a href="./" class="left-menu"><div class="icon-select icon-home"></div>Front end</a>
<a href="?action=admin_manageuser" class="left-menu"><div class="icon-select icon-user"></div>Manage users</a>
{if $smarty.session.sess_permission eq 1}
	<a href="?action=admin_copyfakeprofiles" class="left-menu"><div class="icon-select icon-plus-sign"></div>Copy fake profiles</a>
	{if $submenu eq "admin_copyfakeprofiles"}
		<a href="?action=admin_copyfakeprofiles" class="left-menusub">Search</a>
		<a href="?action=admin_copyfakeprofiles_already" class="left-menusub">Already copied</a>
	{/if}
	<a href="?action=admin_message" class="left-menu"><div class="icon-select icon-th-list"></div>Admin messages</a>
	<!-- <a href="?action=admin_managecard" class="left-menu">Manage E-cards</a> -->
	<a href="?action=admin_manage_picture" class="left-menu{if $submenu eq "admin_manage_picture"} active{/if}"><div class="icon-select icon-picture"></div>Manage Pictures</a>
	{if $submenu eq "admin_manage_picture"}
		<a href="?action=admin_manage_picture&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_manage_picture&type=gallery" class="left-menusub">Gallery</a>
	{/if}

	<a href="?action=admin_approval" class="left-menu{if $submenu eq "admin_approval"} active{/if}"><div class="icon-select icon-ok "></div>Approval</a>
	{if $submenu eq "admin_approval"}
		<a href="?action=admin_approval&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_approval&type=gallery" class="left-menusub">Gallery</a>
		<a href="?action=admin_approval&type=description" class="left-menusub">Description</a>
		<a href="?action=admin_approval&type=delete_account" class="left-menusub">Delete accounts</a>
	{/if}

	<a href="?action=admin_new_members" class="left-menu{if $submenu eq "admin_new_members"} active{/if}"><div class="icon-select icon-time"></div>Newest members</a>
	{if $submenu eq "admin_new_members"}
		<a href="?action=admin_new_members&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_new_members&r=yesterday" class="left-menusub">Yesterday</a>
		<a href="?action=admin_new_members&r=week" class="left-menusub">This week</a>
		<a href="?action=admin_new_members&r=month" class="left-menusub">This Month</a>
		<a href="?action=admin_new_members&r=search" class="left-menusub">Search</a>
	{/if}

	<a href="?action=admin_manage_contents" class="left-menu{if $submenu eq "admin_manage_contents"} active{/if}"><div class="icon-select icon-edit"></div>Manage contents</a>
	{if $submenu eq "admin_manage_contents"}
		<a href="?action=admin_manage_contents&page=terms" class="left-menusub">{#MANAGE_TERMS#} (DE)</a>
		<a href="?action=admin_manage_contents&page=terms-2" class="left-menusub">{#MANAGE_TERMS#} (EN)</a>
		<a href="?action=admin_manage_contents&page=imprint" class="left-menusub">{#MANAGE_IMPRINT#}</a>
		<a href="?action=admin_manage_contents&page=policy" class="left-menusub">{#MANAGE_PRIVACY#} (DE)</a>
		<a href="?action=admin_manage_contents&page=policy-2" class="left-menusub">{#MANAGE_PRIVACY#} (EN)</a>
		<a href="?action=admin_manage_contents&page=refund" class="left-menusub">{#MANAGE_REFUND#} (DE)</a>
		<a href="?action=admin_manage_contents&page=refund-2" class="left-menusub">{#MANAGE_REFUND#} (EN)</a>
	{/if}

	<a href="?action=admin_managecoin" class="left-menu"><div class="icon-select icon-cog"></div>Manage coin</a>
	<a href="?action=admin_manage_package" class="left-menu"><div class="icon-select icon-eye-open"></div>Manage coin packages</a>
	<a href="?action=admin_coin_statistics" class="left-menu{if $submenu eq "admin_coin_statistics"} active{/if}"><div class="icon-select icon-signal"></div>Coin statistics</a>
	{if $submenu eq "admin_coin_statistics"}
		<a href="?action=admin_coin_statistics&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_coin_statistics&r=week" class="left-menusub">Last 7 days</a>
		<a href="?action=admin_coin_statistics&r=month" class="left-menusub">Last 30 days</a>
	{/if}
	<a href="?action=admin_manage_bonus" class="left-menu{if $submenu eq "admin_bonus"} active{/if}"><div class="icon-select icon-heart"></div>Bonus</a>
	{if $submenu eq "admin_bonus"}
		<a href="?action=admin_manage_bonus" class="left-menusub">{#MANAGE_BONUS#}</a>
		<a href="?action=admin_bonus_history" class="left-menusub">{#BONUS_HISTORY#}</a>
	{/if}
	<a href="?action=admin_sms_provider" class="left-menu"><div class="icon-select icon-envelope"></div>SMS Provider</a>
	<a href="?action=admin_emoticons" class="left-menu{if $submenu eq "admin_emoticons"} active{/if}"><div class="icon-select icon-gift"></div>Emoticons</a>
	<a href="?action=admin_chat_logs" class="left-menu"><div class="icon-select icon-adjust"></div>{#LOG_CHAT#}</a>
	<a href="?action=admin_paid" class="left-menu{if $submenu eq "admin_paid"} active{/if}"><div class="icon-select icon-shopping-cart"></div>Payment Transactions</a>
{/if}
<a href="?action=logout" class="left-menu"><div class="icon-select icon-eject"></div>{#LOG_OUT#}</a>
<br/>