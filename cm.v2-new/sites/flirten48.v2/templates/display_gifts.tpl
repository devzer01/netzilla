<ul>
	{foreach from=$gifts item=gift name=membergifts}
		<li><img src="../{$gift.image_path}" /></li>
	{foreachelse}
		<li>You have not sent any gifts to this member</li>
	{/foreach}
</ul>