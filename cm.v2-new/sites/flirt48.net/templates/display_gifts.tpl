<ul>
	{foreach from=$gifts item=gift name=membergifts}
		<li><img src="../{$gift.image_path}" /> | {$gift.sender} | {$gift.created}</li>
	{/foreach}
</ul>