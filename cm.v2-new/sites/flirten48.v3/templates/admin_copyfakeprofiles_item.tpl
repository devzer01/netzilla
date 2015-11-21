<div style="float: left; margin: 5px; border: 1px solid black; overflow: hidden">
	<div style="float: left; width: 130px"><img src="{$server_url}thumbnails.php?file={$profile.picturepath}&w=120&h=120"/></div>
	<div style="float: left; width: 400px">
		<b>Username:</b> {$profile.username}<br/>
		<b>Gender:</b> {$profile.gender}<br/>
		<b>Age:</b> {$profile.age}<br/>
		<b>Country:</b> {$profile.country}<br/>
		<b>State:</b> {$profile.state}<br/>
		<b>City:</b> {$profile.city}<br/>
		<b>Description:</b> {$profile.description|htmlspecialchars|stripslashes}
	</div>
	<div style="float: left; width: 180px">
		{if !$profile.already}
		<a href="#" class="btn-admin" id="copy_{$profile.username}" onclick="copyProfile('{$server}','{$profile.username}'); return false;">Copy</a>
		<a href="#" class="btn-admin" id="edit_{$profile.username}" onclick="showEditProfile('{$server}','{$profile.username}'); return false;">Edit & Copy</a>
		{/if}
	</div>
	<br clear="both"/>
</div>
<br clear="both"/>