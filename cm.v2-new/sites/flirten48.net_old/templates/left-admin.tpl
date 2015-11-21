<!-- {$smarty.template} -->
<a href="./" class="left-menu">Front end</a>
<a href="?action=admin_manageuser" class="left-menu">Manage users</a>
{if $smarty.session.sess_permission eq 1}
	<a href="?action=admin_copyfakeprofiles" class="left-menu">Copy fake profiles</a>
	{if $submenu eq "admin_copyfakeprofiles"}
		<a href="?action=admin_copyfakeprofiles" class="left-menusub">Search</a>
		<a href="?action=admin_copyfakeprofiles_already" class="left-menusub">Already copied</a>
	{/if}
	<a href="?action=admin_message" class="left-menu">Admin messages</a>
	<!-- <a href="?action=admin_managecard" class="left-menu">Manage E-cards</a> -->
	<a href="?action=admin_manage_picture" class="left-menu{if $submenu eq "admin_manage_picture"} active{/if}">Manage Pictures</a>
	{if $submenu eq "admin_manage_picture"}
		<a href="?action=admin_manage_picture&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_manage_picture&type=gallery" class="left-menusub">Gallery</a>
	{/if}

	<a href="?action=admin_approval" class="left-menu{if $submenu eq "admin_approval"} active{/if}">Approval</a>
	{if $submenu eq "admin_approval"}
		<a href="?action=admin_approval&type=profile" class="left-menusub">Profile Picture</a>
		<a href="?action=admin_approval&type=gallery" class="left-menusub">Gallery</a>
		<a href="?action=admin_approval&type=description" class="left-menusub">Description</a>
		<a href="?action=admin_approval&type=delete_account" class="left-menusub">Delete accounts</a>
	{/if}

	<a href="?action=admin_new_members" class="left-menu{if $submenu eq "admin_new_members"} active{/if}">Newest members</a>
	{if $submenu eq "admin_new_members"}
		<a href="?action=admin_new_members&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_new_members&r=yesterday" class="left-menusub">Yesterday</a>
		<a href="?action=admin_new_members&r=week" class="left-menusub">This week</a>
		<a href="?action=admin_new_members&r=month" class="left-menusub">This Month</a>
		<a href="?action=admin_new_members&r=search" class="left-menusub">Search</a>
	{/if}

	<a href="?action=admin_manage_contents" class="left-menu{if $submenu eq "admin_manage_contents"} active{/if}">Manage contents</a>
	{if $submenu eq "admin_manage_contents"}
		<a href="?action=admin_manage_contents&page=terms" class="left-menusub">{#MANAGE_TERMS#} (DE)</a>
		<a href="?action=admin_manage_contents&page=terms-2" class="left-menusub">{#MANAGE_TERMS#} (EN)</a>
		<a href="?action=admin_manage_contents&page=imprint" class="left-menusub">{#MANAGE_IMPRINT#}</a>
		<a href="?action=admin_manage_contents&page=policy" class="left-menusub">{#MANAGE_PRIVACY#} (DE)</a>
		<a href="?action=admin_manage_contents&page=policy-2" class="left-menusub">{#MANAGE_PRIVACY#} (EN)</a>
		<a href="?action=admin_manage_contents&page=refund" class="left-menusub">{#MANAGE_REFUND#} (DE)</a>
		<a href="?action=admin_manage_contents&page=refund-2" class="left-menusub">{#MANAGE_REFUND#} (EN)</a>
	{/if}

	<a href="?action=admin_managecoin" class="left-menu">Manage coin</a>

	<a href="?action=admin_manage_package" class="left-menu">Manage coin packages</a>

	<a href="?action=admin_coin_statistics" class="left-menu{if $submenu eq "admin_coin_statistics"} active{/if}">Coin statistics</a>
	{if $submenu eq "admin_coin_statistics"}
		<a href="?action=admin_coin_statistics&r=today" class="left-menusub">Today</a>
		<a href="?action=admin_coin_statistics&r=week" class="left-menusub">Last 7 days</a>
		<a href="?action=admin_coin_statistics&r=month" class="left-menusub">Last 30 days</a>
	{/if}

	<a href="?action=admin_manage_bonus" class="left-menu{if $submenu eq "admin_bonus"} active{/if}">Bonus</a>
	{if $submenu eq "admin_bonus"}
		<a href="?action=admin_manage_bonus" class="left-menusub">{#MANAGE_BONUS#}</a>
		<a href="?action=admin_bonus_history" class="left-menusub">{#BONUS_HISTORY#}</a>
	{/if}

	<a href="?action=admin_sms_provider" class="left-menu">SMS Provider</a>

	<!-- <a href="?action=admin_add_coins" class="left-menu">Add coins (Paypal)</a> -->
	<!-- <a href="?action=admin_suggestionbox" class="left-menu">Suggestion box</a> -->

	{*<a href="?action=admin_history" class="left-menu">History</a>
	<a href="?action=admin_paid" class="left-menu{if $submenu eq "admin_paid"} active{/if}">Payment Transactions</a>
	{if $submenu eq "admin_paid"}
		<a href="?action=admin_paid" class="left-menusub">All</a>
		<a href="?action=admin_paid&o=successful" class="left-menusub">Completed</a>
		<a href="?action=admin_paid&o=callcenter" class="left-menusub">Callcenter</a>
		<a href="?action=admin_paid&o=error" class="left-menusub">Rejected</a>
		<a href="?action=admin_paid&o=revoked" class="left-menusub">EVN Stornos</a>
		<a href="?action=admin_paid&o=reminder" class="left-menusub">Mahnungs-Zahlung</a>
	{/if}*}
{/if}

<a href="?action=logout" class="left-menu">{#LOG_OUT#}</a>
<br/><br/><br/><br/><br/><br/>