<a href="./" class="butsearch">{#FRONTEND#}</a>
<a href="?action=admin_manageuser" class="butsearch">{#MANAGE_USER#}</a>
{if $smarty.session.sess_permission eq 1}
	<a href="?action=admin_copyfakeprofiles" class="butsearch">Copy fake profiles</a>
	{if $submenu eq "admin_copyfakeprofiles"}
		<a href="?action=admin_copyfakeprofiles" class="butsearchsub">Search</a>
		<a href="?action=admin_copyfakeprofiles_already" class="butsearchsub">Already copied</a>
	{/if}
	<a href="?action=admin_message" class="butsearch">{#ADMIN_MESSAGES#}</a>
	<a href="?action=admin_managecard" class="butsearch">{#MANAGE_CARD#}</a>
	<a href="?action=admin_manage_picture" class="butsearch{if $submenu eq "admin_manage_picture"} active{/if}">Manage Pictures</a>
	{if $submenu eq "admin_manage_picture"}
		<a href="?action=admin_manage_picture&type=profile" class="butsearchsub">Profile Picture</a>
		<a href="?action=admin_manage_picture&type=gallery" class="butsearchsub">Gallery</a>
	{/if}

	<a href="?action=admin_approval" class="butsearch{if $submenu eq "admin_approval"} active{/if}">Approval</a>
	{if $submenu eq "admin_approval"}
		<a href="?action=admin_approval&type=profile" class="butsearchsub">Profile Picture</a>
		<a href="?action=admin_approval&type=gallery" class="butsearchsub">Gallery</a>
		<a href="?action=admin_approval&type=description" class="butsearchsub">Description</a>
		<a href="?action=admin_approval&type=delete_account" class="butsearchsub">Delete accounts</a>
	{/if}
	
	<a href="?action=admin_new_members" class="butsearch{if $submenu eq "admin_new_members"} active{/if}">Newest members</a></td>
	{if $submenu eq "admin_new_members"}
		<a href="?action=admin_new_members&r=today" class="butsearchsub">Today</a>
		<a href="?action=admin_new_members&r=yesterday" class="butsearchsub">Yesterday</a>
		<a href="?action=admin_new_members&r=week" class="butsearchsub">This week</a>
		<a href="?action=admin_new_members&r=month" class="butsearchsub">This Month</a>
		<a href="?action=admin_new_members&r=search" class="butsearchsub">Search</a>
	{/if}

	<a href="?action=admin_manage_contents" class="butsearch{if $submenu eq "admin_manage_contents"} active{/if}">{#MANAGE_CONTENTS#}</a></td>
	{if $submenu eq "admin_manage_contents"}
		<a href="?action=admin_manage_contents&page=terms" class="butsearchsub">{#MANAGE_TERMS#}</a>
		<a href="?action=admin_manage_contents&page=terms-2" class="butsearchsub">{#MANAGE_TERMS#} 2</a>
		<a href="?action=admin_manage_contents&page=imprint" class="butsearchsub">{#MANAGE_IMPRINT#}</a>
		<a href="?action=admin_manage_contents&page=policy" class="butsearchsub">{#MANAGE_PRIVACY#}</a>
		<a href="?action=admin_manage_contents&page=policy-2" class="butsearchsub">{#MANAGE_PRIVACY#} 2</a>
	{/if}

	<a href="?action=admin_managecoin" class="butsearch">{#MANAGE_COIN#}</a>

	<a href="?action=admin_manage_package" class="butsearch">{#MANAGE_PACKAGE#}</a>

	<a href="?action=admin_coin_statistics" class="butsearch{if $submenu eq "admin_coin_statistics"} active{/if}">{#COIN_STATISTICS#}</a></td>
	{if $submenu eq "admin_coin_statistics"}
		<a href="?action=admin_coin_statistics&r=today" class="butsearchsub">Today</a>
		<a href="?action=admin_coin_statistics&r=week" class="butsearchsub">Last 7 days</a>
		<a href="?action=admin_coin_statistics&r=month" class="butsearchsub">Last 30 days</a>
	{/if}

	<a href="?action=admin_manage_bonus" class="butsearch{if $submenu eq "admin_bonus"} active{/if}">{#BONUS#}</a></td>
	{if $submenu eq "admin_bonus"}
		<a href="?action=admin_manage_bonus" class="butsearchsub">{#MANAGE_BONUS#}</a>
		<a href="?action=admin_bonus_history" class="butsearchsub">{#BONUS_HISTORY#}</a>
	{/if}

	<a href="?action=admin_add_coins" class="butsearch{if $submenu eq "admin_add_coins"} active{/if}">Add coins (Paypal)</a></td>

	<a href="?action=admin_sms_provider" class="butsearch">SMS Provider</a></td>

	{if $smarty.session.payment_admin eq 1}
		<a href="?action=admin_suggestionbox" class="butsearch">{#MANAGE_SUGGESTION_BOX#}</a>
	{/if} 

	{*<a href="?action=admin_history" class="butsearch">{#HISTORY#}</a>
	<a href="?action=admin_paid" class="butsearch{if $submenu eq "admin_paid"} active{/if}">Payment Transactions</a>*}
	{if $submenu eq "admin_paid"}
		{*<a href="?action=admin_paid" class="butsearchsub">All</a>*}
		{*<a href="?action=admin_paid&o=successful" class="butsearchsub">Completed</a>*}
		{**<a href="?action=admin_paid&o=callcenter" class="butsearchsub">Callcenter</a>*}
		{*<a href="?action=admin_paid&o=error" class="butsearchsub">Rejected</a>*}
		{*<a href="?action=admin_paid&o=revoked" class="butsearchsub">EVN Stornos</a>*}
		{*<a href="?action=admin_paid&o=reminder" class="butsearchsub">Mahnungs-Zahlung</a>*}
	{/if}
{/if}
<a href="?action=logout" class="butsearch">{#LOG_OUT#}</a>			