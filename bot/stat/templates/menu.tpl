<nav class="navbar navbar-default" role="navigation">
	<ul class="nav navbar-nav">
	{if isset($smarty.session.password) and $smarty.session.password == $smarty.const.ADMIN_PASSWORD}
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/index.php" class="nav">BOTs</a></li> 
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/presets.php" class="nav">Presets</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/op/" class="nav">Operations</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-site.php?action=add" class="nav">Add site</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-user.php?action=add" class="nav">Add user</a></li> 
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-user.php?action=delete" class="nav">Delete user</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-message.php?action=add" class="nav">Messages</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/delete-user-msg.php" class="nav">Delete User Sent MSG</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-url.php" class="nav">Mask URL</a></li> 
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/manage-log.php" class="nav">Logs</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/stat/report" class="nav">Report</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/stat/daily" class="nav">Message Graph</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/stat/redirect" class="nav">Redirect Report</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/stat/redirectsignup" class="nav">Redirect Signup Report</a>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/stat/profilereport" class="nav">Profile Report</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/emails.php" class="nav">Email Accounts</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/vcards.php" class="nav">VCards</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/bot/settings.php" class="nav">Settings</a></li>
		<li><a href="http://{$smarty.server.HTTP_HOST}/monitor/bot-servers" class="nav" target="_blank">Server Status</a></li>
	{/if}
		<li><a href="../logout.php" class="nav">Log Out</a></li>
	</ul>
</nav>